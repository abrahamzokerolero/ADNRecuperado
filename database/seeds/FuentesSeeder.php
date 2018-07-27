<?php

use Illuminate\Database\Seeder;
use App\Fuente;

class FuentesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/fuentes.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            Fuente::create(array(
             'nombre' => $obj->name,
             'id_interno' => $obj->internal_id,
             'id_externo' => $obj->external_id,
             'contacto_fuente' => null,
             'correo_fuente' => null,
             'telefono1_fuente' => null,
             'telefono2_fuente' => null,
           ));
        }
    }
}
