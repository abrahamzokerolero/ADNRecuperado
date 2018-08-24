<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Marcador;
use App\TipoDeMarcador;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Auth;    // Para obtener datos del usuario en la session
use Validator;                          // Para validar el formulario de carga del excel
use Laracast\Flash\Flash;
use App\Log;



class MarcadoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marcadores = Marcador::where('desestimado', '=', 0)->get();
        $tipos_de_marcadores = TipoDeMarcador::get(); 
        return view( 'marcadores.index' ,[
            'marcadores' => $marcadores,
            'tipos_de_marcadores' => $tipos_de_marcadores
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $this->validate($request, [
            'nombre' =>'min:3|max:90|required|unique:marcadores' 
        ],[
            'nombre.min' => 'El tamaño minimo del nombre de la categoria es de 3 caracteres',
            'nombre.max' => 'El tamaño maximo del nombre de la categoria deber de ser de 90 caracteres',
            'nombre.required' => 'El campo debe ser llenado',
            'nombre.unique' => 'El nombre de la categoria asigando ya se encuentra en uso'
        ]);

        $usuario = User::find(Auth::id());
        $marcador = Marcador::create([
            'nombre' => $request->nombre,
            'id_tipo_de_marcador' => $request->id_tipo_de_marcador,
            'id_usuario_registro' => $usuario->id,
            'id_usuario_edito' => $usuario->id,
        ]);

        $log = Log::create([
            'id_usuario' => $usuario->id,
            'id_estado' => $usuario->estado->id,
            'actividad' => 'Creo el marcador: ' . $marcador->nombre,
        ]);

        flash('El marcador se ingreso correctamente', 'success');

        return redirect('marcadores');
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
        $marcador = Marcador::find($id);
        return view('marcadores.edit',[
            'marcador' => $marcador,
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
            'nombre' =>"min:3|max:90|required|unique:marcadores,nombre,$id", 
        ],[
            'nombre.min' => 'El tamaño minimo del nombre del marcador es de 3 caracteres',
            'nombre.max' => 'El tamaño maximo del nombre del marcador debe de ser de 90 caracteres',
            'nombre.required' => 'El campo debe ser llenado',
        ]);

        $marcador = Marcador::find($id);
        $marcador->nombre = $request->nombre;
        $marcador->id_usuario_edito = Auth::id();
        $marcador->save();

        $usuario = User::find(Auth::id());
        $log = Log::create([
            'id_usuario' => $usuario->id,
            'id_estado' => $usuario->estado->id,
            'actividad' => 'Actualizo el marcador: ' . $marcador->nombre,
        ]);

        Flash('La marcador cambio de nombre a: <b>' . $marcador->nombre . '</b>', 'success');
        return redirect()->route('marcadores.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marcador = Marcador::find($id);
        $marcador->desestimado = 1;
        $marcador->save();

        $usuario = User::find(Auth::id());
        $log = Log::create([
            'id_usuario' => $usuario->id,
            'id_estado' => $usuario->estado->id,
            'actividad' => 'Elimino el marcador: ' . $marcador->nombre,
        ]);

        Flash('El marcador ' .$marcador->nombre . ' fue eliminado exitosamente', 'success');

        return redirect()->route('marcadores.index');
    }
}
