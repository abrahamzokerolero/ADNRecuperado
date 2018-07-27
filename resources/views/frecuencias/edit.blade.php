@extends('layouts.app')

@section('title')
    ADN México | Editar frecuencia
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
	
	<div class="card-title p-3 mb-3 card-header">
			<img src="{{asset('images/importar.png')}}" alt="" width="80" height="70" class=""><span class="h4 ml-3 font-weight-bold"> EDICION DE FRECUENCIA </span>

			<div class="float-right">
				@can('importaciones_frecuencias.create')
				<div class="mb-3">
					<button class="btn btn btn-warning" type="button" data-toggle="collapse" data-target="#collapseEnvioDeArchivo" aria-expanded="false" aria-controls="collapseEnvioDeArchivo">
						<i class="fa fa-arrow-circle-o-up"></i> Importar tabla de frecuencias
					</button>
				</div>
				@endcan
			</div>
		</div>

	<!-- Formulario edicion de una etiqueta-->

	<div class="container">
		<p class="card-header bg-warning">Actualizacion de datos</p>
	
		{!! Form::open(array('route' => ['frecuencias.update',$frecuencia->id], 'method' => 'PUT')) !!}﻿

		<div class="p-3">
			<div class="form-group">
				{!! Form::label('marcador' , 'Marcador')!!}
					{!! Form::text('marcador' , $frecuencia->marcador->nombre , [ 'class' => 'form-control', 'placeholder'=> 'Ingrese el marcador' , 'required'])!!}

					{!! Form::label('alelo' , 'Alelo')!!}
					{!! Form::text('alelo' , $frecuencia->alelo , [ 'class' => 'form-control', 'placeholder'=> 'Ingrese el numero de Alelo' , 'required'])!!}

					{!! Form::label('frecuencia' , 'Identificador externo')!!}
					{!! Form::text('frecuencia' , $frecuencia->frecuencia , [ 'class' => 'form-control', 'placeholder'=> 'Ingrese el valor de la frecuencia', 'required'])!!}
			</div>

			<div class="form-group text-right">
				{!! Form::submit('Guardar', ['class' => 'btn btn-primary mr-3']) !!}
				<div class="btn btn-primary"><a href="{{route('importaciones_frecuencias.show', $frecuencia->importacion_frecuencia->id)}}" class="text-white">Regresar</a></div>
			</div>
			{!! Form::close() !!}
		</div>
		
	</div>

@endsection