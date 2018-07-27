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
		<div class="card-title p-2 mb-3 card-header">
			<img src="{{asset('images/usuarios_gris.png')}}" alt="" width="60" height="70" class="ml-3"><span class="h4 ml-5"><b>EDITAR ROL DE PERFIL</b></span>
		</div>
	</div>	
	<div class="d-flex  flex-column align-content-center p-5">
		<div class="card-header p-3 bg-dark text-white">
			<h6>Detalle de usuario</h6>
		</div>
		<div class="card-info p-3">
			{!! Form::label('nombre' , 'Nombre')!!}
			{!! Form::text('nombre' , $user->name , [ 'class' => 'form-control', 'readonly'])!!}
			{!! Form::label('email' , 'Correo')!!}
			{!! Form::text('email' , $user->email , [ 'class' => 'form-control', 'readonly'])!!}
		
			{!! Form::label('username' , 'Nombre de Usuario')!!}
			{!! Form::text('username' , $user->username , [ 'class' => 'form-control', 'readonly'])!!}
		
			{!! Form::label('created_at' , 'Fecha de registro')!!}
			{!! Form::text('created_at' , $user->created_at , [ 'class' => 'form-control', 'readonly'])!!}
		</div>
	</div>
@endsection