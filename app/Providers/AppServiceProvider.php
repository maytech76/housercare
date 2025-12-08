<?php

namespace App\Providers;

use App\Http\Responses\LoginResponse;
use App\Models\Assigment;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryControl;
use App\Observers\InventoryControlObserver;
use App\Services\InventoryService;
use App\Services\StockService;
use App\Models\SupplyDetail; // ✅ Agregar esta línea
use App\Observers\AssignmentObserver;
use App\Observers\SupplyDetailObserver; // ✅ Agregar esta línea





class AppServiceProvider extends ServiceProvider
{
   
    public function register(): void{

    
        $this->app->singleton(LoginResponseContract::class, LoginResponse ::class);


        // Registrar servicios en el contenedor de Laravel
        $this->app->singleton(InventoryService::class, function ($app) {
            return new InventoryService();
        });
        
        $this->app->singleton(StockService::class, function ($app) {
            return new StockService($app->make(InventoryService::class));
        });

        
    
    }

    
    

    public function boot()
        {
            if (env('DB_TIMEZONE')) {
                DB::statement("SET time_zone='".env('DB_TIMEZONE')."'");
            }

            // Registrar el Observer
            InventoryControl::observe(InventoryControlObserver::class);

            //registro del Observer que elimina los items vencidos por limit_date
            SupplyDetail::observe(SupplyDetailObserver::class);

            Assigment::observe(AssignmentObserver::class);

            
        }

}
