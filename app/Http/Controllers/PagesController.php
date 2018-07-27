<?php

namespace App\Http\Controllers;

use App\Fuente;
use App\Etiqueta;
use App\User;
use App\ImportacionPerfil;
use App\PerfilGenetico;
use App\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PagesController extends Controller
{
    public function home(){

    	if(Auth::guest()){
    		return view('welcome');
    	}
    	else{
    		$usuario = User::find(Auth::id());
    		$categorias = Categoria::where('desestimado', '=', 0)->get();

	    	if($usuario->estado->nombre == "CNB"){
	    		$numero_fuentes = Fuente::where('desestimado', '=', 0)->count();
		    	$numero_etiquetas = Etiqueta::where('desestimado', '=', 0)->count();
		    	$numero_usuarios = User::get()->count();
		    	$numero_importaciones = ImportacionPerfil::where('desestimado', '=', 0)->count();
		    	$numero_perfiles = PerfilGenetico::where('desestimado', '=', 0)->count();
		    	$numero_categorias = Categoria::where('desestimado', '=', 0)->count();
	    	}
	    	else{
	    		$numero_fuentes = Fuente::where('desestimado', '=', 0)->count();
		    	$numero_etiquetas = Etiqueta::where('desestimado', '=', 0)->count();
		    	$numero_usuarios = User::where('id_estado', '=', $usuario->id_estado)->get()->count();
		    	$numero_importaciones = ImportacionPerfil::where('id_estado', '=', $usuario->id_estado)->where('desestimado', '=', 0)->count();
		    	$numero_perfiles = PerfilGenetico::where('id_estado', '=', $usuario->id_estado)->where('desestimado', '=', 0)->count();
		    	$numero_categorias = Categoria::where('desestimado', '=', 0)->count();	
	    	}
	    	return view('welcome',[
			'numero_fuentes' => $numero_fuentes,
			'numero_etiquetas' => $numero_etiquetas,
			'numero_usuarios' => $numero_usuarios,
			'numero_importaciones' => $numero_importaciones,
			'numero_perfiles' => $numero_perfiles,
			'numero_categorias' => $numero_categorias,
			'categorias' => $categorias,
		]);
    	}
    }

    public function estadisticas(Request $request){
    	if($request->ajax()){
    		$usuario = User::find(Auth::id());
			$array_de_etiquetas_y_sus_perfiles = array();
			if($usuario->estado->nombre == 'CNB'){
				$etiquetas = Etiqueta::where('categoria_id', '=', $request->fuente)->get(); 
				foreach ($etiquetas as $etiqueta) {
					$numero_de_perfiles = DB::table('etiquetas_asignadas')
					->join('perfiles_geneticos', 'etiquetas_asignadas.id_perfil_genetico' , '=', 'perfiles_geneticos.id' )
					->select('etiquetas_asignadas.id_etiqueta', 
							 'etiquetas_asignadas.id_perfil_genetico',
							 'perfiles_geneticos.id',
							 'perfiles_geneticos.id_estado',
							 'perfiles_geneticos.es_perfil_repetido',
							 'perfiles_geneticos.desestimado'
							)
					->where('perfiles_geneticos.es_perfil_repetido', '=', 0)
					->where('perfiles_geneticos.desestimado', '=', 0)
					->where('etiquetas_asignadas.id_etiqueta', '=', $etiqueta->id)
					->count();

					if($numero_de_perfiles <> 0){
						array_push($array_de_etiquetas_y_sus_perfiles, [ $etiqueta->nombre , $numero_de_perfiles ]);
					}
				}	
			}
			else{
				$etiquetas = Etiqueta::where('categoria_id', '=', $request->fuente)->get(); 
				foreach ($etiquetas as $etiqueta) {
					$numero_de_perfiles = DB::table('etiquetas_asignadas')
					->join('perfiles_geneticos', 'etiquetas_asignadas.id_perfil_genetico' , '=', 'perfiles_geneticos.id' )
					->select('etiquetas_asignadas.id_etiqueta', 
							 'etiquetas_asignadas.id_perfil_genetico',
							 'perfiles_geneticos.id',
							 'perfiles_geneticos.id_estado',
							 'perfiles_geneticos.es_perfil_repetido',
							 'perfiles_geneticos.desestimado'
							)
					->where('perfiles_geneticos.es_perfil_repetido', '=', 0)
					->where('perfiles_geneticos.desestimado', '=', 0)
					->where('etiquetas_asignadas.id_etiqueta', '=', $etiqueta->id)
					->where('perfiles_geneticos.id_estado', '=', $usuario->estado->id)
					->count();

					if($numero_de_perfiles <> 0){
						array_push($array_de_etiquetas_y_sus_perfiles, [ $etiqueta->nombre , $numero_de_perfiles ]);
					}
				}
			}

    		return response()->json([
	          'newData' => $array_de_etiquetas_y_sus_perfiles,
	        ]); 
    	}
    }
}
