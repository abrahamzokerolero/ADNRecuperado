@extends('layouts.app')

@section('title')
    ADN México | Edicion de Rol
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->


@section('content')

	@if(count($errors) > 0)
		<div class="alert alert-danger" role="alert">
			<ul>
				@foreach($errors->all() as $error)
					<li class="text-center">{{$error}}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<div class="d-flex  flex-column align-content-center w-75">
		<div class="card-header">
			<h6>Edicion de Rol</h6>
		</div>
		<div class="card p-3">
			
			{!! Form::open(array('route' => ['users.update',$user->id], 'method' => 'PUT'))!!}

			<div class="form-group">
				{!! Form::label('name' , 'Nombre')!!}
				{!! Form::text('name' , $user->name , [ 'class' => 'form-control', 'required'])!!}
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
							<label>{!! Form::radio('roles[]', $role->id) !!}
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
@endsection