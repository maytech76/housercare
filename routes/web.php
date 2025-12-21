<?php

use App\Exports\ProductsExport;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Guest\ExportImportController; 
use App\Http\Controllers\HorseCard2Controller;
use App\Http\Controllers\HorseCardController;
use App\Http\Controllers\HorseController;
use App\Http\Controllers\HorseMovementController;
use App\Http\Controllers\HorseSpecialConditionController;
use App\Http\Controllers\InventoryControlController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\StableController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\SupplyDetailController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ServiceController;



/* Livewire */
use App\Livewire\ProductImages;
use App\Livewire\Inventory;
use App\Livewire\AdjustStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;




//public access to photo this guest
Route::get('/storage/guest_photos/{filename}', function ($filename) {
    $path = storage_path('app/public/guest_photos/' . $filename);
    
    if (!File::exists($path)) {
        abort(404, 'La imagen no existe');
    }
    
    // Determinar el tipo MIME para la respuesta
    $mime = mime_content_type($path);
    
    return response()->file($path, [
        'Content-Type' => $mime,
        'Cache-Control' => 'public, max-age=31536000', // Cache por 1 año
    ]);
})->name('storage.guest_photos');


//Public acces photo to QR
Route::get('/storage/qr_codes/{filename}', function ($filename) {
    $path = storage_path('app/public/qr_codes/' . $filename);
    
    if (!File::exists($path)) {
        abort(404, 'La imagen no existe');
    }
    
    // Determinar el tipo MIME para la respuesta
    $mime = mime_content_type($path);
    
    return response()->file($path, [
        'Content-Type' => $mime,
        'Cache-Control' => 'public, max-age=31536000', // Cache por 1 año
    ]);
})->name('storage.qr_codes');



//Ruta para escanear el QR
Route::view('/scanner', 'scanner')->name('scanner');


