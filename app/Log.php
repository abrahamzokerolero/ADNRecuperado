<?php

namespace App;
use App\User;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';

    protected $guarded = [];

    public function usuario(){
    	return $this->belongsTo(User::class, 'id_usuario');
    }
}
