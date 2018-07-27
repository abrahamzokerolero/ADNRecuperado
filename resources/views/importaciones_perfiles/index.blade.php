@extends('layouts.app')

@section('title')
    ADN México | Importaciones Perfiles
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')
	<?php $usuario = App\User::find(Illuminate\Support\Facades\Auth::id());?>
	<div class="card-block">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
		<div class="container">
			<div class="card-title p-3 card-header">
				<img src="{{asset('images/importar.png')}}" alt="" width="80" height="70" class=""><span class="h4 ml-3 font-weight-bold"> LISTA DE IMPORTACIONES </span>
				
				<div class="float-right">
					@can('perfiles_geneticos.create')
					<a href="{{route('perfiles_geneticos.create')}}" class="mr-3 mt-2 btn btn-warning "><i class="fa fa-list-ul"></i> Captura manual</a>
					@endcan
					@can('importaciones_perfiles.create')
					<a href="{{route('importaciones_perfiles.create')}}" class="mt-2 btn btn-warning "><i class="fa fa-plus-circle"></i> Nueva importacion de perfiles</a>
					@endcan
				</div>
				
			</div>

			<table id="myTable" class="table">
				<thead class="card-header bg-warning ">
					<td>Fuente</td>
					<td>ID de Importacion</td>
					<td>Fecha de importacion</td>
					<td class="text-center">Genotipos importados</td>
					<td class="text-center">Usuario</td>
					<td class="text-center">Titulo</td>
					<td>Archivo</td>
					<td>Acciones</td>
				</thead>
				<tbody>
					@foreach($importaciones as $importacion)
						<tr>
							<td>{{$importacion->fuente->nombre}}</td>
							
							<td><a href="{{ route('importaciones_perfiles.show', $importacion->id)}}">{{$importacion->identificador}}</a></td>
							
							<td>{{$importacion->created_at}}</td>
							
							<td class="text-center"><a href="" class="btn btn-outline-primary btn-sm disabled">{{$importacion->numero_de_perfiles}}</a></td>

							<td>{{$importacion->usuario->name}}</td>
							<td>{{$importacion->titulo}}</td>
							
							<td class="text-center"><a href="{{asset('storage/'. $importacion->nombre)}}" class="btn btn-info btn-sm"><i class="fa fa-download"></i></a>
								</td>
							
							<td class="text-center">
								@if($usuario->estado->nombre == 'CNB')
									@can('importaciones_frecuencias.destroy')
									<a href="{{ route('importaciones_perfiles.destroy', $importacion->id)}}"  onclick="return confirm('Desea eliminar la importacion de perfiles seleccionada, se eliminaran todos los perfiles asociados asi como sus metadatos?' )" class="btn btn-danger btn-sm">
										<i class="fa fa-times"></i>
									</a> 
									@endcan
								@endif
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
				  	"order": [ 1 , 'desc'],
				    "language": {
				      "url": "http://cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
				    }
				  });
				});
			</script>
		</div>
	</div>
@endsection