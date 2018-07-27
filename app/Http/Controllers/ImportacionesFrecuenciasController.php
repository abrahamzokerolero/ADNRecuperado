<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ImportacionFrecuencia;          // Para la creacion de los datos de importacion
use App\Frecuencia;                     // Para la creacion de las Frecuencias
use App\Marcador;
use App\Estado;
use App\User;
use Illuminate\Support\Facades\Input;   // Para saber el nombre del archivo recibido
use Illuminate\Support\Facades\Auth;    // Para obtener datos del usuario en la session
use Validator;                          // Para validar el formulario de carga del excel
use Maatwebsite\Excel;                  // Para la lectura del excel

class ImportacionesFrecuenciasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuario = User::find(Auth::id());
        if($usuario->estado->nombre == "CNB"){
            $importaciones = ImportacionFrecuencia::where('desestimado', '=', 0)
            ->where('id_estado', 33)
            ->get();    
        }
        else{
            $importaciones = ImportacionFrecuencia::where('id_estado','=', $usuario->id_estado)->where('desestimado', '=', 0)->get();   
        }
        return view('importaciones_frecuencias.index', [
            'importaciones' => $importaciones,
        ]);
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

    public function default($id)
    {
        $usuario = User::find(Auth::id());
        $importaciones = ImportacionFrecuencia::where('id_estado','=', $usuario->id_estado)->where('desestimado', '=', 0)->get();

        foreach ($importaciones as $importacion) {
            if($importacion->id <> $id ){
                $importacion->tabla_default = 0;
                $importacion->save();    
            }
            else{
                $importacion->tabla_default = 1;
                $importacion->save();
                flash('Se asigno a: <b>' . $importacion->nombre_otorgado . ' </b> como la tabla de frecuencas por defecto para las busquedas', 'success');
            }
        }

        return redirect()->route('importaciones_frecuencias.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        // Validacion del tipo de documento
        $validator = Validator::make(
            ['archivo' => Input::file('archivo')],
            ['archivo' => 'mimes:xls,xlsx']
        );

        // Comprobacion de validacion
        if($validator->passes()){

            // Guardado del archivo de excel // ruta storage y nombre
            $ruta_archivo = $request->file('archivo')->storeAs('public', $request->file('archivo')->getClientOriginalName());

            // Obtencion del nombre original del archivo
            $nombreDocumento = $request->file('archivo')->getClientOriginalName();
            
            $usuario = User::find(Auth::id());
            $consecutivo = ImportacionFrecuencia::where('id_estado', '=',$usuario->id_estado)->count() + 1;

            //Creacion de los datos de importacion
            $importacion_frecuencias = ImportacionFrecuencia::create([
                'nombre' => $nombreDocumento,
                'id_usuario' => Auth::id(),
                'id_estado' => $usuario->id_estado,
                'identificador' => 'F-'. $usuario->id_estado . '-' . $consecutivo,
                'nombre_otorgado' => $request->nombre_otorgado,
            ]);
            
            //Lectura del archivo Excel
            \Excel::load('storage/app/'.$ruta_archivo, function ($reader) {

                $documento = $reader->get();

                $id_importacion = ImportacionFrecuencia::all();
                $id_importacion = $id_importacion->last()->id;
                $fallo_importacion = false;

                foreach($documento as $columnas){
                    $marcador=$columnas['alelos'];
                    
                    $marcador = Marcador::where('nombre', "=", $marcador)->first();
                    if($marcador == null){
                        $frecuencias = Frecuencia::where('id_importacion', '=', $id_importacion)->get();
                        if($frecuencias->isNotEmpty()){
                            foreach ($frecuencias as $frecuencia) {
                                $frecuencia->delete();
                            }
                        }
                        $importacion = ImportacionFrecuencia::find($id_importacion);
                        $importacion->delete();
                        $fallo_importacion = true;
                        break;
                    }
                    else{
                        $alelo = key($columnas);
                        foreach($columnas as $key => $fila){
                            if($fila <> null){
                                if($marcador <> $fila){
                                    if ($key == 0) {
                                        $key = '*';
                                    }
                                    // Creacion de cada uno de los registros de excel
                                    
                                    if(floatval($fila) <> 0){
                                        $frecuencia = Frecuencia::create([
                                            'id_importacion' => $id_importacion,
                                            'id_marcador' => $marcador->id,
                                            'alelo' => $key,
                                            'frecuencia' => floatval($fila),
                                        ]);    
                                    }
                                }
                            }      
                        }
                    }
                }
                if($fallo_importacion){
                    flash('El archivo no pudo ser importado, el marcador <b> '. $columnas['alelos'] . '</b> no existe' , 'danger');
                }
                else{
                    flash('El archivo fue importado correctamente' , 'success');   
                }
            });

            
            return redirect()->route('importaciones_frecuencias.index');
        }
        else{
            flash('El formato del archivo no es correcto', 'warning');
            return redirect()->route('importaciones_frecuencias.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $frecuencias = Frecuencia::where('id_importacion', '=', $id)->where('desestimado', '=', 0)->get();
        return view('importaciones_frecuencias.show',[
            'frecuencias' => $frecuencias,
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $importacion = ImportacionFrecuencia::find($id);
        
        foreach ($importacion->frecuencias as $frecuencia) {
            $frecuencia->desestimado = 1;
            $frecuencia->save();
        }

        $importacion->desestimado = 1;
        $importacion->save();

        Flash('Se elimino correctamente la importacion seleccionada', 'success');
        return redirect()->route('importaciones_frecuencias.index');

    }
}