/* Rutas para el modulo Productos y Dropzone */
Route::get('products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}/images', [ProductController::class, 'getImages']);
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
Route::get('products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::post('/products/upload', [ProductController::class, 'uploadImage'])->name('products.upload');
Route::get('products/export/', [ProductsExport::class, 'productsexport'])->name('products.export');



Route::get('/register', [RolController::class, 'index'])->name('register');
Route::post('/register', [RolController::class, 'create'])->name('register.create');
Route::post('/register', [RolController::class, 'store'])->name('register.store'); 
Route::get('users', [UserController::class, 'index'])->name('users');
Route::post('user', [UserController::class, 'store'])->name('users.store'); 
Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('users/{id}', [UserController::class, 'update'])->name('users.update'); 
Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

Route::get('reservation/calendario', function(){
    return view('reservations.calendario');
})->name('reservations.calendario');


/* ------ RUTAS PARA ASESORES ------- */
Route::get('/asesor/calendario',function(){
    return view('asesor.calendario');
    })->name('asesor.calendario');

/* Route::get('asesor/fullcalendar',[ReservationController::class,'getReservationsAsesor'])->name('asesor.fullcalendar');
/* ------ FINAL RUTAS ASESORES ------- */ 


/* ------ RUTAS PARA CLIENTES ------- */
/* Route::get('/cliente/pagos',[ReservationController::class,'showClientPayments'])->name('cliente.pago'); */

Route::get('/cliente/calendario',function(){
    return view('cliente.calendario');
    })->name('cliente.calendario');



Route::get('/test-time', function() {
    return [
        'timezone' => config('app.timezone'),
        'current_time' => now()->format('Y-m-d H:i:s'),
        'db_time' => DB::select(DB::raw('SELECT NOW() as now'))[0]->now
    ];
});

/* ------ FINAL RUTAS CLIENTES ------- */


Route::get('/', function () {
    return view('welcome');
    });

Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
      ])->group(function () {
        
        Route::get('/admin/dashboard', function () {
            return view('/admin/dashboard');
        })->name('dashboard');

        Route::get('/admin/dashboard', [Dashboard::class, 'showDashboard'])->name('dashboard');
      

        Route::get('/admin/products', function () {
            return view('admin/products');
        })->name('product');

        /* Visualizar en el modal producto selecionado y sus detalles */
        Route::get('admin/products/{product}/details', [ProductController::class, 'productDetails'])->name('admin/products.details');

        Route::get('/admin/inventory', Inventory::class)->name('admin.inventory');

        Route::get('/admin/ajust-stock', AdjustStock::class)->name('admin.stock');

        Route::get('admin/products/images', ProductImages::class)->name('admin.products.images');
        
       /**** Inventory - Controller  ****/
       Route::get('inventory-controls', [InventoryControlController::class, 'index'])->name('inventories.index');

        /***** Categories ****/
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('categories/store', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

       
        /**** Stores ****/
        Route::get('stores', [StoreController::class, 'index'])->name('stores.index');
        Route::post('stores/store', [StoreController::class, 'store'])->name('stores.store');
        Route::get('/stores/{store}/edit', [StoreController::class, 'edit'])-> name('stores.edit');
        Route::put('/stores/{store}', [StoreController::class, 'update'])-> name('stores.update');
        Route::delete('/stores/{id}', [StoreController::class, 'destroy'])-> name('stores.destroy');


        /**** Units ****/
        Route::get('units', [UnitController::class, 'index'])->name('units.index');
        Route::post('units/store', [UnitController::class, 'store'])->name('units.store');
        Route::get('/units/{unit}/edit', [UnitController::class, 'edit'])-> name('units.edit');
        Route::put('/units/{unit}', [UnitController::class, 'update'])-> name('units.update');
        /* Route::delete('/units/{id}', [UnitController::class, 'destroy'])-> name('units.destroy'); */
        Route::delete('/units/{unit}', [UnitController::class, 'destroy'])->name('units.destroy');

        /**** InventoryController ****/
        Route::resource('inventory-controls', InventoryControlController::class);
        Route::get('inventory-controls/report', [InventoryControlController::class, 'report'])->name('inventory-controls.report');
        Route::get('inventory-controls/confirmation/pdf/{movement}', [InventoryControlController::class, 'confirmationPdf'])->name('inventory-controls.confirmation.pdf');

        /* Consulta API para recuperar la existencia total de un producto en un store especifico */
        Route::get('/api/product-stock/{productId}/{storeId}', [InventoryControlController::class, 'getProductStock'])->name('api.product-stock');

        // Para los detalles del modal
        Route::get('/inventory-controls/operations/{operation}/details', [InventoryControlController::class, 'operationDetails'])
        ->name('inventory-controls.operation.details');

        // Para editar operación
        Route::get('/inventory-controls/operations/{operation}/edit', [InventoryControlController::class, 'editOperation'])
        ->name('inventory-controls.operation.edit');

        // Para PDF de operación
        Route::get('/inventory-controls/operations/{operation}/pdf', [InventoryControlController::class, 'operationPdf'])
        ->name('inventory-controls.operation');

        //Rutas Modulo Suministros 
        Route::resource('supplies', SupplyController::class);
        Route::post('/supplies/search-products', [SupplyController::class, 'searchProducts'])->name('supplies.search-products');
        Route::post('/supplies/{supply}/execute', [SupplyController::class, 'executeSupply'])->name('supplies.execute');
        Route::get('/supplies-by-date', [SupplyController::class, 'getSuppliesByDate'])->name('supplies.by-date');


        // Ruta para los detalles del suministro (modal)
        Route::get('/supplies/{id}/details', [SupplyController::class, 'supplyDetails'])->name('supplies.details');
        

        // Rutas para detalles de suministros
       Route::prefix('supplies/{supply}/details')->group(function () {
            Route::get('/', [SupplyDetailController::class, 'getBySupply'])->name('supply-details.by-supply');
            Route::post('/', [SupplyDetailController::class, 'store'])->name('supply-details.store');
            Route::get('/create', [SupplyDetailController::class, 'create'])->name('supply-details.create');
            Route::get('/{detail}/edit', [SupplyDetailController::class, 'edit'])->name('supply-details.edit');
            Route::put('/{detail}', [SupplyDetailController::class, 'update'])->name('supply-details.update');
            Route::delete('/{detail}', [SupplyDetailController::class, 'destroy'])->name('supply-details.destroy');
       });


       // RUTA PARA RESET MANUAL
       Route::post('/supplies/reset', [SupplyController::class, 'resetSupplies'])->name('supplies.reset');


       // Ruta Tarjeta de Suministro
       Route::resource('horsecard',HorseCardController::class);

       
       //Selecion de gestion Movimientos, Asignaciones , Suministros
       Route::get('horses/selection', function(){return view('horses.selection'); })->name('horses.selection');

        // ==============================================
        // RUTAS PARA MODULO SERVICES
        // ==============================================

        Route::resource('services', ServiceController::class);
        Route::patch('/services/{service}/change-status', [ServiceController::class, 'changeStatus'])->name('services.change-status');
        Route::get('/services/{service}/data', [ServiceController::class, 'getServiceData'])->name('services.data');
       // ================== FINAL RUTA SERVICE =========
       

        // ==============================================
        // RUTAS PARA MODULO ASIGNACIONES
        // ==============================================
          
        // Rutas general para Assignments
        Route::resource('assignments', AssignmentController::class);


        // O para details  
        Route::get('/assignments/details/{assignment}', [AssignmentController::class, 'details'])
        ->name('assignments.details');



        Route::get('/assignments/{assignment}/edit', [AssignmentController::class, 'edit'])
        ->name('assignments.edit');

        // Eliminar servicio existente de una asignación
        Route::delete('/assignment-details/{assignmentDetail}', [AssignmentController::class, 'destroyDetail'])->name('assignment-details.destroy');
        
        /* Eliminar Asignacion de la tabla asignaciones index */
       /*  Route::delete('/assignments/{assignment}', [AssignmentController::class, 'destroy'])->name('assignments.destroy');
 */
         Route::put('/assignments/{assignment}', [AssignmentController::class, 'update'])
        ->name('assignments.update');


        // Rutas adicionales para funcionalidades específicas
        Route::prefix('assignments')->group(function () {
            // Marcar asignación como ejecutada
            Route::patch('/{assignment}/mark-executed', [AssignmentController::class, 'markAsExecuted'])
                ->name('assignments.mark-executed');
            
            // Asignaciones del día
            Route::get('/today', [AssignmentController::class, 'today'])
                ->name('assignments.today');
            
            // Asignaciones por fecha
            Route::get('/by-date', [AssignmentController::class, 'byDate'])
                ->name('assignments.by-date');
            
            // Asignaciones asignadas
            Route::get('/assigned', [AssignmentController::class, 'assigned'])
                ->name('assignments.assigned');
            
            // Asignaciones ejecutadas
            Route::get('/executed', [AssignmentController::class, 'executed'])
                ->name('assignments.executed');

               
        });


       // Actualizar Suministro a EXCECUTED
        Route::post('/supplies/{supply}/execute-shift', [SupplyController::class, 'executeShift'])
        ->name('supplies.execute-shift');

       // Rutas adicionales para modales
        Route::get('/supplies/{id}/details', [SupplyController::class, 'supplyDetails'])->name('supplies.details');
        Route::get('/supplies/{id}/edit-data', [SupplyController::class, 'editData'])->name('supplies.edit.data');

            // ==========================================================================
            // RUTAS PARA HORSE CARD 2 - MODAL DETALLES
            // ==========================================================================

            // Ruta Tarjeta de Suministros
            Route::resource('horsecard',HorseCardController::class);

            Route::prefix('horsecard')->group(function () {
                // Ruta para obtener detalles del suministro (modal)
                Route::get('/supplies/{id}/supply-details', [HorseCardController::class, 'supplyDetails'])
                    ->name('horsecard.supply.supply-details');
                
                // Ruta para ejecutar turno (checkboxes)
                Route::post('/supplies/{supply}/execute-shift', [HorseCardController::class, 'executeShift'])
                    ->name('horsecard.supply.execute-shift');
            });


            // ==========================================================================
            // RUTAS PARA HORSE CARD 2 - MODAL DETALLES ASIGNACIONES
            // ==========================================================================

                // Ruta Tarjeta de Asignaciones
            Route::resource('horsecard2', HorseCard2Controller::class);

            Route::prefix('horsecard2')->group(function () {
                // Ruta para obtener detalles en la card2 de la Asignación
                Route::get('/assignments/{assignment}/assigned-details', [HorseCard2Controller::class, 'assignedDetails'])
                    ->name('horsecard2.assigned-details'); 

                //  Rura para Ejecutar servicio individual
                Route::post('/assignments/{assignment}/execute-service', [HorseCard2Controller::class, 'executeService'])
                ->name('horsecard2.execute-service');

                // 🔥 NUEVA RUTA: Reset de asignaciones expiradas
                Route::post('/reset-expired-assignments', [HorseCard2Controller::class, 'resetExpiredAssignments'])
                ->name('horsecard2.reset-expired-assignments');
            });

        
        /******* Stables ******/
        Route::get('stables', [StableController::class, 'index'])->name('stables.index');  
        Route::post('stables/store', [StableController::class, 'store'])->name('stables.store');
        Route::get('/stables/{stable}/edit', [StableController::class, 'edit'])-> name('stables.edit');
        Route::put('/stables/{stable}', [StableController::class, 'update'])-> name('stables.update');  
        Route::delete('/stables/{stable}', [StableController::class, 'destroy'])->name('stables.destroy'); 


        

         /******* horses ******/
         Route::get('horses', [HorseController::class, 'index'])->name('horses.index');
         Route::get('horses/create', [HorseController::class, 'create'])->name('horses.create');
         Route::post('horses/store', [HorseController::class, 'store'])->name('horses.store');
         Route::get('horses/{horse}/edit', [HorseController::class, 'edit'])->name('horses.edit');

         Route::put('horses/{horse}', [HorseController::class, 'update'])->name('horses.update');
         
         Route::delete('horses/{horse}', [HorseController::class, 'destroy'])->name('horses.destroy');
         Route::get('horses/{horse}/details', [HorseController::class, 'details'])->name('horses.details');


        // Exportar (GET)
        Route::get('guests/export', [ExportImportController::class, 'export'])->name('guests.export');

        // Importar (POST)
        Route::post('guests/import', [ExportImportController::class, 'import'])->name('guests.import');



        //Horse to Sectors
        Route::get('sectors', [SectorController::class, 'index'])->name('sectors.index');
        Route::get('sectors/create', [SectorController::class, 'create'])->name('sectors.create');
       /*  Route::get('sectors/show', [SectorController::class, 'showSectors'])->name('sectors.show'); */
        Route::post('sectors/store', [SectorController::class, 'store'])->name('sectors.store');
        Route::get('sectors/{sector}/edit', [SectorController::class, 'edit'])->name('sectors.edit');
        Route::put('sectors/{sector}', [SectorController::class, 'update'])->name('sectors.update');

        Route::delete('sectors/{sector}', [SectorController::class, 'destroy'])->name('sectors.destroy');

        Route::get('/sectors/{sector}/horses', [StableController::class, 'horsesBySector'])
        ->name('sectors.horses');

        Route::get('/sectors/{sector}/horses-data', [StableController::class, 'getHorsesBySectorData'])
        ->name('sectors.horses.data');

        Route::get('/horses/{horse}/details', [StableController::class, 'getHorseDetails'])
        ->name('horses.details');


        // Horse Movements
        // Rutas para movimientos de caballos
        Route::get('horse-movements', [HorseMovementController::class, 'index'])->name('horse-movements');
        Route::post('/horse-movements', [HorseMovementController::class, 'store'])->name('horse-movements.store');
        Route::get('/horse-movements/history', [HorseMovementController::class, 'showHorseMovements'])->name('horse-movements.history');
        Route::get('/horse-movements/statistics', [HorseMovementController::class, 'statistics'])->name('horse-movements.statistics');

        // Rutas para Sectores
        Route::prefix('sectors')->group(function () {
           Route::get('/show', [SectorController::class, 'showSectors'])->name('sectors.show');
            Route::get('/{sector}/horses', [SectorController::class, 'getSectorHorses'])->name('sectors.horses');
        });


        // Rutas para Movimientos
        Route::prefix('movements')->group(function () {
            Route::get('/', [MovementController::class, 'index'])->name('movements.index');
            Route::get('/create', [MovementController::class, 'create'])->name('movements.create');
            Route::post('/', [MovementController::class, 'store'])->name('movements.store');
            Route::get('/{movement}', [MovementController::class, 'show'])->name('movements.show');
            Route::get('/{movement}/edit', [MovementController::class, 'edit'])->name('movements.edit');
            Route::put('/{movement}', [MovementController::class, 'update'])->name('movements.update');
            Route::delete('/{movement}', [MovementController::class, 'destroy'])->name('movements.destroy');
            
            // Ejecución
            Route::post('/{movement}/execute', [MovementController::class, 'execute'])->name('movements.execute');
            Route::post('/details/{detail}/execute', [MovementController::class, 'executeDetail'])
                ->name('movements.details.execute');
        });

        // Horse Special Conditions
        Route::get('/horse-conditions', [HorseSpecialConditionController::class, 'index']);
        Route::post('/horse-conditions', [HorseSpecialConditionController::class, 'store']);
        Route::put('/horse-conditions/{id}', [HorseSpecialConditionController::class, 'update']);
        Route::get('/horses/{horse}/conditions', [HorseSpecialConditionController::class, 'getHorseConditions']);
        Route::post('/horse-conditions/{id}/complete', [HorseSpecialConditionController::class, 'completeCondition']);
        Route::get('/horse-conditions/statistics', [HorseSpecialConditionController::class, 'statistics']);

});
