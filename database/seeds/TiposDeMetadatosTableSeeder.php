<?php

use Illuminate\Database\Seeder;
use App\TipoDeMetadato;

class TiposDeMetadatosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/tipos_de_metadatos.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            TipoDeMetadato::create(array(
             'nombre' => $obj->nombre,
           ));
        }
    }
}
