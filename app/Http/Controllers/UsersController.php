<?php

namespace App\Http\Controllers;

use App\User;	// si se usa un modelo se añade al controlador
use App\Estado;  
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Laracast\Flash\Flash;
use Illuminate\Support\Facades\Auth;    // Para obtener datos del usuario en la session
use Faker\Generator as Faker;

class UsersController extends Controller
{

    public function index()
    {   
        $usuarios = User::where('desestimado','=', 0)->get();
        return view('users.index', [
            'usuarios' => $usuarios,
        ]);
    }

    public function show($id){
        $user = User::find($id);
    	return view('users.show',[
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {   
        $roles = Role::get();
        $estados = Estado::get();
        return view('users.edit', compact('user','roles','estados'));  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,User $user)
    {
        $user->update($request->all());
        $user->roles()->sync($request->get('roles'));

        flash('El usuario fue actualizado correctamente', 'success');
        return redirect()->route('users.edit', $user->id)->with('info', 'Usuario actializado con exito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usuario = User::find($id);
        $usuario->desestimado = 1;
        $usuario->save();

        Flash('El usuario ' .$usuario->name . ' fue eliminado exitosamente', 'success');

        return redirect()->route('users.index');      
    }

    public function editar_perfil_personal(){
        $usuario = User::find(Auth::id());
        return view('users.personal_edit',[
            'usuario' => $usuario
        ]);
    }

    public function update_perfil_personal(Request $request, $id){
        $user = User::find($id);
        $user->update($request->all());
        flash('Su perfil fue actualizado correctamente', 'success');
        return redirect()->route('users.personal_edit');
    }
}
