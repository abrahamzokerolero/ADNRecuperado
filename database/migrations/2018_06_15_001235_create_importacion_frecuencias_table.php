<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportacionFrecuenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('importacion_frecuencias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('identificador');
            $table->string('nombre');
            $table->string('nombre_otorgado');
            $table->integer('id_usuario')->unsigned();
            $table->integer('id_estado')->unsigned();
            $table->boolean('desestimado')->default(false);
            $table->boolean('tabla_default')->nullable();
            $table->date('created_at')->default(date("Y-m-d H:i:s"));
            $table->date('updated_at')->default(date("Y-m-d H:i:s"));

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
        Schema::dropIfExists('importacion_frecuencias');
    }
}
