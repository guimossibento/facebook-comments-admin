<?php

namespace App\Interface\Http\Controllers\Admin;

use App\Interface\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }
}
