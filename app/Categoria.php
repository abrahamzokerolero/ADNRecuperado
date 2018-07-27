<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Etiqueta;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $guarded = [];

    public function etiquetas(){
    	return $this->hasMany(Etiqueta::class, 'categoria_id');
    }
}
