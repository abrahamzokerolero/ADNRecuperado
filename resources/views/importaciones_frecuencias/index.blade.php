@extends('layouts.app')

@section('title')
    ADN México | Importaciones Frecuencias
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')
	<?php use Carbon\Carbon;?>
	<div class="container">
		<div class="card-title p-3 mb-3card-header">
			<img src="{{asset('images/importar.png')}}" alt="" width="80" height="70" class=""><span class="h4 ml-3 font-weight-bold"> LISTA DE IMPORTACIONES DE FRECUENCIAS </span>

			<div class="float-right">
				@can('importaciones_frecuencias.create')
				<div class="mb-3">
					<button class="btn btn btn-warning" type="button" data-toggle="collapse" data-target="#collapseEnvioDeArchivo" aria-expanded="false" aria-controls="collapseEnvioDeArchivo">
						<i class="fa fa-arrow-circle-o-up"></i> Importar tabla de frecuencias
					</button>
				</div>
				@endcan
			</div>
		</div>
		<link rel="stylesheet" href="{{asset('css/datatables/dataTables.min.css')}}">
		<div class="row">
			<div class="container">
				
				<div class="collapse w-100 float-left mb-2" id="collapseEnvioDeArchivo">
				  <div class="card border">
					  <div class="card-header"> 
					    <ul class="nav nav-tabs card-header-tabs pull-left"  id="myTab" role="tablist">
					      <li class="nav-item">
					       <a class="nav-link active " id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Descargar formato</a>
					      </li>
					      <li class="nav-item">
					        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Subir tabla de frecuencias</a>
					      </li>
					    </ul>
					  </div>
					  <div class="card-body border">
					   <div class="tab-content" id="myTabContent">
					        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
					        	<div class=" card-header bg-warning">
					        		Importante!! Requisitos para subir su archivo
					        	</div>
					        	<div class="card-body text-justify">
					        		<p>Para comenzar el proceso de carga de su archivo al sistema debera descargar el formato ofrecido por la plataforma, el cual no debera modificar a menos que sea para agregar nuevos marcadores e ingresar las frecuencias correspondientes a cada uno de los alelos. Se recomienda verificar la informacion antes de subirla al sistema. El formato lo podra obtener haciendo clic en el siguiente boton que se presenta a continuacion</p>
			
					        		<div>
					        			<a class="btn btn-primary" href="{{asset('formatos/TablaFrecuenciasAlelicas.xlsx')}}"><i class="fa fa-download"></i> Obtener formato de importacion de frecuencias</a>
					        		</div>
					        	</div>
					        </div>
					  		<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
								<div class="card-block">
									<div class=" card-header bg-warning">
						        		<b>Cargar archivo</b>
						        	</div>
									<div class="card-body">{!! Form::open(['route' => 'importaciones_frecuencias.store', 'method'=> 'POST', 'enctype' => 'multipart/form-data' ]) !!}
										<p>
											Si desea hacer la carga de su archivo al sistema debera seleccionar el boton examinar donde podra elegir su archivo desde su directorio personal. Para concluir la operacion debera hacer click en el boton "Importar". Los formatos soportados por el sistema son xls y xlsx correspondientes a la plataforma de excel. No se aceptan otros formatos.
										</p>
										<div>
											<div class="form-group">
												{!!Form::label('nombre_otorgado', 'Agregar nombre a la tabla de frecuencias')!!}
												{!!Form::text('nombre_otorgado', null, ['class' => 'form-control', 'required'] )!!}
			
												{!! Form::file('archivo', ['required', 'files' => 'true', 'class' => 'mt-3'])!!}
												{!! Form::submit('Importar', ['class' => 'btn btn-primary ml-3']) !!}
											</div>
											<div class="form-group">
												
											</div>
										</div>
										{!! Form::close() !!} 
									</div>
								</div>
					  		</div>
					    </div>
					  </div>
					</div>
				  </div>
				</div>
		</div>

			<table id="myTable" class="table">
				<thead class="card-header bg-warning">
					<td>ID Importacion</td>
					<td>Nombre</td>
					<td>Archivo</td>
					<td>Usuario</td>
					<td>Fecha de importacion</td>
					<td class="text-center">Archivo</td>
					<td>Asignar por defecto</td>
					<td class="text-center">Acciones</td>
				</thead>
				<tbody>
					@foreach($importaciones as $importacion)
						<tr>
							<td><a href="{{ route('importaciones_frecuencias.show', $importacion->id)}}">{{$importacion->identificador}}</a></td>
							@if($importacion->tabla_default == 1)
								<td><b style="color: #117A65;">{{$importacion->nombre_otorgado}}</b></td>
							@else
								<td>{{$importacion->nombre_otorgado}}</td>
							@endif
							<td>{{$importacion->nombre}}</td>
							<td>{{$importacion->usuario->name}}</td>
							<td>{{Carbon::parse($importacion->created_at)->format('d/m/Y')}}</td>
							<td class="text-center">
								<a href="{{'storage/'. $importacion->nombre}}" class="btn btn-info btn-sm text-center"><i class="fa fa-download"></i></a>
							</td>
							<td class="text-center">
								<a href="{{ route('importaciones_frecuencias.default', $importacion->id)}}"  onclick="return confirm('Asignar tabla de frecuencias como la principal' )" class="btn btn-success btn-sm">
									<i class="fa fa-crosshairs"></i>
								</a>
							</td>
							<td class="text-center">									 
									@can('importaciones_frecuencias.destroy')
									<a href="{{ route('importaciones_frecuencias.destroy', $importacion->id)}}"  onclick="return confirm('Desea eliminar la importacion de frecuencias seleccionada?' )" class="btn btn-danger btn-sm">
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