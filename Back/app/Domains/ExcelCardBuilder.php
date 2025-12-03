<?php

namespace App\Domains;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExcelCardBuilder
{
    // Объект Excel документа
    protected object $spreadsheet;
    // Текущий лист Excel документа
    protected object $worksheet;
    // Осталось строк для записи на этом листе
    protected int $rowsLeft;
    // Данные КНВ (заголовок и операции)
    protected object $header;
    protected array $operations;
    // Основной ТД
    protected string $mainTD;
    // Текущий ТД
    protected string $currentTD;
    // Нахождение на лицевой стороне КНВ
    protected bool $isFrontSheet;
    // Текущий лист в Excel документе
    protected int $currentSheet;
    // Текущая строка в Excel документе
    protected int $currentRow;
    // Копии сторон листов шаблона КНВ
    protected object $sheetFront;
    protected object $sheetBack;
    // поле ниже никогда не использовалось
    protected int $currentOperation;

    public function __construct(array $card)
    {
        if (!is_object($card['header'])) {
            throw new \Exception("Передан неверный заголовок карты норм времени");
        }
        $this->header = $card['header'];
        if (!is_array($card['operations'])) {
            throw new \Exception("Переданы неверные операции карты норм времени");
        }
        $this->operations = $card['operations'];
        $this->currentOperation = 0;

        $this->spreadsheet = $this->loadXlsxCardTemplate('cards/CardTemplate.xlsx');

        if ($this->spreadsheet->getSheetCount() != 2) {
            throw new \Exception("В файле шаблона КНВ должно быть два листа");
        }
        $this->currentSheet = 0;

        $this->getNextSheetFrontSide();

        $this->sheetFront = clone $this->spreadsheet->getSheet(0);
        $this->sheetBack = clone $this->spreadsheet->getSheet(1);
    }

    // Основной метод сборки файла КНВ
    public function build()
    {
        $this->fillDocumentWithCardData();

        $filename = now()->timestamp;
        $filepath = 'cards/' . $filename . '.xlsx';
        $this->saveXlsxFile($filepath);

        $this->spreadsheet->disconnectWorksheets();
        return $filepath;
    }

    // Загрузка шаблона КНВ
    private function loadXlsxCardTemplate(string $path)
    {
        $filepath = Storage::disk('local')->path($path);
        $reader = IOFactory::createReader('Xlsx');
        return $reader->load($filepath);
    }

    // Сохранение файла КНВ по пути в /storage/app
    private function saveXlsxFile($filepath)
    {
        $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');

        ob_start();
        $writer->save('php://output');
        $content = ob_get_contents();
        ob_end_clean();

        Storage::disk('local')->put($filepath, $content);
    }

    // Заполнение файла данными
    private function fillDocumentWithCardData()
    {
        $this->parseMainTD();

        foreach ($this->operations as $operation) {
            $this->writeNextOperation($operation);
        }

        $this->writeSheetNumbers();
    }

    private function parseMainTD()
    {
        $cipher = explode('/', $this->header['cipher_main_TD']);

        $this->mainTD = $cipher[0];
        $this->currentTD = $cipher[0];
    }

    // Получение обратной стороны листа для записи
    private function getNextSheetBackSide()
    {
        $this->worksheet = $this->spreadsheet->getSheet($this->currentSheet);
        $this->currentRow = 3;
        $this->rowsLeft = 18;
        $this->isFrontSheet = false;
    }

    // Получение лицевой стороны листа для записи
    private function getNextSheetFrontSide()
    {
        $this->worksheet = $this->spreadsheet->getSheet($this->currentSheet);
        $this->writeCardHeader();
        $this->currentRow = 7;
        $this->rowsLeft = 18;
        $this->isFrontSheet = true;
    }

    // Получение следующей стороны листа для записи
    private function getNextSheet()
    {
        $this->currentSheet++;

        if ($this->isFrontSheet) {
            $this->getNextSheetBackSide();
        } else {
            $this->createMoreSheets();
            $this->getNextSheetFrontSide();
        }
    }

    // Добавление в файл пары листов (лицевая и оборотная сторона)
    private function createMoreSheets()
    {
        $sheetFront = clone $this->sheetFront;
        $sheetFront->setTitle('Лист 1(' . $this->currentSheet / 2 . ')');
        $this->spreadsheet->addSheet($sheetFront);

        $sheetBack = clone $this->sheetBack;
        $sheetBack->setTitle('Лист 2(' . $this->currentSheet / 2 . ')');
        $this->spreadsheet->addSheet($sheetBack);
    }

    // Запись на текущий лист данных заголовка КНВ
    private function writeCardHeader()
    {
        $this->worksheet
            ->setCellValue('B2', $this->header['workshop'])
            ->setCellValue('F3', $this->header['number_technological_notification'])
            ->setCellValue('I3', $this->header['note'])
            ->setCellValue('B4', $this->header['party'])
            ->setCellValue('F4', $this->header['cipher_main_td'] . '/' . $this->header['type_technical_doc'])
            ->setCellValue('B5', substr($this->header['create_service_number'], 3))
            ->setCellValue('I5', $this->header['p003']);
    }

    // Запись данных полей "Лист" и "Листов"
    private function writeSheetNumbers()
    {
        $sheetCount = $this->spreadsheet->getSheetCount();

        for ($sheet = 0; $sheet < $sheetCount; $sheet += 2) {
            $this->spreadsheet->getSheet($sheet)
                ->setCellValue('P4', ($sheet / 2) + 1)
                ->setCellValue('P5', ($sheetCount / 2));
        }
    }

