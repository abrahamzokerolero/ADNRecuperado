<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        /*Creacion de roles y permisos del sistema en categorias*/
        $this->call(PermissionsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CategoriasTableSeeder::class);
        $this->call(EtiquetasTableSeeder::class);
        $this->call(FuentesSeeder::class);
        $this->call(TiposDeMarcadores::class);
        $this->call(MarcadoresTableSeeder::class);
        $this->call(EstadosTableSeeder::class);
        $this->call(TiposDeBusquedasTableSeeder::class);
        $this->call(EstatusBusquedasTableSeeder::class);
    }
}
