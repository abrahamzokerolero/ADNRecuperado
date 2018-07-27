<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlelosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alelos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_perfil_genetico')->unsigned();
            $table->integer('id_marcador')->unsigned();
            $table->string('alelo_1');
            $table->string('alelo_2')->nullable();
            $table->string('alelo_3')->nullable();
            $table->string('alelo_4')->nullable();
            $table->string('alelo_5')->nullable();
            $table->date('created_at')->default(date("Y-m-d H:i:s"));
            $table->date('updated_at')->default(date("Y-m-d H:i:s"));

            $table->foreign('id_perfil_genetico')->references('id')->on('perfiles_geneticos');
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
        Schema::dropIfExists('alelos');
    }
}
