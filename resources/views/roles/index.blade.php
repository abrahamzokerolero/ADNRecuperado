@extends('layouts.app')

@section('title')
    ADN México | Roles
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')
	
	<div class="card-block mt-3">
		<link rel="stylesheet" href="{{asset('css/datatables/dataTables.min.css')}}">
		<div class="container">
			<div class="card-title p-3 card-header">
				<img src="{{asset('images/roles.png')}}" alt="" width="80" height="70" class=""><span class="h4 ml-3 font-weight-bold"> ROLES</span>
				<div class="float-right">
					@can('roles.create')
					<a href="{{route('roles.create')}}" class="btn btn-primary mt-3 ml-2 mb-2"><i class="fa fa-plus-circle"></i> Añadir nuevo rol</a>
					@endcan
				</div>	
			</div>
			<div class="flex-row">
				
			</div>
			<table id="myTable" class="table">
				<thead class="card-header bg-dark text-white">
					<td>Nombre</td>
					<td>Slug</td>
					<td>Descripcion</td>
					<td>Acciones</td>
				</thead>
				<tbody>
					@foreach($roles as $rol)
						<tr>
							<td><a href="roles/{{$rol->id}}" >{{$rol->name}}</a></td>
							<td>{{$rol->slug}}</td>
							<td>{{$rol->description}}</td>
							<td class="float-right">
								@can('roles.destroy')
								<a href="{{ route('roles.destroy', $rol->id)}}"  onclick="return confirm('Desea eliminar el rol seleccionado?, si realiza esta accion los usuarios con dicho rol deberan asignados a uno nuevo' )" class="btn btn-danger btn-sm">
									<i class="fa fa-times"></i>
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