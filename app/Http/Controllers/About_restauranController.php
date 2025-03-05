<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class About_restauranController extends Controller
{
    public function index()
    {
        return view('about_restauran.index');
    }
}
