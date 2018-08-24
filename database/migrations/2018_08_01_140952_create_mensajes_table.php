
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMensajesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mensajes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_perfil_objetivo')->unsigned();
            $table->integer('id_perfil_subordinado')->unsigned();
            $table->integer('id_usuario_envia')->unsigned();
            $table->integer('id_estado_recibe')->unsigned();
            $table->integer('id_busqueda_resultado')->unsigned();
            $table->string('mensaje');
            $table->boolean('revisado');
            $table->boolean('desestimado')->default(false)->index();
            $table->boolean('desestimado_remitente')->default(false)->index();
            $table->date('created_at')->default(date("Y-m-d H:i:s"));
            $table->date('updated_at')->default(date("Y-m-d H:i:s"));

            $table->foreign('id_perfil_objetivo')->references('id')->on('perfiles_geneticos');
            $table->foreign('id_perfil_subordinado')->references('id')->on('perfiles_geneticos');
            $table->foreign('id_usuario_envia')->references('id')->on('users');
            $table->foreign('id_estado_recibe')->references('id')->on('estados');
            $table->foreign('id_busqueda_resultado')->references('id')->on('busquedas_resultados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mensajes');
    }
}
