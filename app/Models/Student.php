<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';
    
    protected $fillable = [

        'user_id',
        'horse_id',
        'doc',
        'photo',
        'name',
        'last_name',
        'address',
        'email',
        'phone',
        'status',

    ];

    public function horses(){
        return $this->belongsTo(Horse::class);
    }
    

    public function user(){
        return $this->belongsTo(User::class);

    }
}
