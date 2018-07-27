<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEtiquetasAsignadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etiquetas_asignadas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_etiqueta')->unsigned();
            $table->integer('id_perfil_genetico')->unsigned();

            $table->date('created_at')->default(date("Y-m-d H:i:s"));
            $table->date('updated_at')->default(date("Y-m-d H:i:s"));
            $table->foreign('id_etiqueta')->references('id')->on('etiquetas');
            $table->foreign('id_perfil_genetico')->references('id')->on('perfiles_geneticos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('etiquetas_asignadas');
    }
}
