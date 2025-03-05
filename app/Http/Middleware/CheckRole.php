<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        // Получаем текущего пользователя
        $user = Auth::user();

        // Если пользователь не аутентифицирован, отклоняем запрос
        if (!$user) {
            abort(403, 'Доступ запрещен!');
        }

        // Получаем название роли пользователя из таблицы post
        $roleTitle = DB::table('post')->where('id', $user->id_post)->value('post');

        // Проверяем, входит ли роль пользователя в допустимые роли
        if (in_array($roleTitle, $roles)) {
            return $next($request);
        }

        abort(403, 'Доступ запрещен');
    }
}
