<?php

namespace App\Observers;

use App\Models\SupplyDetail;
use Illuminate\Support\Facades\Log;

class SupplyDetailObserver
{
    /**
     * Manejar el evento "retrieved" del modelo SupplyDetail
     * Eliminar automáticamente los registros expirados al ser consultados
     */
    public function retrieved(SupplyDetail $supplyDetail){
       

        // Solo eliminar si tiene fecha límite y está expirada
        if ($supplyDetail->limit_date && $supplyDetail->limit_date < now()) {
            Log::info("Eliminando detalle de suministro expirado: {$supplyDetail->id}");
            $supplyDetail->delete();
        }
        
   }
}