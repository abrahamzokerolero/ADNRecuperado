<?php

use Illuminate\Database\Seeder;
use App\TipoDeMarcador;

class TiposDeMarcadores extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/tipos_de_marcadores.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            TipoDeMarcador::create(array(
             'nombre' => $obj->nombre,
           ));
        }
    }
}
