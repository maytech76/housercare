<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use App\Exports\ProductsExport;
use App\Models\Category;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Stock;
use App\Models\Store;
use App\Models\Unit;
use App\Models\UnitConversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // ← Importar Facade Log

class ProductController extends Controller
{


    public function index(){
        try {
            Log::info('Accediendo a la lista de productos');
            
            $products = Product::with(['category', 'purchaseUnit', 'consumptionUnit', 'unitConversion', 'stocks'])
                ->where('status', true)
                ->latest()
                ->get()
                ->map(function ($product) {
                    // Calcular la existencia total sumando purchase_quantity de todos los stocks
                    $product->total_stock = $product->stocks->sum('purchase_quantity');
                    return $product;
                });
                
            Log::info('Productos cargados exitosamente', ['count' => $products->count()]);
            
            return view('products.index', compact('products'));
            
        } catch (\Exception $e) {
            Log::error('Error al cargar la lista de productos', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->back()->withErrors('Error al cargar la lista de productos: ' . $e->getMessage());
        }
    }


    public function create(){

        try {
            Log::info('Accediendo al formulario de creación de producto');
            
            $categories = Category::where('status', true)->get();
            $units = Unit::where('status', true)->get();
            $stores = Store::where('is_active', true)->get();
            $unitConversions = UnitConversion::with(['fromUnit', 'toUnit'])
                ->where('is_active', true)
                ->get();

            Log::debug('Datos para formulario de creación', [
                'categories_count' => $categories->count(),
                'units_count' => $units->count(),
                'stores_count' => $stores->count(),
                'conversions_count' => $unitConversions->count()
            ]);

            return view('products.create', compact(
                'categories', 
                'units', 
                'stores', 
                'unitConversions'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de creación', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->back()->withErrors('Error al cargar el formulario: ' . $e->getMessage());
        }
    }


    public function store(Request $request){

        try {
            Log::info('Iniciando creación de producto', ['request_data' => $request->except(['photo'])]);
            
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id', 
                'type' => 'required|in:supply,product,medicine,equipment,service',
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'purchase_unit_id' => 'required',
                'consumption_unit_id' => 'required',
                'unit_conversion_id' => 'nullable',
                'cost' => 'nullable|numeric|min:0',
                'price' => 'nullable|numeric|min:0',
                'expired' => 'nullable|date',
                'codebar' => 'nullable|string|max:150',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'initial_stock' => 'nullable|array',
                'initial_stock.*.store_id' => 'required_with:initial_stock|exists:stores,id',
                'initial_stock.*.quantity' => 'required_with:initial_stock|numeric|min:0'
            ]);

            Log::debug('Validación exitosa', ['validated_data' => $validated]);


            // Verificar coherencia entre unidades y conversión
            if ($validated['unit_conversion_id']) {
                $conversion = UnitConversion::find($validated['unit_conversion_id']);
                
                if (!$conversion) {
                    Log::warning('Conversión no encontrada', ['conversion_id' => $validated['unit_conversion_id']]);
                    return back()->withErrors([
                        'unit_conversion_id' => 'La conversión seleccionada no existe'
                    ])->withInput();
                }
                
                if ($conversion->from_unit_id != $validated['purchase_unit_id'] || 
                    $conversion->to_unit_id != $validated['consumption_unit_id']) {
                    Log::warning('Inconsistencia en unidades y conversión', [
                        'purchase_unit' => $validated['purchase_unit_id'],
                        'consumption_unit' => $validated['consumption_unit_id'],
                        'conversion_from' => $conversion->from_unit_id,
                        'conversion_to' => $conversion->to_unit_id
                    ]);
                    
                    return back()->withErrors([
                        'unit_conversion_id' => 'La conversión seleccionada no corresponde a las unidades especificadas'
                    ])->withInput();
                }
            }

            // Handle photo upload
            if ($request->hasFile('photo')) {
                Log::debug('Subiendo imagen de producto');
                $validated['photo'] = $request->file('photo')->store('products', 'public');
                Log::info('Imagen subida exitosamente', ['path' => $validated['photo']]);
            }

            $product = Product::create($validated);
            Log::info('Producto creado exitosamente', ['product_id' => $product->id]);

            // Create initial stocks
            if ($request->has('initial_stock')) {
                Log::debug('Procesando stock inicial', ['stock_data' => $request->initial_stock]);
                
                foreach ($request->initial_stock as $index => $stockData) {
                    if ($stockData['quantity'] > 0 && !empty($stockData['store_id'])) {
                        Stock::create([
                            'product_id' => $product->id,
                            'store_id' => $stockData['store_id'],
                            'purchase_quantity' => $stockData['quantity'],
                            'consumption_quantity' => $stockData['quantity'] * ($conversion->factor ?? 1)
                        ]);
                        Log::info('Stock creado', [
                            'store_id' => $stockData['store_id'],
                            'quantity' => $stockData['quantity']
                        ]);
                    }
                }
            }

            Log::info('Producto creado completamente', ['product_id' => $product->id]);
            
            return redirect()->route('products.index')
                ->with('success', 'Producto creado exitosamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Error de validación en creación de producto', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            throw $e;
            
        } catch (\Exception $e) {
            Log::error('Error al crear producto', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors('Error al crear el producto: ' . $e->getMessage())->withInput();
        }
    }


    public function edit(Product $product){
        try {
            Log::info('Accediendo a edición de producto', ['product_id' => $product->id]);
            
            $categories = Category::where('status', true)->get();
            $units = Unit::where('status', true)->get();
            $stores = Store::where('is_active', true)->get();
            $unitConversions = UnitConversion::with(['fromUnit', 'toUnit'])
                ->where('is_active', true)
                ->get();
            
            $product->load('stocks');

            Log::debug('Datos para edición cargados', [
                'product' => $product->toArray(),
                'stocks_count' => $product->stocks->count()
            ]);

            return view('products.edit', compact(
                'product', 
                'categories', 
                'units', 
                'stores',
                'unitConversions'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de edición', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->back()->withErrors('Error al cargar el formulario de edición: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Product $product){

        try {
            Log::info('Iniciando actualización de producto', [
                'product_id' => $product->id,
                'request_data' => $request->except(['photo'])
            ]);
            
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'type' => 'required|in:supply,product,medicine,equipment,service',
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'purchase_unit_id' => 'required|exists:units,id',
                'consumption_unit_id' => 'required|exists:units,id',
                'unit_conversion_id' => 'required|exists:unit_conversions,id',
                'cost' => 'nullable|numeric|min:0',
                'price' => 'nullable|numeric|min:0',
                'expired' => 'nullable|date',
                'codebar' => 'nullable|string|max:150',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            Log::debug('Validación exitosa para actualización', ['validated_data' => $validated]);

            // Verificar coherencia entre unidades y conversión
            $conversion = UnitConversion::find($validated['unit_conversion_id']);
            if (!$conversion) {
                Log::warning('Conversión no encontrada en actualización', ['conversion_id' => $validated['unit_conversion_id']]);
                return back()->withErrors([
                    'unit_conversion_id' => 'La conversión seleccionada no existe'
                ])->withInput();
            }
            
            if ($conversion->from_unit_id != $validated['purchase_unit_id'] || 
                $conversion->to_unit_id != $validated['consumption_unit_id']) {
                Log::warning('Inconsistencia en unidades y conversión durante actualización', [
                    'purchase_unit' => $validated['purchase_unit_id'],
                    'consumption_unit' => $validated['consumption_unit_id'],
                    'conversion_from' => $conversion->from_unit_id,
                    'conversion_to' => $conversion->to_unit_id
                ]);
                
                return back()->withErrors([
                    'unit_conversion_id' => 'La conversión seleccionada no corresponde a las unidades especificadas'
                ])->withInput();
            }

            // Handle photo upload
            if ($request->hasFile('photo')) {
                Log::debug('Actualizando imagen del producto');
                // Delete old photo
                if ($product->photo) {
                    Storage::disk('public')->delete($product->photo);
                    Log::info('Imagen anterior eliminada', ['old_photo' => $product->photo]);
                }
                $validated['photo'] = $request->file('photo')->store('products', 'public');
                Log::info('Nueva imagen subida', ['new_photo' => $validated['photo']]);
            }

            $product->update($validated);
            Log::info('Producto actualizado exitosamente', ['product_id' => $product->id]);

            return redirect()->route('products.index')
                ->with('success', 'Producto actualizado exitosamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Error de validación en actualización de producto', [
                'product_id' => $product->id,
                'errors' => $e->errors()
            ]);
            throw $e;
            
        } catch (\Exception $e) {
            Log::error('Error al actualizar producto', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors('Error al actualizar el producto: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Product $product){
        
        try {
            Log::info('Iniciando eliminación lógica de producto', ['product_id' => $product->id]);
            
            $product->update(['status' => false]);
            
            Log::info('Producto desactivado exitosamente', ['product_id' => $product->id]);

            return redirect()->route('products.index')
                ->with('success', 'Producto eliminado exitosamente.');
                
        } catch (\Exception $e) {
            Log::error('Error al eliminar producto', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->back()->withErrors('Error al eliminar el producto: ' . $e->getMessage());
        }
    }

    public function getConversionsByUnits(Request $request){
        try {
            Log::info('Solicitando conversiones por unidades', [
                'purchase_unit_id' => $request->purchase_unit_id,
                'consumption_unit_id' => $request->consumption_unit_id
            ]);
            
            $request->validate([
                'purchase_unit_id' => 'required|exists:units,id',
                'consumption_unit_id' => 'required|exists:units,id'
            ]);

            $conversions = UnitConversion::with(['fromUnit', 'toUnit'])
                ->where('from_unit_id', $request->purchase_unit_id)
                ->where('to_unit_id', $request->consumption_unit_id)
                ->where('is_active', true)
                ->get();

            Log::debug('Conversiones encontradas', [
                'count' => $conversions->count(),
                'conversions' => $conversions->toArray()
            ]);

            return response()->json($conversions);
            
        } catch (\Exception $e) {
            Log::error('Error al obtener conversiones', [
                'purchase_unit_id' => $request->purchase_unit_id,
                'consumption_unit_id' => $request->consumption_unit_id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'error' => 'Error al obtener las conversiones',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}