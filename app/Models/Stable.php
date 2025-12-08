<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Stable extends Model
{
    use HasFactory;

    protected $table = 'stables';

    protected $fillable = [

        'name',
        'sector_id',
        'capacity',
        'type',
        'condition',
        'status'

    ];

    public function horses(){

        return $this->hasMany(Horse::class);
    }

    public function sector(){
        return $this->belongsTo(Sector::class);
    }
}
