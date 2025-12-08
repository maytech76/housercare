<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;

    protected $table = 'Sectors';

    protected $fillable = [

        'name', 
        'photo', 
        'description', 
        'status'
    ];

    protected $casts = ['status' =>  'integer'];

    public function stables()
    {
        return $this->hasMany(Stable::class);
    }

    public function activeStables()
    {
        return $this->stables()->where('status', true);
    }

    public function horses()
    {
        return $this->hasManyThrough(Horse::class, Stable::class);
    }
}
