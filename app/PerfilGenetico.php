<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ImportacionPerfil;
use App\User;
use App\Estado;
use App\Fuente;
use App\Alelo;
use App\EtiquetaAsignada;
use App\Metadato;
use App\PerfilGenetico;

class PerfilGenetico extends Model
{
    protected $table = 'perfiles_geneticos';
    //protected $dateFormat = 'M j Y h:i:s';
    protected $guarded = [];

    public function importacion_perfil(){
    	return $this->belongsTo(ImportacionPerfil::class, 'id_importacion');
    }

    public function usuario(){
    	return $this->belongsTo(User::class, 'id_usuario');
    }

    public function alelos(){
    	return $this->hasMany(Alelo::class, 'id_perfil_genetico');
    }

    public function etiquetas(){
    	return $this->hasMany(EtiquetaAsignada::class, 'id_perfil_genetico');
    }

    public function metadatos(){
        return $this->hasMany(Metadato::class, 'id_perfil_genetico');
    }

    public function fuente(){
        return $this->belongsTo(Fuente::class, 'id_fuente');
    }

    public function usuario_reviso(){
        return $this->belongsTo(User::class, 'id_usuario_reviso');
    }

    public function perfil_original(){
        return $this->belongsTo(PerfilGenetico::class, 'id_perfil_original');
    }

    public function estado(){
        return $this->belongsTo(Estado::class, 'id_estado');
    }
    public function estado_perfil_original(){
        return $this->belongsTo(Estado::class, 'id_estado_perfil_original');
    }
}
