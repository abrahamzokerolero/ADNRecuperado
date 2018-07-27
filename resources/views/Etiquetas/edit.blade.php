@extends('layouts.app')

@section('title')
    ADN México | Editar etiqueta
@endsection

@section('script')
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('content')
<div class="container">

	<div class="card-title p-3 mb-3 card-header">
		<img src="{{asset('images/etiquetas.png')}}" alt="" width="80" height="70" class=""><span class="h4 ml-3 font-weight-bold"> EDITAR ETIQUETA</span>
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

	<!-- Formulario edicion de una etiqueta-->

	<div class="container w-75">
		<p class="card-header bg-success">Actualizacion de datos</p>
	
		{!! Form::open(array('route' => ['etiquetas.update',$etiqueta->id], 'method' => 'PUT')) !!}﻿

		<div class="p-3">
			<div class="form-group">
				{!! Form::label('nombre' , 'Nombre')!!}
				{!! Form::text('nombre' , $etiqueta->nombre , [ 'class' => 'form-control', 'required'])!!}
				<label for="categoria_id" class="mt-2">Categoria</label>
                <select name="categoria_id" class="form-control">
                    <option disabled selected>Seleccione una categoria</option>
                    @foreach($categorias as $categoria)
                    	@if($etiqueta->categoria_id == $categoria->id)
							<option value="{{$categoria->id}}" selected="selected">{{$categoria->nombre}}</option>
                    	@else
                    		<option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
                    	@endif
                    @endforeach
                </select>
			</div>

			<div class="form-group text-right">
				{!! Form::submit('Guardar', ['class' => 'btn btn-primary mr-3']) !!}
			</div>
			{!! Form::close() !!}
			
		</div>
	</div>

@endsection