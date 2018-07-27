<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Fuente;
use App\User;
use App\TipoDeBusqueda;
use App\Estado;
use App\ImportacionFrecuencia;
use App\EstatusBusqueda;
use App\BusquedaResultado;


class Busqueda extends Model
{
    protected $table = 'busquedas';

    protected $guarded = [];

    public function fuente(){
    	return $this->belongsTo(Fuente::class, 'id_fuente');
    }

    public function usuario(){
    	return $this->belongsTo(User::class, 'id_usuario');
    }

    public function tipo_de_busqueda(){
    	return $this->belongsTo(TipoDeBusqueda::class, 'id_tipo_busqueda');
    }

    public function estado(){
    	return $this->belongsTo(Estado::class, 'id_estado');
    }

    public function tabla_de_frecuencias(){
    	return $this->belongsTo(ImportacionFrecuencia::class, 'id_tabla_de_frecuencias');
    }

    public function resultados(){
    	return $this->hasMany(BusquedaResultado::class, 'id_busqueda');
    }

    public function estatus(){
        return $this->belongsTo(EstatusBusqueda::class, 'id_estatus_busqueda');
    }
}
