<?php

namespace App\Actions;

use App\Helper;
use App\Models\ptt05v01;
use App\Models\ptt05v02;
use App\Models\ptt05v03;
use App\Models\ptt05v04;
use App\Services\CommonService;

use Illuminate\Support\Facades\DB;

final class StoreCardAction
{
    private object $card;
    private object $operations;

    public function __invoke($cardData, $operationsData)
    {
        $cardID = $cardData['id_v01'];

        $this->card = ptt05v01::find($cardID); // получаем КНВ
        $this->operations = ptt05v02::where([['id_v01', $cardID]])->get(); // получаем операцию данной КНВ
        $isChanged = $this->checkChanges($cardData, $operationsData); // проверка изменений 

        if (!$isChanged) {
            return 'Нет измененных полей. Новая версия не создана';
        }

        // Если есть изменения, сохранить текущие версии карты и операций в архив
        DB::beginTransaction();
        try {
            $this->copyArchiveData();

            $operationIDs = $this->storeRealData($cardData, $operationsData);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        return $operationIDs;
    }

    private function checkChanges($cardData, $operationsData)
    {
        $cardChanged = $this->compareCardWithData($cardData);
        $operationsChanged = $this->compareOperationsWithData($operationsData);

        $isChanged = $cardChanged || $operationsChanged;
        return $isChanged;
    }

    private function compareCardWithData($cardData)
    {
        $changes = $this->compare($this->card->toArray(), $cardData);

        return count($changes) > 0;
    }

    private function compareOperationsWithData($operationsData)
    {
        foreach ($operationsData as $operationData) {
            $id = $operationData['id_v02'];

            // Если встретилась новая операция и прошла валидацию - значит изменения есть
            if (is_null($id)) {
                return true;
            }

            $operation = $this->operations->first(function ($item, $key) use ($id) {
                return $item->id_v02 === $id;
            });

            $changes = $this->compare($operation->toArray(), $operationData);

            if (count($changes) > 0) {
                return true;
            }
        }
        return false;
    }

    private function storeRealData($cardData, $operationsData)
    {
        $this->storeCardData($cardData);

        $operationIDs = $this->storeOperationsData($operationsData);

        return $operationIDs;
    }

    private function storeCardData($cardData)
    {
        $cardData['id_version'] = $this->card->id_version + 1;

        $this->card->update($cardData);
    }

    private function storeOperationsData($operationsData)
    {
        $changedOperationIDs = [];

        foreach ($operationsData as $operationData) {
            $id = $operationData['id_v02'];

            $operationData['id_version'] = $this->card->id_version;

            if ($id !== null) {
                $operation = $this->operations->first(function ($item, $key) use ($id) {
                    return $item->id_v02 === $id;
                });

                $operation->update($operationData);
            } else {
                $operationData['id_v01'] = $this->card->id_v01;
                unset($operationData['id_v02']);
                $id = ptt05v02::insertGetId($operationData, 'id_v02');
            }
            $changedOperationIDs[] = $id;
        }

        return $changedOperationIDs;
    }

    private function copyArchiveData()
    {
        $archiveCardID = $this->storeArchiveCardRecord();
        $this->storeArchiveOperationsRecords($archiveCardID);
    }

    private function storeArchiveCardRecord()
    {
        $archiveCard = $this->card->toArray();
        $archiveCard['service_number_editor'] = app(CommonService::class)->getCurrentUser()["tabNum"];
        //ниже удаляем поля которые мешают сохранению данных -> null
        $removeKeys = ['id_v06', 'id_v02', 'code_detail'];
        $archiveCard = app(Helper::class)->removeKeys($archiveCard, $removeKeys);
        return ptt05v03::insertGetId($archiveCard, 'id_v03');
    }

    private function storeArchiveOperationsRecords($archiveCardID)
    {
        $archiveOperations = [];
        foreach ($this->operations as $operation) {
            $archiveOperation = $operation->toArray();

            $archiveOperation['service_number_editor'] = app(CommonService::class)->getCurrentUser()["tabNum"];
            $archiveOperation['id_v03'] = $archiveCardID;

            $archiveOperations[] = $archiveOperation;
        }
        ptt05v04::insert($archiveOperations);
    }
    // Кастомный метод сравнения двух массивов по значениям
    private function compare($item1, $item2)
    {
        $changes = [];
        foreach ($item1 as $key => $value) {
            if (array_key_exists($key, $item2)) {
                if ($item2[$key] != $value) {
                    $changes[$key] = $item1[$key];
                }
            }
        }
        return $changes;
    }
}
