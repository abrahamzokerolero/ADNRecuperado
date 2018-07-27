<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TipoDeMetadato;
use App\PerfilGenetico;

class Metadato extends Model
{
    protected $table = 'metadatos';
    //protected $dateFormat = 'M j Y h:i:s';
    protected $guarded = [];

    public function tipo_de_metadato(){
    	return $this->belongsTo(TipoDeMetadato::class, 'id_tipo_de_metadato');
    }

    public function perfil_genetico(){
    	return $this->belongsTo(PerfilGenetico::class, 'id_perfil_genetico');
    }

}
