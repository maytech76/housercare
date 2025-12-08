<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class Dashboard extends Controller{
    
    public function showDashboard(){
        $date = Carbon::now()->format('d-m-Y'); // Formato de fecha: Año-Mes-Día
        return view('admin.dashboard', compact('date'));
    }
}
