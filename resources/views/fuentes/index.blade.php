@extends('layouts.app')

@section('title')
    ADN México | Fuentes
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')
	<?php $usuario = App\User::find(Illuminate\Support\Facades\Auth::id());?>
	<div class="card-block mt-3">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
		<div class="container">
			<div class="card-title p-3 card-header">
				<img src="{{asset('images/fuentes.png')}}" alt="" width="100" height="70" class=""><span class="h4 ml-3 font-weight-bold"> FUENTES </span>
				<div class="float-right">
					@can('fuentes.create')
					<a href="{{route('fuentes.create')}}" class="btn btn-info float-right mr-3 mb-2"><i class="fa fa-plus-circle"></i> Nueva fuente</a>
					@endcan
				</div>
			</div>
			<table id="myTable" class="table">
				<thead class="card-header bg-info text-white">
					<td>Nombre de fuentes</td>
					<td>Identificador interno</td>
					<td>Identificador externo</td>
					<td>Contacto</td>
					<td>Correo</td>
					<td>Telefono</td>
					<td>Opciones</td>
				</thead>
				<tbody>
					@foreach($fuentes as $fuente)
						<tr>
							<td>{{$fuente->nombre}}</td>
							<td>{{$fuente->id_interno}}</td>
							<td>{{$fuente->id_externo}}</td>
							<td>{{$fuente->contacto_fuente}}</td>
							<td>{{$fuente->correo_fuente}}</td>
							<td>{{$fuente->telefono1_fuente}}<br>{{$fuente->telefono2_fuente}}</td>
							<td>
								@can('fuentes.destroy')
									@if($usuario->estado->nombre == 'CNB')
										<a href="{{ route('fuentes.destroy', $fuente->id)}}"  onclick="return confirm('Desea eliminar la fuente seleccionada?' )" class="btn btn-danger btn-sm">
											<i class="fa fa-times"></i>
										</a>
									@endif 
								@endcan
								@can('fuentes.edit')
								<a href="{{ route('fuentes.edit', $fuente->id)}}" class="btn btn-warning btn-sm" >
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