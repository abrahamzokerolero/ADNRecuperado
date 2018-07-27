@extends('layouts.app')

@section('title')
    ADN México | Busquedas
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')
	<div class="card-block mt-3">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
		<div class="container">
			<div class="card-title p-3 card-header">
				<img src="{{asset('images/busquedas_gris.png')}}" alt="" width="80" height="80" class=""><span class="h4 ml-3 font-weight-bold"> Lista de busquedas </span>
				<div class="float-right">
					@can('fuentes.create')
					<a href="{{route('busquedas.create')}}" class="btn btn-info float-right mr-3 mb-2"><i class="fa fa-plus-circle"></i> Nueva busqueda</a>
					@endcan
				</div>
			</div>
			<table id="myTable" class="table">
				<thead class="card-header bg-info text-white">
					<td>No.</td>
					<td>Fuente</td>
					<td>Motivo</td>
					<td>Descripcion</td>
					<td>Usuario</td>
					<td>Fecha</td>
				</thead>
				<tbody>
					@foreach($busquedas as $busqueda)
						<tr>
							<td><a href="{{route('busquedas.show', $busqueda->id)}}">{{$busqueda->identificador}}</a></td>
							<td>{{$busqueda->fuente->nombre}}</td>
							<td>{{$busqueda->motivo}}</td>
							<td>{{$busqueda->descripcion}}</td>
							<td>{{$busqueda->usuario->name}}</td>
							<td>{{$busqueda->created_at}}</td>
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