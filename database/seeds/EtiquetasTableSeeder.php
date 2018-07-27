<?php

use Illuminate\Database\Seeder;
use App\Etiqueta;

class EtiquetasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/etiquetas.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            Etiqueta::create(array(
             'nombre' => $obj->name,
             'categoria_id' => $obj->category_id,
           ));
        }
    }
}
