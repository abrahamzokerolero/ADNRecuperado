<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Etiqueta;
use App\Categoria;
use Laracast\Flash\Flash;
use App\Log;
use Illuminate\Support\Facades\Auth;
use App\User;


class CategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $categorias = DB::table('categorias')->where('desestimado', '=' , 0)->get();
        return view('categorias.index', [
            'categorias' => $categorias
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
            'nombre' =>'min:3|max:90|required|unique:categorias' 
        ],[
            'nombre.min' => 'El tamaño minimo del nombre de la categoria es de 3 caracteres',
            'nombre.max' => 'El tamaño maximo del nombre de la categoria deber de ser de 90 caracteres',
            'nombre.required' => 'El campo debe ser llenado',
            'nombre.unique' => 'El nombre de la categoria asigando ya se encuentra en uso'
        ]);

        $categoria = Categoria::create([
            'nombre' => $request->input('nombre'),
        ]);

        $usuario = User::find(Auth::id());
        $log = Log::create([
            'id_usuario' => $usuario->id,
            'id_estado' => $usuario->estado->id,
            'actividad' => 'Creo la categoria: ' . $categoria->nombre,
        ]);

        flash('La categoria se ingreso correctamente', 'success');
        return redirect('categorias');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categoria = Categoria::find($id);
        $etiquetas = Etiqueta::where('categoria_id', '=', $id)->where('desestimado', '=', 0)->get();

        return view('categorias.show', [
            'categoria' => $categoria,
            'etiquetas' => $etiquetas,
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
        $categoria = Categoria::find($id);
        return view('categorias.edit')->with('categoria', $categoria);
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
            'nombre' =>"min:3|max:90|required|unique:categorias,nombre,$id", 
        ],[
            'nombre.min' => 'El tamaño minimo del nombre de la categoria es de 3 caracteres',
            'nombre.max' => 'El tamaño maximo del nombre de la categoria deber de ser de 90 caracteres',
            'nombre.required' => 'El campo debe ser llenado',
        ]);

        $categoria = Categoria::find($id);
        $categoria->nombre = $request->nombre;
        $categoria->updated_at = date("Y-m-d H:i:s");
        $categoria->save();

        $usuario = User::find(Auth::id());
        $log = Log::create([
            'id_usuario' => $usuario->id,
            'id_estado' => $usuario->estado->id,
            'actividad' => 'Actualizo la categoria: ' . $categoria->nombre,
        ]);

        Flash('La categoria cambio de nombre a: <b>' . $categoria->nombre . '</b>', 'success');
        return redirect()->route('categorias.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $categoria = Categoria::find($id);

        foreach ($categoria->etiquetas as $etiqueta) {

            $perfiles_etiquetados = EtiquetaAsignada::where('id_etiqueta', '=', $etiqueta->id)->get();

            foreach ($perfiles_etiquetados as $perfil_etiquetado) {
                $perfil_etiquetado->delete();
            }

            $etiqueta->categoria_id = null;
            $etiqueta->save();
        }

        $categoria->desestimado = 1;
        $categoria->save();

        $usuario = User::find(Auth::id());
        $log = Log::create([
            'id_usuario' => $usuario->id,
            'id_estado' => $usuario->estado->id,
            'actividad' => 'Elimino la categoria: ' . $categoria->nombre,
        ]);

        Flash('La categoria ' .$categoria->nombre . ' fue eliminada exitosamente sin embargo debera asignar sus etiquetas manualmente', 'success');

        return redirect()->route('categorias.index');
    }
}
