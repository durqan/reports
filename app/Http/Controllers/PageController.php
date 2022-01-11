<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

class PageController extends Controller
{
    public function index()
    {
        return view('index');
    }
}
