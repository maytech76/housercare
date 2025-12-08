<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        
        'category_id', 
        'type', 
        'name', 
        'description', 
        'purchase_unit_id', 
        'consumption_unit_id',  
        'unit_conversion_id',
        'cost', 
        'price', 
        'expired', 
        'codebar', 
        'photo',
        'status'
    ];

    protected $table = 'products';

    protected $casts = [
        'expired' => 'date',
        'status' => 'boolean',
        'cost' => 'decimal:2',
        'price' => 'decimal:2'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function purchaseUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'purchase_unit_id');
    }

    public function consumptionUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'consumption_unit_id');
    }

    public function unitConversion(): BelongsTo
    {
        return $this->belongsTo(UnitConversion::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryControl::class);
    }

    public function getTotalStockAttribute()
    {
        return $this->stocks->sum('purchase_quantity');
    }



    // Método para obtener el factor de conversión desde unitConversion
    public function getConversionFactorAttribute()
    {
        if ($this->unitConversion && $this->unitConversion->is_active) {
            return $this->unitConversion->factor;
        }
        
        // Si no hay conversión definida, asumir 1:1
        return 1;
    }




}