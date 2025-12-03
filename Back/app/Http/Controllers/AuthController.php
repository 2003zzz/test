<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Авторизация пользователя
    public function auth(Request $request)
    {
        return app(AuthService::class)->getUserRole();
    }

    // Получение информации из кадрового
    public function kadr(Request $request)
    {
        $remoteUser = $this->getTabNum($request);
    }
}
