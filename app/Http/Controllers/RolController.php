<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;



class RolController extends Controller
{
    public function index(){

        $roles = Rol::all();
        return view('auth.register', compact('roles'));

    }
}
