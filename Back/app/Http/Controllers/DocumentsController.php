<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Storage;
use App\Models\ptt05v01;
use App\Models\ptt05v02;
use App\Domains\ExcelCardBuilder;
use App\Domains\ExcelCardParser;

class DocumentsController extends Controller
{
    // Формирование xlsx из данных КНВ
    public function getDocument(Request $request)
    {
        if (!isset($request->cardID) || !is_numeric($request->cardID)) {
            return false;
        }
        $id_v01 = $request->cardID;

        $header = ptt05v01::where([['id_v01', $id_v01]])->get();

        if (is_null($header)) {
            return false;
        }

        $operations = ptt05v02::orderBy('id_v02')->where([['id_v01', $id_v01]])
            ->leftJoin('pak65.pak65e045', 'pak65.pak65e045.p018', '=', DB::Raw("CAST(ptt05v02.cipher_of_the_operation AS INTEGER)")) // Получение наименования операции по ее шифру
            ->leftJoin('pkt50_v', function (JoinClause $join) {
                $join
                    ->on('pkt50_v.profcode70', '=', 'ptt05v02.cipher_of_the_profession') // Получение профессии по ее шифру
                    ->on('pkt50_v.kategoryprof', '=', 'ptt05v02.type_of_profession_reference_book'); // Получение категории (1 или 2)
            })
            ->leftJoin('pak01.pak01e01', 'pak01.pak01e01.p501', '=', DB::Raw("CONCAT('0000', ptt05v02.hardware_cipher)")) // Получение наименования оборудования по коду, добавляя 4 нуля
            ->select('ptt05v02.*', 'pak65.pak65e045.p014', 'pkt50_v.snm_as_scaption', 'pak01.pak01e01.p451') // Вывод требуемых данных
            ->get();

        foreach ($operations as $key) {
            $key->hardware_cipher = preg_replace("/^(\d{2})(\d{4})(\d{5})/u", "$1.$2.$3", $key->hardware_cipher);
        }

        $card = [
            'header' => $header->first(),
            'operations' => $operations->toArray()
        ];

        $builder = new ExcelCardBuilder($card);

        $filepath = $builder->build();
        return Storage::disk('local')->download($filepath, 'Карта норм времени.xlsx');
    }

    // Получение xlsx и сохранение данных КНВ в базе данных
    public function createDocument(Request $request)
    {
        if (!$request->hasFile('document')) {
            return false;
        }
        $file = $request->file('document');

        if (!$file->isValid() || $file->extension() != 'xlsx') {
            return false;
        }
        $filename = $file->store('cards', 'local');
        // $filename = Storage::putFile('cards', $file, 'local');
        $filepath = Storage::disk('local')->path($filename);

        $parser = new ExcelCardParser($filepath);

        $card = $parser->parse();
        $header = $card['header'];
        $operations = $card['operations'];

        // Здесь возможно переписать работу с базой так, чтобы поместить эти данные в 1-2 запроса
        DB::beginTransaction();
        try {
            $cardID = $this->insertCard(new Request(['card' => $header]));
            foreach ($operations as $operation) {
                $operation['id_v01'] = $cardID;
                $this->insertOperation(new Request(['operation' => $operation]));
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

        return $cardID;
    }

    private function insertCard(Request $request)
    {
        $validated = $request->validate([
            'card.workshop' => 'required',
            'card.designation' => '',
            'card.code_detail' => '',
            'card.note' => '',
            'card.cipher_main_td' => '',
            'card.type_technical_doc' => '',
            'card.number_technological_notification' => '',
            'card.party' => '',
            'card.service_number' => '',
            'card.create_service_number' => '',
            'card.id_version' => ''
        ]);

        $card = $validated['card'];

        foreach ($card as $key => $value) {
            if (!$value) {
                unset($card[$key]);
            }
        }

        if (isset($card)) {
            return ptt05v01::insertGetId($card, 'id_v01');
        }

        return null;
    }

    private function insertOperation(Request $request)
    {
        $validated = $request->validate([
            'operation.id_v01' => 'required',
            'operation.time_rate_is_paid' => 'required',
            'operation.unit_of_measurement' => '',
            'operation.end_to_end_operation_number' => 'required',
            'operation.operation_number' => 'required',
            'operation.norm_of_cycle_time' => '',
            'operation.launch_ratio' => '',
            'operation.cipher_of_the_operation' => '',
            'operation.hardware_cipher' => '',
            'operation.cipher_of_the_profession' => 'required',
            'operation.type_of_profession_reference_book' => '',
            'operation.cipher_of_the_reference_tp' => '',
            'operation.code_of_the_tariff_grid' => 'required',
            'operation.category_of_work' => 'required',
            'operation.number_notification_sgt' => '',
            'operation.type_of_norms' => '',
            'operation.unit_of_the_rationong' => 'required',
            'operation.id_version' => '',
            "operation.operation_as_needed" => '',
            "operation.operations_for_samples" => '',
            "operation.number_of_worker" => '',
            "operation.operation_with_technological_shutdowns" => '',
            "operation.operation_for_execution" => ''
        ]);

        $operation = $validated['operation'];

        foreach ($operation as $key => $value) {
            if (!$value) {
                unset($operation[$key]);
            }
        }

        if (isset($operation)) {
            return ptt05v02::insertGetId($operation, 'id_v02');
        }

        return null;
    }
}
