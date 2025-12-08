<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horse extends Model
{
    use HasFactory;

    protected $table = 'horses';

    protected $fillable = [

        'user_id',
        'stable_id',
        'photo',
        'name',
        'age',
        'breed',
        'color',
        'sex',
        'height',
        'weight',
        'pathology',
        'vaccination',
        'last_exam',
        'observation',
        'condition',
        'status',
        'is_supplied',
        'add_service'

        //15 camps
    ];

    //define relations

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function stable(){
        return $this->belongsTo(Stable::class, 'stable_id');
    }


    public function supply(){

        return $this->belongsTo(Supply::class, 'horse_id');
    }

   

   public function student(){
        
        return $this->hasOne(Student::class);
    }

    // Agregar estas relaciones al modelo Horse
    public function specialConditions()
    {
        return $this->hasMany(HorseSpecialCondition::class);
    }

    public function activeSpecialConditions()
    {
        return $this->specialConditions()->where('status', 'ACTIVE');
    }

    public function movements()
    {
        return $this->hasMany(HorseMovement::class)->orderBy('created_at', 'desc');
    }

    public function currentStable()
    {
        return $this->belongsTo(Stable::class, 'stable_id');
    }

    public function sector(){
        
        return $this->through('currentStable')->has('sector');
    }

    public function supplies(){

        return $this->hasMany(Supply::class);
    }

    public function assigment(){

        return $this->hasMany(Assigment::class);
    }





}
