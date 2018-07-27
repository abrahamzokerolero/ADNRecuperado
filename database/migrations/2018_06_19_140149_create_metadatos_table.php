<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetadatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metadatos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_perfil_genetico')->unsigned();
            $table->integer('id_tipo_de_metadato')->unsigned();
            $table->string('dato');
            $table->date('created_at')->default(date("Y-m-d H:i:s"));
            $table->date('updated_at')->default(date("Y-m-d H:i:s"));

            $table->foreign('id_perfil_genetico')->references('id')->on('perfiles_geneticos');
            $table->foreign('id_tipo_de_metadato')->references('id')->on('tipos_de_metadatos');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metadatos');
    }
}
