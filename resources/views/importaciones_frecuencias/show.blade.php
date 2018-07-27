@extends('layouts.app')

@section('title')
    ADN México | Frecuencias en {{$frecuencias->last()->importacion_frecuencia->nombre}}
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')
	<div class="card-title p-3 mb-3card-header">
			<img src="{{asset('images/importar.png')}}" alt="" width="80" height="70" class=""><span class="h4 ml-3 font-weight-bold"> {{$frecuencias->last()->importacion_frecuencia->nombre}} </span>

			<div class="float-right">
				@can('fuentes.create')
				<a href="{{route('importaciones_frecuencias.index')}}" class="btn btn-warning float-left ml-3 mb-2"><i class="fa fa-chevron-left mr-2"></i> Regresar a la lista de importaciones</a>
				@endcan
			</div>
		</div>
	<div class="card-block mt-3">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
		<div class="container">
			<table id="myTable" class="table">
				<thead class="card-header bg-warning">
					<td>Marcador</td>
					<td>Alelo</td>
					<td>frecuencia</td>
					<td>Acciones</td>
				</thead>
				<tbody>
					@foreach($frecuencias as $frecuencia)
						<tr>
							<td>{{$frecuencia->marcador->nombre}}</td>
							<td>{{$frecuencia->alelo}}</td>
							<td>{{$frecuencia->frecuencia}}</td>
							<td class="float-right">
								@can('frecuencias.destroy')
								<a href="{{ route('frecuencias.destroy', $frecuencia->id)}}"  onclick="return confirm('Desea eliminar el marcador seleccionado?' )" class="btn btn-danger btn-sm">
									<i class="fa fa-times"></i>
								</a> 
								@endcan
								@can('frecuencias.edit')
								<a href="{{ route('frecuencias.edit', $frecuencia->id)}}" class="btn btn-warning btn-sm" >
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
				    "language": {
				      "url": "http://cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
				    }
				  });
				});
			</script>
		</div>
	</div>
@endsection