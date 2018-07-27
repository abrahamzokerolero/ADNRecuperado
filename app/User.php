<?php

namespace App;

use Caffeinated\Shinobi\Traits\ShinobiTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\ImportacionFrecuencia;
use App\Marcador;
use App\Estado;

class User extends Authenticatable
{
    use Notifiable, ShinobiTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','apellido_paterno', 'apellido_materno', 'direccion','telefono_particular', 'telefono_celular', 'username', 'avatar', 'email', 'password','id_estado'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function importaciones_frecuencias(){
        return $this->hasMany(ImportacionFrecuencia::class, 'id_usuario');
    }

    public function marcadores(){
        return $this->hasMany(Marcador::class, 'id_usuario_registro');
    }

    public function estado(){
        return $this->belongsTo(Estado::class, 'id_estado');
    }
}
