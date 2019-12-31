<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\System\User;

class AccessController extends Controller
{
    public function index () {
    	$usuarios = User::all();

    	return view('system.access.index')->with('usuarios', $usuarios);
    }
}
