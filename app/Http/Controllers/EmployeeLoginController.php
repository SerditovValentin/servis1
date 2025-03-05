<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeLoginController extends Controller
{
    public function index()
    {
        return view('login.index');
    }

    public function login(Request $request)
    {   
        
        $date = $request->validate([
            'phone'=> ['required', 'integer', 'exists:client,phone_number'],
            'password'=>['required'],
        ]);
        $credentials  = [
            'phone_number' => $date['phone'],
            'password' => $date['password']
        ];

        if (Auth::guard('employee')->attempt($credentials)) {
            // Аутентификация успешна, перенаправляем на страницу сотрудника
            return redirect()->intended('/employee');
        }

        // if (Auth::attempt($credentials)) {
        //     $request->session()->regenerate();
        //     return redirect()->intended('employee');
        // } 
    }

    // protected function guard()
    // {
    //     return Auth::guard();
    // }

    // public function logout(Request $request)
    // {
    //     $this->guard()->logout();

    //     $request->session()->invalidate();

    //     $request->session()->regenerateToken();

    //     return redirect('/')->withHeaders([
    //         'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
    //         'Pragma' => 'no-cache',
    //         'Expires' => 'Wed, 11 Jan 1984 05:00:00 GMT',
    //     ]);
    // }
    public function logout()
    {
        Auth::guard('employee')->logout();
        return redirect()->route('employee.login');
    }
}
