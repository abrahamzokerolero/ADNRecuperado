<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusquedasResultadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('busquedas_resultados', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_busqueda')->unsigned()->nullable();
            $table->integer('id_perfil_objetivo')->unsigned()->nullable();
            $table->integer('id_perfil_subordinado')->unsigned()->nullable();
            $table->double('IP', 18, 6);
            $table->double('PP', 11, 8);
            $table->integer('marcadores_minimos');
            $table->integer('exclusiones');
            $table->date('created_at')->default(date("Y-m-d H:i:s"));
            $table->date('updated_at')->default(date("Y-m-d H:i:s"));

            $table->foreign('id_busqueda')->references('id')->on('busquedas');
            $table->foreign('id_perfil_objetivo')->references('id')->on('perfiles_geneticos');
            $table->foreign('id_perfil_subordinado')->references('id')->on('perfiles_geneticos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('busquedas_resultados');
    }
}
