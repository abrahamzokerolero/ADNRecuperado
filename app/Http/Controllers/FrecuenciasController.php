<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Frecuencia;
use Carbon\Carbon;

class FrecuenciasController extends Controller
{

    public function edit($id)
    {
        $frecuencia = Frecuencia::find($id);
        return view('frecuencias.edit',[
            'frecuencia' => $frecuencia,
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
            'marcador' => "min:2|max:90|required",
            'alelo' => "min:1|max:10|required",
            'frecuencia' => "min:5|max:10|required",

        ],[
            'marcador.min' => 'El tamaño minimo del marcador de la fuente es de 2 caracteres',
            'marcador.max' => 'El tamaño maximo del marcador de la fuente deber de ser de 90 caracteres',
            'marcador.required' => 'El campo marcador debe ser llenado',
            'alelo.min' => 'El tamaño minimo del numero del alelo es de 1 caracteres',
            'alelo.max' => 'El tamaño maximo del numero del alelo deber de ser de 10 caracteres',
            'alelo.required' => 'El campo alelo debe ser llenado',
            'frecuencia.min' => 'El tamaño minimo del numero de la frecuencia es de 5 caracteres',
            'frecuencia.max' => 'El tamaño maximo del numero de la frecuencia es de 10 caracteres',
            'frecuencia.required' => 'El campo frecuencia debe ser llenado',
        ]);

        $frecuencia = Frecuencia::find($id);
        $frecuencia->marcador->nombre = $request->marcador;
        $frecuencia->alelo = $request->alelo;
        $frecuencia->frecuencia = $request->frecuencia;
        $frecuencia->updated_at = date("Y-m-d H:i:s");
        $frecuencia->save();
        flash('El marcador fue actualizado correctamente', 'success');
        return redirect()->route('importaciones_frecuencias.show', $frecuencia->importacion_frecuencia->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $frecuencia = Frecuencia::find($id);
        $frecuencia->desestimado = 1;
        $frecuencia->save();
        flash('El marcador fue eliminado correctamente', 'success');
        return redirect()->route('importaciones_frecuencias.show', $frecuencia->importacion_frecuencia->id);
    }
}
