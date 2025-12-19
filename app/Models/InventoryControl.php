<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryControl extends Model
{
    protected $fillable = [

        'inventory_op_id',
        'product_id',
        'store_id',
        'destination_store_id',
        'movement_type',
        'quantity',
        'unit_type',
        'reason',
        'notes',
        'ubication',
        'user_id',
        'reference_id'
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'created_at' => 'datetime'
    ];

    // NUEVA RELACIÓN CON INVENTARYOPERATION
    public function inventoryOperation(): BelongsTo{

        return $this->belongsTo(InventoryOperation::class, 'inventory_op_id');
    }

    public function product(): BelongsTo{

        return $this->belongsTo(Product::class);
    }

    public function store(): BelongsTo{

        return $this->belongsTo(Store::class);
    }

    public function destinationStore(): BelongsTo{

        return $this->belongsTo(Store::class, 'destination_store_id');
    }

    public function user(): BelongsTo{

        return $this->belongsTo(User::class);
    }

    public function reference(): BelongsTo{

        return $this->belongsTo(InventoryControl::class, 'reference_id');
    }

    public function products(){

        return $this->hasMany(InventoryControl::class, 'reference_id');
    }

    public function parent(){

        return $this->belongsTo(InventoryControl::class, 'reference_id');
    }

    // Calcular costo del movimiento individual
    public function getMovementCostAttribute(){
        
        return $this->quantity * $this->product->cost;
    }
}