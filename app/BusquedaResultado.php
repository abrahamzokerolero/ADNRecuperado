<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Busqueda;
use App\PerfilGenetico;

class BusquedaResultado extends Model
{
    protected $table = 'busquedas_resultados';

    protected $guarded = [];

    public function busqueda(){
    	return $this->belongsTo(Busqueda::class, 'id_busqueda');
    }

    public function perfil_objetivo(){
    	return $this->belongsTo(PerfilGenetico::class, 'id_perfil_objetivo');
    }

    public function perfil_subordinado(){
    	return $this->belongsTo(PerfilGenetico::class, 'id_perfil_subordinado');
    }

}
