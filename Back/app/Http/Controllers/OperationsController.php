<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ptt05v02;
use App\Actions\DuplicateOperationAction;
use App\Http\Requests\DuplicateOperationRequest;

class OperationsController extends Controller
{
    // Удаление операции
    public function deleteOperation(Request $request)
    {
        return ptt05v02::destroy($request->operationID);
    }

    // Копирование операции в карты указанных ДСЕ
    public function duplicateOperationToCards(DuplicateOperationRequest $request)
    {
        return (new DuplicateOperationAction)($request->validated());
    }
}
