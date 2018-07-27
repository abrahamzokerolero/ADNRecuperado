<?php

use Illuminate\Database\Seeder;
use Caffeinated\Shinobi\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    
    public function run()
    {
    	/* User Permissions. No se incluye la creacion ya que solo con el paquete auth se podran logear*/
        Permission::create([
        	'name' => 'Navegar usuarios',
        	'slug' => 'users.index',
        	'description' => 'Lista y navega todos los usuarios del sistema'
        ]);
        Permission::create([
        	'name' => 'Ver detalle de un usuario',
        	'slug' => 'users.show',
        	'description' => 'Ver en detalle cada usuario del sistema'
        ]);
        Permission::create([
        	'name' => 'Edicion de usuarios',
        	'slug' => 'users.edit',
        	'description' => 'Editar el rol de los usuarios del sistema'
        ]);
        Permission::create([
        	'name' => 'Eliminar usuarios',
        	'slug' => 'users.destroy',
        	'description' => 'Eliminar un usuario del sistema'
        ]);

        /* User Roles*/
        Permission::create([
        	'name' => 'Navegar roles',
        	'slug' => 'roles.index',
        	'description' => 'Lista y navega todos los roles del sistema'
        ]);
        Permission::create([
        	'name' => 'Ver detalle de un rol',
        	'slug' => 'roles.show',
        	'description' => 'Ver en detalle cada rol del sistema'
        ]);
        Permission::create([
        	'name' => 'Creacion de roles',
        	'slug' => 'roles.create',
        	'description' => 'Crear roles en el sistema'
        ]);
        Permission::create([
        	'name' => 'Edicion de roles',
        	'slug' => 'roles.edit',
        	'description' => 'Editar cualquier dato de un rol del sistema'
        ]);
        Permission::create([
        	'name' => 'Eliminar roles',
        	'slug' => 'roles.destroy',
        	'description' => 'Eliminar un rol del sistema'
        ]);

        /* Categorias*/
        Permission::create([
        	'name' => 'Navegar categorias',
        	'slug' => 'categorias.index',
        	'description' => 'Lista y navega todas los categorias del sistema'
        ]);
        Permission::create([
        	'name' => 'Ver detalle de una categoria',
        	'slug' => 'categorias.show',
        	'description' => 'Ver en detalle cada categoria del sistema'
        ]);
        Permission::create([
        	'name' => 'Creacion de categorias',
        	'slug' => 'categorias.store',
        	'description' => 'Crear categorias en el sistema'
        ]);
        Permission::create([
        	'name' => 'Edicion de categorias',
        	'slug' => 'categorias.edit',
        	'description' => 'Editar cualquier dato de una categoria del sistema'
        ]);
        Permission::create([
        	'name' => 'Eliminar categorias',
        	'slug' => 'categorias.destroy',
        	'description' => 'Eliminar una categoria del sistema'
        ]);

        /* Etiquetas*/
        Permission::create([
            'name' => 'Navegar Etiquetas',
            'slug' => 'etiquetas.index',
            'description' => 'Lista y navega todas las etiquetas del sistema'
        ]);
        Permission::create([
            'name' => 'Creacion de etiquetas',
            'slug' => 'etiquetas.store',
            'description' => 'Crear etiquetas en el sistema'
        ]);
        Permission::create([
            'name' => 'Edicion de etiquetas',
            'slug' => 'etiquetas.edit',
            'description' => 'Editar cualquier dato de una etiqueta del sistema'
        ]);
        Permission::create([
            'name' => 'Eliminar etiquetas',
            'slug' => 'etiquetas.destroy',
            'description' => 'Eliminar una etiqueta del sistema'
        ]);

        /* Fuentes*/
        Permission::create([
            'name' => 'Navegar fuentes',
            'slug' => 'fuentes.index',
            'description' => 'Lista y navega todas las fuentes del sistema'
        ]);
        Permission::create([
            'name' => 'Creacion de fuentes',
            'slug' => 'fuentes.create',
            'description' => 'Crear fuentes en el sistema'
        ]);
        Permission::create([
            'name' => 'Edicion de fuentes',
            'slug' => 'fuentes.edit',
            'description' => 'Editar cualquier dato de una fuente del sistema'
        ]);
        Permission::create([
            'name' => 'Eliminar fuentes',
            'slug' => 'fuentes.destroy',
            'description' => 'Eliminar una fuente del sistema'
        ]);

        /* Importaciones de Frecuencias*/
        Permission::create([
            'name' => 'Navegar importaciones de frecuencias',
            'slug' => 'importaciones_frecuencias.index',
            'description' => 'Lista y navega todas las importaciones de frecuencias del sistema'
        ]);
        Permission::create([
            'name' => 'Creacion de importaciones de frecuencias',
            'slug' => 'importaciones_frecuencias.create',
            'description' => 'Crear importaciones de frecuencias en el sistema'
        ]);
        Permission::create([
            'name' => 'Eliminar importaciones de frecuencias',
            'slug' => 'importaciones_frecuencias.destroy',
            'description' => 'Eliminar una importacion de frecuencias del sistema'
        ]);
        Permission::create([
            'name' => 'Ver detalle de una importacion de frecuencias',
            'slug' => 'importaciones_frecuencias.show',
            'description' => 'Ver el listado de frecuencias de uan importacion'
        ]);

        /* Frecuencias*/
        
        Permission::create([
            'name' => 'Edicion de importaciones de frecuencias',
            'slug' => 'frecuencias.edit',
            'description' => 'Editar cualquier dato de una importacion de frecuencias del sistema'
        ]);
        Permission::create([
            'name' => 'Eliminar importaciones de frecuencias',
            'slug' => 'frecuencias.destroy',
            'description' => 'Eliminar una importacion de frecuencias del sistema'
        ]);

        /* Marcadores*/
        Permission::create([
            'name' => 'Navegar marcadores',
            'slug' => 'marcadores.index',
            'description' => 'Lista y navega todos los marcadores del sistema'
        ]);
        Permission::create([
            'name' => 'Creacion de marcadores',
            'slug' => 'marcadores.store',
            'description' => 'Crear marcadores en el sistema'
        ]);
        Permission::create([
            'name' => 'Edicion de marcadores',
            'slug' => 'marcadores.edit',
            'description' => 'Editar cualquier dato de un marcador del sistema'
        ]);
        Permission::create([
            'name' => 'Eliminar marcadores',
            'slug' => 'marcadores.destroy',
            'description' => 'Eliminar un marcador del sistema'
        ]);

        /* Perfiles Geneticos*/
        Permission::create([
            'name' => 'Navegar perfiles geneticos',
            'slug' => 'perfiles_geneticos.index',
            'description' => 'Lista y navega todos los perfiles geneticos del sistema'
        ]);
        Permission::create([
            'name' => 'Ver detalle de un perfil genetico',
            'slug' => 'perfiles_geneticos.show',
            'description' => 'Ver en detalle cada perfil genetico del sistema'
        ]);
        Permission::create([
            'name' => 'Creacion de perfiles geneticos',
            'slug' => 'perfiles_geneticos.store',
            'description' => 'Crear perfiles geneticos en el sistema'
        ]);
        Permission::create([
            'name' => 'Edicion de perfiles geneticos',
            'slug' => 'perfiles_geneticos.edit',
            'description' => 'Editar cualquier dato de un perfil genetico del sistema'
        ]);
        Permission::create([
            'name' => 'Eliminar perfiles geneticos',
            'slug' => 'perfiles_geneticos.destroy',
            'description' => 'Eliminar un perfil genetico del sistema'
        ]);
        Permission::create([
            'name' => 'Navegar perfiles en revision',
            'slug' => 'perfiles_geneticos.revision',
            'description' => 'Ver todo los perfiles que requieren revision'
        ]);
        Permission::create([
            'name' => 'Navegar perfiles duplicados',
            'slug' => 'perfiles_geneticos.duplicados',
            'description' => 'Ver todo los perfiles que requieren revision'
        ]);

        /* Busquedas*/
        Permission::create([
            'name' => 'Navegar busquedas',
            'slug' => 'busquedas.index',
            'description' => 'Lista y navega todas las busquedas del sistema'
        ]);
        Permission::create([
            'name' => 'Ver detalle de una busqueda',
            'slug' => 'busquedas.show',
            'description' => 'Ver en detalle cada busqueda del sistema'
        ]);
        Permission::create([
            'name' => 'Creacion de busquedas',
            'slug' => 'busquedas.store',
            'description' => 'Crear busquedas en el sistema'
        ]);
        Permission::create([
            'name' => 'Eliminar busquedas',
            'slug' => 'busquedas.destroy',
            'description' => 'Eliminar un perfil genetico del sistema'
        ]);
    }
}
