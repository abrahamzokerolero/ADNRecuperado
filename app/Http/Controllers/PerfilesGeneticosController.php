<?php

namespace App\Http\Controllers;

use App\PerfilGenetico;
use App\ImportacionPerfil;
use App\Marcador;
use App\Metadato;
use App\Fuente;
use App\Alelo;
use App\User;
use App\Categoria;
use App\TipoDeMetadato;
use App\EtiquetaAsignada;
use App\Etiqueta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\CollectionDataTable;
use Illuminate\Support\Facades\DB;


class PerfilesGeneticosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuario = User::find(Auth::id());
        $tipos_de_metadatos = TipoDeMetadato::get();
        $fuentes = Fuente::where('desestimado', '=', 0)->get();

        if($usuario->estado->nombre == "CNB"){
            $categorias = Categoria::with(array('etiquetas' => function($query){
              $query->with(array('perfiles_geneticos_asociados' => function($query){ 
                $query->join('perfiles_geneticos', 'etiquetas_asignadas.id_perfil_genetico', '=', 'perfiles_geneticos.id')
                ->where('perfiles_geneticos.desestimado', 0)
                ->where('perfiles_geneticos.es_perfil_repetido', 0);
              }))->where('desestimado', 0);
            }))->where('desestimado', '=', 0)->get();

            $perfiles_geneticos = DB::table('perfiles_geneticos')
            ->select('perfiles_geneticos.*','users.name') 
            ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
            ->where('perfiles_geneticos.desestimado', 0)
            ->where('perfiles_geneticos.es_perfil_repetido', 0)
            ->where('perfiles_geneticos.requiere_revision', 0)
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

            $perfiles_geneticos = DB::table('perfiles_geneticos')
            ->select('perfiles_geneticos.*','users.name') 
            ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
            ->where('perfiles_geneticos.desestimado', 0)
            ->where('perfiles_geneticos.es_perfil_repetido', 0)
            ->where('perfiles_geneticos.requiere_revision', 0)
            ->where('perfiles_geneticos.id_estado', $usuario->id_estado)
            ->get();  
        }        
        return view('perfiles_geneticos.index', [
            'perfiles_geneticos' => $perfiles_geneticos->toJson(),
            'usuario' => $usuario,
            'tipos_de_metadatos' => $tipos_de_metadatos,
            'categorias' => $categorias,
            'fuentes' => $fuentes
        ]);
    }

    public function filtro_por_metadato(Request $request){

      if($request->ajax()){
        
        $datos = $request->all();
        $id_tipo_de_metadato;
        $filtro_por_metadato;
        foreach ($datos as $key => $value) {
          if($key == 'id_tipo_de_metadato'){
            $id_tipo_de_metadato = $value;
          }
          if($key == 'filtro_por_metadato'){
            $filtro_por_metadato = $value;
          }
        }

        if($filtro_por_metadato <> null && $id_tipo_de_metadato <> null){
          $usuario = User::find(Auth::id());
          if($usuario->estado->nombre == 'CNB'){
            $perfiles_geneticos_filtrados = DB::table('perfiles_geneticos')
            ->select('perfiles_geneticos.*','users.name', 'metadatos.id_tipo_de_metadato', 'metadatos.dato') 
            ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
            ->join('metadatos', 'metadatos.id_perfil_genetico', '=', 'perfiles_geneticos.id')
            ->where('perfiles_geneticos.desestimado', 0)
            ->where('perfiles_geneticos.es_perfil_repetido', 0)
            ->where('perfiles_geneticos.requiere_revision', 0)
            ->where('metadatos.id_tipo_de_metadato', $id_tipo_de_metadato)
            ->where('metadatos.dato', 'like' ,$filtro_por_metadato.'%')
            ->get();
          }
          else{
            $perfiles_geneticos_filtrados = DB::table('perfiles_geneticos')
            ->select('perfiles_geneticos.*','users.name', 'metadatos.id_tipo_de_metadato', 'metadatos.dato') 
            ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
            ->join('metadatos', 'metadatos.id_perfil_genetico', '=', 'perfiles_geneticos.id')
            ->where('perfiles_geneticos.desestimado', 0)
            ->where('perfiles_geneticos.es_perfil_repetido', 0)
            ->where('perfiles_geneticos.requiere_revision', 0)
            ->where('perfiles_geneticos.id_estado', $usuario->estado->id)
            ->where('metadatos.id_tipo_de_metadato', $id_tipo_de_metadato)
            ->where('metadatos.dato', 'like' ,$filtro_por_metadato.'%')
            ->get();
          }
        }  
        return response()->json([
          'newData' => $perfiles_geneticos_filtrados,
        ]); 
      }
    }

    public function filtro_por_etiquetas(Request $request){

      if($request->ajax()){
        $usuario = User::find(Auth::id());
        // se buscan todos los perfiles geneticos con dichas etiquetas y se agrupan para evitar duplicados
        $perfiles_geneticos_temporales = DB::raw("(SELECT id_perfil_genetico From etiquetas_asignadas where id_etiqueta in (". implode(',',$request->etiquetas) .") group by id_perfil_genetico) as perfiles_geneticos_temporales");

        // se buscan los perfiles geneticos que cumplan las scondiciones

        if($usuario->estado->nombre == 'CNB'){
          $perfiles_geneticos = DB::table('perfiles_geneticos')
          ->select('perfiles_geneticos.*', 'perfiles_geneticos_temporales.id_perfil_genetico', 'users.name')
          ->join($perfiles_geneticos_temporales, 'perfiles_geneticos_temporales.id_perfil_genetico', '=', 'perfiles_geneticos.id')
          ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
          ->where('perfiles_geneticos.desestimado', 0)
          ->where('perfiles_geneticos.requiere_revision', 0)
          ->where('perfiles_geneticos.es_perfil_repetido', 0)
          ->get();  
        }
        else{
          $perfiles_geneticos = DB::table('perfiles_geneticos')
          ->select('perfiles_geneticos.*', 'perfiles_geneticos_temporales.id_perfil_genetico', 'users.name')
          ->join($perfiles_geneticos_temporales, 'perfiles_geneticos_temporales.id_perfil_genetico', '=', 'perfiles_geneticos.id')
          ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
          ->where('perfiles_geneticos.id_estado', '=', $usuario->estado->id)
          ->where('perfiles_geneticos.desestimado', 0)
          ->where('perfiles_geneticos.requiere_revision', 0)
          ->where('perfiles_geneticos.es_perfil_repetido', 0)
          ->get();
        } 
        return response()->json([
          'newData' => $perfiles_geneticos,
        ]); 
      }
    }

    public function filtro_por_fuentes(Request $request){
      if($request->ajax()){
        $usuario = User::find(Auth::id());
        if($usuario->estado->nombre == 'CNB'){
          $perfiles_geneticos = DB::table('perfiles_geneticos')
          ->select('perfiles_geneticos.*', 'users.name')
          ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
          ->where('perfiles_geneticos.desestimado', 0)
          ->where('perfiles_geneticos.es_perfil_repetido', 0)
          ->where('perfiles_geneticos.requiere_revision', 0)
          ->where('id_fuente', '=', $request->id_fuente)
          ->get();
        }
        else{
          $perfiles_geneticos = DB::table('perfiles_geneticos')
          ->select('perfiles_geneticos.*', 'users.name')
          ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
          ->where('perfiles_geneticos.id_estado', '=', $usuario->estado->id)
          ->where('perfiles_geneticos.desestimado', 0)
          ->where('perfiles_geneticos.es_perfil_repetido', 0)
          ->where('perfiles_geneticos.requiere_revision', 0)
          ->where('id_fuente', '=', $request->id_fuente)
          ->get();
        } 
        return response()->json([
          'newData' => $perfiles_geneticos,
        ]); 
      }
    }

    public function filtro_por_id_importacion(Request $request){
      if($request->ajax()){
        $usuario = User::find(Auth::id());
        $importaciones;
        if(isset($request->id_importacion_exacto)){
          if($usuario->estado->nombre == 'CNB'){
            $importaciones = ImportacionPerfil::with(array('perfiles_geneticos' => function($query){
            $query->select('perfiles_geneticos.*', 'users.name')
                  ->join('users', 'perfiles_geneticos.id_usuario' , '=', 'users.id')
                  ->where('perfiles_geneticos.desestimado', 0)
                  ->where('perfiles_geneticos.requiere_revision', 0)
                  ->where('perfiles_geneticos.es_perfil_repetido', 0);
            }))
            ->where('identificador', '=', $request->id_importacion) 
            ->where('desestimado', 0)->get();  
          }
          else{
            $importaciones = ImportacionPerfil::with(array('perfiles_geneticos' => function($query){
            $query->select('perfiles_geneticos.*', 'users.name')
                  ->join('users', 'perfiles_geneticos.id_usuario' , '=', 'users.id')
                  ->where('perfiles_geneticos.desestimado', 0)
                  ->where('perfiles_geneticos.requiere_revision', 0)
                  ->where('perfiles_geneticos.es_perfil_repetido', 0)
                  ->where('perfiles_geneticos.id_estado', '=', $usuario->estado->id);
            }))
            ->where('identificador', '=', $request->id_importacion) 
            ->where('desestimado', 0)->get();
          }
        }
        else{
          if($usuario->estado->nombre == 'CNB'){
            $importaciones = ImportacionPerfil::with(array('perfiles_geneticos' => function($query){
            $query->select('perfiles_geneticos.*', 'users.name')
                  ->join('users', 'perfiles_geneticos.id_usuario' , '=', 'users.id')
                  ->where('perfiles_geneticos.desestimado', 0)
                  ->where('perfiles_geneticos.requiere_revision', 0)
                  ->where('perfiles_geneticos.es_perfil_repetido', 0);
            }))
            ->where('identificador', 'like', $request->id_importacion . '%') 
            ->where('desestimado', 0)->get();  
          }
          else{
            $importaciones = ImportacionPerfil::with(array('perfiles_geneticos' => function($query){
            $query->select('perfiles_geneticos.*', 'users.name')
                  ->join('users', 'perfiles_geneticos.id_usuario' , '=', 'users.id')
                  ->where('perfiles_geneticos.desestimado', 0)
                  ->where('perfiles_geneticos.es_perfil_repetido', 0)
                  ->where('perfiles_geneticos.requiere_revision', 0)
                  ->where('perfiles_geneticos.id_estado', '=', $usuario->estado->id);
            }))
            ->where('identificador', 'like', $request->id_importacion . '%') 
            ->where('desestimado', 0)->get();
          }
        }

        $perfiles_geneticos = collect();

        foreach ($importaciones as $importacion) {
          foreach ($importacion->perfiles_geneticos as $perfil) {
            $perfiles_geneticos->push($perfil);
          }
        }

        return response()->json([
          'newData' => $perfiles_geneticos,
        ]); 
      }  
    }

    public function filtro_combinado(Request $request){
      if($request->ajax()){
        $datos = $request->all();
        $id_tipo_de_metadato;
        $filtro_por_metadato;
        foreach ($datos as $key => $value) {
          if($key == 'id_tipo_de_metadato'){
            $id_tipo_de_metadato = $value;
          }
          if($key == 'filtro_por_metadato'){
            $filtro_por_metadato = $value;
          }
        }
        $perfiles_geneticos_filtrados;
        $usuario = User::find(Auth::id());

        if(isset($request->id_importacion_exacto)){
          if($filtro_por_metadato <> null && $id_tipo_de_metadato <> null){
            if($usuario->estado->nombre == 'CNB'){
              $perfiles_geneticos_filtrados = DB::table('perfiles_geneticos')
              ->select('perfiles_geneticos.*','users.name', 'metadatos.id_tipo_de_metadato', 'metadatos.dato', 'importaciones_perfiles.identificador') 
              ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
              ->join('metadatos', 'metadatos.id_perfil_genetico', '=', 'perfiles_geneticos.id')
              ->join('importaciones_perfiles', 'importaciones_perfiles.id', '=', 'perfiles_geneticos.id_importacion')
              ->where('perfiles_geneticos.desestimado', 0)
              ->where('perfiles_geneticos.es_perfil_repetido', 0)
              ->where('perfiles_geneticos.requiere_revision', 0)
              ->where('importaciones_perfiles.identificador', '=', $request->id_importacion)
              ->where('metadatos.id_tipo_de_metadato', $id_tipo_de_metadato)
              ->where('metadatos.dato', 'like' ,$filtro_por_metadato . '%')
              ->get();
            }
            else{
              $perfiles_geneticos_filtrados = DB::table('perfiles_geneticos')
              ->select('perfiles_geneticos.*','users.name', 'metadatos.id_tipo_de_metadato', 'metadatos.dato' , 'importaciones_perfiles.identificador') 
              ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
              ->join('metadatos', 'metadatos.id_perfil_genetico', '=', 'perfiles_geneticos.id')
              ->join('importaciones_perfiles', 'importaciones_perfiles.id', '=', 'perfiles_geneticos.id_importacion')
              ->where('perfiles_geneticos.desestimado', 0)
              ->where('perfiles_geneticos.es_perfil_repetido', 0)
              ->where('perfiles_geneticos.requiere_revision', 0)
              ->where('perfiles_geneticos.id_estado', $usuario->estado->id)
              ->where('importaciones_perfiles.identificador', '=', $request->id_importacion)
              ->where('metadatos.id_tipo_de_metadato', $id_tipo_de_metadato)
              ->where('metadatos.dato', 'like' ,$filtro_por_metadato.'%')
              ->get();
            }
          }
        }
        else{
          if($filtro_por_metadato <> null && $id_tipo_de_metadato <> null){
            if($usuario->estado->nombre == 'CNB'){
              $perfiles_geneticos_filtrados = DB::table('perfiles_geneticos')
              ->select('perfiles_geneticos.*','users.name', 'metadatos.id_tipo_de_metadato', 'metadatos.dato', 'importaciones_perfiles.identificador') 
              ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
              ->join('metadatos', 'metadatos.id_perfil_genetico', '=', 'perfiles_geneticos.id')
              ->join('importaciones_perfiles', 'importaciones_perfiles.id', '=', 'perfiles_geneticos.id_importacion')
              ->where('perfiles_geneticos.desestimado', 0)
              ->where('perfiles_geneticos.es_perfil_repetido', 0)
              ->where('perfiles_geneticos.requiere_revision', 0)
              ->where('importaciones_perfiles.identificador', 'like', $request->id_importacion . '%')
              ->where('metadatos.id_tipo_de_metadato', $id_tipo_de_metadato)
              ->where('metadatos.dato', 'like' ,$filtro_por_metadato.'%')
              ->get();
            }
            else{
              $perfiles_geneticos_filtrados = DB::table('perfiles_geneticos')
              ->select('perfiles_geneticos.*','users.name', 'metadatos.id_tipo_de_metadato', 'metadatos.dato' , 'importaciones_perfiles.identificador') 
              ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
              ->join('metadatos', 'metadatos.id_perfil_genetico', '=', 'perfiles_geneticos.id')
              ->join('importaciones_perfiles', 'importaciones_perfiles.id', '=', 'perfiles_geneticos.id_importacion')
              ->where('perfiles_geneticos.desestimado', 0)
              ->where('perfiles_geneticos.es_perfil_repetido', 0)
              ->where('perfiles_geneticos.requiere_revision', 0)
              ->where('perfiles_geneticos.id_estado', $usuario->estado->id)
              ->where('importaciones_perfiles.identificador', 'like', $request->id_importacion . '%')
              ->where('metadatos.id_tipo_de_metadato', $id_tipo_de_metadato)
              ->where('metadatos.dato', 'like' ,$filtro_por_metadato.'%')
              ->get();
            }
          }  
        }
        return response()->json([
          'newData' => $perfiles_geneticos_filtrados,
        ]);   
      }  
    }

    public function filtro_combinado2(Request $request){
      if($request->ajax()){
        $datos = $request->all();
        $id_tipo_de_metadato;
        $filtro_por_metadato;
        foreach ($datos as $key => $value) {
          if($key == 'id_tipo_de_metadato'){
            $id_tipo_de_metadato = $value;
          }
          if($key == 'filtro_por_metadato'){
            $filtro_por_metadato = $value;
          }
        }

        if($filtro_por_metadato <> null && $id_tipo_de_metadato <> null){
          $usuario = User::find(Auth::id());
          if($usuario->estado->nombre == 'CNB'){
            $perfiles_geneticos_filtrados = DB::table('perfiles_geneticos')
            ->select('perfiles_geneticos.*','users.name', 'metadatos.id_tipo_de_metadato', 'metadatos.dato') 
            ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
            ->join('metadatos', 'metadatos.id_perfil_genetico', '=', 'perfiles_geneticos.id')
            ->where('perfiles_geneticos.desestimado', 0)
            ->where('perfiles_geneticos.es_perfil_repetido', 0)
            ->where('perfiles_geneticos.requiere_revision', 0)
            ->where('perfiles_geneticos.id_fuente', $request->id_fuente)
            ->where('metadatos.id_tipo_de_metadato', $id_tipo_de_metadato)
            ->where('metadatos.dato', 'like' ,$filtro_por_metadato.'%')
            ->get();
          }
          else{
            $perfiles_geneticos_filtrados = DB::table('perfiles_geneticos')
            ->select('perfiles_geneticos.*','users.name', 'metadatos.id_tipo_de_metadato', 'metadatos.dato') 
            ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
            ->join('metadatos', 'metadatos.id_perfil_genetico', '=', 'perfiles_geneticos.id')
            ->where('perfiles_geneticos.desestimado', 0)
            ->where('perfiles_geneticos.es_perfil_repetido', 0)
            ->where('perfiles_geneticos.requiere_revision', 0)
            ->where('perfiles_geneticos.id_fuente', $request->id_fuente)
            ->where('perfiles_geneticos.id_estado', $usuario->estado->id)
            ->where('metadatos.id_tipo_de_metadato', $id_tipo_de_metadato)
            ->where('metadatos.dato', 'like' ,$filtro_por_metadato.'%')
            ->get();
          }
        }
        return response()->json([
          'newData' => $perfiles_geneticos_filtrados,
        ]); 
      } 
    }

    public function filtro_combinado3(Request $request){
      if($request->ajax()){
        $usuario = User::find(Auth::id());
        // se buscan todos los perfiles geneticos con dichas etiquetas y se agrupan para evitar duplicados
        $perfiles_geneticos_temporales = DB::raw("(SELECT id_perfil_genetico From etiquetas_asignadas where id_etiqueta in (". implode(',',$request->etiquetas2) .") group by id_perfil_genetico) as perfiles_geneticos_temporales");

        // se buscan los perfiles geneticos que cumplan las scondiciones

        if($usuario->estado->nombre == 'CNB'){
          $perfiles_geneticos = DB::table('perfiles_geneticos')
          ->select('perfiles_geneticos.*', 'perfiles_geneticos_temporales.id_perfil_genetico', 'users.name')
          ->join($perfiles_geneticos_temporales, 'perfiles_geneticos_temporales.id_perfil_genetico', '=', 'perfiles_geneticos.id')
          ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
          ->where('perfiles_geneticos.desestimado', 0)
          ->where('perfiles_geneticos.requiere_revision', 0)
          ->where('perfiles_geneticos.es_perfil_repetido', 0)
          ->where('perfiles_geneticos.id_fuente', $request->id_fuente)
          ->get();  
        }
        else{
          $perfiles_geneticos = DB::table('perfiles_geneticos')
          ->select('perfiles_geneticos.*', 'perfiles_geneticos_temporales.id_perfil_genetico', 'users.name')
          ->join($perfiles_geneticos_temporales, 'perfiles_geneticos_temporales.id_perfil_genetico', '=', 'perfiles_geneticos.id')
          ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
          ->where('perfiles_geneticos.id_estado', '=', $usuario->estado->id)
          ->where('perfiles_geneticos.desestimado', 0)
          ->where('perfiles_geneticos.requiere_revision', 0)
          ->where('perfiles_geneticos.es_perfil_repetido', 0)
          ->where('perfiles_geneticos.id_fuente', $request->id_fuente)
          ->get();
        }
        return response()->json([
          'newData' => $perfiles_geneticos,
        ]);  
      }
    }

    public function restablecer(Request $request){
      if($request->ajax()){
        $usuario = User::find(Auth::id());
        if($usuario->estado->nombre == "CNB"){
          $perfiles_geneticos = DB::table('perfiles_geneticos')
          ->select('perfiles_geneticos.*','users.name') 
          ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
          ->where('perfiles_geneticos.desestimado', 0)
          ->where('perfiles_geneticos.es_perfil_repetido', 0)
          ->where('perfiles_geneticos.requiere_revision', 0)
          ->get();
        }
        else{

          $perfiles_geneticos = DB::table('perfiles_geneticos')
          ->select('perfiles_geneticos.*','users.name') 
          ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
          ->where('perfiles_geneticos.desestimado', 0)
          ->where('perfiles_geneticos.es_perfil_repetido', 0)
          ->where('perfiles_geneticos.requiere_revision', 0)
          ->where('perfiles_geneticos.id_estado', $usuario->id_estado)
          ->get();  
        }
        return response()->json([
          'newData' => $perfiles_geneticos,
        ]);  
      }    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $marcadores = Marcador::get();
        return view('perfiles_geneticos.create',[
            'marcadores' => $marcadores,
        ]);    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        function normaliza ($cadena){
            $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
            $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
            $cadena = utf8_decode($cadena);
            $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
            $cadena = strtolower($cadena);
            return utf8_encode($cadena);
        }

        $usuario = User::find(Auth::id());
        $consecutivo = PerfilGenetico::where('id_estado', '=', $usuario->estado->id)->count()+1;
        $perfil_genetico = PerfilGenetico::create([
            'identificador' => 'G-' . $usuario->id_estado . '-' . $consecutivo,
            'id_externo' => $request->clave_de_muestra,
            'id_usuario' => Auth::id(),
            'id_fuente' => $request->id_fuente,
            'id_estado' => $usuario->id_estado,
        ]);

        $contador = 0;
        $homocigotos = 0;
        $marcador_anterior = '';

        foreach ($request->all() as $key => $value) {
            if($value<>null){
                if(is_array($value)){
                    foreach ($value as $key => $value) {
                        $etiqueta = EtiquetaAsignada::create([
                            'id_etiqueta' => $value,
                            'id_perfil_genetico' => $perfil_genetico->id,

                        ]);
                    }
                }
                else{
                    if($key <> '_token'){ // descartamos el token de la consulta
                        if($key <> 'id_fuente'){    // descartamos la fuente
                          if($key <> 'clave_de_muestra'){
                            $primera_palabra = explode('_', $key);    // dividimos el $key en palabras separadas
                            $verifica_marcador = Marcador::where('nombre', '=', $primera_palabra[0])->first(); // buscamos si la palabra existe en marcadores

                            if(empty($verifica_marcador)){  // si la busqueda devuelve un arreglo vacio, es un metadato
                                $id_tipo_de_metadato = TipoDeMetadato::where('nombre','=', $key)->first();
                                if(empty($id_tipo_de_metadato)){    // si no existe el tipo de metadato lo creamos y guardamos
                                    $tipo_de_metadato = TipoDeMetadato::create([
                                        'nombre' => $key
                                    ]);

                                    $metadato = Metadato::create([
                                        'id_perfil_genetico' => $perfil_genetico->id,
                                        'id_tipo_de_metadato' => $tipo_de_metadato->id,
                                        'dato' => strtoupper(normaliza($value)),
                                    ]);                                    
                                }   
                                else{                               // si existe solo creamos el metadato asociado al perfil
                                    $metadato = Metadato::create([
                                        'id_perfil_genetico' => $perfil_genetico->id,
                                        'id_tipo_de_metadato' => $id_tipo_de_metadato->id,
                                        'dato' => strtoupper(normaliza($value)),
                                    ]);   
                                }
                            }
                            else{
                              if($verifica_marcador->nombre == $marcador_anterior){  // update del marcador en alelos

                                $alelo = Alelo::where('id_perfil_genetico', '=', $perfil_genetico->id)->where('id_marcador', '=', $verifica_marcador->id)->first();

                                $alelo->alelo_2 = $value;
                                $alelo->save();

                                if($alelo->alelo_1 == $alelo->alelo_2){
                                    $homocigotos++;
                                }

                                $marcador_anterior = $verifica_marcador;
                              }
                              else{ // create del marcador en alelos

                                $alelo = Alelo::create([
                                    'id_perfil_genetico' => $perfil_genetico->id,
                                    'id_marcador' => $verifica_marcador->id,
                                    'alelo_1' => $value,
                                ]);
                                $marcador_anterior = $verifica_marcador->nombre;
                                $contador ++;
                              }     
                            }
                          }
                        }
                    }
                }
            }
        }

        foreach($perfil_genetico->alelos as $alelo){
          
          if($alelo->alelo_2 == null &&  $alelo->marcador->id_tipo_de_marcador == 1 && $alelo->marcador->nombre <> 'yindel' || $alelo->alelo_2 == null &&  $alelo->marcador->nombre == 'dys385' ){
            $alelo->alelo_2 = $alelo->alelo_1;
            $alelo->save();
            $homocigotos++;
          }
        }

        if($homocigotos > 5){
            $perfil_genetico->requiere_revision = true;
            flash('El perfil ingresado presento mas de 5 homocigotos','warning');
        }

        if($contador < 12){
            $perfil_genetico->requiere_revision = true;
            flash('El perfil ingresado presento menos de 12 marcadores','warning');
        }

        $perfil_genetico->numero_de_homocigotos = $homocigotos;
        $perfil_genetico->numero_de_marcadores = $contador;
        $perfil_genetico->save();

        $verifica_perfil_repetido = false;

        $datos_perfil = array();

        foreach ($perfil_genetico->alelos->sortBy('id_marcador') as $perfil) {
          array_push( $datos_perfil, $perfil->id_marcador, $perfil->alelo_1, $perfil->alelo_2); 
        }

        $datos_perfil = implode($datos_perfil);
        $perfil_genetico->cadena_unica = $datos_perfil;
        $perfil_genetico->save();

        $genotipo_repetido = PerfilGenetico::where('desestimado', '=', 0)->where('cadena_unica', '=', $perfil_genetico->cadena_unica)->orWhere('es_perfil_repetido', '=' , 0)->where('cadena_unica', '=', $perfil_genetico->cadena_unica)->first(); 
        
        if($genotipo_repetido <> null){
          if($genotipo_repetido->id <> $perfil_genetico->id){
            $perfil_genetico->es_perfil_repetido = 1;
            $perfil_genetico->id_perfil_original = $genotipo_repetido->id;
            $perfil_genetico->id_estado_perfil_original = $genotipo_repetido->id_estado;
            $perfil_genetico->save();
            $usuario = User::find(Auth()->id());  
            if($usuario->estado->nombre == 'CNB'){
                flash("El perfil genetico fue agregado sin embargo existe otro con los mismos marcadores y mismos valores en sus alelos. El perfil fue enviado al apartado de perfiles duplicados: <a href=". route('perfiles_geneticos.validar_duplicado', $perfil_genetico->id) ."> <b>" . $perfil_genetico->identificador . '</b></a>'  , 'danger');
            }
            else{
              if($perfil_genetico->id_estado == $perfil_genetico->id_estado_perfil_original){
                flash("El perfil genetico fue agregado sin embargo existe otro con los mismos marcadores y mismos valores en sus alelos. El perfil fue enviado al apartado de perfiles duplicados: <a href=". route('perfiles_geneticos.validar_duplicado', $perfil_genetico->id) ."> <b>" . $perfil_genetico->identificador . '</b></a>'  , 'danger');
              }
              else{
                flash("El perfil genetico fue agregado sin embargo existe otro perfil en otro estado con los mismos marcadores y mismos valores en sus alelos. CNB se encargara de validar la informacion de ambos perfiles, de ser necesario se comunicara con ustedes para validar la informacion. No ingrese nuevamente el perfil genetico ya fue registrado en la base de datos de CNB"  , 'danger');
              }
            }  
          }
          else{
            flash('Operacion completada', 'success');
          }  
        }
        
        return redirect()->route('perfiles_geneticos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $perfil_genetico = PerfilGenetico::find($id);
        return view('perfiles_geneticos.show', [
            'perfil_genetico' => $perfil_genetico,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $fuentes = Fuente::get();
        $categorias = Categoria::with('etiquetas')->orderBy('nombre', 'ASC')->get();
        $marcadores = Marcador::get();
        $perfil_genetico = PerfilGenetico::find($id);
        // metadatos
        $fecha_de_hallazgo= null;
        $lugar= null;
        $paraje= null;
        $fosa= null;
        $nombre_del_donante= null;
        $nombre_del_desaparecido= null;
        $curp_del_desaparecido = null;
        $parentesco_con_el_desaparecido= null;
        $curp_del_familiar = null;
        $clave_de_muestra = $perfil_genetico->id_externo;
        $descripcion_de_la_muestra= null;
        $observaciones= null;
        $talla= null;
        $peso= null;
        $s_particulares_o_malformaciones= null;
        $tatuaje= null;
        $sexo= null;
        $ci_nuc_ap= null;
        $fecha_desaparicion= null;
        $lugar_de_desaparicion= null;
        $no_de_caso_relacionado= null;
        $curp = null;

        

        foreach($perfil_genetico->metadatos as $metadato){
          
          if($metadato->tipo_de_metadato->nombre == 'fecha_de_hallazgo'){$fecha_de_hallazgo = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'lugar'){$lugar = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'paraje'){$paraje = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'fosa'){$fosa = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'nombre_del_donante'){$nombre_del_donante = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'nombre_del_desaparecido'){$nombre_del_desaparecido = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'curp_del_desaparecido'){$curp_del_desaparecido = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'parentesco_con_el_desaparecido'){$parentesco_con_el_desaparecido = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'curp_del_familiar'){$curp_del_familiar = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'descripcion_de_la_muestra'){$descripcion_de_la_muestra = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'observaciones'){$observaciones = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'talla'){$talla = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'peso'){$peso = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 's_particulares_o_malformaciones'){$s_particulares_o_malformaciones = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'tatuaje'){$tatuaje = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'sexo'){$sexo = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'ci_nuc_ap'){$ci_nuc_ap = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'fecha_desaparicion'){$fecha_desaparicion = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'lugar_de_desaparicion'){$lugar_de_desaparicion = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'no_de_caso_relacionado'){$no_de_caso_relacionado = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'curp'){$curp = $metadato->dato;}
        }

        return view('perfiles_geneticos.edit', [
            'perfil_genetico' => $perfil_genetico,
            'marcadores' => $marcadores,
            'fuentes' => $fuentes,
            'categorias' => $categorias,
            //metadatos
            'fecha_de_hallazgo' => $fecha_de_hallazgo,
            'lugar' => $lugar,
            'paraje' => $paraje,
            'fosa'  => $fosa,
            'nombre_del_donante' => $nombre_del_donante,
            'nombre_del_desaparecido' => $nombre_del_desaparecido,
            'curp_del_desaparecido' => $curp_del_desaparecido,
            'parentesco_con_el_desaparecido' => $parentesco_con_el_desaparecido,
            'curp_del_familiar' => $curp_del_familiar,
            'clave_de_muestra' => $clave_de_muestra,
            'descripcion_de_la_muestra' => $descripcion_de_la_muestra,
            'observaciones' => $observaciones,
            'talla' => $talla,
            'peso' => $peso,
            's_particulares_o_malformaciones' => $s_particulares_o_malformaciones,
            'tatuaje' => $tatuaje,
            'sexo' => $sexo,
            'ci_nuc_ap' => $ci_nuc_ap,
            'fecha_desaparicion' => $fecha_desaparicion,
            'lugar_de_desaparicion' => $lugar_de_desaparicion,
            'no_de_caso_relacionado' => $no_de_caso_relacionado,
            'curp' => $curp,
        ]);
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
        function normaliza ($cadena){
            $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
            $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
            $cadena = utf8_decode($cadena);
            $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
            $cadena = strtolower($cadena);
            return utf8_encode($cadena);
        }

        $perfil_genetico = PerfilGenetico::find($id);

        $perfil_genetico->id_fuente = $request->id_fuente;
        $perfil_genetico->id_externo = $request->clave_de_muestra;
        foreach ($perfil_genetico->etiquetas as $etiqueta){
            $etiqueta->delete();
        }
        foreach ($perfil_genetico->alelos as $alelo) {
            $alelo->delete();          
        }
        foreach ($perfil_genetico->metadatos as $metadato) {
            $metadato->delete();          
        }

        $contador = 0;
        $homocigotos = 0;
        $marcador_anterior = '';

        foreach ($request->all() as $key => $value) {
            if($value<>null){
                if(is_array($value)){
                    foreach ($value as $key => $value) {
                        $etiqueta = EtiquetaAsignada::create([
                            'id_etiqueta' => $value,
                            'id_perfil_genetico' => $perfil_genetico->id,
                        ]);
                    }
                }
                else{
                    if($key <> '_token'){ // descartamos el token de la consulta
                        if($key <> 'id_fuente'){    // descartamos la fuente
                          if($key <> 'clave_de_muestra'){
                            if($key <> '_method'){
                              $primera_palabra = explode('_', $key);    // dividimos el $key en palabras separadas
                              $verifica_marcador = Marcador::where('nombre', '=', $primera_palabra[0])->first(); // buscamos si la palabra existe en marcadores

                              if(empty($verifica_marcador)){  // si la busqueda devuelve un arreglo vacio, es un metadato
                                  $id_tipo_de_metadato = TipoDeMetadato::where('nombre','=', $key)->first();
                                  if(empty($id_tipo_de_metadato)){    // si no existe el tipo de metadato lo creamos y guardamos
                                      $tipo_de_metadato = TipoDeMetadato::create([
                                          'nombre' => $key
                                      ]);

                                      $metadato = Metadato::create([
                                          'id_perfil_genetico' => $perfil_genetico->id,
                                          'id_tipo_de_metadato' => $tipo_de_metadato->id,
                                          'dato' => strtoupper(normaliza($value)),
                                      ]);                                    
                                  }   
                                  else{                               // si existe solo creamos el metadato asociado al perfil
                                      $metadato = Metadato::create([
                                          'id_perfil_genetico' => $perfil_genetico->id,
                                          'id_tipo_de_metadato' => $id_tipo_de_metadato->id,
                                          'dato' => strtoupper(normaliza($value)),
                                      ]);   
                                  }
                              }
                              else{
                                if($verifica_marcador->nombre == $marcador_anterior){  // update del marcador en alelos

                                  $alelo = Alelo::where('id_perfil_genetico', '=', $perfil_genetico->id)->where('id_marcador', '=', $verifica_marcador->id)->first();

                                  $alelo->alelo_2 = $value;
                                  $alelo->save();

                                  if($alelo->alelo_1 == $alelo->alelo_2){
                                      $homocigotos++;
                                  }

                                  $marcador_anterior = $verifica_marcador;
                                }
                                else{ // create del marcador en alelos

                                  $alelo = Alelo::create([
                                      'id_perfil_genetico' => $perfil_genetico->id,
                                      'id_marcador' => $verifica_marcador->id,
                                      'alelo_1' => $value,
                                  ]);
                                  $marcador_anterior = $verifica_marcador->nombre;
                                  $contador ++;
                                }     
                              }
                            }
                          }
                        }
                    }
                }
            }
        }

        $perfil_genetico = PerfilGenetico::find($id);

        foreach($perfil_genetico->alelos as $alelo){
          
          if($alelo->alelo_2 == null &&  $alelo->marcador->id_tipo_de_marcador == 1 && $alelo->marcador->nombre <> 'yindel' || $alelo->alelo_2 == null &&  $alelo->marcador->nombre == 'dys385' ){
            $alelo->alelo_2 = $alelo->alelo_1;
            $alelo->save();
            $homocigotos++;
          }
        }



        if($homocigotos > 5 || $contador < 12 ){
            $perfil_genetico->requiere_revision = 1;
            flash('El perfil ingresado presento mas de 5 homocigotos o menos de 12 marcadores','warning');
        }
        else{
            $perfil_genetico->requiere_revision = 0;
        }

        $perfil_genetico->numero_de_homocigotos = $homocigotos;
        $perfil_genetico->numero_de_marcadores = $contador;
        $perfil_genetico->save();

        $verifica_perfil_repetido = false;

        $datos_perfil = array();

        foreach ($perfil_genetico->alelos->sortBy('id_marcador') as $perfil) {
          array_push( $datos_perfil, $perfil->id_marcador, $perfil->alelo_1, $perfil->alelo_2); 
        }

        $datos_perfil = implode($datos_perfil);
        $perfil_genetico->cadena_unica = $datos_perfil;
        $perfil_genetico->save();

        $genotipo_repetido = PerfilGenetico::where('desestimado', '=', 0)->where('cadena_unica', '=', $perfil_genetico->cadena_unica)->orWhere('es_perfil_repetido', '=' , 0)->where('cadena_unica', '=', $perfil_genetico->cadena_unica)->first(); 
        
        if($genotipo_repetido <> null){
          if($genotipo_repetido->id <> $perfil_genetico->id){
            $perfil_genetico->es_perfil_repetido = 1;
            $perfil_genetico->id_perfil_original = $genotipo_repetido->id;
            $perfil_genetico->id_estado_perfil_original = $genotipo_repetido->id_estado;
            $perfil_genetico->save();
            $usuario = User::find(Auth()->id());  
            if($usuario->estado->nombre == 'CNB'){
                flash("El perfil genetico fue agregado sin embargo existe otro con los mismos marcadores y mismos valores en sus alelos. El perfil fue enviado al apartado de perfiles duplicados: <a href=". route('perfiles_geneticos.validar_duplicado', $perfil_genetico->id) ."> <b>" . $perfil_genetico->identificador . '</b></a>'  , 'danger');
            }
            else{
              if($perfil_genetico->id_estado == $perfil_genetico->id_estado_perfil_original){
                flash("El perfil genetico fue agregado sin embargo existe otro con los mismos marcadores y mismos valores en sus alelos. El perfil fue enviado al apartado de perfiles duplicados: <a href=". route('perfiles_geneticos.validar_duplicado', $perfil_genetico->id) ."> <b>" . $perfil_genetico->identificador . '</b></a>'  , 'danger');
              }
              else{
                flash("El perfil genetico fue agregado sin embargo existe otro perfil en otro estado con los mismos marcadores y mismos valores en sus alelos. CNB se encargara de validar la informacion de ambos perfiles, de ser necesario se comunicara con ustedes para validar la informacion. No ingrese nuevamente el perfil genetico ya fue registrado en la base de datos de CNB"  , 'danger');
              }
            }  
          }
          else{
            flash('Operacion completada', 'success');
          }  
        }
        
        return redirect()->route('perfiles_geneticos.index');
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

    public function revision(){
        $usuario = User::find(Auth::id());
        if($usuario->estado->nombre == "CNB"){
            $perfiles_geneticos = DB::table('perfiles_geneticos')
             ->select('perfiles_geneticos.*','users.name') 
            ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
            ->where('perfiles_geneticos.requiere_revision','=',1)
            ->where('perfiles_geneticos.es_perfil_repetido','=',0)
            ->where('perfiles_geneticos.desestimado', '=', 0)
            ->get();
        }
        else{
            $perfiles_geneticos = DB::table('perfiles_geneticos')
             ->select('perfiles_geneticos.*','users.name') 
            ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
            ->where('perfiles_geneticos.id_estado','=',$usuario->estado->id)
            ->where('perfiles_geneticos.requiere_revision','=',1)
            ->where('perfiles_geneticos.es_perfil_repetido','=',0)
            ->where('perfiles_geneticos.desestimado', '=', 0)
            ->get();  
        }  
        return view('perfiles_geneticos.revision', [
            'perfiles_geneticos' =>$perfiles_geneticos->toJson(),
        ]);
    }

    public function validar(Request $request, $id){
      $perfil_genetico = PerfilGenetico::find($id);
      $validacion = $request->validacion;
      if($validacion == 'aprobar'){
        $perfil_genetico->requiere_revision = 0;
        $perfil_genetico->id_usuario_reviso = Auth::id();
        flash('El perfil <b>' . $perfil_genetico->identificador . '</b> fue validado, ahora podra ser usado para las busquedas de las busquedas');
      }
      else{
        $perfil_genetico->desestimado = 1;
        $perfil_genetico->requiere_revision = 1;
        $perfil_genetico->id_usuario_reviso = Auth::id(); 
        flash('El perfil fue <b>' . $perfil_genetico->identificador . '</b> fue desestimado');
      }
      $perfil_genetico->save();
      
      return redirect()->route('perfiles_geneticos.revision');
    }

    public function comprobacion(Request $request){

      $fuentes = Fuente::get();
      $categorias = Categoria::with('etiquetas')->orderBy('nombre', 'ASC')->get();
      $marcadores = Marcador::get();
      return view('perfiles_geneticos.comprobacion', [
          'marcadores' => $marcadores,
          'requestAnterior' =>$request,
          'fuentes' => $fuentes,
          'categorias' => $categorias, 

      ]);
    }

    public function duplicados(){
        $usuario = User::find(Auth::id());
        if($usuario->estado->nombre == "CNB"){
            $perfiles_geneticos = PerfilGenetico::with(array('usuario' => function($query)
            {$query->select('users.id', 'users.name');}))
            ->with(array('estado' => function($query){$query->select('estados.id', 'estados.nombre');}))
            ->with(array('perfil_original' => function($query){$query->select('perfiles_geneticos.id', 'perfiles_geneticos.identificador');}))
            ->with(array('estado_perfil_original' => function($query){$query->select('estados.id', 'estados.nombre');}))
            ->where('es_perfil_repetido','=',1)
            ->where('desestimado', '=', 0)
            ->get();
        }
        else{
            
            $perfiles_geneticos = PerfilGenetico::with(array('usuario' => function($query)
            {$query->select('users.id', 'users.name');}))
            ->with(array('estado' => function($query){$query->select('estados.id', 'estados.nombre');}))
            ->with(array('perfil_original' => function($query){$query->select('perfiles_geneticos.id', 'perfiles_geneticos.identificador');}))
            ->with(array('estado_perfil_original' => function($query){$query->select('estados.id', 'estados.nombre');}))
            ->where('id_estado', '=', $usuario->id_estado)
            ->where('id_estado_perfil_original','=',$usuario->id_estado)
            ->where('es_perfil_repetido','=',1)
            ->where('desestimado', '=', 0)
            ->get();   
        }  
        return view('perfiles_geneticos.duplicados', [
            'perfiles_geneticos' =>$perfiles_geneticos,
        ]);
    }

    public function desestimados(){
        $usuario = User::find(Auth::id());
        if($usuario->estado->nombre == "CNB"){
            $perfiles_geneticos = PerfilGenetico::with(array('usuario_reviso' => function($query)
            {$query->select('users.id', 'users.name');}))
            ->where('desestimado', '=', 1)
            ->get();
        }
        else{
            $perfiles_geneticos = PerfilGenetico::where('id_estado', '=', $usuario->id_estado)->where('desestimado', '=', 1)->get();   
        }  

        return view('perfiles_geneticos.desestimados', [
            'perfiles_geneticos' =>$perfiles_geneticos,
        ]);
    }

    public function estadisticas($etiqueta){

        $usuario = User::find(Auth::id());
        $etiqueta_objetivo = Etiqueta::where('nombre', '=', $etiqueta)->first();
        if($usuario->estado->nombre == "CNB"){
            $perfiles_geneticos = DB::table('perfiles_geneticos')
            ->select('perfiles_geneticos.*', 'etiquetas_asignadas.id_perfil_genetico', 'etiquetas_asignadas.id_etiqueta', 'users.name')
            ->join('etiquetas_asignadas', 'etiquetas_asignadas.id_perfil_genetico', '=', 'perfiles_geneticos.id')
            ->join('users', 'users.id', '=', 'perfiles_geneticos.id_usuario')
            ->where('perfiles_geneticos.desestimado', '=', 0)
            ->where('perfiles_geneticos.es_perfil_repetido', '=', 0)
            ->where('etiquetas_asignadas.id_etiqueta', '=', $etiqueta_objetivo->id)
            ->get();
        }
        else{
            
            $perfiles_geneticos = DB::table('perfiles_geneticos')
            ->select('perfiles_geneticos.*', 'etiquetas_asignadas.id_perfil_genetico', 'etiquetas_asignadas.id_etiqueta')
            ->join('etiquetas_asignadas', 'etiquetas_asignadas.id_perfil_genetico', '=', 'perfiles_geneticos.id')
            ->where('perfiles_geneticos.desestimado', '=', 0)
            ->where('perfiles_geneticos.es_perfil_repetido', '=', 0)
            ->where('etiquetas_asignadas.id_etiqueta', '=', $etiqueta_objetivo->id)
            ->where('id_estado', '=', $usuario->id_estado)
            ->get();   
        }  


        return view('perfiles_geneticos.estadisticas', [
            'perfiles_geneticos' =>$perfiles_geneticos,
            'etiqueta' => $etiqueta_objetivo,
        ]);
    }

    public function validar_duplicado($id){
        $fuentes = Fuente::get();
        $categorias = Categoria::with('etiquetas')->orderBy('nombre', 'ASC')->get();
        $marcadores = Marcador::get();
        $perfil_genetico_repetido = PerfilGenetico::find($id);
        $perfil_genetico = PerfilGenetico::find($perfil_genetico_repetido->id_perfil_original);
        // metadatos
        $fecha_de_hallazgo= null;
        $lugar= null;
        $paraje= null;
        $fosa= null;
        $nombre_del_donante= null;
        $nombre_del_desaparecido = null;
        $curp_del_desaparecido = null;
        $parentesco_con_el_desaparecido= null;
        $curp_del_familiar = null;
        $clave_de_muestra = $perfil_genetico->id_externo;
        $descripcion_de_la_muestra= null;
        $observaciones= null;
        $talla= null;
        $peso= null;
        $s_particulares_o_malformaciones= null;
        $tatuaje= null;
        $sexo= null;
        $ci_nuc_ap= null;
        $fecha_desaparicion= null;
        $lugar_de_desaparicion= null;
        $no_de_caso_relacionado= null;
        $curp = null;

        

        foreach($perfil_genetico->metadatos as $metadato){
          
          if($metadato->tipo_de_metadato->nombre == 'fecha_de_hallazgo'){$fecha_de_hallazgo = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'lugar'){$lugar = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'paraje'){$paraje = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'fosa'){$fosa = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'nombre_del_donante'){$nombre_del_donante = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'nombre_del_desaparecido'){$nombre_del_desaparecido = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'curp_del_desaparecido'){$curp_del_desaparecido = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'parentesco_con_el_desaparecido'){$parentesco_con_el_desaparecido = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'curp_del_familiar'){$curp_del_familiar = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'descripcion_de_la_muestra'){$descripcion_de_la_muestra = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'observaciones'){$observaciones = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'talla'){$talla = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'peso'){$peso = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 's_particulares_o_malformaciones'){$s_particulares_o_malformaciones = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'tatuaje'){$tatuaje = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'sexo'){$sexo = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'ci_nuc_ap'){$ci_nuc_ap = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'fecha_desaparicion'){$fecha_desaparicion = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'lugar_de_desaparicion'){$lugar_de_desaparicion = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'no_de_caso_relacionado'){$no_de_caso_relacionado = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'curp'){$curp = $metadato->dato;}
        }

        return view('perfiles_geneticos.validar_duplicado', [
            'perfil_genetico_repetido' => $perfil_genetico_repetido,
            'perfil_genetico' => $perfil_genetico,
            'marcadores' => $marcadores,
            'fuentes' => $fuentes,
            'categorias' => $categorias,
            //metadatos
            'fecha_de_hallazgo' => $fecha_de_hallazgo,
            'lugar' => $lugar,
            'paraje' => $paraje,
            'fosa'  => $fosa,
            'nombre_del_donante' => $nombre_del_donante,
            'nombre_del_desaparecido' => $nombre_del_desaparecido,
            'curp_del_desaparecido' => $curp_del_desaparecido,
            'parentesco_con_el_desaparecido' => $parentesco_con_el_desaparecido,
            'curp_del_familiar' => $curp_del_familiar,
            'clave_de_muestra' => $clave_de_muestra,
            'descripcion_de_la_muestra' => $descripcion_de_la_muestra,
            'observaciones' => $observaciones,
            'talla' => $talla,
            'peso' => $peso,
            's_particulares_o_malformaciones' => $s_particulares_o_malformaciones,
            'tatuaje' => $tatuaje,
            'sexo' => $sexo,
            'ci_nuc_ap' => $ci_nuc_ap,
            'fecha_desaparicion' => $fecha_desaparicion,
            'lugar_de_desaparicion' => $lugar_de_desaparicion,
            'no_de_caso_relacionado' => $no_de_caso_relacionado,
            'curp' => $curp,
        ]);
    }

    public function guardar_validacion_de_duplicado(Request $request, $id){
      $usuario_reviso = User::find(Auth()->id());
      $perfil_genetico_repetido = PerfilGenetico::find($id);
      $perfil_genetico_original = PerfilGenetico::find($perfil_genetico_repetido->id_perfil_original);
      $perfil_genetico_repetido->desestimado = 1;
      $perfil_genetico_repetido->id_usuario_reviso = $usuario_reviso->id;
      $perfil_genetico_repetido->save();
      $perfil_genetico_original->id_externo = $request->clave_de_muestra;
      $perfil_genetico_original->id_fuente = $request->id_fuente;
      $perfil_genetico_original->save();

      
        foreach ($perfil_genetico_original->etiquetas as $etiqueta){
            $etiqueta->delete();
        }
        foreach ($perfil_genetico_original->metadatos as $metadato) {
            $metadato->delete();          
        }

        foreach ($request->all() as $key => $value) {
            if($value<>null){
                if(is_array($value)){
                    foreach ($value as $key => $value) {
                        $etiqueta = EtiquetaAsignada::create([
                            'id_etiqueta' => $value,
                            'id_perfil_genetico' => $perfil_genetico_original->id,
                        ]);
                    }
                }
                else{
                    if($key <> '_token'){ // descartamos el token de la consulta
                        if($key <> 'id_fuente'){    // descartamos la fuente
                          if($key <> 'clave_de_muestra'){
                            if($key <> '_method'){
                              $primera_palabra = explode('_', $key);    // dividimos el $key en palabras separadas
                              $verifica_marcador = Marcador::where('nombre', '=', $primera_palabra[0])->first(); // buscamos si la palabra existe en marcadores

                              if(empty($verifica_marcador)){  // si la busqueda devuelve un arreglo vacio, es un metadato
                                $id_tipo_de_metadato = TipoDeMetadato::where('nombre','=', $key)->first();
                                if(empty($id_tipo_de_metadato)){    // si no existe el tipo de metadato lo creamos y guardamos
                                    $tipo_de_metadato = TipoDeMetadato::create([
                                        'nombre' => $key
                                    ]);

                                    $metadato = Metadato::create([
                                        'id_perfil_genetico' => $perfil_genetico_original->id,
                                        'id_tipo_de_metadato' => $tipo_de_metadato->id,
                                        'dato' => $value
                                    ]);                                    
                                }   
                                else{   // si existe solo creamos el metadato asociado al perfil
                                    $metadato = Metadato::create([
                                        'id_perfil_genetico' => $perfil_genetico_original->id,
                                        'id_tipo_de_metadato' => $id_tipo_de_metadato->id,
                                        'dato' => $value
                                    ]);   
                                }
                              }
                            }
                          }
                        }
                    }
                }
            }
        }
        flash('El perfil duplicado fue desestimado  y el perfil original fue actualizado', 'success');
        return redirect()->route('perfiles_geneticos.show', $perfil_genetico_original); 
    }
}
