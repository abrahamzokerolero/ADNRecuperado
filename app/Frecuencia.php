<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ImportacionFrecuencia;
use App\Marcador;

class Frecuencia extends Model
{
    protected $table = 'frecuencias';

    protected $guarded = [];

    public function importacion_frecuencia(){
    	return $this->belongsTo(ImportacionFrecuencia::class, 'id_importacion');
    }

    public function marcador(){
    	return $this->belongsTo(Marcador::class, 'id_marcador');
    }
}
