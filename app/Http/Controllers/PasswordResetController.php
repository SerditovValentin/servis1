<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function showResetForm()
    {
        return view('password_reset.reset');
    }

    public function reset(Request $request)
    {
        // Валидация данных
        $request->validate([
            'phone_number' => ['required', 'integer', 'exists:employee,phone_number', 'digits:11'],
            'password' => ['required', 'min:6','confirmed'],
        ]);

        // Найти пользователя по номеру телефона
        $employee = Employee::where('phone_number', $request->phone_number)->first();

        if (!$employee) {
            return back()->withErrors(['phone_number' => 'Пользователь с таким номером телефона не найден.']);
        }

        // Обновить пароль
        $employee->password = Hash::make($request->password);
        $employee->save();

        return redirect()->route('password.reset')->with('status', 'Пароль успешно обновлен.');
    }
}
