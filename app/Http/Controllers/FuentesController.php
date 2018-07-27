<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Laracast\Flash\Flash;
use App\Fuente;

class FuentesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fuentes = Fuente::where('desestimado', '=', 0)->get();
        return view('fuentes.index', [
            'fuentes' => $fuentes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('fuentes.create');
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
            'nombre' => 'min:3|max:90|required|unique:fuentes',
            'id_interno' => 'min:1|max:20|required|unique:fuentes'
        ],[
            'nombre.min' => 'El tamaño minimo del nombre de la fuente es de 3 caracteres',
            'nombre.max' => 'El tamaño maximo del nombre de la fuente deber de ser de 90 caracteres',
            'nombre.required' => 'El campo nombre debe ser llenado',
            'nombre.unique' => 'El nombre de fuente ya existe',
            'id_interno.min' => 'El tamaño minimo del id idinterno de la fuente es de 3 caracteres',
            'id_interno.max' => 'El tamaño maximo del id interno de la fuente deber de ser de 20 caracteres',
            'id_interno.required' => 'El campo id interno debe ser llenado',
            'id_interno.unique' => 'El id interno de fuente ya existe'
        ]);

        $categoria = Fuente::create([
            'nombre' => $request->input('nombre'),
            'id_interno' => $request->input('id_interno'),
            'id_externo' => $request->input('id_externo'),
            'contacto_fuente' => $request->input('contacto_fuente'),
            'correo_fuente' => $request->input('correo_fuente'),
            'telefono1_fuente' => $request->input('telefono1_fuente'),
            'telefono2_fuente' => $request->input('telefono2_fuente'),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        flash('La fuente se ingreso correctamente', 'success');
        return redirect('fuentes');
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
        $fuente = Fuente::find($id);

        return view('fuentes.edit',[
            'fuente' => $fuente,
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
            'nombre' => "min:3|max:90|required|unique:fuentes,nombre,$id",
            'id_interno' => "min:1|max:20|required|unique:fuentes,id_interno,$id"
        ],[
            'nombre.min' => 'El tamaño minimo del nombre de la fuente es de 3 caracteres',
            'nombre.max' => 'El tamaño maximo del nombre de la fuente deber de ser de 90 caracteres',
            'nombre.required' => 'El campo nombre debe ser llenado',
            'id_interno.min' => 'El tamaño minimo del id idinterno de la fuente es de 3 caracteres',
            'id_interno.max' => 'El tamaño maximo del id interno de la fuente deber de ser de 20 caracteres',
            'id_interno.required' => 'El campo id interno debe ser llenado',
        ]);
        
        $fuente = Fuente::find($id);
        $fuente->nombre = $request->nombre;
        $fuente->id_interno = $request->id_interno;
        $fuente->id_externo = $request->id_externo;
        $fuente->contacto_fuente = $request->contacto_fuente;
        $fuente->correo_fuente = $request->correo_fuente;
        $fuente->telefono1_fuente = $request->telefono1_fuente;
        $fuente->telefono2_fuente = $request->telefono2_fuente;
        $fuente->updated_at = date("Y-m-d H:i:s");
        $fuente->save();

        Flash('La fuente fue actualizada', 'success');

        return redirect()->route('fuentes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fuente = Fuente::find($id);
        $fuente->desestimado = 1;
        $fuente->save();

        Flash('La fuente ' .$fuente->nombre . ' fue eliminada exitosamente', 'success');

        return redirect()->route('fuentes.index');
    }
}
