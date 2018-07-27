<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\PerfilGenetico;
use App\EtiquetaAsignada;
use App\User;
use App\Busqueda;
use App\BusquedaResultado;
use App\Categoria;
use App\Fuente;
use App\ImportacionFrecuencia;
use App\Frecuencia;
use App\Marcador;
use App\EstatusBusqueda;
use Illuminate\Support\Facades\DB;


class BusquedasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $busquedas = Busqueda::where('id_estatus_busqueda', '<>', 3)->get();
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
            'id_estatus_busqueda' => 1
        ]);

        // subconsulta que reducira aquellos perfiles geneticos que se repitan en las egtiquetas
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

        // Si se usuario de CNB se buscara en todos los perfiles geneticos
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

        // comparamos aquellos marcadores en comun entre el perfil objetivo y los etiquetados

        foreach ($perfiles_geneticos as $perfil_genetico) {
            $IP = 1;
            $exclusiones = 0;
            $marcadores_usados = 0;
            foreach ($perfil_objetivo->alelos as $m_p1) {
                $m_p2 = $perfil_genetico->alelos->firstWhere('id_marcador', $m_p1->id_marcador);
                if($exclusiones > $request->exclusiones){
                    break;
                }
                else{
                    if($m_p2 <> null){
                        $f1 = $frecuencias->where('id_marcador', $m_p1->id_marcador)->firstWhere('alelo', $m_p1->alelo_1);
                        $f2 = $frecuencias->where('id_marcador', $m_p1->id_marcador)->firstWhere('alelo', $m_p1->alelo_2);
                        if($m_p1->marcador->tipo_de_marcador->nombre == 'Autosomico'){
                            if(!empty($f1) && !empty($f2)){
                            // Exclusion    a <> b && c <> d && a <> c && a <> d && b <> c && b <> d
                                if ($m_p1->alelo_1 <> $m_p1->alelo_2 && $m_p2->alelo_1 <> $m_p2->alelo_2 && $m_p1->alelo_1 <> $m_p2->alelo_1
                                && $m_p1->alelo_1 <> $m_p2->alelo_2 && $m_p1->alelo_2 <> $m_p2->alelo_1 && $m_p1->alelo_2 <> $m_p2->alelo_2    
                                ){
                                    $IP = $IP * 0;
                                    $exclusiones++;
                                    $marcadores_usados++;
                                }
                                //  AA AA      a = b && c = d && a = c
                                elseif ($m_p1->alelo_1 == $m_p1->alelo_2 && $m_p2->alelo_1 == $m_p2->alelo_2 && $m_p1->alelo_1 == $m_p2->alelo_1){  
                                    $IP = $IP * (1/$f1->frecuencia);
                                    $marcadores_usados++;
                                }
                                //  AA AB      a = b  && c <> d &&  ( a = c || a = d )
                                elseif ($m_p1->alelo_1 == $m_p1->alelo_2 && $m_p2->alelo_1 <> $m_p2->alelo_2 && 
                                       ( $m_p1->alelo_1 == $m_p2->alelo_1 || $m_p1->alelo_1 == $m_p2->alelo_2)) {
                                    $IP = $IP * (1/(2*$f1->frecuencia));
                                    $marcadores_usados++;
                                }
                                //  AB AA      a <> b && c = d && ( a = c || b = c )  // si b = c || b = d  res f1 = f2  
                                elseif ($m_p1->alelo_1 <> $m_p1->alelo_2 && $m_p2->alelo_1 == $m_p2->alelo_2 && 
                                       ($m_p1->alelo_1 == $m_p2->alelo_1 || $m_p1->alelo_2 == $m_p2->alelo_1)){
                                       
                                       if( $m_p1->alelo_2 == $m_p2->alelo_1 || $m_p1->alelo_2 == $m_p2->alelo_2 ){
                                            $f1->frecuencia = $f2->frecuencia;
                                       }

                                       $IP = $IP * (1/(2*$f1->frecuencia));
                                       $marcadores_usados++;
                                }
                                //  AB AB      a <> b && c <> d && ( a = c || a = d ) && ( b = c || b = d )   
                                elseif ($m_p1->alelo_1 <> $m_p1->alelo_2 && $m_p2->alelo_1 <> $m_p2->alelo_2 &&
                                       ($m_p1->alelo_1 == $m_p2->alelo_1 || $m_p1->alelo_1 == $m_p2->alelo_2) &&
                                       ($m_p1->alelo_2 == $m_p2->alelo_1 || $m_p1->alelo_2 == $m_p2->alelo_2)){

                                       $IP = $IP * (($f1->frecuencia + $f2->frecuencia)/(4 * $f1->frecuencia * $f2->frecuencia ));
                                       $marcadores_usados++;
                                }
                                //  AB AC      a <> b && c <> d && (( a = c && b <> c && b <> d && a <> d) || 
                                //             ( a = d && a <> c && b <> c && b <> d))
                                else{
                                   if($m_p1->alelo_1 <> $m_p1->alelo_2 && $m_p2->alelo_1 <> $m_p2->alelo_2 &&
                                     (($m_p1->alelo_1 == $m_p2->alelo_1 && $m_p1->alelo_2 <> $m_p2->alelo_1 && 
                                     $m_p1->alelo_2 <> $m_p2->alelo_2 && $m_p1->alelo_1 <> $m_p2->alelo_2) ||
                                     ($m_p1->alelo_1 == $m_p2->alelo_2 && $m_p1->alelo_1 <> $m_p2->alelo_1 &&
                                     $m_p1->alelo_2 <> $m_p2->alelo_1 && $m_p1->alelo_2 <> $m_p2->alelo_2))){
                                     $IP = $IP * ( 1/ (4 * $f1->frecuencia));
                                     $marcadores_usados++;
                                   }   
                                }
                            }
                        }  
                    }
                }
            }
            
            if($exclusiones <= $request->exclusiones && $marcadores_usados >= $request->marcadores_minimos){
                $perfil_genetico_resultado = BusquedaResultado::create([
                    'id_busqueda' => $busqueda->id,
                    'id_perfil_objetivo' => $perfil_objetivo->id,
                    'id_perfil_subordinado' => $perfil_genetico->id,
                    'IP' => $IP,
                    'PP' => ($IP / ($IP + 1) * 100),
                    'marcadores_minimos' => $marcadores_usados,
                    'exclusiones' => $exclusiones
                ]);
            }
        }
        return redirect()->route('busquedas.index');
    }

    public function store2(Request $request)
    {
        dd($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $busqueda = Busqueda::with('resultados')->find($id); 
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
            $perfiles_geneticos = PerfilGenetico::where('requiere_revision', '=', 0)->get();
        }
        else{
            $perfiles_geneticos = PerfilGenetico::where('id_estado', '=', $usuario->id_estado)->where('requiere_revision', '=', 0)->get();   
        }      
        return view('busquedas.ventana',[
            'perfiles_geneticos' =>$perfiles_geneticos,
        ]);
    }
}
