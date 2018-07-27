<?php

namespace App;

use App\User;
use App\Frecuencia;
use App\TipoDeMarcador;
use Illuminate\Database\Eloquent\Model;

class Marcador extends Model
{
    protected $table = 'marcadores';

    protected $guarded = [];

    public function frecuencias(){
    	return $this->hasMany(Frecuencia::class, 'id_marcador');
    }

    public function usuario_registro(){
    	return $this->belongsTo(User::class, 'id_usuario_registro');
    }

    public function usuario_edito(){
    	return $this->belongsTo(User::class, 'id_usuario_edito');
    }

    public function tipo_de_marcador(){
        return $this->belongsTo(TipoDeMarcador::class, 'id_tipo_de_marcador');
    }

}
