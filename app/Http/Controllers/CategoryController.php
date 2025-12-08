<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
   
    public function index(){

        $types = [

            'supply' => 'Insumo',
            'shopping' => 'Compras',
            'service' => 'Servicios',
            
        ];

        $categories = Category::all();
        return view('categories.index', compact('categories', 'types'));

    }

    
    public function create(){

        $types = [

            'supply' => 'Insumo',
            'shopping' => 'Compras',
            'service' => 'Servicios',
            
        ];

        return view('categories.create', compact('types'));
    }

    
    public function store(Request $request){

        /*  dd($request); */

        $category = Category::create($request->only( 'name', 'type', 'description'));

        return redirect()->route('categories.index')->with([
            'success' => 'Categoria creada correctamente.',
            'category_id' => $category->id
        ]);
    }

    
    public function show(string $id)
    {
        //
    }

   
    public function edit(string $id){

        $types = [

            'supply' => 'Insumo',
            'shopping' => 'Compras',
            'service' => 'Servicios',
            
        ];

        $category = Category::findOrFail($id);
            return response()->json( [
            
            'category' => $category,
            'types' => $types,
            'current_type' => $category->type

            ]);
    }

    
    public function update(Request $request, string $id){

        $validated = $request->validate([

            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:supply,shopping,service',

        ]);
    
        $category = Category::findOrFail($id);
    
        $category->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'type' => $validated['type']
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Categoría actualizada correctamente'
        ]);
    }

    
    public function destroy(string $id){

        $category = Category::findOrFail($id);

        // Eliminar el usuario
        $category->delete();
    
        return response()->json(['message' => 'Categoria eliminada correctamente'], 200);
    }
}
