<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Estado;

class Estado extends Model
{
    protected $table = 'estados';

    protected $guarded = [];

    public function usuarios(){
    	return $this->hasMany(Estado::class, 'id_estado');
    }
}
