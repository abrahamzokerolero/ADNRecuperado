<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PerfilGenetico;
use App\Etiqueta;

class EtiquetaAsignada extends Model
{
    protected $table = 'etiquetas_asignadas';
    //protected $dateFormat = 'M j Y h:i:s';
    protected $guarded = [];

    public function perfil_genetico(){
    	return $this->belongsTo(PerfilGenetico::class, 'id_perfil_genetico');
    }

    public function etiqueta(){
    	return $this->belongsTo(Etiqueta::class, 'id_etiqueta');
    }
}
