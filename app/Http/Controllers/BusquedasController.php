<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\PerfilGenetico;
use App\EtiquetaAsignada;
use App\User;
use App\Etiqueta;
use App\Busqueda;
use App\BusquedaResultado;
use App\Categoria;
use App\Fuente;
use App\ImportacionFrecuencia;
use App\Frecuencia;
use App\Marcador;
use App\EstatusBusqueda;
use App\Mensaje;
use Illuminate\Support\Facades\DB;
use App\Alelo;
use App\Log;


class BusquedasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $usuario = User::find(Auth::id());
        if($usuario->estado->nombre == 'CNB'){
            $busquedas = Busqueda::with(array('fuente' => function($query){
                $query->select('fuentes.id','fuentes.nombre');
            }))->with(array('usuario' => function($query){
                $query->select('users.id', 'users.name');
            }))->with(array('tipo_de_busqueda' => function($query){
                $query->select('tipos_de_busquedas.id','tipos_de_busquedas.nombre');
            }))->with(array('estatus' => function($query){
                $query->select('estatus_busquedas.id', 'estatus_busquedas.nombre');
            }))->get();
        }
        else{
            $busquedas = Busqueda::with(array('fuente' => function($query){
                $query->select('fuentes.id','fuentes.nombre');
            }))->with(array('usuario' => function($query){
                $query->select('users.id', 'users.name');
            }))->with(array('tipo_de_busqueda' => function($query){
                $query->select('tipos_de_busquedas.id','tipos_de_busquedas.nombre');
            }))->with(array('estatus' => function($query){
                $query->select('estatus_busquedas.id', 'estatus_busquedas.nombre');
            }))->where('id_estado', $usuario->estado->id)->get();
            
        }        
        return view('busquedas.index', [
            'busquedas' => $busquedas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fuentes = Fuente::get();
        $usuario = User::find(Auth::id());
        if($usuario->estado->nombre == "CNB"){
            $categorias = Categoria::with(array('etiquetas' => function($query){
              $query->with(array('perfiles_geneticos_asociados' => function($query){ 
                $query->join('perfiles_geneticos', 'etiquetas_asignadas.id_perfil_genetico', '=', 'perfiles_geneticos.id')
                ->where('perfiles_geneticos.desestimado', 0)
                ->where('perfiles_geneticos.es_perfil_repetido', 0);
              }))->where('desestimado', 0);
            }))->where('desestimado', '=', 0)->get();
            $tablas_de_frecuencias = ImportacionFrecuencia::where('id_estado', '33')
                ->where('desestimado', 0)
                ->get();
        }
        else{
            $categorias = Categoria::with(array('etiquetas' => function($query) use( &$usuario ){
              $query->with(array('perfiles_geneticos_asociados' => function($query) use( &$usuario ){ 
                $query->join('perfiles_geneticos', 'etiquetas_asignadas.id_perfil_genetico', '=', 'perfiles_geneticos.id')
                ->where('perfiles_geneticos.desestimado', 0)
                ->where('perfiles_geneticos.es_perfil_repetido', 0)
                ->where('perfiles_geneticos.id_estado', '=', $usuario->estado->id);
              }))->where('desestimado', 0);
            }))->where('desestimado', '=', 0)->get();
            $tablas_de_frecuencias = ImportacionFrecuencia::where('id_estado', $usuario->estado->id)
            ->where('desestimado', 0)
            ->get();
        }

        
        return view('busquedas.create',[
            'categorias' => $categorias,
            'fuentes' => $fuentes,
            'tablas_de_frecuencias' => $tablas_de_frecuencias,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // Busqueda individual
    public function store(Request $request)
    {  
        // $etiquetas_usadas = ';';
        // $perfil_objetivo = PerfilGenetico::where('identificador', $request->perfil)->first();
        // $usuario = User::find(Auth::id());

        // foreach ($request->etiquetas as $etiqueta) {
        //     $etiquetas_usadas .= $etiqueta . ';';
        // }

        // //store_procedure (etiquetas, tabla_de_frec, perfil_obj, id_estado, marcadores_minimos, descartar_p_en_revision )

        // $respuesta = DB::select('EXEC BUSQUEDA_INDIVIDUAL "'. $etiquetas_usadas .'", ' . $request->id_tabla_de_frecuencias . ',' . $perfil_objetivo->id . ',' . $usuario->estado->id . ',' . $request->marcadores_minimos .',' . $request->descartar_perfiles_en_revision . ';');

        // dd($respuesta);

        $usuario = User::find(Auth::id());
        $consecutivo = Busqueda::where('id_estado' , $usuario->estado->id)->count() + 1;
        $busqueda = Busqueda::create([
            'identificador' => 'B-'. $usuario->estado->id . '-' . $consecutivo,
            'motivo' => $request->motivo,
            'descripcion' => $request->descripcion,
            'marcadores_minimos' => $request->marcadores_minimos,
            'numero_de_exclusiones' => $request->exclusiones,
            'id_fuente' => $request->id_fuente,
            'id_usuario' => $usuario->id,
            'id_tipo_busqueda' => 1,
            'id_estado' => $usuario->estado->id,
            'id_tabla_de_frecuencias' => $request->id_tabla_de_frecuencias,
            'id_estatus_busqueda' => 1,
            'perfiles_en_revision_descartados' => $request->descartar_perfiles_en_revision,
            'etiquetas_usadas' => "" . implode(',', $request->etiquetas)
        ]);

        // subconsulta que reducira aquellos perfiles geneticos que se repitan en las etiquetas
        $perfiles_geneticos_temporales = DB::raw("(SELECT id_perfil_genetico From etiquetas_asignadas where id_etiqueta in (". implode(',',$request->etiquetas) .") group by id_perfil_genetico) as perfiles_geneticos_temporales");

        // subconsulta que reducira los alelos de los perfiles a aquellos que se encuentren solo en la tabla de frecuencias
        $marcadores_a_comparar = DB::raw("(SELECT frecuencias.id_marcador  from frecuencias where frecuencias.id_importacion = '". $request->id_tabla_de_frecuencias ."' group by frecuencias.id_marcador) as marcadores_a_comparar");


        // Se obtiene el  Perfil Objetivo con solo los marcadores que se tienen en la tabla de frecuencias
        $perfil_objetivo = PerfilGenetico::with(array('alelos' => function($query) use (&$marcadores_a_comparar){
                    $query
                    ->join($marcadores_a_comparar, 'marcadores_a_comparar.id_marcador', '=', 'alelos.id_marcador');
                }))
                ->select('perfiles_geneticos.*', 'users.name')
                ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
                ->where('perfiles_geneticos.identificador', $request->perfil)->first();

        // Si es usuario de CNB se buscara en todos los perfiles geneticos
        if($usuario->estado->nombre == 'CNB'){
            // si no se descartan los perfiles en revision
            if($request->descartar_perfiles_en_revision == 0){
                $perfiles_geneticos = PerfilGenetico::with(array('alelos' => function($query) use (&$marcadores_a_comparar){
                    $query
                    ->join($marcadores_a_comparar, 'marcadores_a_comparar.id_marcador', '=', 'alelos.id_marcador');
                }))
                ->select('perfiles_geneticos.*', 'perfiles_geneticos_temporales.id_perfil_genetico', 'users.name')
                ->join($perfiles_geneticos_temporales, 'perfiles_geneticos_temporales.id_perfil_genetico', '=', 'perfiles_geneticos.id')
                ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
                ->where('perfiles_geneticos.desestimado', 0)
                ->where('perfiles_geneticos.es_perfil_repetido', 0)
                ->where('perfiles_geneticos.id', '<>', $perfil_objetivo->id)
                ->where('perfiles_geneticos.numero_de_marcadores', '>=', $request->marcadores_minimos)
                ->get();    
            }
            else{ // Si se descartan los perfiles en revision
                $perfiles_geneticos = PerfilGenetico::with(array('alelos' => function($query) use (&$marcadores_a_comparar){
                    $query
                    ->join($marcadores_a_comparar, 'marcadores_a_comparar.id_marcador', '=', 'alelos.id_marcador');
                    
                }))
                ->select('perfiles_geneticos.*', 'perfiles_geneticos_temporales.id_perfil_genetico', 'users.name')
                ->join($perfiles_geneticos_temporales, 'perfiles_geneticos_temporales.id_perfil_genetico', '=', 'perfiles_geneticos.id')
                ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
                ->where('perfiles_geneticos.desestimado', 0)
                ->where('perfiles_geneticos.es_perfil_repetido', 0)
                ->where('perfiles_geneticos.requiere_revision', 0)
                ->where('perfiles_geneticos.id', '<>', $perfil_objetivo->id)
                ->where('perfiles_geneticos.numero_de_marcadores', '>=', $request->marcadores_minimos)
                ->get();    
            }  
        }
        else{   // Si se es de un estado se filtraran los perfiles geneticos por estado
            // si no se descartan los perfiles en revision
            if($request->descartar_perfiles_en_revision == 0){
                $perfiles_geneticos = PerfilGenetico::with(array('alelos' => function($query) use (&$marcadores_a_comparar){
                    $query
                    ->join($marcadores_a_comparar, 'marcadores_a_comparar.id_marcador', '=', 'alelos.id_marcador');
                }))
                ->select('perfiles_geneticos.*', 'perfiles_geneticos_temporales.id_perfil_genetico', 'users.name')
                ->join($perfiles_geneticos_temporales, 'perfiles_geneticos_temporales.id_perfil_genetico', '=', 'perfiles_geneticos.id')
                ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
                ->where('perfiles_geneticos.id_estado', '=' , $usuario->estado->id)
                ->where('perfiles_geneticos.desestimado', 0)
                ->where('perfiles_geneticos.es_perfil_repetido', 0)
                ->where('perfiles_geneticos.id', '<>', $perfil_objetivo->id)
                ->where('perfiles_geneticos.numero_de_marcadores', '>=', $request->marcadores_minimos)
                ->get();    
            }
            else{ // Si se descartan los perfiles en revision
                $perfiles_geneticos = PerfilGenetico::with(array('alelos' => function($query) use (&$marcadores_a_comparar){
                    $query
                    ->join($marcadores_a_comparar, 'marcadores_a_comparar.id_marcador', '=', 'alelos.id_marcador');
                    
                }))
                ->select('perfiles_geneticos.*', 'perfiles_geneticos_temporales.id_perfil_genetico', 'users.name')
                ->join($perfiles_geneticos_temporales, 'perfiles_geneticos_temporales.id_perfil_genetico', '=', 'perfiles_geneticos.id')
                ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
                ->where('perfiles_geneticos.id_estado', '=' , $usuario->estado->id)
                ->where('perfiles_geneticos.desestimado', 0)
                ->where('perfiles_geneticos.es_perfil_repetido', 0)
                ->where('perfiles_geneticos.requiere_revision', 0)
                ->where('perfiles_geneticos.id', '<>', $perfil_objetivo->id)
                ->where('perfiles_geneticos.numero_de_marcadores', '>=', $request->marcadores_minimos)
                ->get();    
            }   
        }

        $frecuencias = Frecuencia::where('id_importacion', $request->id_tabla_de_frecuencias)->get();

        $log = Log::create([
            'id_usuario' => $usuario->id,
            'id_estado' => $usuario->estado->id,
            'actividad' => 'Realizo una busqueda individual: ' . $busqueda->identificador,
        ]);
        
        // Con las subconsultas se comparan solo aquellos marcadores en comun entre el perfil objetivo y los etiquetados

        foreach ($perfiles_geneticos as $perfil_genetico){
            obtenerIP($perfil_objetivo, $perfil_genetico, $request , $busqueda, $frecuencias);
        }

        
        return redirect()->route('busquedas.index');
    }


    // GRUPAL
    public function store2(Request $request)
    {   
        $usuario = User::find(Auth::id());

        // subconsulta que reducira aquellos perfiles geneticos objetivo que se repitan en las etiquetas
        $perfiles_geneticos_temporales = DB::raw("(SELECT id_perfil_genetico From etiquetas_asignadas where id_etiqueta in (". implode(',',$request->etiquetasObjetivo) .") group by id_perfil_genetico) as perfiles_geneticos_temporales");

        // subconsulta que reducira aquellos perfiles geneticos subordinados que se repitan en las etiquetas
        $perfiles_geneticos_temporales2 = DB::raw("(SELECT id_perfil_genetico From etiquetas_asignadas where id_etiqueta in (". implode(',',$request->etiquetasSubordinadas) .") group by id_perfil_genetico) as perfiles_geneticos_temporales");

        // subconsulta que reducira los alelos de los perfiles a aquellos que se encuentren solo en la tabla de frecuencias
        $marcadores_a_comparar = DB::raw("(SELECT frecuencias.id_marcador  from frecuencias where frecuencias.id_importacion = '". $request->id_tabla_de_frecuencias ."' group by frecuencias.id_marcador) as marcadores_a_comparar");

        // COMPARACIONES PARA OBTENER LOS PERFILES GENETICOS OBJETIVO Y SUBORDINADOS FILTRANDOLOS CON LAS SUBCONSULTAS Y POR ESTADO
        // si es usuario de CNB
        if($usuario->estado->nombre == 'CNB'){
            // si no se descartaron los perfiles en revision
            if($request->descartar_perfiles_en_revision == 0){
                $perfiles_objetivo = PerfilGenetico::with(array('alelos' => function($query) use (&$marcadores_a_comparar){
                        $query
                        ->join($marcadores_a_comparar, 'marcadores_a_comparar.id_marcador', '=', 'alelos.id_marcador');
                    }))
                    ->select('perfiles_geneticos.*', 'perfiles_geneticos_temporales.id_perfil_genetico', 'users.name')
                    ->join($perfiles_geneticos_temporales, 'perfiles_geneticos_temporales.id_perfil_genetico', '=', 'perfiles_geneticos.id')
                    ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
                    ->where('perfiles_geneticos.desestimado', 0)
                    ->where('perfiles_geneticos.es_perfil_repetido', 0)
                    ->where('perfiles_geneticos.numero_de_marcadores', '>=', $request->marcadores_minimos)
                    ->get();

                $perfiles_subordinados = PerfilGenetico::with(array('alelos' => function($query) use (&$marcadores_a_comparar){
                        $query
                        ->join($marcadores_a_comparar, 'marcadores_a_comparar.id_marcador', '=', 'alelos.id_marcador');
                    }))
                    ->select('perfiles_geneticos.*', 'perfiles_geneticos_temporales.id_perfil_genetico', 'users.name')
                    ->join($perfiles_geneticos_temporales2, 'perfiles_geneticos_temporales.id_perfil_genetico', '=', 'perfiles_geneticos.id')
                    ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
                    ->where('perfiles_geneticos.desestimado', 0)
                    ->where('perfiles_geneticos.es_perfil_repetido', 0)
                    ->where('perfiles_geneticos.numero_de_marcadores', '>=', $request->marcadores_minimos)
                    ->get();
            }
            else{ // si se descartaron los perfiles en revision
                $perfiles_objetivo = PerfilGenetico::with(array('alelos' => function($query) use (&$marcadores_a_comparar){
                        $query
                        ->join($marcadores_a_comparar, 'marcadores_a_comparar.id_marcador', '=', 'alelos.id_marcador');
                    }))
                    ->select('perfiles_geneticos.*', 'perfiles_geneticos_temporales.id_perfil_genetico', 'users.name')
                    ->join($perfiles_geneticos_temporales, 'perfiles_geneticos_temporales.id_perfil_genetico', '=', 'perfiles_geneticos.id')
                    ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
                    ->where('perfiles_geneticos.desestimado', 0)
                    ->where('perfiles_geneticos.es_perfil_repetido', 0)
                    ->where('perfiles_geneticos.requiere_revision', 0)
                    ->where('perfiles_geneticos.numero_de_marcadores', '>=', $request->marcadores_minimos)
                    ->get();

                $perfiles_subordinados = PerfilGenetico::with(array('alelos' => function($query) use (&$marcadores_a_comparar){
                        $query
                        ->join($marcadores_a_comparar, 'marcadores_a_comparar.id_marcador', '=', 'alelos.id_marcador');
                    }))
                        ->select('perfiles_geneticos.*', 'perfiles_geneticos_temporales.id_perfil_genetico', 'users.name')
                    ->join($perfiles_geneticos_temporales2, 'perfiles_geneticos_temporales.id_perfil_genetico', '=', 'perfiles_geneticos.id')
                    ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
                    ->where('perfiles_geneticos.desestimado', 0)
                    ->where('perfiles_geneticos.es_perfil_repetido', 0)
                    ->where('perfiles_geneticos.requiere_revision', 0)
                    ->where('perfiles_geneticos.numero_de_marcadores', '>=', $request->marcadores_minimos)
                    ->get();
            }            
        }
        // Si es usuario de un estado.
        else{
            // si no se descartan los perfiles en revision.
            if($request->descartar_perfiles_en_revision == 0){
                $perfiles_objetivo = PerfilGenetico::with(array('alelos' => function($query) use (&$marcadores_a_comparar){
                        $query
                        ->join($marcadores_a_comparar, 'marcadores_a_comparar.id_marcador', '=', 'alelos.id_marcador');
                    }))
                    ->select('perfiles_geneticos.*', 'perfiles_geneticos_temporales.id_perfil_genetico', 'users.name')
                    ->join($perfiles_geneticos_temporales, 'perfiles_geneticos_temporales.id_perfil_genetico', '=', 'perfiles_geneticos.id')
                    ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
                    ->where('perfiles_geneticos.id_estado', '=' , $usuario->estado->id)
                    ->where('perfiles_geneticos.desestimado', 0)
                    ->where('perfiles_geneticos.es_perfil_repetido', 0)
                    ->where('perfiles_geneticos.numero_de_marcadores', '>=', $request->marcadores_minimos)
                    ->get();

                $perfiles_subordinados = PerfilGenetico::with(array('alelos' => function($query) use (&$marcadores_a_comparar){
                        $query
                        ->join($marcadores_a_comparar, 'marcadores_a_comparar.id_marcador', '=', 'alelos.id_marcador');
                    }))
                    ->select('perfiles_geneticos.*', 'perfiles_geneticos_temporales.id_perfil_genetico', 'users.name')
                    ->join($perfiles_geneticos_temporales2, 'perfiles_geneticos_temporales.id_perfil_genetico', '=', 'perfiles_geneticos.id')
                    ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
                    ->where('perfiles_geneticos.id_estado', '=' , $usuario->estado->id)
                    ->where('perfiles_geneticos.desestimado', 0)
                    ->where('perfiles_geneticos.es_perfil_repetido', 0)
                    ->where('perfiles_geneticos.numero_de_marcadores', '>=', $request->marcadores_minimos)
                    ->get();
            }
            else{// si se descartan los perfiles en revision
                $perfiles_objetivo = PerfilGenetico::with(array('alelos' => function($query) use (&$marcadores_a_comparar){
                        $query
                        ->join($marcadores_a_comparar, 'marcadores_a_comparar.id_marcador', '=', 'alelos.id_marcador');
                    }))
                    ->select('perfiles_geneticos.*', 'perfiles_geneticos_temporales.id_perfil_genetico', 'users.name')
                    ->join($perfiles_geneticos_temporales, 'perfiles_geneticos_temporales.id_perfil_genetico', '=', 'perfiles_geneticos.id')
                    ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
                    ->where('perfiles_geneticos.id_estado', '=' , $usuario->estado->id)
                    ->where('perfiles_geneticos.desestimado', 0)
                    ->where('perfiles_geneticos.es_perfil_repetido', 0)
                    ->where('perfiles_geneticos.requiere_revision', 0)
                    ->where('perfiles_geneticos.numero_de_marcadores', '>=', $request->marcadores_minimos)
                    ->get();

                $perfiles_subordinados = PerfilGenetico::with(array('alelos' => function($query) use (&$marcadores_a_comparar){
                        $query
                        ->join($marcadores_a_comparar, 'marcadores_a_comparar.id_marcador', '=', 'alelos.id_marcador');
                    }))
                    ->select('perfiles_geneticos.*', 'perfiles_geneticos_temporales.id_perfil_genetico', 'users.name')
                    ->join($perfiles_geneticos_temporales2, 'perfiles_geneticos_temporales.id_perfil_genetico', '=', 'perfiles_geneticos.id')
                    ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
                    ->where('perfiles_geneticos.id_estado', '=' , $usuario->estado->id)
                    ->where('perfiles_geneticos.desestimado', 0)
                    ->where('perfiles_geneticos.es_perfil_repetido', 0)
                    ->where('perfiles_geneticos.requiere_revision', 0)
                    ->where('perfiles_geneticos.numero_de_marcadores', '>=', $request->marcadores_minimos)
                    ->get();
            }
        }      

        $consecutivo = Busqueda::where('id_estado' , $usuario->estado->id)->count() + 1;
        $busqueda = Busqueda::create([
            'identificador' => 'B-'. $usuario->estado->id . '-' . $consecutivo,
            'motivo' => $request->motivo,
            'descripcion' => $request->descripcion,
            'marcadores_minimos' => $request->marcadores_minimos,
            'numero_de_exclusiones' => $request->exclusiones,
            'id_fuente' => $request->id_fuente,
            'id_usuario' => $usuario->id,
            'id_tipo_busqueda' => 2,
            'id_estado' => $usuario->estado->id,
            'id_tabla_de_frecuencias' => $request->id_tabla_de_frecuencias,
            'id_estatus_busqueda' => 1,
            'perfiles_en_revision_descartados' => $request->descartar_perfiles_en_revision,
            'etiquetas_objetivo' => "". implode(',', $request->etiquetasObjetivo),
            'etiquetas_usadas' => "". implode(',', $request->etiquetasSubordinadas)
        ]);

        $frecuencias = Frecuencia::where('id_importacion', $request->id_tabla_de_frecuencias)->get();

        // Con las subconsultas se comparan solo aquellos marcadores en comun entre los perfil objetivos y los subordinados


        foreach ($perfiles_objetivo as $perfil_objetivo) {
            foreach ($perfiles_subordinados as $perfil_subordinado){
                if($perfil_objetivo->id <> $perfil_subordinado->id){
                    obtenerIP($perfil_objetivo, $perfil_subordinado, $request , $busqueda, $frecuencias);
                }
            } 
        }
        
        $log = Log::create([
            'id_usuario' => $usuario->id,
            'id_estado' => $usuario->estado->id,
            'actividad' => 'Realizo una busqueda grupal: ' . $busqueda->identificador,
        ]);

        return redirect()->route('busquedas.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   

        $busqueda = Busqueda::with(array('resultados' => function ($query){
              $query->with(array('perfil_objetivo' => function ($query){
                $query->with(array('alelos' => function ($query){
                    $query->with('marcador');
                }))->with(array('metadatos' => function($query){
                    $query->with('tipo_de_metadato');
                }))->with(array('etiquetas' => function($query){
                    $query->with('etiqueta');
                }));
              }))->with(array('perfil_subordinado' => function ($query){
                $query->with(array('alelos' => function ($query){
                    $query->with('marcador');
                }))->with(array('metadatos' => function($query){
                    $query->with('tipo_de_metadato');
                }))->with(array('etiquetas' => function($query){
                    $query->with('etiqueta');
                }));
              }));            
            }))->find($id); 

        $marcadores = Marcador::get();
        $estatus_disponibles = EstatusBusqueda::where('id', '<>', 1 )->get();

        return view('busquedas.show',[
            'busqueda' => $busqueda,
            'marcadores' => $marcadores,
            'estatus_disponibles' => $estatus_disponibles,
        ]);
    }

    public function concluir(Request $request,$id)
    {   
        $busqueda = Busqueda::find($id);
        $busqueda->conclusiones = $request->conclusiones;
        $busqueda->id_estatus_busqueda = $request->id_estatus_busqueda;
        $busqueda->save();

        $usuario = User::find(Auth::id());
        $log = Log::create([
            'id_usuario' => $usuario->id,
            'id_estado' => $usuario->estado->id,
            'actividad' => 'Agrego conclusiones a: ' . $busqueda->identificador,
        ]);

        return redirect()->route('busquedas.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function ventana(){
        $usuario = User::find(Auth::id());

        if($usuario->estado->nombre == "CNB"){
            $perfiles_geneticos = PerfilGenetico::with(array('fuente' => function($query){
              $query->select('id','nombre');}))
            ->select('perfiles_geneticos.identificador', 'perfiles_geneticos.id_externo', 'perfiles_geneticos.created_at', 'perfiles_geneticos.id_fuente' )
            ->where('requiere_revision', '=', 0)
            ->get();
        }
        else{
            $perfiles_geneticos = PerfilGenetico::with(array('fuente' => function($query){
              $query->select('id','nombre');}))
            ->select('perfiles_geneticos.identificador', 'perfiles_geneticos.id_externo', 'perfiles_geneticos.created_at', 'perfiles_geneticos.id_fuente' )
            ->where('id_estado', '=', $usuario->id_estado)
            ->where('requiere_revision', '=', 0)
            ->get();   
        }      
        return view('busquedas.ventana',[
            'perfiles_geneticos' =>$perfiles_geneticos->toJson(),
        ]);
    }

    public function mensaje(Request $request, $id){
        
        $resultado = BusquedaResultado::find($id);
        $usuario = User::find(Auth::id());
        // dd($resultado->perfil_subordinado->estado->id);

        $mensaje = Mensaje::create([
            'id_perfil_objetivo' => $resultado->id_perfil_objetivo,
            'id_perfil_subordinado' => $resultado->id_perfil_subordinado,
            'id_usuario_envia' => $usuario->id,
            'id_estado_recibe' => $resultado->perfil_subordinado->estado->id,
            'id_busqueda_resultado' => $resultado->id_busqueda,
            'mensaje' => $request->mensaje,
            'revisado' => 0
        ]);

        flash('Se envio el mensaje exitosamente', 'success');
        return redirect()->route('busquedas.show', $resultado->id_busqueda); 
    }

    public function busquedas_exportar(Request $request, $busqueda){

        \Excel::create('Exportacion_de_resultados', function($excel) use (&$busqueda){
            
            $excel->getDefaultStyle()
            ->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $busqueda = Busqueda::find($busqueda);

            $usuario = User::find(Auth::id());
            $log = Log::create([
                'id_usuario' => $usuario->id,
                'id_estado' => $usuario->estado->id,
                'actividad' => 'Exporto los resultados de la busqueda: ' . $busqueda->identificador,
            ]);

            // $perfiles_geneticos = EtiquetaAsignada::with(array('perfil_genetico' => function ($query){
            //     $query->with('alelos')->with('metadatos');
            // }))->whereIn('id_perfil_genetico', explode(',',$perfiles_geneticos))->get();

            if($busqueda->resultados->count() == 0){
                dd('detente');
            }
            else{
                $excel->sheet('Compatibles', function($sheet) use($busqueda) {
                

                    
                    $sheet->cells('A1:B1', function($cells){
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->mergeCells('A1:B1');
                    $sheet->row(1, function($row) { $row->setBackground('#CCCCCC'); });                    
                    $sheet->row(1, ['Parametros de busqueda']);

                    $sheet->cell('A1', function($cell) {
                        $cell->setFontWeight('bold');
                        $cell->setBackground('#99b3ff');
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });

                    $sheet->getStyle('A1')->getAlignment()->applyFromArray(
                        array('horizontal' => 'center') //left,right,center & vertical
                    );

                    for( $i = 2; $i<=8 ; $i++){
                        $celda = "A" . $i;
                        $sheet->cell($celda, function($cell){
                            $cell->setFontWeight('bold');
                            $cell->setBorder('thin', 'thin', 'thin', 'thin'); 
                        });
                        
                        $celda = "B" . $i;
                        $sheet->cell($celda, function($cell){
                            $cell->setBorder('thin', 'thin', 'thin', 'thin'); 
                        });    
                    }
                    
                    $sheet->row(2, ['Motivo de busqueda', $busqueda->motivo]);
                    $sheet->row(3, ['Identificador y tipo de Busqueda', $busqueda->identificador . ': ' . $busqueda->tipo_de_busqueda->nombre]);
                    $sheet->row(4, ['Marcadores minimos', $busqueda->marcadores_minimos]);
                    $sheet->row(5, ['Exclusiones maximas', $busqueda->numero_de_exclusiones]);
                    $sheet->row(6, ['Tabla de frecuencias', $busqueda->tabla_de_frecuencias->nombre_otorgado]);

                    if($busqueda->etiquetas_objetivo <> null){
                        $etiquetas_objetivo = explode(',' , $busqueda->etiquetas_objetivo);
                        $etiquetas_objetivo = Etiqueta::whereIn('id', $etiquetas_objetivo )->get();    
                        $arreglo_nombres_etiquetas_objetivo = [];

                        foreach ($etiquetas_objetivo as $etiqueta) {
                            array_push($arreglo_nombres_etiquetas_objetivo, $etiqueta->nombre . ' ');
                        }

                        $etiquetas_objetivo = implode(',' , $arreglo_nombres_etiquetas_objetivo);
                    }
                    

                    $etiquetas_usadas = explode(',' , $busqueda->etiquetas_usadas);
                    $etiquetas_usadas = Etiqueta::whereIn('id', $etiquetas_usadas )->get();

                    $arreglo_nombres_etiquetas_usadas = [];

                    foreach ($etiquetas_usadas as $etiqueta) {
                        array_push($arreglo_nombres_etiquetas_usadas, $etiqueta->nombre . ' ');
                    }

                    $etiquetas_usadas = implode(',' , $arreglo_nombres_etiquetas_usadas);


                    if ($busqueda->tipo_de_busqueda->id == 1) {
                        $sheet->row(7, ['Etiquetas a comparar', $etiquetas_usadas]);
                    }
                    else{
                        $sheet->row(7, ['Etiquetas objetivo', $etiquetas_objetivo]);
                        $sheet->row(8, ['Etiquetas a comparar', $etiquetas_usadas]);
                    }                    

                    $sheet->cell('A10', function($cell) {
                        $cell->setFontWeight('bold');
                        $cell->setBackground('#5DD10C');
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });

                    $sheet->mergeCells('A10:H10');                
                    $sheet->setCellValue('A10', 'METADATOS');

                    $sheet->cell('I10', function($cell) {
                        $cell->setFontWeight('bold');
                        $cell->setBackground('#F7DC6F');
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });

                    $sheet->mergeCells('I10:BV10');
                    $sheet->setCellValue('I10', 'PERFIL GENETICO');
           

                    $sheet->getStyle('A10')->getAlignment()->applyFromArray(
                        array('horizontal' => 'center') //left,right,center & vertical
                    );

                    $sheet->getStyle('I10')->getAlignment()->applyFromArray(
                        array('horizontal' => 'center') //left,right,center & vertical
                    );


                    // Se definen las columnas de los metadatos de cada perfil y la lista de marcadores
                    $arreglo_de_columnas = [];
                    array_push($arreglo_de_columnas, 'Identificador');
                    array_push($arreglo_de_columnas, 'Id_externo');
                    array_push($arreglo_de_columnas, 'Nombre de la muestra o Nombre del familiar');
                    array_push($arreglo_de_columnas, 'Nombre del desaparecido');
                    array_push($arreglo_de_columnas, 'Parentesco');
                    array_push($arreglo_de_columnas, 'Fuente');
                    array_push($arreglo_de_columnas, 'Estado');
                    array_push($arreglo_de_columnas, 'IP o numero de exclusiones');
                    $marcadores = Marcador::get();
                    foreach ($marcadores as $marcador) {
                        array_push($arreglo_de_columnas, $marcador->nombre);
                    }

                    $id_anterior = 0;
                    $ultima_fila_usada = 11;

                    // Se pintan las columnas del perfil genetico
                    $sheet->row($ultima_fila_usada, $arreglo_de_columnas);
                    $sheet->row($ultima_fila_usada, function($row) { $row->setBackground('#85C1E9'); $row->setFontWeight('bold'); });

                    for($i = 0; $i <= count($arreglo_de_columnas)-1; $i++){
                        $celda_nombre = $sheet->getCellByColumnAndRow($i, $ultima_fila_usada)->getColumn();
                        $sheet->cell($celda_nombre . $ultima_fila_usada, function($cell) {
                            $cell->setBorder('thin', 'thin', 'thin', 'thin');
                        });
                    }

                    $ultima_fila_usada++;

                    foreach ($busqueda->resultados as $resultado) {
                        
                        if($resultado->id_perfil_objetivo <> $id_anterior){
                            

                            $arreglo_de_datos_del_perfil_objetivo = [];
                            array_push($arreglo_de_datos_del_perfil_objetivo, $resultado->perfil_objetivo->identificador);
                            array_push($arreglo_de_datos_del_perfil_objetivo, $resultado->perfil_objetivo->id_externo);
                            $nombre_del_donante = $resultado->perfil_objetivo->metadatos->firstWhere('id_tipo_de_metadato', 5);
                            array_push($arreglo_de_datos_del_perfil_objetivo, $nombre_del_donante );
                            $nombre_del_desaparecido = $resultado->perfil_objetivo->metadatos->firstWhere('id_tipo_de_metadato', 7);
                            if(!empty($nombre_del_desaparecido)){
                                array_push($arreglo_de_datos_del_perfil_objetivo, $nombre_del_desaparecido->dato);
                            }
                            else{
                                array_push($arreglo_de_datos_del_perfil_objetivo, 'SIN DATO');   
                            }                        
                            $parentesco = $resultado->perfil_objetivo->metadatos->firstWhere('id_tipo_de_metadato', 9);
                            if(!empty($parentesco)){
                                array_push($arreglo_de_datos_del_perfil_objetivo, $parentesco->dato);    
                            }
                            else{
                                array_push($arreglo_de_datos_del_perfil_objetivo, 'SIN DATO');   
                            }
                            array_push($arreglo_de_datos_del_perfil_objetivo, $resultado->perfil_objetivo->fuente->nombre);
                            array_push($arreglo_de_datos_del_perfil_objetivo, $resultado->perfil_objetivo->estado->nombre);
                            array_push($arreglo_de_datos_del_perfil_objetivo, '');
                            // if(!$resultado->IP == 0){
                            //     array_push($arreglo_de_datos_del_perfil_objetivo, '');
                            // }
                            // else{
                            //     array_push($arreglo_de_datos_del_perfil_objetivo, $resultado->exclusiones);   
                            // }
                            foreach ($marcadores as $marcador) {
                                $marcador_perfil_objetivo = $resultado->perfil_objetivo->alelos->firstWhere('id_marcador', $marcador->id);
                                if(!empty($marcador_perfil_objetivo)){
                                    if($marcador_perfil_objetivo->alelo_2 <> null){
                                        array_push($arreglo_de_datos_del_perfil_objetivo, $marcador_perfil_objetivo->alelo_1 . ',' . $marcador_perfil_objetivo->alelo_2);           
                                    }
                                    else{
                                        array_push($arreglo_de_datos_del_perfil_objetivo, $marcador_perfil_objetivo->alelo_1);   
                                    }
                                }
                                else{
                                    array_push($arreglo_de_datos_del_perfil_objetivo, '');   
                                }
                            }

                            $sheet->row($ultima_fila_usada, function($row) { $row->setBackground('#CCCCCC'); });
                            $sheet->row($ultima_fila_usada, $arreglo_de_datos_del_perfil_objetivo);

                            $ultima_fila_usada++;
                        }

                        $arreglo_de_datos_del_perfil_subordinado = [];
                        
                        array_push($arreglo_de_datos_del_perfil_subordinado, $resultado->perfil_subordinado->identificador);
                        array_push($arreglo_de_datos_del_perfil_subordinado, $resultado->perfil_subordinado->id_externo);
                        $nombre_del_donante = $resultado->perfil_objetivo->metadatos->firstWhere('id_tipo_de_metadato', 5);
                        array_push($arreglo_de_datos_del_perfil_objetivo, $nombre_del_donante );
                        $nombre_del_donante = $resultado->perfil_subordinado->metadatos->firstWhere('id_tipo_de_metadato', 5);
                        if(!empty($nombre_del_donante)){
                            array_push($arreglo_de_datos_del_perfil_subordinado, $nombre_del_donante->dato);
                        }
                        else{
                            array_push($arreglo_de_datos_del_perfil_subordinado, 'SIN DATO');   
                        }                        
                        $parentesco = $resultado->perfil_subordinado->metadatos->firstWhere('id_tipo_de_metadato', 9);
                        if(!empty($parentesco)){
                            array_push($arreglo_de_datos_del_perfil_subordinado, $parentesco->dato);    
                        }
                        else{
                            array_push($arreglo_de_datos_del_perfil_subordinado, 'SIN DATO');   
                        }
                        array_push($arreglo_de_datos_del_perfil_subordinado, $resultado->perfil_subordinado->fuente->nombre);
                        array_push($arreglo_de_datos_del_perfil_subordinado, $resultado->perfil_subordinado->estado->nombre);
                        if(!$resultado->IP == 0){
                            array_push($arreglo_de_datos_del_perfil_subordinado, sprintf("'%e'\n", $resultado->IP)); // notación científica sprintf("%E", $resultado->IP));
                        }
                        else{
                            array_push($arreglo_de_datos_del_perfil_subordinado, $resultado->exclusiones);   
                        }

                        $arreglo_de_exclusiones = [];

                        foreach ($marcadores as $marcador) {
                            
                            $marcador_perfil_objetivo = $resultado->perfil_objetivo->alelos->firstWhere('id_marcador', $marcador->id);
                            $marcador_perfil_subordinado = $resultado->perfil_subordinado->alelos->firstWhere('id_marcador', $marcador->id);

                            // a <> b && c <> d && a <> c && a <> d && b <> c && b <> d
                            // if(!empty($marcador_perfil_objetivo) && !empty($marcador_perfil_subordinado)){
                            //     if($marcador_perfil_objetivo->alelo_1 <> $marcador_perfil_objetivo->alelo_2 &&
                            //        $marcador_perfil_subordinado->alelo_1 <> $marcador_perfil_subordinado->alelo_2 &&
                            //        $marcador_perfil_objetivo->alelo_1 <> $marcador_perfil_subordinado->alelo_1 &&
                            //        $marcador_perfil_objetivo->alelo_1 <> $marcador_perfil_subordinado->alelo_2 &&
                            //        $marcador_perfil_objetivo->alelo_2 <> $marcador_perfil_subordinado->alelo_1 &&
                            //        $marcador_perfil_objetivo->alelo_2 <> $marcador_perfil_subordinado->alelo_2

                            //     ){
                                   
                            //     }
                            // }

                            if(!empty($marcador_perfil_subordinado)){
                                array_push($arreglo_de_datos_del_perfil_subordinado, $marcador_perfil_subordinado->alelo_1 . ',' . $marcador_perfil_subordinado->alelo_2);       
                            }
                            else{
                                array_push($arreglo_de_datos_del_perfil_subordinado, '');   
                            }
                        }

                        $sheet->row($ultima_fila_usada, $arreglo_de_datos_del_perfil_subordinado);
                        $id_anterior = $resultado->id_perfil_objetivo;
                        $ultima_fila_usada ++;
                    } 

                    $sheet->mergeCells('A'. ($ultima_fila_usada + 1) . ':B' . ($ultima_fila_usada + 1) ); 
                    $sheet->cell('A' . ($ultima_fila_usada + 1), function($cell) {
                        $cell->setFontWeight('bold');
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });               
                    $sheet->setCellValue('A' . ($ultima_fila_usada + 1) , 'Codigos de color');
                    $sheet->getStyle('A' . ($ultima_fila_usada + 1))->getAlignment()->applyFromArray(
                        array('horizontal' => 'center') //left,right,center & vertical
                    );


                    $sheet->cell('A'. ($ultima_fila_usada + 2), function($cell) {
                        $cell->setBackground('#CCCCCC');
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('A'. ($ultima_fila_usada + 3), function($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('B'. ($ultima_fila_usada + 2), function($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $sheet->cell('B'. ($ultima_fila_usada + 3), function($cell) {
                        $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    
                    $sheet->setCellValue('B' . ($ultima_fila_usada + 2) , 'Datos y perfil del Genotipo objetivo');
                    $sheet->setCellValue('B' . ($ultima_fila_usada + 3) , 'Datos y perfil del Genotipo compatible');

                    // $sheet->cell('I10', function($cell) {
                    //     $cell->setFontWeight('bold');
                    //     $cell->setBackground('#F7DC6F');
                    //     $cell->setBorder('thin', 'thin', 'thin', 'thin');
                    // });

                });
            }
         
            
         
        })->export('xlsx');
    }
}




function ObtenerIP ( $p_objetivo, $p_subordinado, $request_busqueda , $busqueda, $frecuencias ){
    $IP = 1;
    $exclusiones = 0;
    $marcadores_usados = 0;
    $request = $request_busqueda;

    foreach($p_objetivo->alelos as $m_p1) {
        $m_p2 = $p_subordinado->alelos->firstWhere('id_marcador', $m_p1->id_marcador);
        if($exclusiones > $request->exclusiones){
            break;
        }
        else{
            if($m_p2 <> null){
                $f1 = $frecuencias->where('id_marcador', $m_p1->id_marcador)->firstWhere('alelo', $m_p1->alelo_1);
                $f2 = $frecuencias->where('id_marcador', $m_p1->id_marcador)->firstWhere('alelo', $m_p1->alelo_2);
                if($m_p1->marcador->tipo_de_marcador->nombre == 'Autosomico'){
                    // if(!empty($f1) && !empty($f2)){

                    // Exclusion    a <> b && c <> d && a <> c && a <> d && b <> c && b <> d

                        //$m_p1->alelo_1 <> $m_p1->alelo_2 && $m_p2->alelo_1 <> $m_p2->alelo_2 && $m_p1->alelo_1 <> $m_p2->alelo_1
                        // && $m_p1->alelo_1 <> $m_p2->alelo_2 && $m_p1->alelo_2 <> $m_p2->alelo_1 && $m_p1->alelo_2 <> $m_p2->alelo_2
                    

                        if ( $m_p1->alelo_1 <> $m_p2->alelo_1 && $m_p1->alelo_1 <> $m_p2->alelo_2 && $m_p1->alelo_2 <> $m_p2->alelo_1 && $m_p1->alelo_2 <> $m_p2->alelo_2    
                        ){	
                            $IP = $IP * 0;
                            $exclusiones++;
                            $marcadores_usados++;
                        }

                        //  AA AA      a = b && c = d && a = c
                        elseif ($m_p1->alelo_1 == $m_p1->alelo_2 && $m_p2->alelo_1 == $m_p2->alelo_2 && $m_p1->alelo_1 == $m_p2->alelo_1 && !empty($f1)){  
                            $IP = $IP * (1/$f1->frecuencia);
                            $marcadores_usados++;
                        }


                        //  AA AB      a = b  && c <> d &&  ( a = c || a = d )
                        elseif ($m_p1->alelo_1 == $m_p1->alelo_2 && $m_p2->alelo_1 <> $m_p2->alelo_2 && 
                               ( $m_p1->alelo_1 == $m_p2->alelo_1 || $m_p1->alelo_1 == $m_p2->alelo_2) && !empty($f1)) {
                            $IP = $IP * (1/(2*$f1->frecuencia));
                            $marcadores_usados++;
                        }


                        //  AB AA      a <> b && c = d && ( a = c || b = c )  // si b = c || b = d  res f1 = f2  
                        elseif ($m_p1->alelo_1 <> $m_p1->alelo_2 && $m_p2->alelo_1 == $m_p2->alelo_2 && 
                               ($m_p1->alelo_1 == $m_p2->alelo_1 || $m_p1->alelo_2 == $m_p2->alelo_1)&& !empty($f1) && !empty($f2)){
                               
                               // if( $m_p1->alelo_2 == $m_p2->alelo_1 || $m_p1->alelo_2 == $m_p2->alelo_2 ){
                               //      $f1->frecuencia = $f2->frecuencia;
                               // }

                               if( $m_p1->alelo_2 == $m_p2->alelo_1 || $m_p1->alelo_2 == $m_p2->alelo_2 ){
                                    $f1->frecuencia = $f2->frecuencia;
                               }

                               $IP = $IP * (1/(2*$f1->frecuencia));
                               $marcadores_usados++;
                        }


                        //  AB AB      a <> b && c <> d && ( a = c || a = d ) && ( b = c || b = d )   
                        elseif ($m_p1->alelo_1 <> $m_p1->alelo_2 && $m_p2->alelo_1 <> $m_p2->alelo_2 &&
                               ($m_p1->alelo_1 == $m_p2->alelo_1 || $m_p1->alelo_1 == $m_p2->alelo_2) &&
                               ($m_p1->alelo_2 == $m_p2->alelo_1 || $m_p1->alelo_2 == $m_p2->alelo_2)&& !empty($f1) && !empty($f2)){

                               $IP = $IP * (($f1->frecuencia + $f2->frecuencia)/(4 * $f1->frecuencia * $f2->frecuencia ));
                               $marcadores_usados++;
                        }

                        
                        //  AB AC      a <> b && c <> d && (( a = c && b <> c && b <> d && a <> d) || 
                        //             ( a = d && a <> c && b <> c && b <> d)) || (a <> d && b = c && a <> c && b <> d) ||
                        //             ( a <> c && a <> d && b <> c && b = d ))

                        else{//  a <> b && c <> d &&
                           if($m_p1->alelo_1 <> $m_p1->alelo_2 && $m_p2->alelo_1 <> $m_p2->alelo_2 &&
                             // ( a = c && b <> c && b <> d && a <> d)
                             (($m_p1->alelo_1 == $m_p2->alelo_1 && $m_p1->alelo_2 <> $m_p2->alelo_1 && 
                             $m_p1->alelo_2 <> $m_p2->alelo_2 && $m_p1->alelo_1 <> $m_p2->alelo_2) ||
                             // ( a = d && a <> c && b <> c && b <> d)
                             ($m_p1->alelo_1 == $m_p2->alelo_2 && $m_p1->alelo_1 <> $m_p2->alelo_1 &&
                             $m_p1->alelo_2 <> $m_p2->alelo_1 && $m_p1->alelo_2 <> $m_p2->alelo_2) ||
                             // (a <> d && b = c && a <> c && b <> d)
                             ($m_p1->alelo_1 <> $m_p2->alelo_2 && $m_p1->alelo_2 == $m_p2->alelo_1 &&
                                $m_p1->alelo_1 <> $m_p2->alelo_1 && $m_p1->alelo_2 <> $m_p2->alelo_2 ) ||
                             // ( a <> c && a <> d && b <> c && b = d )
                             ($m_p1->alelo_1 <> $m_p2->alelo_1 && $m_p1->alelo_1 <> $m_p2->alelo_2 &&
                              $m_p1->alelo_2 <> $m_p2->alelo_1 && $m_p1->alelo_2 == $m_p2->alelo_2)) && !empty($f1) && !empty($f2)) {
                                 if( $m_p1->alelo_2 == $m_p2->alelo_1 || $m_p1->alelo_2 == $m_p2->alelo_2){
                                    $f1->frecuencia = $f2->frecuencia;
                                 }

                                $IP = $IP * ( 1/ (4 * $f1->frecuencia));
                                $marcadores_usados++;
                           }   
                           
                           // a <> b && c <> d && (((a = c || a = d ) && (( a <> c && b <> d) ) || ( a <> d && b <> d))) || (( b = c || b = d) && ((a <> d && b <> c) || ( a <> d && b <> d))))

                                // a <> b && c <> d &&
                            // if ($m_p1->alelo_1 <> $m_p1->alelo_2 && $m_p2->alelo_1 <> $m_p2->alelo_2 &&

                            //     // (((a = c || a = d ) && (( a <> c && b <> d) ) || ( a <> d && b <> d))) ||
                            //     ((($m_p1->alelo_1 == $m_p2->alelo_1 || $m_p1->alelo_1 == $m_p2->alelo_2) &&
                            //      (($m_p1->alelo_1 <> $m_p2->alelo_1 && $m_p1->alelo_2 <> $m_p2->alelo_2 ) ||
                            //      ($m_p1->alelo_1 <> $m_p2->alelo_2 && $m_p1->alelo_2 <> $m_p2->alelo_2))) ||
                            //     // (( b = c || b = d) && ((a <> d && b <> c) ||
                            //      (($m_p1->alelo_2 == $m_p2->alelo_1 || $m_p1->alelo_2 == $m_p2->alelo_2) &&
                            //      (($m_p1->alelo_1 <> $m_p2->alelo_2 && $m_p1->alelo_2 <> $m_p2->alelo_1) ||
                            //     // ( a <> d && b <> d))))                                         
                            //      ($m_p1->alelo_1 <> $m_p2->alelo_2 && $m_p1->alelo_2 <> $m_p2->alelo_2))))   
                            // ){
                            //     if(($m_p1->alelo_2 == $m_p2->alelo_1 || $m_p1->alelo_2 == $m_p2->alelo_2)){
                            //         $f1->frecuencia = $f2->frecuencia;
                            //     }
                            //     $IP = $IP * ( 1/ (4 * $f1->frecuencia));
                            //     $marcadores_usados++;
                            // }
                        }
                    // } Deshabilitado temporalmente
                }  
            }
        }
    }
    if($exclusiones <= $request->exclusiones && $marcadores_usados >= $request->marcadores_minimos){
        // echo "IP " . $IP .'<br>';

        $amel = Alelo::where('id_perfil_genetico', $p_subordinado->id)
        ->where('id_marcador', 1)
        ->first();

        if(!empty($amel)){
            $amel = $amel->alelo_1 . ',' . $amel->alelo_2;
            $perfil_genetico_resultado = BusquedaResultado::create([
                'id_busqueda' => $busqueda->id,
                'id_perfil_objetivo' => $p_objetivo->id,
                'id_perfil_subordinado' => $p_subordinado->id,
                'amel' => $amel,
                'IP' => $IP,
                'PP' => ($IP / ($IP + 1) * 100),
                'marcadores_minimos' => $marcadores_usados,
                'exclusiones' => $exclusiones
            ]);
        }
        else{
            $perfil_genetico_resultado = BusquedaResultado::create([
                'id_busqueda' => $busqueda->id,
                'id_perfil_objetivo' => $p_objetivo->id,
                'id_perfil_subordinado' => $p_subordinado->id,
                'IP' => $IP,
                'PP' => ($IP / ($IP + 1) * 100),
                'marcadores_minimos' => $marcadores_usados,
                'exclusiones' => $exclusiones
            ]);   
        }    
        
    }
}