<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrecuenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frecuencias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_importacion')->unsigned();
            $table->integer('id_marcador')->unsigned();
            $table->string('alelo');
            $table->double('frecuencia', 6, 4);
            $table->boolean('desestimado')->default(false);
            $table->date('created_at')->default(date("Y-m-d H:i:s"));
            $table->date('updated_at')->default(date("Y-m-d H:i:s"));
            $table->foreign('id_importacion')->references('id')->on('importacion_frecuencias');
            $table->foreign('id_marcador')->references('id')->on('marcadores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('frecuencias');
    }
}