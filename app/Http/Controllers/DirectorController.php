<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class DirectorController extends Controller
{
    public function index()
    {
        return view('director.index');
    }
}