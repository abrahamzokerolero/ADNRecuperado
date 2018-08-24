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
use Maatwebsite\Excel; // Para la lectura del exce
use App\Log;


class ExportacionesController extends Controller
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
        return view('exportaciones.index', [
            'perfiles_geneticos' => $perfiles_geneticos->toJson(),
            'usuario' => $usuario,
            'tipos_de_metadatos' => $tipos_de_metadatos,
            'categorias' => $categorias,
            'fuentes' => $fuentes
        ]);
    }


    public function exportar(Request $request, $perfiles_geneticos){

        $usuario = User::find(Auth::id());
        $log = Log::create([
            'id_usuario' => $usuario->id,
            'id_estado' => $usuario->estado->id,
            'actividad' => 'Realizo una exportacion de perfiles geneticos',
        ]);

        \Excel::create('Exportacion_de_Genotipos', function($excel) use (&$perfiles_geneticos){
            
            $perfiles_geneticos = PerfilGenetico::with('alelos')
            ->with(array('metadatos' => function($query){
                $query->with('tipo_de_metadato');
            }))->find(explode(',',$perfiles_geneticos));
            // $perfiles_geneticos = EtiquetaAsignada::with(array('perfil_genetico' => function ($query){
            //     $query->with('alelos')->with('metadatos');
            // }))->whereIn('id_perfil_genetico', explode(',',$perfiles_geneticos))->get();

         
            $excel->sheet('Perfiles Geneticos', function($sheet) use($perfiles_geneticos) {
                
                $arreglo_de_columnas = [];

                array_push($arreglo_de_columnas, 'Identificador');
                array_push($arreglo_de_columnas, 'Id_externo');

                $marcadores = Marcador::get();
                $tipos_de_metadatos = TipoDeMetadato::get();

                foreach ($marcadores as $marcador) {
                   array_push($arreglo_de_columnas, $marcador->nombre); 
                }

                foreach ($tipos_de_metadatos as $tipo_de_metadato) {
                    array_push($arreglo_de_columnas, $tipo_de_metadato->nombre);
                }
                // dd($arreglo_de_columnas);

                // Declaracion de columnas del excel
                $sheet->row(1, $arreglo_de_columnas);
                $sheet->row(1, function($row) { $row->setBackground('#CCCCCC'); });

                foreach($perfiles_geneticos as $index => $perfil_genetico) {
                    // Autosomicos

                    $array_de_datos = [];
                    array_push($array_de_datos, $perfil_genetico->identificador);
                    array_push($array_de_datos, $perfil_genetico->id_externo);

                    foreach ($marcadores as $marcador) {

                        $marcador = $perfil_genetico->alelos->where('id_marcador', $marcador->id)->first();
                        if(!empty($marcador)){
                            array_push($array_de_datos, '' . $marcador->alelo_1 . ',' . $marcador->alelo_2);
                        }
                        else{
                            array_push($array_de_datos, '');
                        }
                    }

                    foreach ($tipos_de_metadatos as $tipo_de_metadato) {
                        $metadato = $perfil_genetico->metadatos->where('id_tipo_de_metadato', $tipo_de_metadato->id)->first();
                        if(!empty($metadato)){
                            array_push($array_de_datos, $metadato->dato);
                        }
                        else{
                            array_push($array_de_datos, '');
                        }   
                    }

                    $sheet->row($index+2, $array_de_datos);
                }
             
            });
         
        })->export('xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
}
