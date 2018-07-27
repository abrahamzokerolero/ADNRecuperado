@extends('layouts.app')

@section('title')
    ADN México | Crear nuevo rol
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

	<div class="container w-75">
		<p class="card-header"><b>Crear nuevo rol</b></p>
		{!! Form::open(['route' => 'roles.store', 'method'=> 'PUT' ]) !!}
		<div class="p-3">
				<divv class="form-group">
					{!! Form::label('name' , 'Nombre del Rol')!!}
					{!! Form::text('name' , null , [ 'class' => 'form-control', 'placeholder'=> 'Ingrese un nombre para le nuevo rol' , 'required'])!!}

					{!! Form::label('slug' , 'URL amigable')!!}
					{!! Form::text('slug' , null , [ 'class' => 'form-control', 'placeholder'=> 'Ingrese un nombre amigable para el rol' , 'required'])!!}

					{!! Form::label('description' , 'Descripcion')!!}
					{!! Form::text('description' , null , [ 'class' => 'form-control', 'placeholder'=> 'Ingrese una descripcion del rol'])!!}
				</div>
				<div class="form-group">
					<hr>
					<h5>Acceso especial</h5>
					<label>{{Form::radio('especial', 'all-access')}} Acceso Total</label>
					<label>{{Form::radio('especial', 'all-access')}} Sin acceso</label>
				</div>
				<div class="card-footer">
				<h5>Asignar permisos por modulo</h5>
				</div>
				
				<div class="form-control`">
					<ul class="list-unstyled">
						@foreach($permissions as $permission)
							<li>
								<label>{!! Form::checkbox('permissions[]', $permission->id, null) !!}
									<b>{{ $permission->name}} : </b>
									<em>({{ $permission->description ?: 'Sin description'}})</em>
								</label>
							</li>
						@endforeach
					</ul>
				</div>
		</div>

		<div class="form-group text-center">
			{!! Form::submit('Guardar', ['class' => 'btn btn-primary mr-3']) !!}
			
			<div class="btn btn-primary"><a href="{{route('roles.index')}}" class="text-white">Regresar</a></div>

		</div>
		{!! Form::close() !!}
	</div>
</div>

@endsection