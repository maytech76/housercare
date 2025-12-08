<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping{

    protected $products;
    protected $selectedWarehouse; // Definir la propiedad

    // Recibir productos filtrados
    public function __construct($products, $selectedWarehouse = null){

        $this->products = $products;
        $this->selectedWarehouse = $selectedWarehouse; // Asignar el almacén seleccionado
    }

    public function collection(){
        
        return $this->products->flatMap(function ($product) {
            if ($this->selectedWarehouse) {
                // Filtrar solo el almacén seleccionado
                $filteredWarehouse = $product->warehouses->where('id', $this->selectedWarehouse)->first();

                return $filteredWarehouse ? [$product] : [];
            }

            // Si no hay almacén seleccionado, devolver una entrada por cada almacén
            return $product->warehouses->map(fn($warehouse) => $product);
        });
    }

    public function headings(): array{

        return ["ID", "Nombre", "Descripción", "Precio", "Stock", "Almacén"];
    }

    public function map($product): array{

        {
            $rows = [];
        
            if ($this->selectedWarehouse) {
                // Filtrar solo el almacén seleccionado
                $filteredWarehouse = $product->warehouses->where('id', $this->selectedWarehouse)->first();
        
                if ($filteredWarehouse) {
                    $rows[] = [

                        $product->category->name,
                        $product->name,
                        $product->description,
                        $product->price,
                        $filteredWarehouse->pivot->stock ?? 0,
                        $filteredWarehouse->name
                       
                    ];
                }
            } else {
                // Si no hay almacén seleccionado, recorrer todos los almacenes
                foreach ($product->warehouses as $warehouse) {
                    $rows[] = [
                        $product->category->name,
                        $product->name,
                        $product->description,
                        $product->price,
                        $warehouse->pivot->stock ?? 0,
                        $warehouse->name
                       
                    ];
                }
            }
        
            return $rows;
       }
    }


}
