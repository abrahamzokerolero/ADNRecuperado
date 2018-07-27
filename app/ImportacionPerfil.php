<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PerfilGenetico;
use App\User;
use App\Fuente;
use App\Estado;

class ImportacionPerfil extends Model
{
    protected $table = 'importaciones_perfiles';

    protected $guarded = [];

    public function perfiles_geneticos(){
    	return $this->hasMany(PerfilGenetico::class, 'id_importacion');
    }

    public function usuario(){
    	return $this->belongsTo(User::class, 'id_usuario');
    }

    public function fuente(){
    	return $this->belongsTo(Fuente::class, 'id_fuente');
    }

    public function estado(){
        return $this->belongsTo(Estado::class, 'id_estado');
    }
}
