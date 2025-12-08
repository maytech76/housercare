<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SectorController extends Controller
{
    
    public function index(){

        $sectors = Sector::all();

        return view('sectors.index', compact('sectors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(){

        return view('sectors.create');
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){

        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'status' => 'required',
            'photo' => 'nullable', // Validación para la foto
        ]);

        // Validamos el campo photo
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('sectors', 'public');
        }

        $sector = Sector::create($validated);

        return redirect()->route('sectors.index')->with('success', 'Registro exitoso');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id){

        $sector = Sector::findOrFail($id);
        return view('sectors.edit', compact('sector'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sector $sector){

        $validated = $request->validate([

            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'status' => 'required',
            'photo' => 'nullable', // Validación para la foto

        ]);

        if ($request->hasFile('photo')) {
            // Eliminar foto anterior si existe
            if ($sector->photo) {
                Storage::disk('public')->delete($sector->photo);
            }
            $validated['photo'] = $request->file('photo')->store('horses', 'public');
        }

        $sector->update($validated);


        return redirect()->route('sectors.index')->with('success', 'Caballo actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sector $sector){
        
        try {
            // Eliminar foto si existe
            if ($sector->photo) {
                Storage::disk('public')->delete($sector->photo);
            }


            // Eliminar el registro
            $sector->delete();

            // Respuesta para AJAX
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registro eliminado exitosamente'
                ]);
            }

            // Redirección normal para peticiones no AJAX
            return redirect()->route('sectors.index');

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el registro: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error al eliminar el registro: ' . $e->getMessage());
        }
    }
}
