<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\WithFileUploads;


class ProductImages extends Component
{

    use WithFileUploads;

    public $categories;
    public $category_id, $name, $description, $price;
    public $prod_id;
    public $images = [];

    public $step = 1; // Paso actual (1 = datos del producto, 2 = carga de imágenes)

    public function mount(){
        $this->step = 1; // Paso inicial
        $this->categories = Category::all(); // Cargar las categorías
    }

    public function saveProduct(){
        // Validar los datos del producto
        $validatedData = $this->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        // Crear el producto
        $product = Product::create($validatedData);

        // Guardar el ID del producto en la sesión
        $this->prod_id = $product->id;
        session()->put('prod_id', $this->prod_id);

        // Cambiar al paso 2 (carga de imágenes)
        $this->step = 2;
    }

    public function uploadImages(){

        $this->validate([
            'images.*' => 'image|max:4096', // Validar imágenes (máximo 4MB)
        ]);

        // Subir las imágenes
        foreach ($this->images as $image) {
            $image->store('products', 'public');
        }

        session()->flash('message', 'Producto creado y las imágenes fueron cargadas correctamente.');

        // Redirigir a la vista de productos
        return redirect()->route('products.index');
    }

    public function render(){

        return view('admin.product-images')
            ->layout('admin.layouts.master');
    }
}
