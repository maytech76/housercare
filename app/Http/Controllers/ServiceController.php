<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    /**
     * Display a listing of the services
     */
    public function index(){

        $services = Service::all();
    
        // Agregar índice manualmente
        $services->each(function ($service, $index) {
            $service->row_number = $index + 1;
        });
        
        return view('services.index', compact('services'));
    }

    /**
     * Show the form for creating a new services.
     */
    public function create(){
        return view('services.create');
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request){

        $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|boolean'
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('services', 'public');
            $data['photo'] = $photoPath;
        }

        Service::create($data);

        return redirect()->route('services.index')
            ->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     * MODIFICADO: Ahora retorna JSON para AJAX
     */
    public function edit(Service $service){
        // Retornar datos en formato JSON para la petición AJAX
        return response()->json([

               'success' => true,
                'service' => [
                'id' => $service->id,
                'name' => $service->name,
                'photo' => $service->photo,
                'status' => $service->status,
                'created_at' => $service->created_at,
                'updated_at' => $service->updated_at
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     * MODIFICADO: Maneja tanto peticiones normales como AJAX
     */
    public function update(Request $request, Service $service){

        $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|boolean'
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            // Eliminar imagen anterior si existe
            if ($service->photo && Storage::disk('public')->exists($service->photo)) {
                Storage::disk('public')->delete($service->photo);
            }
            
            $photoPath = $request->file('photo')->store('services', 'public');
            $data['photo'] = $photoPath;
        } else {
            unset($data['photo']);
        }

        $service->update($data);

        // Si es petición AJAX, retornar JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Service updated successfully.'
            ]);
        }

        // Para peticiones normales
        return redirect()->route('services.index')
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
   /**/
    public function destroy(Service $service){
        try {
            // Eliminar imagen si existe
            if ($service->photo && Storage::disk('public')->exists($service->photo)) {
                Storage::disk('public')->delete($service->photo);
            }

            $service->delete();

            // Si es petición AJAX, retornar JSON
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Service successfully removed.'
                ]);
            }

            // Para peticiones normales
            return redirect()->route('services.index')
                ->with('success', 'Service successfully removed.');

        } catch (\Exception $e) {
            // Si es petición AJAX, retornar error JSON
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting service: ' . $e->getMessage()
                ], 500);
            }

            // Para peticiones normales
            return redirect()->route('services.index')
                ->with('error', 'Error deleting service: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar el estado del servicio al presionar checkbox
     */
    public function changeStatus(Service $service){

        $service->update([
            'status' => !$service->status
        ]);

        $newStatus = $service->status ? 'active' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Service {$newStatus} correctly.");
    }

    public function getServiceData(Service $service){
        
        try {
            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $service->name,
                    'photo' => $service->photo ? asset('storage/' . $service->photo) : asset('admin/img/default-service.png')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving service data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}