<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTiposDeMetadatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipos_de_metadatos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre')->unique();
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
        Schema::dropIfExists('tipos_de_metadatos');
    }
}
