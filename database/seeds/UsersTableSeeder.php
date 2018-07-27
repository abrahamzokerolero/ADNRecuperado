<?php

use Illuminate\Database\Seeder;
use Caffeinated\Shinobi\Models\Role;
use App\User;
use App\RoleUser;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       	Role::create([
       		'name' => 'Admin',
          'description' => 'Rol de Acceso total a la app',
       		'slug' => 'admin',
       		'special' => 'all-access'
       	]);

        Role::create([
          'name' => 'Suspendido',
          'description' => 'Rol sin privilegios',
          'slug' => 'suspendido',
          'special' => 'no-access'
        ]);

        User::create([
          'name' => 'Administrador',
          'email' => 'admin@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'cnbadmin',
          'id_estado' => 33,
          'remember_token' => null,
        ]);

        User::create([
          'name' => 'Aguascalientes',
          'email' => 'aguascalientes@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'aguascalientesadmin',
          'id_estado' => 1,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Baja California',
          'email' => 'bajacalifornia@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'bajacaliforniaadmin',
          'id_estado' => 2,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Baja California Sur',
          'email' => 'bajacaliforniasur@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'bajacaliforniasuradmin',
          'id_estado' => 3,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Campeche',
          'email' => 'campeche@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'campecheadmin',
          'id_estado' => 4,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Coahulia',
          'email' => 'coahuila@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'coahuilaadmin',
          'id_estado' => 5,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Colima',
          'email' => 'colima@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'colimaadmin',
          'id_estado' => 6,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Chiapas',
          'email' => 'chiapas@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'chiapasadmin',
          'id_estado' => 7,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Chihuahua',
          'email' => 'chihuahua@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'chihuahuaadmin',
          'id_estado' => 8,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Ciudad de Mexico',
          'email' => 'ciudaddemexico@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'ciudaddemexicoadmin',
          'id_estado' => 9,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Durango',
          'email' => 'durango@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'durangoadmin',
          'id_estado' => 10,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Guanajuato',
          'email' => 'guanajuato@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'guanajuatoadmin',
          'id_estado' => 11,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Guerrero',
          'email' => 'guerrero@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'guerreroadmin',
          'id_estado' => 12,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Hidalgo',
          'email' => 'hidalgo@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'hidalgoadmin',
          'id_estado' => 13,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Jalisco',
          'email' => 'jalisco@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'jaliscoadmin',
          'id_estado' => 14,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Estado de Mexico',
          'email' => 'estadodemexico@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'estadodemexicoadmin',
          'id_estado' => 15,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Michoacan',
          'email' => 'michoacan@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'michoacanadmin',
          'id_estado' => 16,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Morelos',
          'email' => 'morelos@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'morelosadmin',
          'id_estado' => 17,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Nayarit',
          'email' => 'nayarit@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'nayaritadmin',
          'id_estado' => 18,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Nuevo Leon',
          'email' => 'nuevoleon@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'nuevoleonadmin',
          'id_estado' => 19,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Oaxaca',
          'email' => 'oaxaca@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'oaxacanadmin',
          'id_estado' => 20,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Puebla',
          'email' => 'puebla@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'pueblaadmin',
          'id_estado' => 21,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Queretaro',
          'email' => 'queretaro@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'queretaroadmin',
          'id_estado' => 22,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Quintana Roo',
          'email' => 'quintanaroo@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'quintanarooadmin',
          'id_estado' => 23,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'San Luis Potosi',
          'email' => 'sanluispotosi@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'sanluispotosiadmin',
          'id_estado' => 24,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Sinaloa',
          'email' => 'sinaloa@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'sinaloaadmin',
          'id_estado' => 25,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Sonora',
          'email' => 'sonora@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'sonoraadmin',
          'id_estado' => 26,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Tabasco',
          'email' => 'tabasco@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'tabascoadmin',
          'id_estado' => 27,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Tamaulipas',
          'email' => 'tamaulipas@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'tamaulipasadmin',
          'id_estado' => 28,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Tlaxcala',
          'email' => 'tlaxcala@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'tlaxcalaadmin',
          'id_estado' => 29,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Veracruz',
          'email' => 'veracruz@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'veracruzadmin',
          'id_estado' => 30,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Yucatan',
          'email' => 'yucatan@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'yucatanadmin',
          'id_estado' => 31,
          'remember_token' => null,
        ]);
        User::create([
          'name' => 'Zacatecas',
          'email' => 'zacatecas@example.com',
          'password' => bcrypt('admin123'),
          'username' => 'zacatecasadmin',
          'id_estado' => 32,
          'remember_token' => null,
        ]);



        

        RoleUser::create([
          'role_id' => 1,
          'user_id' => 1,
        ]);

        RoleUser::create([
          'role_id' => 1,
          'user_id' => 2,
        ]);

        RoleUser::create([
          'role_id' => 1,
          'user_id' => 3,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 4,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 5,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 6,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 7,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 8,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 9,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 10,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 11,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 12,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 13,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 14,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 15,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 16,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 17,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 18,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 19,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 20,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 21,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 22,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 23,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 24,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 25,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 26,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 27,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 28,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 29,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 30,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 31,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 32,
        ]);
        RoleUser::create([
          'role_id' => 1,
          'user_id' => 33,
        ]);
    }
}
