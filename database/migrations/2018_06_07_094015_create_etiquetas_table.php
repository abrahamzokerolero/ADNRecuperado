<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEtiquetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etiquetas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre')->unique();
            $table->integer('categoria_id')->unsigned()->nullable();
            $table->boolean('desestimado')->default(false);
            $table->date('created_at')->default(date("Y-m-d H:i:s"));
            $table->date('updated_at')->default(date("Y-m-d H:i:s"));

            $table->foreign('categoria_id')->references('id')->on('categorias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('etiquetas', function (Blueprint $table) {
            $table->dropForeign('etiquetas_categoria_id_foreign');
        });
        
        Schema::dropIfExists('etiquetas');
    }
}
