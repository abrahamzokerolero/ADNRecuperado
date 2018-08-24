@extends('layouts.app')

@section('title')
    ADN México | Usuarios
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')
	<?php use Carbon\Carbon;?>
	<div class="card-block mt-3">
		<link rel="stylesheet" href="{{asset('css/datatables/dataTables.min.css')}}">
		<div class="container">
			<div class="card-title p-3 card-header">
				<img src="{{asset('images/group.png')}}" alt="" width="80" height="60" class="ml-3"><span class="h4 ml-5"><b>USUARIOS REGISTRADOS</b></span>
				@can('users.create')
				<div class="float-right">
					<a href="{{route('users.create')}}" class="btn btn-primary mt-3 ml-2 mb-2"><i class="fa fa-plus-circle"></i> Registrar nuevo usuario</a>
				</div>
				@endcan	
			</div>
			<table id="myTable" class="table">
				<thead class="card-header bg-dark text-white">
					<td>Nombre</td>
					<td>Email</td>
					<td>Fecha de creacion</td>
					<td>Acciones</td>
				</thead>
				<tbody>
					@foreach($usuarios as $usuario)
						<tr>
							<td><a href="users/{{$usuario->id}}" >{{$usuario->name}}</a></td>
							<td>{{$usuario->email}}</td>
							<td>{{Carbon::parse($usuario->created_at)->format('d/m/Y')}}</td>
							<td>
								@can('users.destroy')
								<a href="{{ route('users.destroy', $usuario->id)}}"  onclick="return confirm('Desea eliminar el usuario seleccionado?' )" class="btn btn-danger btn-sm">
									<i class="fa fa-times"></i>
								</a> 
								@endcan
								@can('users.edit')
								<a href="{{ route('users.edit', $usuario->id)}}" class="btn btn-warning btn-sm" >
									<i class="fa fa-pencil-square-o"></i>
								</a>
								@endcan
							</td>
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