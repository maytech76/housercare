<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Category;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Product_warehouse;


class AdjustStock extends Component
{

    public $categories , $warehouses, $users;
    public $products = [];
    public $orderItems;
    public $inventoryItems = [];
    public $category_id;
    public $searchProduct = '';
    public $selectedWarehouse = null; // Almacén seleccionado

    public function mount(){

        $this->users = User::all();
        $this->categories = Category::all();// listado de categorias
        $this->warehouses = warehouse::all(); // Cargar todos los almacenes
        $this->products = Product::with('warehouses')->get(); // inicia Cargando todos los productos
        $this->inventoryItems = collect(); // Inicializa la colección de items de orden.
        $this->orderItems = collect(); // Inicializa la colección de items de orden.
    }

    public function updated($propertyName){

        if ($propertyName === 'searchProduct' || $propertyName === 'category_id') {

            $this->filterProducts();
        }
    }

    public function filterProducts(){

            // Si el input de búsqueda está vacío, no mostrar productos
            if (empty($this->searchProduct)|| $this->category_id == 0) {
                $this->products = [];
                return;
            }

            // Buscar productos por nombre y categoría
            $query = Product::query();


            if (!empty($this->searchProduct)) {
                $query->where('name', 'like', '%' . $this->searchProduct . '%');
            }


            if (!empty($this->category_id)) {
                $query->where('category_id', $this->category_id);
            }

            $this->products = $query->get();
    }

    // Método para agregar productos a la tabla de ajuste de existencia
    public function addProduct($productId){
        
        $product = Product::find($productId);
    
        // Verifica si el producto ya está en la orden
        $existingItemIndex = collect($this->inventoryItems)
            ->search(fn($item) => $item['id'] === $productId);
    
        if ($existingItemIndex !== false) {
            // Si el producto ya existe, actualiza la cantidad directamente
            $this->inventoryItems[$existingItemIndex]['quantity'] += 1;
        } else {
            // Si no existe, añade el producto con cantidad inicial 1
            $this->inventoryItems[] = [
                
                'id' => $product->id,
                'name' => $product->name,
                'stock'=>$product->id,
                'price' => $product->price,
                
            ];
        }
    
        // Recalcula los totales
       /*  $this->calculateTotals(); */
    
        // Emitir evento para SweetAlert2
        $this->dispatch('productAdd');
    }





    public function render(){

        return view('livewire.adjust-stock')
        ->layout('admin.layouts.master', [
            'title' => 'Gestión de Inventario', // Opcional: pasar variables al layout
        ]);

    }
}
