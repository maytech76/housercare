<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Warehouse;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\PageMargins;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Inventory extends Component
{

    public $categories,$warehouses; // Lista de almacenes
    public $product_warehouses; // Lista de almacenes
    public $products =[];
    public $refreshDataTable;
    public $exportToPDF;
    public $selectedCategory = null;
    public $selectedWarehouse =null; // Almacén seleccionado
  
    public $stockAdjustment; // Cantidad a ajustar
    
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(){

        $this->categories = Category::all();// listado de categorias
        $this->warehouses = warehouse::all(); // Cargar todos los almacenes
      /*   $this->loadProducts(); */
         $this->products = Product::with('warehouses')->get(); // inicia Cargando todos los productos
    }

    public function searchProducts(){
        
       /*  logger('Botón de búsqueda presionado'); */

        $query = Product::query()->with('warehouses');

        // Filtrar por categoría si está seleccionada
        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        // Filtrar por almacén si está seleccionado
        if ($this->selectedWarehouse) {
            $query->whereHas('warehouses', function ($q) {
                $q->where('warehouse_id', $this->selectedWarehouse);
            });
        }

        $this->products = $query->get();

         // Emitir evento para reinicializar DataTables después de actualizar la tabla
        $this->dispatch('refreshDataTable');

        // Forzar actualización
        $this->dispatch('refreshComponent');
    }


    public function updatedSelectedCategory(){

        $this->searchProducts();
    }

    public function updatedSelectedWarehouse(){

        $this->searchProducts();
    }

    //Crear Pdf
    public function exportToPDF(){

        $pdf = Pdf::loadView('exports.products', ['products' => $this->products]);
        return response()->streamDownload(fn () => print($pdf->output()), 'products.pdf');
    }


    // Exportar a Excel
    public function exportToExcel(){

        return Excel::download(new class($this->products, $this->selectedWarehouse) 
            implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithDrawings, WithEvents{
            protected $products;
            protected $selectedWarehouse;

        public function __construct($products, $selectedWarehouse)
        {
            $this->products = $products;
            $this->selectedWarehouse = $selectedWarehouse;
        }

        // Datos que se exportarán al Excel
        public function collection()
        {
            return $this->products->map(function ($product) {
                return [
                    'Nombre' => $product->name,
                    'Descripción' => $product->description,
                    'Precio' => $product->price,
                    'Stock' => $this->selectedWarehouse 
                        ? ($product->warehouses->where('id', $this->selectedWarehouse)->first()?->pivot->stock ?? 0) 
                        : ($product->total_stock ?? 0), 
                    'Almacenes' => $product->warehouses->pluck('name')->join(', '),
                ];
            });
        }

        // Encabezados del archivo
        public function headings(): array
        {
            return ['PRODUCTO', 'DESCRIPCIÓN', 'PRECIO', 'STOCK', 'ALMACEN', 'CONTADO'];
        }

        // Definir los estilos de los encabezados y filas
        public function styles(Worksheet $sheet){

            // Estilos para encabezados (Fila 5 porque hay 4 filas para el logo)
            $sheet->getStyle('B5:G5')->applyFromArray([

                'font' => ['bold' => true, 'size' => 8, 'name' => 'Arial'],

                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'D3D3D3'] // Gris claro
                ],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],

                // Datos: Arial 10px
                'A2:E1000' => [ 
                    'font' => ['size' => 8, 'name' => 'Arial'],
                ],

                // Centrar la columna "Stock" horizontalmente
                'D' => [
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ],

            
             

            ]);

            
        }

        // Agregar el logo de la empresa
        public function drawings(){

            $drawing = new Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo de la empresa');
            $drawing->setPath(public_path('admin/img/brand/logo.png')); // Ruta del logo en tu proyecto
            $drawing->setHeight(50); // Tamaño del logo
            $drawing->setCoordinates('B1'); // Posición del logo
            $drawing->setOffsetX(60); // Ajuste horizontal
            $drawing->setOffsetY(15); // Ajuste vertical
            return [$drawing];
        }

        // Iniciar tabla después del logo
        public function startCell(): string
        {
            return 'B5';
        }

        // Configuración de la hoja con eventos (orientación vertical y tamaño carta)
        public function registerEvents(): array{

            return [
                BeforeSheet::class => function (BeforeSheet $event) {

                    $sheet = $event->sheet->getDelegate();

                    // Ajustar automáticamente el ancho de las columnas
                    foreach (range('B', 'G') as $col) {
                        $sheet->getColumnDimension($col)->setAutoSize(true);
                    }

                    // Configurar márgenes reducidos
                    $pageMargins = $sheet->getPageMargins();
                    $pageMargins->setTop(0.4);
                    $pageMargins->setRight(0.2);
                    $pageMargins->setLeft(0.2);
                    $pageMargins->setBottom(0.4);
                    $pageMargins->setHeader(0.2);
                    $pageMargins->setFooter(0.2);

                    $event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                    /* $event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT); */ /* Orientacion de la hoja en Vectical */
                    $event->sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LETTER);

                    // Agregar título "TOMA DE INVENTARIO"
                    $sheet->setCellValue('C2', 'TOMA DE INVENTARIO');

                    // Aplicar estilos al título
                    $sheet->getStyle('C2')->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => 14,
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                    ]);

                // Fusionar celdas para centrar el título
                $sheet->mergeCells('C1:E1');
            },
                
            ];
        }

        

     }, 'products.xlsx');

    }
    

    public function render(){

        

        
        return view('livewire.inventory')
        ->layout('admin.layouts.master', [
            'title' => 'Gestión de Inventario', // Opcional: pasar variables al layout
        ]);
    }

}
