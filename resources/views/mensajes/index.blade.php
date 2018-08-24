@extends('layouts.app')

@section('title')
    ADN México | Mensajes
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')
	<?php use Carbon\Carbon;  $usuario = App\User::find(Illuminate\Support\Facades\Auth::id());?>
	<div class="card-block mt-3">
		<link rel="stylesheet" href="{{asset('css/datatables/dataTables.min.css')}}">
		<div class="container">
			<div class="card-title p-3 card-header">
				<img src="{{asset('images/mensaje.png')}}" alt="" width="100" height="70" class=""><span class="h4 ml-3 font-weight-bold"> Mensajes enviados </span>
			</div>
			<table id="myTable" class="table">
				<thead class="card-header bg-info text-white">
					<td hidden>Id</td>
					<td>Remitente</td>
					<td>Destinatario</td>
					<td>ID de busqueda</td>
					<td>Mensaje</td>
					<td>Estatus</td>
					<td>Fecha de envio</td>
					<td class="text-center">Acciones</td>
				</thead>
				<tbody>
					@foreach($mensajes as $mensaje)
						<tr>
							<td hidden>{{$mensaje->id}}</td>
							<td>{{$mensaje->usuario_envia->name}}</td>
							<td>{{$mensaje->estado_recibe->nombre}}</td>
							<td><a href="{{route('busquedas.show', $mensaje->busqueda_resultado->id)}}">{{$mensaje->busqueda_resultado->identificador}}</a></td>
							<td>{{$mensaje->mensaje}}</td>
							@if($mensaje->revisado == 0)
							<td><span class="btn btn-warning btn-sm disabled">PENDIENTE</span></td>
							@else
							<td><span class="btn btn-success btn-sm disabled">REVISADO</span></td>
							@endif
							<td>{{Carbon::parse($mensaje->created_at)->format('d/m/Y')}}</td>
							@can('mensajes.destroy2')
								<td class="text-center"><a class="btn btn-danger btn-sm" href="{{route('mensajes.destroy2', $mensaje->id)}}">Borrar</a></td>
							@else
								<td class="text-center"><a class="btn btn-danger btn-sm disabled" href="">Req.Permisos</a></td>
							@endcan
						</tr>
					@endforeach
				</tbody>
			</table>
			<script src="{{asset('js/jquery-3.3.1.js')}}"></script>
			<script src="{{asset('js/datatables/dataTables.min.js')}}"></script>
			<script>
				$(document).ready(function() {
				  $('#myTable').DataTable({
				  	"order": [ 0 , 'desc'],				  	
				    "language": {
				      "url": "http://cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json",
				    columnDefs: [{"className": "dt-center", "targets": "_all"}, {
		                "targets": [ 0 ],
		                "visible": false,
		                "searchable": false,
		            },],
				    }
				  });
				});
			</script>
		</div>
	</div>
@endsection