    // Записать следующую операцию
    private function writeNextOperation(array $operation)
    {
        $refTD = $operation["cipher_of_the_reference_tp"];
        if ($refTD) {
            if ($refTD != $this->currentTD) {
                $this->writeNextReferenceTD($refTD);
                $this->currentTD = $refTD;
            }
        } else {
            $this->currentTD = $this->mainTD;
        }

        $operation["operation_number"] = $operation["operation_number"] ?: $operation["end_to_end_operation_number"];

        $this->writeCardOperation($operation);

        $moreData = $this->createMoreData($operation);
        if (is_array($moreData)) {
            $this->writeMoreDataRows($moreData, $operation['operation_number']);
        } else if (is_string($moreData)) {
            $this->writeMoreDataCell($moreData);
        }
    }

    // Сдвиг указателя на текущую обрабатываемую строку
    private function moveRowCursor(int $change)
    {
        $this->currentRow += $change;
        $this->rowsLeft -= $change;
    }

    // Запись на текущий лист данных операции
    private function writeCardOperation(array $operation)
    {
        if ($this->rowsLeft < 2) $this->getNextSheet();

        $upperRow = $this->currentRow;
        $lowerRow = $this->currentRow + 1;

        if ($operation["end_to_end_operation_number"] != $operation["operation_number"]) {
            $this->worksheet->setCellValue('B' . $lowerRow, $operation["end_to_end_operation_number"]);
        }
        $this->worksheet->setCellValue('C' . $lowerRow, $operation["operation_number"]);
        $this->worksheet->setCellValue('D' . $upperRow, $operation["cipher_of_the_operation"]);
        $this->worksheet->setCellValue('D' . $lowerRow, $operation["p014"]);
        $this->worksheet->setCellValue('G' . $upperRow, $operation["hardware_cipher"]);
        $this->worksheet->setCellValue('H' . $lowerRow, $operation["cipher_of_the_profession"]);
        $this->worksheet->setCellValue('I' . $lowerRow, $operation["category_of_work"]);
        $this->worksheet->setCellValue('J' . $lowerRow, $operation["code_of_the_tariff_grid"]);
        $this->worksheet->setCellValue('K' . $lowerRow, $operation["type_of_norms"]);
        $this->worksheet->setCellValue('L' . $lowerRow, $operation["unit_of_the_rationong"]);
        $this->worksheet->setCellValue('M' . $lowerRow, $operation["time_rate_is_paid"]);
        $this->worksheet->setCellValue('N' . $lowerRow, $operation["norm_of_cycle_time"]);
        $this->worksheet->setCellValue('O' . $lowerRow, $operation["launch_ratio"]);
        $this->worksheet->setCellValue('P' . $lowerRow, $operation["number_notification_sgt"]);

        $this->moveRowCursor(2);
    }

    // Запись строки "Далее по т/п ..."
    private function writeNextReferenceTD(string $referenceTD)
    {
        if ($this->rowsLeft < 2) $this->getNextSheet();

        $upperRow = $this->currentRow;
        $lowerRow = $this->currentRow + 1;

        $this->worksheet->unmergeCells('D' . $lowerRow . ':F' . $lowerRow);
        $this->worksheet->mergeCells('A' . $upperRow . ':P' . $lowerRow);

        $this->worksheet->setCellValue('A' . $upperRow, 'Далее по т/п ' . $referenceTD);
        $this->worksheet->getStyle('A' . $upperRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $this->moveRowCursor(2);
    }

    // Создание строки доп. данных
    private function createMoreData(array $operation): array | string
    {
        $data = [];

        // number_parts_of_detail
        if ($operation["operation_as_needed"]) {
            array_push($data, 'м/н');
        }
        if ($operation["operations_for_samples"]) {
            array_push($data, 'обр');
        }
        if ($operation["number_of_worker"]) {
            array_push($data, 'на ' . $operation["number_of_worker"] . 'х.');
        }
        if ($operation["operation_with_technological_shutdowns"]) {
            array_push($data, $operation["operation_with_technological_shutdowns"] . 'т.о.');
        }
        if ($operation["operation_for_execution"]) {
            array_push($data, '-' . $operation["operation_for_execution"]);
        }

        if (count($data) == 0) {
            return "";
        }

        if (count($data) == 1) {
            return $data[0];
        }

        return $data;
    }

    // Запись на текущий лист доп. данных операции в виде двух строк
    private function writeMoreDataRows(array $moreData, string $operationNumber)
    {
        if ($this->rowsLeft < 2) $this->getNextSheet();

        $upperRow = $this->currentRow;
        $lowerRow = $this->currentRow + 1;

        $moreDataString = 'Доп.данные к оп.' . $operationNumber . ': ' . implode(', ', $moreData);

        $this->worksheet->unmergeCells('D' . $lowerRow . ':F' . $lowerRow);
        $this->worksheet->mergeCells('A' . $upperRow . ':P' . $lowerRow);

        $this->worksheet->setCellValue('A' . $upperRow, $moreDataString);
        $this->worksheet->getStyle('A' . $upperRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $this->moveRowCursor(2);
    }

    // Запись на текущий лист доп. данных операции в виде ячейки
    private function writeMoreDataCell(string $moreData)
    {
        $this->worksheet->setCellValue('A' . $this->currentRow, $moreData);
    }
}
