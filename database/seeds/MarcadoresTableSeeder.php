<?php

use Illuminate\Database\Seeder;
use App\Marcador;

class MarcadoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/marcadores.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            Marcador::create(array(
             'nombre' => $obj->marker,
             'id_usuario_registro' => $obj->who_registered,
             'id_usuario_edito' => $obj->who_edited,
             'id_tipo_de_marcador' =>$obj->id_tipo_de_marcador
           ));
        }
    }
}
