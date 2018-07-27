<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Marcador;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;    // Para obtener datos del usuario en la session
use Validator;                          // Para validar el formulario de carga del excel

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
        return view( 'marcadores.index' ,[
            'marcadores' => $marcadores,
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
            'nombre' =>"min:3|max:90|required|unique:marcadores" 
        ],[
            'nombre.min' => 'El tamaño minimo del nombre del marcador es de 3 caracteres',
            'nombre.max' => 'El tamaño maximo del nombre del marcador debe de ser de 90 caracteres',
            'nombre.required' => 'El campo debe ser llenado',
            'nombre.unique' => 'El nombre del marcador asigando ya se encuentra en uso'
        ]);

        $marcador = Marcador::create([
            'nombre' => $request->input('nombre'),
            'id_usuario_registro' => Auth::id(),
            'id_usuario_edito' => Auth::id(),
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
        $marcador->updated_at = date("Y-m-d H:i:s");
        $marcador->save();
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

        Flash('El marcador ' .$marcador->nombre . ' fue eliminado exitosamente', 'success');

        return redirect()->route('marcadores.index');
    }
}
