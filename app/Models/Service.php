<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable =[

        'name',
        'photo',
        'status'
    ];

    //Relacion assigments
    public function assignments(){

        return $this->belongsTo(Assigment::class);
    }
}
