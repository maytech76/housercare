<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [

      'name', 
      'type',
      'description',
      'status'
    ];

    public function products(){
      
        return $this->hasMany(Product::class); //una categoria tiene muchos productos
    }

    
}
