<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Categoria;
use App\Etiqueta;
use App\EtiquetaAsignada;
use Laracast\Flash\Flash;


class EtiquetasController extends Controller
{
    public function index()
    {   

        $categoria_sin_asignar = Categoria::where('nombre', '=', 'SIN ASIGNAR')->where('desestimado', '=', 0)->first();

        if($categoria_sin_asignar == null){
            $etiquetas = Etiqueta::where('categoria_id','=',null)->where('desestimado', '=', 0)->get();            
        }
        else{
            $etiquetas = Etiqueta::where('categoria_id','=',null)->where('desestimado', '=', 0)->orWhere('categoria_id','=', $categoria_sin_asignar->id)->where('desestimado', '=', 0)->get();  
        }
        return view('etiquetas.index',[
            'etiquetas' => $etiquetas,
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

        $this->validate($request, [
            'nombre' => 'min:3|max:90|required|unique:etiquetas'
        ],[
            'nombre.min' => 'El tamaño minimo del nombre de la etiqueta es de 3 caracteres',
            'nombre.max' => 'El tamaño maximo del nombre de la etiqueta deber de ser de 90 caracteres',
            'nombre.required' => 'El campo debe ser llenado',
            'nombre.unique' => 'El nombre de etiqueta ya existe'
        ]);


        // Validacion de categoria antes de Guardar
        if($request->categoria_id == ''){
            flash('La etiqueta debe ser asignada a una categoria', 'danger');
            return redirect('categorias');            
        }
        else{

            /* Se obtiene la cadena de etiquetas*/            
            $etiquetas = $request->nombre;

            /* Elimina los espacios en blanco de la cadena            
            $etiquetas = str_replace(' ', '', $etiquetas);*/


            /* Separa las etiquetas entre comas*/            
            $etiquetas = explode(',', $etiquetas);

            /* Si se registran mas de 2 etiquetas*/
            if( count($etiquetas) > 1){
                for ($i = 0; $i < count($etiquetas); $i++) {
                    $etiqueta = Etiqueta::create([
                        /* la funcion trim elimina los espacios en blanco al principio y al final de la cadena*/
                        'nombre' => trim($etiquetas[$i]),
                        'categoria_id' => $request->categoria_id,
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s"),
                    ]);
                }
            }
            else {
                $etiqueta = Etiqueta::create([
                    'nombre' => $request->input('nombre'),
                    'categoria_id' => $request->categoria_id,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ]);  
            }
            flash('Se realizaron exitosamente los cambios', 'success');
            return redirect('categorias'); 
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
        $etiqueta = Etiqueta::find($id);
        $categorias = Categoria::get();

        return view('etiquetas.edit',[
            'etiqueta' => $etiqueta,
            'categorias' => $categorias,
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
        $this->validate($request, [
            'nombre' => "min:3|max:90|required|unique:etiquetas,nombre,$id",
        ],[
            'nombre.min' => 'El tamaño minimo del nombre de la etiqueta es de 3 caracteres',
            'nombre.max' => 'El tamaño maximo del nombre de la etiqueta deber de ser de 90 caracteres',
            'nombre.required' => 'El campo debe ser llenado',
            'nombre.unique' => 'El nombre de etiqueta ya existe'
        ]);

        $etiqueta = Etiqueta::find($id);

        if($request->categoria_id == ''){
            flash('La etiqueta debe ser asignada a una categoria', 'danger');
            return redirect('etiquetas/'. $etiqueta->id . '/edit');            
        }
        else{
            $etiqueta->nombre = $request->nombre;
            $etiqueta->categoria_id = $request->categoria_id;
            $etiqueta->updated_at = date("Y-m-d H:i:s");
            $etiqueta->save();
            Flash('La etiqueta se actualizo correctamente', 'success');
            return redirect('categorias/'. $request->categoria_id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $etiqueta = Etiqueta::find($id);

        $perfiles_etiquetados = EtiquetaAsignada::where('id_etiqueta', '=', $etiqueta->id)->get();

        foreach ($perfiles_etiquetados as $perfil_etiquetado) {
            $perfil_etiquetado->delete();
        }

        $etiqueta->desestimado = 1;
        $etiqueta->save();

        Flash('La etiqueta ' .$etiqueta->nombre . ' fue eliminada exitosamente', 'success');

        if($etiqueta->categoria_id <> null){
            return redirect('categorias/'. $etiqueta->categoria_id);
        }
        else{
            return redirect()->route('etiquetas.index');
        }      
    }
}
