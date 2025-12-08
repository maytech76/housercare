<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    protected $fillable = [
        
        'product_id', 
        'store_id', 
        'purchase_quantity', 
        'consumption_quantity'
    
    ];

    protected $table = 'stocks';

    public function product(): BelongsTo{

        return $this->belongsTo(Product::class);
    }

    public function store(): BelongsTo{
        
        return $this->belongsTo(Store::class);
    }
}
