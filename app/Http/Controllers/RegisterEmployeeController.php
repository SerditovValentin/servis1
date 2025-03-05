<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RegisterEmployeeController extends Controller
{
    public function index()
    {
        $posts = DB::table('post')->select('id', 'post')->get();
        return view('registerEmployee.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'surname'=> ['required', 'string', 'max:50'],
            'name'=> ['required', 'string', 'max:50'],
            'patronymic' => ['nullable', 'string', 'max:50'],
            'date_of_birth'=> ['required', 'before:' . now()->subYears(14)->format('Y-m-d')],
            'id_post'=> ['required'],
            'phone'=> ['required', 'integer', 'unique:employee,phone_number', 'digits:11'],
            'password'=>['required', 'min:6','confirmed'],
            
        ]);

        $employee = new Employee;
        $employee->surname = $validated['surname'];
        $employee->name = $validated['name'];
        $employee->patronymic = $validated['patronymic'];
        $employee->date_of_birth = $validated['date_of_birth'];
        $employee->id_post = $validated['id_post'];
        $employee->phone_number = $validated['phone'];
        $employee->password = bcrypt($validated['password']);
        $employee->save();

        return redirect()->route('employee'); 

              
    }
}
