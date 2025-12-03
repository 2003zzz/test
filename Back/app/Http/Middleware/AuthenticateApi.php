<?php

namespace App\Http\Middleware;

use App\Services\CommonService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

class AuthenticateApi
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Если разработка проекта (подключение не с сервера) - пропускаем авторизацию
        $result = app(CommonService::class)->getCurrentUser();
        if (!App::environment(['local', 'staging'])) {
            // Проверка на доступность переменной REMOTE_USER
            $login = explode('@', request()->server('REMOTE_USER'))[0];
            if (isset($login)) {
                if ($login) {
                    // Авторизуем пользователя 
                    config()->set('database.connections.pgsql.username', $login);
                    DB::purge('pgsql');

                    if (!isset($result['login'])) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Пользователь не найден'
                        ], 401);
                    }

                    if (!isset($result['role'])) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Не хватает прав'
                        ], 403);
                    }
                } else {
                    // Обработка ошибки: пользователь не найден
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Пользователь не найден'
                    ], 401);
                }
            } else {
                // Обработка ошибки: переменная REMOTE_USER не доступна
                return response()->json([
                    'status' => 'error',
                    'message' => 'Не авторизован'
                ], 401);
            }
        }

        if (!empty($roles)) {
            if (!$this->hasRole($result['roles'], $roles)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Доступ запрещён',
                ], 403);
            }
        }

        return $next($request);
    }

    private function hasRole($userRoles, $needRoles)
    {
        if (empty($userRoles)) {
            return false;
        }

        if (empty($needRoles)) {
            return true;
        }

        $userRoles = array_map(function ($item) {
            return is_object($item) ? $item->role : $item;
        }, $userRoles);

        return count(array_intersect($userRoles, $needRoles)) > 0;
    }
}
