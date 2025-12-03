<?php

namespace App\Actions;

use App\Models\ptt05v01;
use App\Models\ptt05v02;

use Illuminate\Support\Facades\DB;

final class DuplicateOperationAction
{
    private array $operation;
    private array $cardIds;

    public function __invoke($request)
    {
        $this->operation = $request['operation'];
        $this->cardIds = $request['cardIds'];

        DB::beginTransaction();
        try {
            $operations = $this->fetchMaxOperationNumbers($this->cardIds);

            $copyOperations = $this->formatAndRenumerateCopiedOperations($operations);

            ptt05v02::insert($copyOperations);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

        return true;
    }

    public function fetchMaxOperationNumbers(array $cardIds)
    {
        // Все возможные карты для последующего join, включая карты, где нет операций
        $subQuery = ptt05v01::select('id_v01')->whereIn('id_v01', $cardIds);
        // Если нет операций, отсчет номеров начинается с 5
        $operations = ptt05v02::query()
            ->select('ids.id_v01', DB::raw('COALESCE(MAX(end_to_end_operation_number), 1) as end_to_end_operation_number'))
            ->rightJoinSub($subQuery, 'ids', function ($join) {
                $join->on('ptt05v02.id_v01', 'ids.id_v01');
            })
            ->groupBy('ids.id_v01')
            ->orderBy('ids.id_v01', 'desc')
            ->get();

        return $operations->toArray();
    }

    public function formatAndRenumerateCopiedOperations(array $operations)
    {
        $copyOperations = [];
        // Сортировка для правильной последовательности применения номеров операций
        $sortedIndices = collect($this->cardIds)->sortDesc()->values();

        foreach ($operations as $index => $operation) {
            $newOperation = $this->operation;

            $newOperation['id_v01'] = $sortedIndices[$index];
            unset($newOperation['id_v02']);
            $newOperation['end_to_end_operation_number'] = $operation['end_to_end_operation_number'] - $operation['end_to_end_operation_number'] % 5 + 5;

            $copyOperations[] = $newOperation;
        }

        return $copyOperations;
    }
}
