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
use Illuminate\Support\Facades\Validator;
use App\Log;


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

    public function create(){
        $estados = Estado::get();
        return view('users.crear', [
            'estados' => $estados,
        ]);
    }

    public function store(Request $request){
        

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255@|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ],[
           'name.required' => 'Debe ingresar un nombre obligatoriamente',
           'name.max' => 'El nombre debe contener un maximo de 255 caracteres',
           'username.required' => 'Debe ingresar un nombre de usuario obligatoriamente',
           'username.max' => 'El nombre debe contener un maximo de 255 caracteres',
           'username.unique' => 'El nombre de usuario ya existe',
           'email.required' => 'Debe ingresar un correo electronico',
           'email.email' => 'Debe ingresar un formato valido de correo electronico',
           'email.unique' => 'Ya existe un usuario con el mismo correo electronico',
           'password.required' => 'Debe ingresar una contrasena',
           'password.confirmed' => 'Las contrasenas no coinciden',
           'password.min' => 'La contrasena debe contener un minimo de 6 caracteres',

        ]);

        $usuario = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'id_estado' => $request->id_estado,
            'password' => bcrypt($request->password),
        ]);

        Flash('El usuario ' . $usuario->name . ' fue creado exitosamente', 'success');

        return redirect()->route('users.index');  
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
        $usuario = User::find(Auth::id());
        $log = Log::create([
            'id_usuario' => $usuario->id,
            'id_estado' => $usuario->estado->id,
            'actividad' => 'Se edito informacion de a nivel de acceso del usuario: ' .  $user->name ,
        ]);

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

        $usuario_autenticado = User::find(Auth::id());
        $log = Log::create([
            'id_usuario' => $usuario_autenticado->id,
            'id_estado' => $usuario_autenticado->estado->id,
            'actividad' => 'Se elimino el usuario ' . $usuario->name,
        ]);

        Flash('El usuario ' . $usuario->name . ' fue eliminado exitosamente', 'success');

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

        $usuario = User::find(Auth::id());
        $log = Log::create([
            'id_usuario' => $usuario->id,
            'id_estado' => $usuario->estado->id,
            'actividad' => 'El usuario: ' . $usuario->name . ' actualizo su informacion personal',
        ]);

        flash('Su perfil fue actualizado correctamente', 'success');
        return redirect()->route('users.personal_edit');
    }
}
