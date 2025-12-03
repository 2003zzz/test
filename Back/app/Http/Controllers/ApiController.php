<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Services\CommonService;

class ApiController extends Controller
{
    // Метод проверки работоспособности API
    public function works(Request $request)
    {
        // тут выводим данные о текущем подключении к бд в view 

        $connecttionName = config('database.default');
        $connecttionConfig = config("database.connections.$connecttionName");
        $databaseName = DB::connection()->getDatabaseName();

        return view('info', [
            'connectionName' => $connecttionName,
            'connectionName' => $connecttionConfig,
            'databaseName ' => $databaseName,
        ]);
    }

    // echo '<b>pass: </b>api works!' . '<br>';
    // try {
    //     $dbconnect = DB::connection();
    //     echo '<b>pass: </b>database works!' . '<br>';
    // } catch (\Throwable $th) {
    //     echo '<b>ERROR: </b>database connection failed!' . '<br>';
    // }

    // try {
    //     $tableconnect = $dbconnect->table('ptt05v01');
    //     echo '<b>pass: </b>connection to tables works!' . '<br>';
    // } catch (\Throwable $th) {
    //     echo '<b>ERROR: </b>no permissions to connect to tables or timeout! ' . '<br>';
    // }

    // try {
    //     $tableselect = $tableconnect->get();
    //     echo '<b>pass: </b>get table data works!' . '<br>';
    // } catch (\Throwable $th) {
    //     echo '<b>ERROR: </b>no permissions to getting data from tables or timeout! ' . '<br>';
    // }

    // phpinfo();

    // Метод записи логов
    public function log(Request $request)
    {
        $dateTime = date('d.m.Y H:i:s');
        $message = "{__}: {$dateTime}: {$request->message}";
        $personnelNumber = app(CommonService::class)->getCurrentUser()["tabNum"];
        Storage::disk('local')->append('logs/' . $personnelNumber . '.log', $message);
    }
}
