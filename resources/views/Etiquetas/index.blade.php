@extends('layouts.app')

@section('title')
    ADN México | Etiquetas
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')
	<?php $usuario = App\User::find(Illuminate\Support\Facades\Auth::id());?>
	<div class="card-title p-3 mb-3 card-header">
		<img src="{{asset('images/etiquetasSA.png')}}" alt="" width="80" height="70" class=""><span class="h4 ml-3 font-weight-bold"> ETIQUETAS SIN ASIGNAR</span>
		<div class="float-right">
			@can('categorias.index')
			<a href="{{route('categorias.index')}}" class="btn btn-secondary mt-2 ml-3"><i class="fa fa-chevron-left mr-2"></i>Ir a categorias y etiquetas</a>
			@endcan
		</div>
	</div>

	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
	<div class="container">
		<table id="myTable" class="table">
			<thead class="card-header bg-success text-white">
				<td>ID</td>
				<td>Nombre</td>
				<td class="text-right">Acciones</td>
			</thead>
			<tbody>
				@foreach($etiquetas as $etiqueta)
					<tr> 
						<td>{{$etiqueta->id}}</td>
						<td>{{$etiqueta->nombre}}</td>
						<td class="text-right">
							@if($usuario->estado->nombre == 'CNB')
								@can('etiquetas.destroy')
								<a href="{{ route('etiquetas.destroy', $etiqueta->id)}}"  onclick="return confirm('Desea eliminar la etiqueta seleccionada?' )" class="btn btn-danger">
									<i class="fa fa-times"></i>
								</a> 
								@endcan
							@endif
							@can('etiquetas.edit')
							<a href="{{ route('etiquetas.edit', $etiqueta->id)}}" class="btn btn-warning" >
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
			  	"order": [0, "desc"],
			    "language": {
			      "url": "http://cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
			    }
			  });
			});
		</script>
	</div>
@endsection