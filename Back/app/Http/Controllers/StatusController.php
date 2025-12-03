<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\ptt05status;

class StatusController extends Controller
{
    // Получение статусов
    public function getStatuses(Request $request)
    {
        return ptt05status::where('id_status', '>', '1')->get();
    }
}
