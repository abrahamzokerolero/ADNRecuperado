<?php

use Illuminate\Database\Seeder;
use App\TipoDeBusqueda;

class TiposDeBusquedasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/tipos_de_busquedas.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            TipoDeBusqueda::create(array(
             'nombre' => $obj->nombre,
           ));
        }
    }
}
