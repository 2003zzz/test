<?php

namespace App\Domains;

use App\Http\Controllers\AuthController;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelCardParser
{
    // Объект Excel документа
    protected object $spreadsheet;
    // Текущий лист Excel документа
    protected object $worksheet;
    // Основной и текущий ТД карты норм времени для
    protected string $mainTD;
    protected string $currentTD;
    // Текущая строка в Excel документе
    protected int $currentRow;

    public function __construct($filepath)
    {
        $this->spreadsheet = $this->loadXlsxFile($filepath);
    }

    // Загрузка переданного файла КНВ
    private function loadXlsxFile($filepath)
    {
        $reader = IOFactory::createReader('Xlsx');
        return $reader->load($filepath);
    }

    public function parse()
    {
        $content = [];

        // Иногда в конце находится пустой лист, убираем его из расчета
        $sheetCount = $this->spreadsheet->getSheetCount();
        $sheetCount -= $sheetCount % 2;

        for ($sheetIndex = 0; $sheetIndex < $sheetCount; $sheetIndex += 2) {
            $this->mainTD = '';
            $this->currentTD = '';

            // Чтение заголовка карты и операций с лицевой стороны карты
            $this->worksheet = $this->spreadsheet->getSheet($sheetIndex);
            $header = $this->parseCardHeader();
            $this->currentRow = 7;
            $operationsFront = $this->parseCardOperations();

            // Чтение операций с обратной стороны карты
            $this->worksheet = $this->spreadsheet->getSheet($sheetIndex + 1);
            $this->currentRow = 3;
            $operationsBack = $this->parseCardOperations();

            array_push($content, [
                'header' => $header,
                'operations' => array_merge($operationsFront, $operationsBack)
            ]);
        }
        $this->spreadsheet->disconnectWorksheets();
        return $this->mergeCardSheets($content);
    }

    // Собираем данные, полученные с отдельных пар листов, в один массив
    private function mergeCardSheets($content)
    {
        $header = null;
        $operations = [];
        foreach ($content as $sheetContent) {
            if ($header) {
                if ($header != $sheetContent['header']) {
                    throw new \Exception("Заголовки листов карты норм времени не совпадают");
                }
            }
            $header = $sheetContent['header'];
            $operations = [...$operations, ...$sheetContent['operations']];
        }
        return [
            'header' => $header,
            'operations' => $operations
        ];
    }

    // Считывание заголовка КНВ из документа
    private function parseCardHeader()
    {
        $worksheet = $this->worksheet;
        $header = [];
        $header['id_version'] = 1;

        $cipher = explode('/', $worksheet->getCell('F4')->getValue());
        $this->mainTD = $cipher[0];

        $header['workshop'] = trim($worksheet->getCell('B2')->getValue());
        $header['number_technological_notification'] = trim($worksheet->getCell('F3')->getValue());
        $header['note'] = trim($worksheet->getCell('I3')->getValue());
        $header['party'] = trim($worksheet->getCell('B4')->getValue());
        $header['cipher_main_td'] = trim($cipher[0] ?? '');
        $header['type_technical_doc'] = trim($cipher[1] ?? '');
        $header['designation'] = trim(preg_replace('/[^А-я\d]/ui', '', $worksheet->getCell('I4')->getValue()));
        $header['create_service_number'] = '023' . trim($worksheet->getCell('B5')->getValue());
        $header['p003'] = trim(preg_replace('/[^А-я\d]/ui', '', $worksheet->getCell('I5')->getValue()));
        $header['service_number'] = app(AuthController::class)->getTabNum(); // для сервера 
        $header['service_number'] = '0238430';

        // id_status, number_notification_ott, number_of_parts_in_batch, validity_period_norms, minimum_number_blanks
        return $header;
    }

    // Заполнение операции доп. данными из строки
    private function getMoreDataString($operation, $string)
    {
        $string = mb_strtolower($string);
        // number_parts_of_detail
        if (preg_match('/м\/н/', $string, $matches)) $operation["operation_as_needed"] = 1;
        if (preg_match('/обр/', $string, $matches)) $operation["operations_for_samples"] = 1;
        if (preg_match('/на (\d+)х./', $string, $matches)) $operation["number_of_worker"] = $matches[1];
        if (preg_match('/(\d+) т.о./', $string, $matches)) $operation["operation_with_technological_shutdowns"] = $matches[1];
        if (preg_match('/-(\d+)/', $string, $matches)) $operation["operation_for_execution"] = $matches[1];

        return $operation;
    }

    // Считывание операций КНВ из документа
    private function parseCardOperations()
    {
        $worksheet = $this->worksheet;
        $operations = [];

        while (true) {
            $operationNumber = $worksheet->getCell('C' . $this->currentRow + 1)->getValue();
            $nextWithTP = $worksheet->getCell('G' . $this->currentRow + 1)->getValue();

            if (strlen($operationNumber) > 0) {
                $operation = $this->parseNextOperation();
                if (isset($operation)) {
                    array_push($operations, $operation);
                }
            } else if (str_starts_with($nextWithTP, 'Далее по т/п ')) {
                $this->currentTD = substr($nextWithTP, strlen('Далее по т/п '));
            } else {
                break;
            }
            $this->currentRow += 2;
        }

        return $operations;
    }

    // Считывание следующей операции
    private function parseNextOperation()
    {
        $worksheet = $this->worksheet;
        $operation = [];
        $operation['id_version'] = 1;

        $upperRow = $this->currentRow;
        $lowerRow = $this->currentRow + 1;

        $isTimeInHours = strpos($worksheet->getCell('M' . $lowerRow)->getValue(), 'ч') !== false;

        $operation['end_to_end_operation_number'] = trim($worksheet->getCell('B' . $lowerRow)->getValue());
        $operation['operation_number'] = trim($worksheet->getCell('C' . $lowerRow)->getValue());
        if (!$operation['end_to_end_operation_number']) {
            $operation['end_to_end_operation_number'] = $operation['operation_number'];
        }
        $operation['cipher_of_the_operation'] = trim($worksheet->getCell('D' . $upperRow)->getValue());
        // $operation['p014'] = trim($worksheet->getCell('D'.$lowerRow)->getValue());

        $withAnotherTP = trim($worksheet->getCell('G' . $lowerRow)->getValue());
        if (str_starts_with($withAnotherTP, 'по ')) {
            $operation['cipher_of_the_reference_tp'] = substr($withAnotherTP, strlen('по '));
            // TODO: Код алгоритма обработки операции, идущей по отдельному ТП
            // Пока просто пропускаем данную операцию
            // return $operation;
            return null;
        }

        // $operation['p451'] = trim($worksheet->getCell('G'.$lowerRow)->getValue());
        $operation['cipher_of_the_reference_tp'] = ($this->currentTD == '') ? $this->mainTD : $this->currentTD;
        $operation['hardware_cipher'] = trim($worksheet->getCell('G' . $upperRow)->getValue());
        $operation['cipher_of_the_profession'] = trim($worksheet->getCell('H' . $lowerRow)->getValue());
        $operation['category_of_work'] = trim($worksheet->getCell('I' . $lowerRow)->getValue());
        $operation['code_of_the_tariff_grid'] = trim($worksheet->getCell('J' . $lowerRow)->getValue());
        $operation['type_of_norms'] = trim($worksheet->getCell('K' . $lowerRow)->getValue());
        $operation['unit_of_the_rationong'] = trim($worksheet->getCell('L' . $lowerRow)->getValue());
        $operation['time_rate_is_paid'] = str_replace('ч', '', str_replace(',', '.', trim($worksheet->getCell('M' . $lowerRow)->getFormattedValue())));
        $operation['unit_of_measurement'] = ($isTimeInHours) ? 'ч' : 'м';
        $operation['norm_of_cycle_time'] = str_replace(',', '.', trim($worksheet->getCell('N' . $lowerRow)->getFormattedValue()));
        $operation['launch_ratio'] = trim($worksheet->getCell('O' . $lowerRow)->getValue());
        $operation['number_notification_sgt'] = trim($worksheet->getCell('P' . $lowerRow)->getValue());

        $moreDataCell = trim($worksheet->getCell('A' . $upperRow)->getValue());
        $moreDataRow = trim($worksheet->getCell('A' . $upperRow + 2)->getValue());
        $isMoreDataRow = preg_match('/Доп. данные к оп.(\d{3}): (.*)/', $moreDataRow, $matches);

        if ($moreDataCell) {
            $operation = $this->getMoreDataString($operation, $moreDataCell);
        } else if ($isMoreDataRow && $operation['end_to_end_operation_number'] == $matches[1]) {
            $operation = $this->getMoreDataString($operation, $matches[2]);
            $this->currentRow += 2;
        }
        return $operation;
    }
}
