@extends('layouts.app')

@section('title')
    ADN México | Roles
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('script')
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('content')
		<div class="d-flex  flex-column align-content-center">
			<div class="card-header">
				<h6>Detalle de usuario</h6>
			</div>
			<div class="card-info p-3">
				
				{!! Form::label('nombre' , 'Nombre')!!}
				{!! Form::text('nombre' , $rol->name , [ 'class' => 'form-control', 'readonly'])!!}

				{!! Form::label('slug' , 'Slug')!!}
				{!! Form::text('slug' , $rol->slug , [ 'class' => 'form-control', 'readonly'])!!}
			
				{!! Form::label('description' , 'Descripcion')!!}
				{!! Form::text('description' , $rol->description , [ 'class' => 'form-control', 'readonly'])!!}
			</div>
		</div>
@endsection