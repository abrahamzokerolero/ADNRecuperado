<?php

use Illuminate\Database\Seeder;
use App\Estado;

class EstadosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/estados.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            Estado::create(array(
             'nombre' => $obj->name,
           ));
        }
    }
}
