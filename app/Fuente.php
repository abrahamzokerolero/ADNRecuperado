<?php

namespace App;

use App\PerfilGenetico;
use App\ImportacionPerfil;
use Illuminate\Database\Eloquent\Model;

class Fuente extends Model
{
    protected $table = 'fuentes';

    protected $guarded = [];

    public function importaciones_perfiles(){
    	return $this->hasMany(ImportacionPerfil::class);
    }

    public function perfiles_geneticos(){
    	return $this->hasMany(PerfilGenetico::class);
    }
}
