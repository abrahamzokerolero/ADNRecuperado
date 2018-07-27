<?php

use Illuminate\Database\Seeder;
use App\EstatusBusqueda;

class EstatusBusquedasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/estatus_busquedas.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            EstatusBusqueda::create(array(
             'nombre' => $obj->nombre,
           ));
        }
    }
}
