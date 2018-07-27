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
				<img src="{{asset('images/busquedas_gris.png')}}" alt="" width="80" height="80" class=""><span class="h4 ml-3 font-weight-bold"> Resultados de Busqueda  </span>
				<div class="float-right">
					@can('busquedas.create')
					<a href="{{route('busquedas.create')}}" class="btn btn-info float-right mr-3 mb-2"><i class="fa fa-plus-circle"></i>Nueva busqueda</a>
					@endcan
				</div>
			</div>
			<!-- Modal para metadatos de ambos perfiles-->
			<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
			  <div class="modal-dialog modal-lg" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="exampleModalLongTitle">Comparativo de Metadatos</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			        <div class="row">
	               		<div class="col">
	               			<div class="metadatos_perfil_objetivo">
	               				<div class="card-header bg-danger text-white text-center"> Metadatos del perfil objetivo</div>
	               				<div class="mt-2 form-group">
									@foreach($busqueda->resultados[0]->perfil_objetivo->metadatos as $metadato)
									<div class="row pl-3 pr-3 pt-3">
									    <div class="col">
									      <label for="{{$metadato->tipo_de_metadato->nombre}}" class="">{{str_replace('_',' ',ucwords($metadato->tipo_de_metadato->nombre))}}</label>
										  <input type="text" name="{{$metadato->tipo_de_metadato->nombre}}" value="{{$metadato->dato}}" disabled class="form-control">
									    </div>
									</div>
									@endforeach
								</div>
	               			</div>
	               		</div>
	               		<div class="col">
	               			<div class="bg-danger text-white text-center">
	               				<div class="card-header"> Metadatos del perfil Compatible</div>
	               				<div class="mt-2 form-group metadatos_perfil_compatible">
	               					<!--Se rellena con JQuery-->
	               				</div>	
	               			</div>
	               		</div>
	               	</div>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			      </div>
			    </div>
			  </div>
			</div>

			<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog" role="document">
			  	{!! Form::open(array('route' => ['busquedas.concluir',$busqueda->id], 'method' => 'POST')) !!}﻿
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="exampleModalLabel">Conclusiones y estatus de la busqueda</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			          <div class="form-group">
			            <label for="id_estatus_busqueda" class="col-form-label">Cambiar estatus de la Busqueda</label>
			            <select name="id_estatus_busqueda" class="form-control" required>
						  <option disabled selected>Seleccione un estatus</option>
						  @foreach($estatus_disponibles as $estatus)
						  	<option value="{{$estatus->id}}">{{strtoupper($estatus->nombre)}}</option>
						  @endforeach
						</select>
			          </div>
			          <div class="form-group">
			            <label for="conclusiones" class="col-form-label">Agregar conclusiones</label>
			            <textarea class="form-control" name="conclusiones" required></textarea>
			          </div>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			        <input type="submit" class="btn btn-primary" value="Guardar cambios">
			      </div>
			    {!!Form::close()!!} 
			    </div>
			  </div>
			</div>

			<div class="mt-5">
				<div class="row">
					<div class="col">
						<div class="card">
							<div class='card-header bg-info text-white'><b>DETALLES DE LA BUSQUEDA</b></div>
							<div class="container">
								<p class="m-0"><b>Fuente solicitante:</b> <span class="float-right">{{$busqueda->fuente->nombre}}</span></p>
								<p class="m-0"><b>Usuario:</b> <span class="float-right">{{strtoupper($busqueda->usuario->name)}}</span></p>
								<p class="m-0"><b>Fecha:</b> <span class="float-right">{{$busqueda->created_at}}</span></p>
								<p class="m-0"><b>Motivo de la busqueda:</b> <span class="float-right">{{strtoupper($busqueda->motivo)}}</span></p>
								<p class="m-0"><b>Descripcion de la  busqueda:</b> <span class="float-right">{{strtoupper($busqueda->descripcion)}}</span></p>
								@if($busqueda->conclusiones == null)
									<p class="mt-2 pt-2 pb-2"><b class="text-warning">No se han agregado conclusiones</b>
								@else
									<p class="mt-2 pt-2 pb-2"><b>Conclusiones:</b>	
								@endif 
									<span class="float-right">
										@if($busqueda->conclusiones == null)
											<button type="button" class="btn btn-warning text-white" data-toggle="modal" data-target="#exampleModal">
												<i class="fa fa-pencil mr-2"></i> Agregar conclusiones
											</button>
										@else
											{{strtoupper($busqueda->conclusiones)}}
										@endif
									</span>
								</p>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="card">
							<div class='card-header bg-info text-white'><b>CONDICIONES FILTRO DE LA BUSQUEDA</b></div>
							<div class="container">
								<p class="m-0"><b>Marcadores minimos:</b> <span class="float-right">{{$busqueda->marcadores_minimos}}</span></p>
								<p class="m-0"><b>Exclusiones maximas:</b> <span class="float-right">{{strtoupper($busqueda->numero_de_exclusiones)}}</span></p>
								<p class="m-0"><b>Tabla de frecuencias usada:</b> <span class="float-right">{{strtoupper($busqueda->tabla_de_frecuencias->nombre_otorgado)}}</span></p>
								<p class="m-0"><b>Perfiles en revision descartados:</b> <span class="float-right">{{strtoupper($busqueda->motivo)}}</span></p>
								<p class="m-0"><b>Etiquetas:</b> <span class="float-right">{{strtoupper($busqueda->descripcion)}}</span></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<hr class="mb-5 mt-5">

			<div class="mt-5">
				<div class="card-header bg-info text-white"><b>DETALLE DE RESULTADOS DE LA BUSQUEDA</b></div>
				<div class="row mt-3">
					<div class="col">
						<table id="myTable" class="table">
							<thead class="card-header bg-info text-white">
								<td class="text-center">Genotipo objetivo</td>
								<td class="text-center">Genotipo compatible</td>
								<td class="text-center">IP</td>
								<td class="text-center">PP</td>
								<td class="text-center">SMS</td>
							</thead>
							<tbody>
								@foreach($busqueda->resultados as $resultado)
									<tr>
										<td>
											<button type="button" class="btn btn-sm btn-primary boton_metadatos" id="{{$resultado->id}}">
											  {{$resultado->perfil_objetivo->identificador}}
											</button>
										</td>
										<td>
											<span class="btn btn-sm btn-secondary" id="{{$resultado->perfil_subordinado->id}}">{{$resultado->perfil_subordinado->identificador}}
											</span>
										</td>
										@if($resultado->IP == 0)
											<td class="text-center"><b>0</b></td>
											<td class="text-center"><b>Exclusiones = {{$resultado->exclusiones}}</b></td>
										@else
											<td>{{sprintf( '%E' ,$resultado->IP)}}</td>
											<td>{{$resultado->PP}}</td>
										@endif
										<td><span class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Mejora aun no disponible"><i class="fa fa-envelope"></i></span></td>
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
					<div class="col">
						<div class="card-header bg-info mb-3">
							<div class="text-white text-center">Comparativo por marcadores</div>
						</div>
						<div class="row">
							<div class="col">
								<table id="myTable" class="table">
									<thead class="card-header bg-danger text-white">
										<td>Marcadores</td>
										<td>Alelo 1</td>
										<td>Alelo 2</td>
									</thead>
									<tbody>
										@foreach($marcadores as $marcador)
											<tr class="marcador">
												<?php 
													// se le asigna un nombre a cada alelo para diferenciar los marcadores
													$alelo_1 = $marcador->nombre.'_alelo_1';
													$alelo_2 = $marcador->nombre.'_alelo_2';

													// se busca si ya existe el marcador para el perfil a modificar
													$marcador_individual = App\Alelo::where('id_perfil_genetico', '=', $busqueda->resultados[0]->perfil_objetivo->id)->where('id_marcador', '=', $marcador->id)->first();

													// si no existe el marcador se deja en blanco el campo
													if(empty($marcador_individual)){
														$marcador_alelo_1 = "";
														$marcador_alelo_2 = "";
													}
													// si ya existe el marcador en el perfil se extraen los datos
													else{
														$marcador_alelo_1 = $marcador_individual->alelo_1;
														$marcador_alelo_2 = $marcador_individual->alelo_2;	
													}
												?>	
												<!-- Se muestra el marcador-->
												<td>{{$marcador->nombre}}</td>
												<td>
													{!!Form::text($alelo_1, $marcador_alelo_1, ['class' => "text-center w-100 $alelo_1",'disabled'])!!}
												</td>
												<td>
													{!!Form::text($alelo_2, $marcador_alelo_2, ['class' => "text-center w-100 $alelo_2", 'disabled'])!!}
												</td>
											</tr>
											@endforeach
									</tbody>
								</table>
								</table>
							</div>
							<div class="col">
								<table id="myTable3" class="table">
									<thead class="card-header bg-danger text-white">
										<td>Marcadores</td>
										<td>Alelo 1</td>
										<td>Alelo 2</td>
									</thead>
									<tbody>
										@foreach($marcadores as $marcador)
										<tr class="marcador">
											<?php 
												$alelo_1 = $marcador->nombre.'_alelo_1';
												$alelo_2 = $marcador->nombre.'_alelo_2';
											?>	
								
											<td>{{$marcador->nombre}}</td>
											<td>{!!Form::text($alelo_1, null, ['class' => "text-center w-100 $alelo_1" , 'disabled'])!!}</td>											
											<td>{!!Form::text($alelo_2, null, ['class' => "text-center w-100 $alelo_2", 'disabled'])!!}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>					
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			$(".boton_metadatos").click(function(e){
	        	var id_resultado_de_la_busqueda = e.currentTarget.id;
	        	var perfiles_compatibles = <?php echo $busqueda->resultados;?>;
	        	for(x in perfiles_compatibles){
	        		if(perfiles_compatibles[x].id == id_resultado_de_la_busqueda){
	        			console.log(perfiles_compatibles[x]);		
	        		}
	        	}

					        	


	        	
	        	$('#exampleModalLong').modal('show')
	        	
	    	});
		});
	</script>
@endsection