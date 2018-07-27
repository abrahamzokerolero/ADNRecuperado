<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();
            $table->string('direccion')->nullable();
            $table->string('telefono_particular')->nullable();
            $table->string('telefono_celular')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('username')->unique();
            $table->string('avatar')->nullable();
            $table->integer('id_estado')->unsigned()->nullable();
            $table->boolean('desestimado')->default(false);
            $table->rememberToken();
            $table->date('created_at')->default(date("Y-m-d H:i:s"));
            $table->date('updated_at')->default(date("Y-m-d H:i:s"));

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
