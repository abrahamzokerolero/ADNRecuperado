@extends('layouts.app')

@section('title')
    ADN México | Editar Perfil
@endsection

@section('script')
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('content')
	
	<div class="card-block mt-3">
		<div class="container">
			<div class="card-title p-3 card-header mb-3">
				<img src="{{asset('images/group.png')}}" alt="" width="80" height="60" class="ml-3"><span class="h4 ml-5"><b>EDITAR PERFIL PERSONAL</b></span>
			</div>

			
			<p class="card-header">Actualizacion de datos</p>
		
			{!! Form::open(array('route' => ['users.personal_update',$usuario->id], 'method' => 'PUT')) !!}﻿

			<div class="card p-3">
				<div class="row">
					<div class="col">
						{!!Form::label('name', 'Nombre')!!}
						{!!Form::text('name', $usuario->name, ['class'=>'form-control'])!!}
					</div>
					<div class="col">
						{!!Form::label('apellido_paterno', 'Apellido paterno')!!}
						@if($usuario->apellido_paterno <> null)
							{!!Form::text('apellido_paterno',  $usuario->apellido_paterno  , ['class'=>'form-control'])!!} 
						@else
							{!!Form::text('apellido_paterno',  null  , ['class'=>'form-control'])!!} 
						@endif
					</div>
				</div>
				<div class="row">
					<div class="col">
						{!!Form::label('apellido_materno', 'Apellido materno')!!}
						@if($usuario->apellido_materno <> null)
							{!!Form::text('apellido_materno',  $usuario->apellido_materno  , ['class'=>'form-control'])!!} 
						@else
							{!!Form::text('apellido_materno',  null  , ['class'=>'form-control'])!!} 
						@endif
					</div>
					<div class="col">
						{!!Form::label('username', 'Nombre de usuario')!!}
						{!!Form::text('username',  $usuario->username  , ['class'=>'form-control', 'required', 'disabled'])!!} 
					</div>
				</div>

				<div class="row">
					<div class="col">
						{!!Form::label('telefono_particular', 'Telefono particular')!!}
						@if($usuario->telefono_particular <> null)
							{!!Form::text('telefono_particular',  $usuario->telefono_particular  , ['class'=>'form-control'])!!} 
						@else
							{!!Form::text('telefono_particular',  null  , ['class'=>'form-control'])!!} 
						@endif
					</div>
					<div class="col">
						{!!Form::label('telefono_celular', 'Telefono Celular')!!}
						@if($usuario->telefono_celular <> null)
							{!!Form::text('telefono_celular',  $usuario->telefono_celular  , ['class'=>'form-control'])!!} 
						@else
							{!!Form::text('telefono_celular',  null  , ['class'=>'form-control'])!!} 
						@endif
					</div>
				</div>
				<div class="row">
					<div class="col">
						{!!Form::label('direccion', 'Direccion')!!}
						@if($usuario->direccion <> null)
							{!!Form::text('direccion',  $usuario->direccion  , ['class'=>'form-control'])!!} 
						@else
							{!!Form::text('direccion',  null  , ['class'=>'form-control'])!!} 
						@endif
					</div>
					<div class="col">
						{!!Form::label('email', 'Email')!!}
						{!!Form::text('email',  $usuario->email  , ['class'=>'form-control','required', 'disabled'])!!} 
					</div>
				</div>

				<div class="form-group text-right">
					{!! Form::submit('Guardar', ['class' => 'mt-4 btn btn-primary']) !!}
				</div>
				{!! Form::close() !!}
				
			</div>
		
		</div>
	</div>
@endsection