<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use PhpParser\Node\Stmt\Foreach_;

class CheckAdmin
{
    const ADMIN_NAME = '02338430';
    const ADMINS = [
        "02335279",
        "02337744",
        "02338430",
        // вписать таб. номер для возможности просмотра логов
    ];

    public function handle(Request $request, Closure $next)
    {
        if (!App::environment(['local', 'staging'])) {
            if ($request->server('REMOTE_USER') === null) {
                abort(401);
                return redirect('home');
            }
            $remoteUser = substr($request->server('REMOTE_USER'), 1 + strpos($request->server('REMOTE_USER'), '\\')); // ”бираем им¤домена
            if (in_array($remoteUser, self::ADMINS)) {
                abort(401);
                return redirect('home');
            }
        }
        return $next($request);
    }
}
