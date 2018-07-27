<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Frecuencia;
use App\User;
use App\Estado;

class ImportacionFrecuencia extends Model
{
    protected $table = 'importacion_frecuencias';

    protected $guarded = [];

    public function frecuencias(){
    	return $this->hasMany(Frecuencia::class, 'id_importacion');
    }

    public function usuario(){
    	return $this->belongsTo(User::class, 'id_usuario');
    }

    public function estado(){
    	return $this->belongsTo(Estado::class, 'id_estado');
    }
}
