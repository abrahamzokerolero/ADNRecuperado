<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportacionesPerfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('importaciones_perfiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('identificador');
            $table->string('nombre')->nullable();
            $table->integer('id_fuente')->unsigned()->nullable();
            $table->integer('id_usuario')->unsigned()->nullable();
            $table->integer('numero_de_perfiles')->nullable();
            $table->integer('numero_de_marcadores')->nullable();
            $table->string('tipo_de_muestra')->nullable();
            $table->string('observaciones')->nullable();
            $table->string('titulo')->nullable();
            $table->integer('id_estado')->unsigned()->nullable();
            $table->boolean('desestimado')->default(false);
            $table->date('created_at')->default(date("Y-m-d H:i:s"));
            $table->date('updated_at')->default(date("Y-m-d H:i:s"));

            $table->foreign('id_fuente')->references('id')->on('fuentes');
            $table->foreign('id_usuario')->references('id')->on('users');
            $table->foreign('id_estado')->references('id')->on('estados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('importaciones_perfiles');
    }
}
