<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryOperation extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'operation_number',
        'operation_type',
        'total_cost',
        'general_notes',
        'user_id'
    ];

    protected $casts = [
        'total_cost' => 'decimal:4',
        'created_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function inventoryControls(): HasMany
    {
        return $this->hasMany(InventoryControl::class, 'inventory_op_id');
    }

    public function movements(): HasMany{

        return $this->hasMany(InventoryControl::class, 'inventory_op_id');
    }

    // Generar número de operación automáticamente
    protected static function boot(){

        parent::boot();

        static::creating(function ($model) {
            if (empty($model->operation_number)) {
                $lastOperation = self::latest('id')->first();
                $lastNumber = $lastOperation ? intval(substr($lastOperation->operation_number, 4)) : 0;
                $model->operation_number = 'OP-' . str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    // Calcular costo total automáticamente
    public function calculateTotalCost(){

        $this->total_cost = $this->inventoryControls->sum(function ($movement) {
            return $movement->quantity * $movement->product->cost;
        });
        $this->save();
    }

    // Generamos el proximo reg de Operaciones enviarlo a la vista inventoryControl/create 
    public static function generateNextOperationNumber(){

        $lastOperation = self::latest('id')->first();
        $nextId = $lastOperation ? $lastOperation->id + 1 : 1;
        
        return 'OP-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
    }

    // Relaciones adicionales útiles
    public function products(){

        return $this->hasManyThrough(Product::class, InventoryControl::class, 'inventory_op_id', 'id', 'id', 'product_id');
    }

    public function stores(){
        
        return $this->hasManyThrough(Store::class, InventoryControl::class, 'inventory_op_id', 'id', 'id', 'store_id');
    }
}