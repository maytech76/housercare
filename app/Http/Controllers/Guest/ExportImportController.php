<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\GuestsExport;
use App\Imports\GuestsImport;
use Maatwebsite\Excel\Facades\Excel;


class ExportImportController extends Controller
{
    // Exportar
    public function export() 
    {
        return Excel::download(new GuestsExport, 'guests.xlsx');
    }

    // Importar
    public function import(Request $request) 
    {
        $request->validate(['file' => 'required|mimes:xls,csv']);
        Excel::import(new GuestsImport, $request->file('file'));
        return back()->with('success', '¡Datos importados!');
    }
}
