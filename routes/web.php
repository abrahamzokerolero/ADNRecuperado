<?php

/*
|--------------------------------------------------------------------------
| Rutas de Auth y de Welcome
|--------------------------------------------------------------------------
*/
Auth::routes();
Route::get('/', "PagesController@home")->name('home');
Route::get('/estadisticas', "PagesController@estadisticas")->name('estadisticas');


/*
|--------------------------------------------------------------------------
| Rutas a categorias
|--------------------------------------------------------------------------
| Resource agrupa el CRUD de categorias / ver con php artisan routes:list
*/


Route::middleware(['auth'])->group(function(){

	/* Rutas de Roles con Shinobi*/
	Route::get('roles', 'RolesController@index')->name('roles.index')->middleware('permission:roles.index');
	Route::get('roles/create', 'RolesController@create')->name('roles.create')->middleware('permission:roles.create');
	Route::post('roles/store', 'RolesController@store')->name('roles.store')->middleware('permission:roles.create');
	Route::get('roles/{role}/edit', 'RolesController@edit')->name('roles.edit')->middleware('permission:roles.edit');
	Route::put('roles/{role}','RolesController@update')->name('roles.update')->middleware('permission:roles.edit');
	Route::get('roles/{role}', 'RolesController@show')->name('roles.show')->middleware('permission:roles.show');
	Route::get('roles/{role}/destroy', 'RolesController@destroy')->name('roles.destroy')->middleware('permission:roles.destroy');


	/* Rutas de Usuarios con Shinobi*/
	Route::get('users', 'UsersController@index')->name('users.index')->middleware('permission:users.index');
	Route::get('users/{user}/edit', 'UsersController@edit')->name('users.edit')->middleware('permission:users.edit');
	Route::put('users/{user}','UsersController@update')->name('users.update')->middleware('permission:users.edit');
	Route::get('users/{user}', 'UsersController@show')->name('users.show')->middleware('permission:users.show');
	Route::get('users/{user}/destroy', 'UsersController@destroy')->name('users.destroy')->middleware('permission:users.destroy');
	Route::get('personal/edit', 'UsersController@editar_perfil_personal')->name('users.personal_edit');
	Route::put('personal/{user}/edit','UsersController@update_perfil_personal')->name('users.personal_update');

	/* Rutas de Categorias con Shinobi*/
	Route::get('categorias', 'CategoriasController@index')->name('categorias.index')->middleware('permission:categorias.index');
	Route::get('categorias/create', 'CategoriasController@create')->name('categorias.create')->middleware('permission:categorias.create');
	Route::post('categorias/store', 'CategoriasController@store')->name('categorias.store')->middleware('permission:categorias.create');
	Route::put('categorias/{categoria}','CategoriasController@update')->name('categorias.update')->middleware('permission:categorias.edit');
	Route::get('categorias/{categoria}/edit','CategoriasController@edit')->name('categorias.edit')->middleware('permission:categorias.edit');
	Route::get('categorias/{categoria}', 'CategoriasController@show')->name('categorias.show')->middleware('permission:categorias.show');
	Route::get('categorias/{categoria}/destroy', 'CategoriasController@destroy')->name('categorias.destroy')->middleware('permission:categorias.destroy');

	/* Rutas de Etiquetas con Shinobi*/
	Route::get('etiquetas', 'EtiquetasController@index')->name('etiquetas.index')->middleware('permission:etiquetas.index');
	Route::get('etiquetas/create', 'EtiquetasController@create')->name('etiquetas.create')->middleware('permission:etiquetas.create');
	Route::post('etiquetas/store', 'EtiquetasController@store')->name('etiquetas.store')->middleware('permission:etiquetas.create');
	Route::put('etiquetas/{etiqueta}','EtiquetasController@update')->name('etiquetas.update')->middleware('permission:etiquetas.edit');
	Route::get('etiquetas/{etiqueta}/edit','EtiquetasController@edit')->name('etiquetas.edit')->middleware('permission:etiquetas.edit');
	Route::get('etiquetas/{etiqueta}', 'EtiquetasController@show')->name('etiquetas.show')->middleware('permission:etiquetas.show');
	Route::get('etiquetas/{etiqueta}/destroy', 'EtiquetasController@destroy')->name('etiquetas.destroy')->middleware('permission:etiquetas.destroy');

	/* Rutas de Fuentes con Shinobi*/
	Route::get('fuentes', 'FuentesController@index')->name('fuentes.index')->middleware('permission:fuentes.index');
	Route::get('fuentes/create', 'FuentesController@create')->name('fuentes.create')->middleware('permission:fuentes.create');
	Route::post('fuentes/store', 'FuentesController@store')->name('fuentes.store')->middleware('permission:fuentes.create');
	Route::put('fuentes/{fuente}','FuentesController@update')->name('fuentes.update')->middleware('permission:fuentes.edit');
	Route::get('fuentes/{fuente}/edit','FuentesController@edit')->name('fuentes.edit')->middleware('permission:fuentes.edit');
	Route::get('fuentes/{fuente}', 'FuentesController@show')->name('fuentes.show')->middleware('permission:fuentes.show');
	Route::get('fuentes/{fuente}/destroy', 'FuentesController@destroy')->name('fuentes.destroy')->middleware('permission:fuentes.destroy');	

	/* Rutas de Importaciones Frecuencias con Shinobi*/
	Route::get('importaciones_frecuencias', 'ImportacionesFrecuenciasController@index')->name('importaciones_frecuencias.index')->middleware('permission:importaciones_frecuencias.index');
	Route::get('importaciones_frecuencias/create', 'ImportacionesFrecuenciasController@create')->name('importaciones_frecuencias.create')->middleware('permission:importaciones_frecuencias.create');
	Route::post('importaciones_frecuencias/store', 'ImportacionesFrecuenciasController@store')->name('importaciones_frecuencias.store')->middleware('permission:importaciones_frecuencias.create');
	Route::get('importaciones_frecuencias/{importacion_frecuencia}', 'ImportacionesFrecuenciasController@show')->name('importaciones_frecuencias.show')->middleware('permission:importaciones_frecuencias.show');
	Route::get('importaciones_frecuencias/{importacion_frecuencia}/destroy', 'ImportacionesFrecuenciasController@destroy')->name('importaciones_frecuencias.destroy')->middleware('permission:importaciones_frecuencias.destroy');
	Route::get('importaciones_frecuencias/{importacion_frecuencia}/default', 'ImportacionesFrecuenciasController@default')->name('importaciones_frecuencias.default');

	/* Rutas de Importaciones Frecuencias con Shinobi*/
	Route::put('frecuencias/{frecuencia}','FrecuenciasController@update')->name('frecuencias.update')->middleware('permission:frecuencias.edit');
	Route::get('frecuencias/{frecuencia}/edit','FrecuenciasController@edit')->name('frecuencias.edit')->middleware('permission:frecuencias.edit');
	Route::get('frecuencias/{frecuencia}/destroy', 'FrecuenciasController@destroy')->name('frecuencias.destroy')->middleware('permission:frecuencias.destroy');

	/* Rutas de Marcadores con Shinobi*/
	Route::get('marcadores', 'MarcadoresController@index')->name('marcadores.index')->middleware('permission:marcadores.index');
	Route::get('marcadores/create', 'MarcadoresController@create')->name('marcadores.create')->middleware('permission:marcadores.create');
	Route::post('marcadores/store', 'MarcadoresController@store')->name('marcadores.store')->middleware('permission:marcadores.create');
	Route::put('marcadores/{marcador}','MarcadoresController@update')->name('marcadores.update')->middleware('permission:marcadores.edit');
	Route::get('marcadores/{marcador}/edit','MarcadoresController@edit')->name('marcadores.edit')->middleware('permission:marcadores.edit');
	Route::get('marcadores/{marcador}', 'MarcadoresController@show')->name('marcadores.show')->middleware('permission:marcadores.show');
	Route::get('marcadores/{marcador}/destroy', 'MarcadoresController@destroy')->name('marcadores.destroy')->middleware('permission:marcadores.destroy');	

	/* Rutas de Importaciones de perfiles*/
	Route::get('importaciones_perfiles', 'ImportacionesPerfilesController@index')->name('importaciones_perfiles.index')->middleware('permission:importaciones_perfiles.index');
	Route::get('importaciones_perfiles/create', 'ImportacionesPerfilesController@create')->name('importaciones_perfiles.create')->middleware('permission:importaciones_perfiles.create');
	Route::post('importaciones_perfiles/store', 'ImportacionesPerfilesController@store')->name('importaciones_perfiles.store')->middleware('permission:importaciones_perfiles.create');
	Route::get('importaciones_perfiles/{importacion_perfil}/edit','ImportacionesPerfilesController@edit')->name('importaciones_perfiles.edit')->middleware('permission:importaciones_perfiles.edit');
	Route::get('importaciones_perfiles/{importacion_perfil}', 'ImportacionesPerfilesController@show')->name('importaciones_perfiles.show')->middleware('permission:importaciones_perfiles.show');
	Route::get('importaciones_perfiles/{importacion_perfil}/destroy', 'ImportacionesPerfilesController@destroy')->name('importaciones_perfiles.destroy')->middleware('permission:importaciones_perfiles.destroy');

	// Rutas Ajax	

	Route::post('importaciones_perfiles/crear_categoria', 'ImportacionesPerfilesController@crear_categoria')->name('importaciones_perfiles.crear_categoria');
	Route::post('importaciones_perfiles/crear_etiquetas', 'ImportacionesPerfilesController@crear_etiquetas')->name('importaciones_perfiles.crear_etiquetas');

	
	/* Rutas de Perfiles Geneticos*/
	Route::get('perfiles_geneticos', 'PerfilesGeneticosController@index')->name('perfiles_geneticos.index')->middleware('permission:perfiles_geneticos.index');
	Route::get('perfiles_geneticos/create', 'PerfilesGeneticosController@create')->name('perfiles_geneticos.create')->middleware('permission:perfiles_geneticos.create');
	Route::put('perfiles_geneticos/{perfil_genetico}', 'PerfilesGeneticosController@update')->name('perfiles_geneticos.update')->middleware('permission:perfiles_geneticos.edit');
	Route::post('perfiles_geneticos/store', 'PerfilesGeneticosController@store')->name('perfiles_geneticos.store')->middleware('permission:perfiles_geneticos.create');
	Route::get('perfiles_geneticos/{importacion_perfil}/edit','PerfilesGeneticosController@edit')->name('perfiles_geneticos.edit')->middleware('permission:perfiles_geneticos.edit');
	Route::get('perfiles_geneticos/{importacion_perfil}', 'PerfilesGeneticosController@show')->name('perfiles_geneticos.show')->middleware('permission:perfiles_geneticos.show');
	Route::get('perfiles_geneticos/{importacion_perfil}/destroy', 'PerfilesGeneticosController@destroy')->name('perfiles_geneticos.destroy')->middleware('permission:perfiles_geneticos.destroy');
	
	// En revision
	Route::get('perfiles/revision', 'PerfilesGeneticosController@revision')->name('perfiles_geneticos.revision');
	Route::put('perfiles/{perfil_genetico}/validar', 'PerfilesGeneticosController@validar')->name('perfiles_geneticos.validar');

	Route::post('perfiles/comprobacion', 'PerfilesGeneticosController@comprobacion')->name('perfiles_geneticos.comprobacion');

	// Duplicados
	Route::get('perfiles/duplicados', 'PerfilesGeneticosController@duplicados')->name('perfiles_geneticos.duplicados');
	Route::get('perfiles/{perfil_genetico}/validar_duplicado', 'PerfilesGeneticosController@validar_duplicado')->name('perfiles_geneticos.validar_duplicado');
	Route::put('perfiles/{perfil_genetico}/guardar_validacion_de_duplicado', 'PerfilesGeneticosController@guardar_validacion_de_duplicado')->name('perfiles_geneticos.guardar_validacion_de_duplicado');
	
	// Desestimados
	Route::get('perfiles/desestimados', 'PerfilesGeneticosController@desestimados')->name('perfiles_geneticos.desestimados');

	// Desde estadisticas
	Route::get('perfiles/{etiqueta}/estadisticas', 'PerfilesGeneticosController@estadisticas')->name('perfiles_geneticos.estadisticas');

	// Rutas para ajax
	Route::post('perfiles/filtro_por_metadato', 'PerfilesGeneticosController@filtro_por_metadato')->name('perfiles_geneticos.filtro_por_metadato');
	Route::post('perfiles/filtro_por_etiquetas', 'PerfilesGeneticosController@filtro_por_etiquetas')->name('perfiles_geneticos.filtro_por_etiquetas');
	Route::post('perfiles/filtro_por_fuentes', 'PerfilesGeneticosController@filtro_por_fuentes')->name('perfiles_geneticos.filtro_por_fuentes');
	Route::post('perfiles/filtro_por_id_importacion', 'PerfilesGeneticosController@filtro_por_id_importacion')->name('perfiles_geneticos.filtro_por_id_importacion');
	Route::post('perfiles/filtro_combinado', 'PerfilesGeneticosController@filtro_combinado')->name('perfiles_geneticos.filtro_combinado');
	Route::post('perfiles/filtro_combinado2', 'PerfilesGeneticosController@filtro_combinado2')->name('perfiles_geneticos.filtro_combinado2');
	Route::post('perfiles/filtro_combinado3', 'PerfilesGeneticosController@filtro_combinado3')->name('perfiles_geneticos.filtro_combinado3');
	Route::get('perfiles/restablecer', 'PerfilesGeneticosController@restablecer')->name('perfiles_geneticos.restablecer');


	/* Rutas de Busquedas*/
	Route::get('busquedas', 'BusquedasController@index')->name('busquedas.index')->middleware('permission:busquedas.index');
	Route::get('busquedas/create', 'BusquedasController@create')->name('busquedas.create')->middleware('permission:busquedas.create');
	Route::post('busquedas/store', 'BusquedasController@store')->name('busquedas.store')->middleware('permission:busquedas.store');
	Route::post('busquedas/store2', 'BusquedasController@store2')->name('busquedas.store2');
	Route::get('busquedas/{importacion_perfil}', 'BusquedasController@show')->name('busquedas.show')->middleware('permission:busquedas.show');
	Route::get('busquedas/{importacion_perfil}/destroy', 'BusquedasController@destroy')->name('busquedas.destroy')->middleware('permission:busquedas.destroy');
	Route::get('ventana/busquedas', 'BusquedasController@ventana')->name('busquedas.ventana');
	Route::post('busquedas_resultados/{busqueda}/concluir', 'BusquedasController@concluir')->name('busquedas.concluir');

});