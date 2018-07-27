<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Categoria;
use App\EtiquetaAsignada;

class Etiqueta extends Model
{
    protected $table = 'etiquetas';

    protected $guarded = [];

    public function categoria(){
    	return $this->belongsTo(Categoria::class, 'id');
    }

    public function perfiles_geneticos_asociados(){
    	return $this->hasMany(EtiquetaAsignada::class, 'id_etiqueta');	
    }
}
