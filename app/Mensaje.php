<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Estado;
use App\User;
use App\Busqueda;
use App\PerfilGenetico;


class Mensaje extends Model
{
	protected $table = 'mensajes';

    protected $guarded = []; 

    public function perfil_objetivo(){
        return $this->belongsTo(PerfilGenetico::class, 'id_perfil_objetivo');
    }

    public function perfil_subordinado(){
        return $this->belongsTo(PerfilGenetico::class, 'id_perfil_subordinado');
    }

    public function usuario_envia(){
    	return $this->belongsTo(User::class, 'id_usuario_envia');
    }

    public function estado_recibe(){
    	return $this->belongsTo(Estado::class, 'id_estado_recibe');
    }

    public function busqueda_resultado(){
    	return $this->belongsTo(Busqueda::class, 'id_busqueda_resultado');
    }
}