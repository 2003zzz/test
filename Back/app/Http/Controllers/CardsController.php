<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\CreateCardRequest;
use App\Services\CommonService;
use App\Models\ptt05v01;
use App\Models\ptt05v02;
use App\Models\ptt05v03;
use App\Models\ptt05v04;
use App\Models\pam22e09;
use App\Actions\StoreCardAction;
use Illuminate\Support\Facades\Log;

class CardsController extends Controller
{
    // Получение всех КНВ для изделия
    public function showCards(Request $request)
    {
        $item = $request->item;

        $code_detail = preg_replace('/[^А-я\d]/ui', '', $item['code_detail']);

        return ptt05v01::select(['id_v01', 'workshop', 'cipher_main_td'])
            ->where('code_detail', $code_detail)
            ->orderBy('date_of_create', 'DESC')
            ->get();
    }

    // Создание новой КНВ
    public function createCard(CreateCardRequest $request)
    {
        $card = $request->validated();

        // Проверка на существование карточки с такими данными
        if (ptt05v01::where([
            ['code_detail', $card['code_detail']],
            ['cipher_main_td', $card['cipher_main_td']],
            ['workshop', $card['workshop']]
        ])->exists()) {
            return 'Карта существует';
        }

        // Проверка на существование изделия
        if (pam22e09::where([
            ['c006', $card['designation']],
            ['p003', $card['code_detail']],
        ])->doesntExist()) {
            return 'Карта не найдена';
        }
        $existingCard = ptt05v01::where('workshop', $card['workshop'])
            ->where('cipher_main_td', '!=', $card['cipher_main_td'])
            ->first();

        $existingId = ptt05v01::select(['id_v01', 'id_v02', 'cipher_of_the_reference_tp'])->where(
            ['code_detail', $card['code_detail']],
            ['workshop', $card['workshop']]
        )
            ->orderByDesc('date_create')
            ->value('id_v01');
        return [
            'existing' => true,
            'id_v01' => $existingId,
        ];
        return ptt05v01::insertGetId($card, 'id_v01');
    }
    // Удаление и архивирование КНВ
    public function deleteCard(Request $request)
    {
        $card = ptt05v01::find($request->cardID);
        $operations = ptt05v02::where([['id_v01', $card->id_v01]])->get();

        DB::beginTransaction();
        try {
            $personnelNumber = app(CommonService::class)->getCurrentUser()["tabNum"];

            $card->service_number_editor = $personnelNumber;
            $card->id_status = '6';

            $archiveCardID = ptt05v03::insertGetId($card->toArray(), 'id_v03');

            foreach ($operations as $operation) {
                $operation->service_number_editor = $personnelNumber;
                $operation->id_v03 = $archiveCardID;
            }

            ptt05v04::insert($operations->toArray());

            ptt05v02::destroy($operations);
            $card->delete();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
    // Сохранение данных в КНВ и операциях
    public function saveCard(StoreCardRequest $request)
    {
        [
            'card' => $card,
            'operations' => $operations,
        ] = $request->validated();

        return (new StoreCardAction)($card, $operations ?? []);
    }
    // Получение данных КНВ
    public function getCard(Request $request)
    {
        // получение данных карты по пришедшему id 
        $card = ptt05v01::find($request->cardID);

        // Получение операций: 
        // имя операции по ее шифру
        // профессии по ее шифру
        // наименование оборудования

        $operations = ptt05v02::select('ptt05v02.*')
            ->where([['id_v01', $card->id_v01]])
            ->withOperationName()
            ->withProfessionName()
            ->withHardwareName()
            ->orderBy('end_to_end_operation_number')
            ->get();

        foreach ($operations as $operation) {
            $operation->hardware_cipher = preg_replace("/^(\d{2})(\d{4})(\d{5})/u", "$1.$2.$3", $operation->hardware_cipher);
        }
        // Список цехов где:
        // код детали = текущему коду найденной детали 
        // обозначение изделия не пустое
        // исключаем дублирующие записи 
        $workshops = ptt05v01::select('id_v01', 'workshop')
            ->where([['code_detail', $card->code_detail]])
            ->whereNotNull('designation')
            ->distinct()
            ->orderBy('workshop')
            ->get();

        return [
            'card' => $card,
            'operations' => $operations,
            'workshops' => $workshops
        ];
    }
    // Установка статуса КНВ
    public function updateCardStatus(Request $request)
    {
        $card = ptt05v01::find($request->cardID);

        $card->update(['id_status' => $request->statusID]);
    }
    // Получение списка версий КНВ
    public function getVersions(Request $request)
    {
        $card = ptt05v01::find($request->cardID);

        $currentVersion = $card->only('id_version', 'date_of_create');

        $versions = ptt05v03::select('id_version', 'updated_at')
            ->where('id_v01', $card->id_v01)
            ->orderBy('id_version')
            ->get()
            ->transform(function ($item) {
                return $item->getOriginal();
            });

        return $versions->concat([$currentVersion]);
    }
    // Получение архивной версии КНВ
    public function getArchiveCard(Request $request)
    {
        $card = ptt05v03::where([
            ['id_v01', $request->cardID],
            ['id_version', $request->archiveID]
        ])->firstOrFail();

        $operations = ptt05v04::select('ptt05v04.*')
            ->where([
                ['id_v01', $request->cardID],
                ['id_version', $request->archiveID]
            ])
            ->withOperationName()
            ->withProfessionName()
            ->withHardwareName()
            ->orderBy('end_to_end_operation_number')
            ->get();

        foreach ($operations as $operation) {
            $operation->hardware_cipher = preg_replace("/^(\d{2})(\d{4})(\d{5})/u", "$1.$2.$3", $operation->hardware_cipher);
        }

        return [
            'card' => $card,
            'operations' => $operations
        ];
    }
}
