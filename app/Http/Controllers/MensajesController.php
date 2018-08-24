<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mensaje;
use App\User;
use Illuminate\Support\Facades\Auth;

class MensajesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $usuario = User::find(Auth::id());
        $mensajes = Mensaje::where('id_usuario_envia', $usuario->id)
        ->where('desestimado_remitente', 0)
        ->get();

        return view('mensajes.index', [
            'mensajes' => $mensajes,
        ]);
    }

    public function recibidos(){

        $usuario = User::find(Auth::id());
        $mensajes = Mensaje::where('id_estado_recibe', $usuario->estado->id)
        ->where('desestimado', 0)
        ->get();
        
        $mensajes_sin_revisar = Mensaje::where('id_estado_recibe', $usuario->estado->id)
                    ->where('revisado', 0)
                    ->where('desestimado', 0)
                    ->get();
                    
        foreach ($mensajes_sin_revisar as $mensaje_sin_revisar) {
            $mensaje_sin_revisar->revisado = 1;
            $mensaje_sin_revisar->save();
        }

        return view('mensajes.recibidos', [
            'mensajes' => $mensajes,
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
        $mensaje = Mensaje::find($id);

        $mensaje->desestimado = 1;
        $mensaje->save();

        Flash('El mensaje fue eliminado exitosamente', 'success');

        return redirect()->route('mensajes.recibidos');
    }

    public function destroy2($id)
    {   
        $mensaje = Mensaje::find($id);

        $mensaje->desestimado_remitente = 1;
        $mensaje->save();

        Flash('El mensaje fue eliminado exitosamente', 'success');

        return redirect()->route('mensajes.recibidos');
    }
}
