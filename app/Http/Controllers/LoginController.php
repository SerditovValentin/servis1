<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout', 'index');
    }

    public function index()
    {
        if (Auth::check()) {
            // Получаем id_post авторизованного сотрудника
            $employee = Auth::user();
            $id_post = $employee->id_post;

            // Выполняем SQL-запрос для получения должности
            $postTitle = DB::table('post')->where('id', $id_post)->value('post');

            // Редирект в зависимости от должности
            if ($postTitle === 'Админ') {
                return redirect()->route('employee');
            } elseif ($postTitle === 'Менеджер по закупкам') {
                return redirect()->route('stkeeper');
            }  else {
                return redirect()->route('position'); // Можно использовать любой маршрут по умолчанию
            }
        }
        
        // Если пользователь гость, возвращаем форму входа
        return view('login.index');
    }

    public function store(Request $request)
    {   
        $date = $request->validate([
            'phone'=> ['required', 'integer', 'digits:11', 'exists:employee,phone_number'],
            'password'=>['required'],
        ]);
        $credentials  = [
            'phone_number' => $date['phone'],
            'password' => $date['password']
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $employee = Auth::user();
            $id_post = $employee->id_post;

            // Массив маршрутов для ролей
            $roleRoutes = [
                'Админ' => 'employee',
                'Менеджер по закупкам' => 'stkeeper',
            ];

            // Получаем должность
            $postTitle = DB::table('post')->where('id', $id_post)->value('post');

            // Перенаправляем в зависимости от роли
            if (array_key_exists($postTitle, $roleRoutes)) {
                return redirect()->intended($roleRoutes[$postTitle]);
            } else {
                return back()->withErrors([
                    'message' => 'Не удалось определить должность пользователя.'
                ]);
            }
        } else {
            return back()->withErrors([
                'message' => 'Неверные учетные данные. Пожалуйста, попробуйте еще раз.',
            ]);
        }
    }

    protected function guard()
    {
        return Auth::guard();
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
