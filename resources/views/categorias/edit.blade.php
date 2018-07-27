@extends('layouts.app')

@section('title')
    ADN México | Editar categoria
@endsection

@section('script')
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('content')
<div class="container">
	
	<div class="card-title p-3 mb-3 card-header">
		<img src="{{asset('images/etiquetas.png')}}" alt="" width="80" height="70" class=""><span class="h4 ml-3 font-weight-bold"> EDITAR CATEGORIA</span>
		<div class="float-right">
			@can('categorias.index')
			<a href="{{route('categorias.index')}}" class="btn btn-secondary mt-2 ml-3"><i class="fa fa-chevron-left mr-2"></i>Volver a categorias</a>
			@endcan
		</div>
	</div>

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

	<div class="container w-100">
		<p class="card-header bg-secondary text-white">Actualizacion de datos</p>
	
		{!! Form::open(array('route' => ['categorias.update',$categoria->id], 'method' => 'PUT')) !!}﻿

		<div class="p-3">
				<div class="form-group">
				<p class="text-info ">Nota: El nombre debe ser diferente al actual</p>
					{!! Form::label('nombre' , 'Nombre')!!}
					{!! Form::text('nombre' , null , [ 'class' => 'form-control', 'placeholder'=> 'Nombre actual: ' .$categoria->nombre , 'required'])!!}
				</div>
		<div class="form-group text-center">
			{!! Form::submit('Guardar', ['class' => 'btn btn-primary mr-3']) !!}
			
			<div class="btn btn-primary"><a href="{{route('categorias.index')}}" class="text-white">Regresar</a></div>

		</div>
		{!! Form::close() !!}
		
	</div>
</div>

@endsection