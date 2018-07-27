<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusquedasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('busquedas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('identificador');
            $table->string('motivo')->nullable();
            $table->string('descripcion')->nullable();
            $table->integer('marcadores_minimos')->nullable(); 
            $table->integer('numero_de_exclusiones')->nullable(); // si ninguno de los alelos del pobjetivo coinciden
            $table->string('conclusiones')->nullable();
            $table->integer('id_fuente')->unsigned()->nullable();
            $table->integer('id_usuario')->unsigned()->nullable();
            $table->integer('id_tipo_busqueda')->unsigned()->nullable();
            $table->integer('id_estado')->unsigned()->nullable();
            $table->integer('id_tabla_de_frecuencias')->unsigned()->nullable();
            $table->integer('id_estatus_busqueda')->unsigned()->nullable();
            $table->date('created_at')->default(date("Y-m-d H:i:s"));
            $table->date('updated_at')->default(date("Y-m-d H:i:s"));

            $table->foreign('id_fuente')->references('id')->on('fuentes');
            $table->foreign('id_usuario')->references('id')->on('users');
            $table->foreign('id_tipo_busqueda')->references('id')->on('tipos_de_busquedas');
            $table->foreign('id_estado')->references('id')->on('estados');
            $table->foreign('id_tabla_de_frecuencias')->references('id')->on('importacion_frecuencias');
            $table->foreign('id_estatus_busqueda')->references('id')->on('estatus_busquedas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('busquedas');
    }
}
