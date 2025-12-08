<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(){

        $units = Unit::all();
        return view('units.index', compact('units'));
    }

    public function create(){
        return view('units.create');
    }

    public function store(Request $request){

        $request->validate([

            'name' => 'required',
            'symbol' => 'required|max:5',
            'description' => 'required'
        ]);

        $unit = Unit::create($request->only(['name', 'symbol', 'description']));

        return redirect()->route('units.index')->with([
            'success' => 'Deposito creado correctamente.',
            'store_id' => $unit->id
        ]);
    }

    public function edit(string $id){

        // Obtener el almacén por ID
        $unit = Unit::findOrFail($id);
        
        // Opciones para el estado activo/inactivo
        $statusOptions = [
            1 => 'Activo',
            0 => 'Inactivo'
        ];
        
        // Devolver los datos al formato JSON
        return response()->json([
            
            'id' => $unit->id,
            'name' => $unit->name,
            'symbol' => $unit->symbol,
            'description' => $unit->description,
            'status' => $unit->status,
            'status_options' => $statusOptions
        ]);

    }

    public function update(Request $request, string $id){

        $request->validate([

            'name' => 'required',
            'symbol' => 'required|max:5',
            'description' => 'required'
        ]);

        $unit = Unit::findOrFail($id);
        $unit->update($request->all());
        
        return response()->json(['success' => true]);

    }

    public function destroy($id)
    {
        try {
            $unit = Unit::findOrFail($id);
            $unit->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Unidad eliminada correctamente'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la unidad: ' . $e->getMessage()
            ], 500);
        }
    }


}
