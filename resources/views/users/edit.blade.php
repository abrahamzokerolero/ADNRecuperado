@extends('layouts.app')

@section('title')
    ADN México | Usuario {{$user->name}}
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->
@section('script')
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('content')

	<div class="container">
		<div class="card-title p-3 mb-3 card-header">
			<img src="{{asset('images/usuarios_gris.png')}}" alt="" width="80" height="80" class="ml-3"><span class="h4 ml-5"><b>EDITAR ROL DE PERFIL</b></span>
		</div>
	</div>

	<div class="container">
		@if(count($errors) > 0)
			<div class="alert alert-danger" role="alert">
				<ul>
					@foreach($errors->all() as $error)
						<li class="text-center">{{$error}}</li>
					@endforeach
				</ul>
			</div>
		@endif
		
		<div class="d-flex  flex-column align-content-center w-100">
			<div class="card-header">
				<h6>Detalle de usuario</h6>
			</div>
			<div class="card p-3">
				
				<?php $rol = Illuminate\Support\Facades\DB::table('role_user')
		            ->join('users', 'users.id', '=', 'role_user.user_id')
		            ->join('roles', 'roles.id', '=', 'role_user.role_id')
		            ->where('users.name', '=', $user->name)
		            ->select('roles.name' )
		            ->first();
		
		            if ($rol==null) {
		            	$rol_nombre = 'Sin rol asignado';
		            	
		            } 
		            else{
		            	$rol_nombre = $rol->name;
		            }
		        	?>
		
				{!! Form::open(array('route' => ['users.update',$user->id], 'method' => 'PUT'))!!}
		
				<div class="form-group">
					{!! Form::label('name' , 'Nombre')!!}
					{!! Form::text('name' , $user->name , [ 'class' => 'form-control', 'required'])!!}
				</div>
				<div class="form-group">
					<label for="categoria_id" class="mt-2">Estado</label>
						<select name="id_estado" class="form-control">
						<option disabled selected>Seleccione un estado para el usuario</option>
						  @foreach($estados as $estado)
						  	@if($user->id_estado == $estado->id)
								<option value="{{$estado->id}}" selected="selected">{{$estado->nombre}}</option>
	                    	@else
	                    		<option value="{{$estado->id}}">{{$estado->nombre}}</option>
	                    	@endif
						  @endforeach
						</select>
				</div>
				<div class="form-group">
					{!! Form::label('rol' , 'Rol actual
		
					')!!}
					{!! Form::text('rol' , $rol_nombre , [ 'class' => 'form-control', 'readonly'])!!}
				</div>
				
				<hr>
				<div class="card-footer">
					<h5>Asignar nuevo rol</h5>
				</div>
				
				<div class="form-control`">
					<ul class="list-unstyled">
						@foreach($roles as $role)
							<li>
								<label>{!! Form::checkbox('roles[]', $role->id, null) !!}
									<b>{{ $role->name}} : </b>
									<em>({{$role->description ?: 'Sin description'}})</em>
								</label>
							</li>
						@endforeach
					</ul>
				</div>
			</div>
			<div class="card-footer">
				{!!form::submit('Guardar cambios', ['class' => 'btn btn-primary'])!!}
			</div>
			{!!Form::close()!!}
		</div>
@endsection