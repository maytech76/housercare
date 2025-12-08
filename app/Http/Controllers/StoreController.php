<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    
    public function index()
    {
        $stores = Store::all();
        return view('stores.index', compact('stores'));
    }

    

    
    public function store(Request $request){

        $store = Store::create($request->only( 'name', 'location'));

        return redirect()->route('stores.index')->with([
            'success' => 'Deposito creado correctamente.',
            'store_id' => $store->id
        ]);
    }


    
    public function edit(string $id){

        // Obtener el almacén por ID
        $store = Store::findOrFail($id);
        
        // Opciones para el estado activo/inactivo
        $statusOptions = [
            1 => 'Activo',
            0 => 'Inactivo'
        ];
        
        // Devolver los datos al formato JSON
        return response()->json([
            
            'id' => $store->id,
            'name' => $store->name,
            'location' => $store->location,
            'is_active' => $store->is_active,
            'status_options' => $statusOptions
        ]);
    }

   
    public function update(Request $request, string $id){

        $store = Store::findOrFail($id);
        $store->update($request->all());
        
        return response()->json(['success' => true]);
    }

    
    public function destroy(string $id){

        $store = Store::findOrFail($id);

        // Eliminar Deposito
        $store->delete();
    
        return response()->json(['message' => 'Deposito eliminado correctamente'], 200);
    }
}
