@extends('layouts.app')

@section('title')
    ADN México | Crear Fuente
@endsection

@section('script')
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('content')
<div class="container">
	
	<div class="card-title p-3 mb-5 card-header">
		<img src="{{asset('images/fuentes.png')}}" alt="" width="100" height="70" class=""><span class="h4 ml-3 font-weight-bold"> CREACION DE FUENTE </span>
		<div class="float-right">
			@can('fuentes.create')
			<a href="{{route('fuentes.index')}}" class="btn btn-info float-right mr-3 mb-2"><i class="fa fa-chevron-left mr-2"></i> Regresar a la lista de fuentes</a>
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
	<div class="container w-75">
		
		<p class="card-header bg-info"><b>Crear fuente</b></p>
		{!! Form::open(['route' => 'fuentes.store', 'method'=> 'POST' ]) !!}
		<div class="p-3">
				<divv class="form-group">
					{!! Form::label('nombre' , 'Nombre de fuente')!!}
					{!! Form::text('nombre' , null , [ 'class' => 'form-control', 'placeholder'=> 'Ingrese un nombre asociado a la fuente' , 'required'])!!}

					{!! Form::label('id_interno' , 'Identificador interno')!!}
					{!! Form::text('id_interno' , null , [ 'class' => 'form-control', 'placeholder'=> 'Ingrese un identificador interno' , 'required'])!!}

					{!! Form::label('id_externo' , 'Identificador externo')!!}
					{!! Form::text('id_externo' , null , [ 'class' => 'form-control', 'placeholder'=> 'Ingrese un identificador externo'])!!}

					{!! Form::label('contacto_fuente' , 'Nombre de contacto')!!}
					{!! Form::text('contacto_fuente' , null , [ 'class' => 'form-control', 'placeholder'=> 'Ingrese un nombre de contacto'])!!}

					{!! Form::label('correo_fuente' , 'Correo de contacto')!!}
					<input type="email" name="correo_fuente" class="form-control" placeholder="Ingrese el correo de contacto">	


					{!! Form::label('telefono1_fuente' , 'Telefono de contacto')!!}
					{!! Form::text('telefono1_fuente' , null , [ 'class' => 'form-control', 'placeholder'=> 'Ingrese un telefono de contacto'])!!}

					{!! Form::label('telefono2_fuente' , 'Otro numero de contacto')!!}
					{!! Form::text('telefono2_fuente' , null , [ 'class' => 'form-control', 'placeholder'=> 'Ingrese un telefono alternativo'])!!}
				</div>
				
				
		</div>
		<div class="form-group text-center">
			{!! Form::submit('Guardar', ['class' => 'btn btn-primary mr-3']) !!}
			
			<div class="btn btn-primary"><a href="{{route('fuentes.index')}}" class="text-white">Regresar</a></div>

		</div>
		{!! Form::close() !!}
		
	</div>
</div>

@endsection
