@extends('layouts.app')

@section('title')
    ADN México | Categorias
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')
	<?php $usuario = App\User::find(Illuminate\Support\Facades\Auth::id());?>
	@if(count($errors) > 0)
		<div class="alert alert-danger" role="alert">
			<ul>
				@foreach($errors->all() as $error)
					<li class="text-center">{{$error}}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<div class="card-title p-3 card-header">
		<img src="{{asset('images/etiquetas.png')}}" alt="" width="80" height="70" class=""><span class="h4 ml-3 font-weight-bold"> CATEGORIAS Y ETIQUETAS </span>
	</div>
	
	<div class="card-block">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">

		<!--Botones colapsables-->
		@if($usuario->estado->nombre == 'CNB')
		<div class="d-flex flex-row justify-content-between p-3">
			@can('categorias.store')
			<div class="p-2">
				<button class="btn btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapseCategoria" aria-expanded="false" aria-controls="collapseExample">
					<i class="fa fa-th-large"></i> Agregar categoria
				</button>
			</div>
			@endcan
			@can('etiquetas.store')
			<div class="p-2">
				<button class="btn btn-success" type="button" data-toggle="collapse" data-target="#collapseEtiquetas" aria-expanded="false" aria-controls="collapseExample">
					<i class="fa fa-bookmark"></i> Agregar Etiquetas
				</button>
			</div>
			@endcan
		</div>
		@endif
		
		<!--formulario para cateorias colapsable-->

		<div class="collapse float-left mb-2 ml-3" id="collapseCategoria">
		  <div class="card">
		  	<div class="card-header bg-secondary text-white">
		  		Crear nueva categoria
		  	</div>
		  	{!! Form::open(['route' => 'categorias.store', 'method'=> 'POST' ]) !!}
			<div class="p-3">
					<div class="form-group">
						{!! Form::label('nombre' , 'Nombre')!!}
						{!! Form::text('nombre' , null , [ 'class' => 'form-control', 'placeholder'=> 'Ingrese una categoria' , 'required'])!!}
					</div>
					<div class="form-group">
						{!! Form::submit('Guardar', ['class' => 'btn btn-primary mt-2']) !!}
					</div>
			</div>
			{!! Form::close() !!}
		  </div>
		</div>

		<!--Formulario para etiquetas colapsable-->

		<div class="collapse w-50 float-right mb-2 mr-3" id="collapseEtiquetas">
		  	<div class="card">
		  		<div class="card-header bg-success text-white">
			  		Crear etiquetas
			  	</div>
		  		{!! Form::open(['route' => 'etiquetas.store', 'method'=> 'POST' ]) !!}
	  				<div class="p-3">
	  						<div class="form-group">
	  							<p class="text-info border p-2">Pueden ser asignadas multiples etiquetas separandolas por una coma</p>
	  							{!! Form::label('nombre' , 'Nombre')!!}
	  							{!! Form::text('nombre' , null , [ 'class' => 'form-control', 'placeholder'=> 'Ejemplo 1, Ejemplo 2, Ejemplo 3' , 'required'])!!}
	  							<label for="categoria_id" class="mt-2">Categoria</label>
								<select name="categoria_id" class="form-control">
								  <option disabled selected>Seleccione una categoria</option>
								  @foreach($categorias as $categoria)
								  	<option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
								  @endforeach
								</select>
	  						</div>
	  						
	  						<div class="form-group">
	  							{!! Form::submit('Guardar', ['class' => 'btn btn-primary mr-3']) !!}
	  						</div>
	  				</div>
	  				{!! Form::close() !!}
			</div>
		</div>
		<div class="container">
			
			<table id="myTable" class="table">
				<thead class="card-header bg-secondary text-white">
					<td>ID Categoria</td>
					<td>Nombre</td>
					<td class="text-center">Etiquetas Registradas</td>
					<td>Acciones</td>
				</thead>
				<tbody>
					@foreach($categorias as $categoria)
						<tr>
							<td>{{$categoria->id}}</td>
							<td><a href="/categorias/{{$categoria->id}}">{{$categoria->nombre}}</a></td>
							<td class="text-center"><span class="btn btn-outline-success btn-sm disabled">{{App\Etiqueta::where('categoria_id', '=', $categoria->id)->where('desestimado', '=', 0)->count()}}</span></td>
							<td class="text-right">
								@if($usuario->estado->nombre == 'CNB')
									@can('categorias.destroy')
									<a href="{{ route('categorias.destroy', $categoria->id)}}"  onclick="return confirm('Desea eliminar la categoria seleccionada?' )" class="btn btn-danger btn-sm">
										<i class="fa fa-times"></i>
									</a>
									@endcan
								@endif
								@can('categorias.edit') 
								<a href="{{ route('categorias.edit', $categoria->id)}}" class="btn btn-warning btn-sm" >
									<i class="fa fa-pencil-square-o"></i>
								</a>
								@endcan
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
			<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
			<script>
				$(document).ready(function() {
				  $('#myTable').DataTable({
				  	"order": [ 0 , 'desc'],
				    "language": {
				      "url": "http://cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
				    }
				  });
				});
			</script>
		</div>
	</div>
@endsection