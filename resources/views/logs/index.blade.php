@extends('layouts.app')

@section('title')
    ADN México | Historial
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')
	<?php $usuario = App\User::find(Illuminate\Support\Facades\Auth::id());?>
	<?php use Carbon\Carbon;?>
	<div class="card-block mt-3">
		<link rel="stylesheet" href="{{asset('css/datatables/dataTables.min.css')}}">
		<div class="container">
			<div class="card-title p-3 card-header">
				<img src="{{asset('images/roles.png')}}" alt="" width="80" height="80" class=""><span class="h4 ml-3 font-weight-bold"> Historial de cambios en el sistema </span>
			</div>
			<table id="myTable" class="table">
				<thead class="card-header bg-info text-white">
					<td class="d-none">id</td>
					<td>Usuario</td>
					<td>Estado</td>
					<td>Actividad realizada</td>
					<td>Fecha</td>
				</thead>
				<tbody>
					@foreach($logs as $log)
						<tr>
							<td class="d-none">{{$log->id}}</td>
							<td>{{$log->usuario->name}}</td>
							<td>{{$log->usuario->estado->nombre}}</td>
							<td>{{$log->actividad}}</td>
							<td>{{Carbon::parse($log->created_at)->format('Y-m-d')}}</td>
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
				      "url": "http://cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
				    }
				  });
				});
			</script>
		</div>
	</div>
@endsection