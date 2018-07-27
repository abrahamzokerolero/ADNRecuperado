@extends('layouts.app')

@section('title')
    ADN México | Editar marcador
@endsection

@section('script')
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('content')
<div class="container">

	<!-- Codigo de muestra de errores traidos desde las condiciones del Request de categorias -->
	@if(count($errors) > 0)
		<div class="alert alert-danger" role="alert">
			<ul>
				@foreach($errors->all() as $error)
					<li class="text-center">{{$error}}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<!-- Formulario de ingreso de una nueva categoria-->

	<div class="container w-50">
		<p class="card-header">Actualizacion de datos</p>
	
		{!! Form::open(array('route' => ['marcadores.update',$marcador->id], 'method' => 'PUT')) !!}﻿

		<div class="p-3">
				<div class="form-group">
				<p class="text-info ">Nota: El nombre debe ser diferente al actual</p>
					{!! Form::label('nombre' , 'Nombre')!!}
					{!! Form::text('nombre' , null , [ 'class' => 'form-control', 'placeholder'=> 'Nombre actual: ' .$marcador->nombre , 'required'])!!}
				</div>
		<div class="form-group text-center">
			{!! Form::submit('Guardar', ['class' => 'btn btn-primary mr-3']) !!}
			
			<div class="btn btn-primary"><a href="{{route('marcadores.index')}}" class="text-white">Regresar</a></div>

		</div>
		{!! Form::close() !!}
		
	</div>
</div>

@endsection