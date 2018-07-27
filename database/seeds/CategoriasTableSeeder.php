<?php

use Illuminate\Database\Seeder;
use App\Categoria;

class CategoriasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/categorias.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            Categoria::create(array(
             'nombre' => $obj->name,
           ));
        }
    }
}
