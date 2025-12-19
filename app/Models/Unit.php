<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $fillable = [
        
        'name', 
        'symbol', 
        'description',
        'status'
    ];
    protected $table = 'units';

    public function purchaseProducts(){

        return $this->hasMany(Product::class, 'purchase_unit_id');
    }

    public function consumptionProducts()
    {
        return $this->hasMany(Product::class, 'consumption_unit_id');
    }

    //Conversitions

    public function conversionsFrom() {

        return $this->hasMany(UnitConversion::class, 'from_unit_id');
    }
    
    public function conversionsTo() {
        
        return $this->hasMany(UnitConversion::class, 'to_unit_id');
    }
}
