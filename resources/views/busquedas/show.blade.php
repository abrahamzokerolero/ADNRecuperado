@extends('layouts.app')

@section('title')
    ADN México | Busquedas
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')
	<?php use Carbon\Carbon;?>
	<script type="text/javascript">
		function obtener_id(id){
			var form = $('#form_mensaje');
			var cadena = form.attr('action').split('/')
			$("#form_mensaje").attr('action', form.attr('action').replace(cadena[4], id, "gi"));
		}
	</script>
	<div class="card-block mt-3">
		<link rel="stylesheet" href="{{asset('css/datatables/dataTables.min.css')}}">
		<div class="container">
			<div class="card-title p-3 card-header">
				<img src="{{asset('images/busquedas_gris.png')}}" alt="" width="80" height="80" class=""><span class="h4 ml-3 font-weight-bold"> Resultados de Busqueda  </span>
				<div class="float-right">
					@can('busquedas.create')
					<a href="{{route('busquedas.create')}}" class="btn btn-info float-right mr-3 mb-2"><i class="fa fa-plus-circle"></i> Nueva busqueda</a>
					@endcan
				</div>
			</div>
			@if($busqueda->resultados->count() <> 0)
			<!-- Modal para metadatos de ambos perfiles-->
			<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
			  <style type="text/css">
			  	.modal-lg {
				    max-width: 90%;
				}
			  </style>	
			  <div class="modal-dialog modal-lg" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title text-center" id="exampleModalLongTitle">Detalle del resultado</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			        <div class="row">
	               		<div class="col-sm-3">
	               			<div class="text-center mb-1">
								<b class="perfil_objetivo"></b>	
							</div>
	               			<div class="card-header bg-danger text-white text-center"> Metadatos del perfil objetivo</div>
	               			<div class="mt-2 form-group metadatos_perfil_objetivo">
	               				
	               			</div>
	               		</div>
	               		<div class="col-sm-3">
	               			<div class="">
	               				<div class="text-center mb-1">
									<b class="perfil_subordinado"></b>	
								</div>
	               				<div class="card-header bg-danger text-white text-center"> Metadatos del perfil compatible</div>
               					<div class="mt-2 form-group metadatos_perfil_compatible">

               					</div>
	               			</div>
	               		</div>

	               		<div class="col-6">
							<div class="card-header bg-info mb-3">
								<div class="text-white text-center">Comparativo por marcadores</div>
							</div>
							<div class="row">
								<div class="col">
									<div class="text-center mb-1">
										<b class="perfil_objetivo"></b>	
									</div>									
									<table id="myTable2" class="table">
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
													?>	
													<!-- Se muestra el marcador-->
													<td>{{$marcador->nombre}}</td>
													<td>{!!Form::text($alelo_1, null, ['class' => "text-center w-100 $alelo_1" , 'disabled'])!!}</td>											
													<td>{!!Form::text($alelo_2, null, ['class' => "text-center w-100 $alelo_2", 'disabled'])!!}</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
								<div class="col">
									<div class="text-center mb-1">
										<b class="perfil_subordinado"></b>	
									</div>							
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
														$alelo_1 = $marcador->nombre.'_alelo_1_1';
														$alelo_2 = $marcador->nombre.'_alelo_2_2';
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
			      <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			      </div>
			    </div>
			  </div>
			</div>
			@endif
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
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
			        <input type="submit" class="btn btn-primary" value="Guardar cambios">
			      </div>
			    {!!Form::close()!!} 
			    </div>
			  </div>
			</div>

			<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModal2Label" aria-hidden="true">
			  <div class="modal-dialog" role="document">
			  	{!! Form::open(array('route' => ['busquedas.mensaje', 'id_resultado'], 'method' => 'POST', 'id' => 'form_mensaje')) !!}﻿
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="exampleModal2Label">Enviar Mensaje</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			          <div class="form-group">
			            <label for="conclusiones" class="col-form-label">Contenido del mensaje</label>
			            <textarea class="form-control" name="mensaje" required></textarea>
			          </div>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
			        <input type="submit" class="btn btn-primary " value="Guardar cambios">
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
								<p class="m-0"><b>Fuente solicitante:</b> <span class="">{{$busqueda->fuente->nombre}}</span></p>
								<p class="m-0"><b>Usuario:</b> <span class="">{{strtoupper($busqueda->usuario->name)}}</span></p>
								<p class="m-0"><b>Fecha:</b> <span class="">{{Carbon::parse($busqueda->created_at)->format('d/m/Y')}}</span></p>
								<p class="m-0"><b>Motivo de la busqueda:</b> <span class="">{{strtoupper($busqueda->motivo)}}</span></p>
								<p class="m-0"><b>Descripcion de la  busqueda:</b> <span class="">{{strtoupper($busqueda->descripcion)}}</span></p>
								@if($busqueda->conclusiones == null)
									<p class="mt-2 pt-2 pb-2"><b class="text-warning">No se han agregado conclusiones</b>
								@else
									<p class="mt-2 pt-2 pb-2"><b>Conclusiones:</b>	
								@endif 
									<span class="">
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
							<div class='card-header bg-info text-white'><b>PARAMETROS DE BUSQUEDA</b></div>
							<div class="container">
								<p class="m-0"><b>Marcadores minimos usados:</b> <span class="">{{$busqueda->marcadores_minimos}}</span></p>
								<p class="m-0"><b>Exclusiones maximas:</b> <span class="">{{strtoupper($busqueda->numero_de_exclusiones)}}</span></p>
								<p class="m-0"><b>Tabla de frecuencias usada:</b> <span class="">{{strtoupper($busqueda->tabla_de_frecuencias->nombre_otorgado)}}</span></p>
								<p class="m-0"><b>Perfiles en revision descartados:</b> 
									<span class="">
										@if($busqueda->perfiles_en_revision_descartados == 0 )
											NO SE DESCARTARON
										@else
											SE DESCARTARON
										@endif
									</span>
								</p>
								@if($busqueda->etiquetas_objetivo <> null)
									<p class=""><b>Etiquetas objetivo:</b> 
										<?php 
											$etiquetas_objetivo = explode(',' , $busqueda->etiquetas_objetivo);
										?>	
										@foreach($etiquetas_objetivo as $etiqueta_objetivo)
											<span class="btn btn-success btn-sm disabled"> <?php $etiqueta_objetivo = App\Etiqueta::find($etiqueta_objetivo); echo $etiqueta_objetivo->nombre; ?></span>
										@endforeach
									</p>	
								@endif
								<p class=""><b>Etiquetas a comparar:</b> 
									<?php 
										$etiquetas = explode(',' , $busqueda->etiquetas_usadas);
									?>	
									@foreach($etiquetas as $etiqueta)
										<span class="btn btn-success btn-sm disabled"> <?php $etiqueta = App\Etiqueta::find($etiqueta); echo $etiqueta->nombre; ?></span>
									@endforeach
								</p>

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
								<td class="d-none">Indice</td>
								<td class="text-center">Genotipo objetivo</td>
								<td class="text-center">Genotipo compatible</td>
								<td class="text-center">Amel</td>
								<td class="text-center">IP</td>
								<td class="text-center">PP</td>
								<td class="text-center">SMS</td>
							</thead>
							<tbody>
								<?php $perfil_objetivo_anterior = '';?>
								@foreach($busqueda->resultados as $resultado)
									<tr>
										<td class="d-none">{{$resultado->id}}</td>
										@if($perfil_objetivo_anterior <> $resultado->perfil_objetivo->identificador)
											<td>
												<span class="btn btn-secondary btn-sm disabled">{{$resultado->perfil_objetivo->identificador}}
												</span>
											</td>
											<?php $perfil_objetivo_anterior = $resultado->perfil_objetivo->identificador;?>
										@else
											<td></td>
											<?php $perfil_objetivo_anterior = $resultado->perfil_objetivo->identificador;?>
										@endif
										<td>
											<button type="button" class="btn btn-sm btn-primary boton_metadatos" id="{{$resultado->id}}">
												{{$resultado->perfil_subordinado->identificador}}
											</button>
										</td>
										<td class="text-center">{{$resultado->amel}}</td>
										@if($resultado->IP == 0)
											<td class="text-center"><b>0</b></td>
											<td class="text-center"><b>Exclusiones = {{$resultado->exclusiones}}</b></td>
										@else
											<td class="text-center">{{sprintf( '%E' ,$resultado->IP)}}</td>
											<td class="text-center">{{$resultado->PP}} %</td>
										@endif
										<td>
											<script type="text/javascript"> var a = {{$resultado->id}}</script>
											<button type="button" class="btn btn-sm btn-success mensaje" data-toggle="modal" data-target="#exampleModal2" onclick="obtener_id({{$resultado->id}})">
												<i class="fa fa-envelope"></i>
											</button>										
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
							  	"order" : 0,							  								  	
							    "language": {
							      "url": "http://cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
							    }
							  });
							});
						</script>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {

			if($)

			$(".boton_metadatos").click(function(e){
				$('input').removeClass('bg-danger text-white');
				$('input').removeClass('bg-success text-white');
				
				// Borramos los metadatos del perfil compatible anterior
				var div_metadatos = $('.metadatos_perfil_compatible');
				div_metadatos.empty();
				
				// Borramos los metadatos del perfil objetivo anterior
				var div_metadatos2 = $('.metadatos_perfil_objetivo');
				div_metadatos2.empty();

				// Obtenemos el id del resultado seleccionado
	        	var id_resultado_de_la_busqueda = e.currentTarget.id;

	        	// Obtenemos todos los resultados
	        	var perfiles_compatibles = <?php echo $busqueda->resultados;?>;
	        	for(x in perfiles_compatibles){

	        		if(perfiles_compatibles[x].id == id_resultado_de_la_busqueda){
	        			// Descomentar para ver todos los datos del resultado en consola 
	        			// console.log(perfiles_compatibles[x]);

	        			// Asignamos el titulo al perfil genetico objetivo y al compatible
	        			$('.perfil_objetivo').text('P. OBJETIVO: ' + perfiles_compatibles[x].perfil_objetivo.identificador + ' '  + perfiles_compatibles[x].perfil_objetivo.id_externo)

	        			$('.perfil_subordinado').text('P COMPATIBLE: ' + perfiles_compatibles[x].perfil_subordinado.identificador + ' ' + perfiles_compatibles[x].perfil_subordinado.id_externo)

	        			// Mostramos los metadatos del perfil objetivo
	        			for(y in perfiles_compatibles[x].perfil_objetivo.metadatos){
	        				div_metadatos2.append("<div class='row pl-3 pr-3 pt-3'><div class='col'><label for='"+ perfiles_compatibles[x].perfil_objetivo.metadatos[y].tipo_de_metadato.nombre  +"''>" + perfiles_compatibles[x].perfil_objetivo.metadatos[y].tipo_de_metadato.nombre.replace(/_/g, ' ') + "</label><input name='"+ perfiles_compatibles[x].perfil_objetivo.metadatos[y].tipo_de_metadato.nombre +"' type='text' value = '" + perfiles_compatibles[x].perfil_objetivo.metadatos[y].dato + "' class='form-control' disabled><div><div>");	
	        			}



	        			// Mostramos los metadatos del perfil compatible
	        			for(y in perfiles_compatibles[x].perfil_subordinado.metadatos){
	        				div_metadatos.append("<div class='row pl-3 pr-3 pt-3'><div class='col'><label for='"+ perfiles_compatibles[x].perfil_subordinado.metadatos[y].tipo_de_metadato.nombre  +"''>" + perfiles_compatibles[x].perfil_subordinado.metadatos[y].tipo_de_metadato.nombre.replace(/_/g, ' ') + "</label><input name='"+ perfiles_compatibles[x].perfil_subordinado.metadatos[y].tipo_de_metadato.nombre +"' type='text' value = '" + perfiles_compatibles[x].perfil_subordinado.metadatos[y].dato + "' class='form-control' disabled><div><div>");	
	        			}
	        			// Etiquetas del perfil compatible
	        			for(y in perfiles_compatibles[x].perfil_subordinado.etiquetas){
	        				div_metadatos.append("<span class='btn btn-success btn-sm m-2'>" + perfiles_compatibles[x].perfil_subordinado.etiquetas[y].etiqueta.nombre + "</span>")
	        			}


	        			// Etiquetas del perfil objetivo
	        			for(y in perfiles_compatibles[x].perfil_objetivo.etiquetas){
	        				div_metadatos2.append("<span class='btn btn-success btn-sm m-2'>" + perfiles_compatibles[x].perfil_objetivo.etiquetas[y].etiqueta.nombre + "</span>")
	        			}
	        			// metadatos del perfil objetivo
	        			for(y in perfiles_compatibles[x].perfil_objetivo.alelos){
	        				var marcador_alelo_1P = '.' + perfiles_compatibles[x].perfil_objetivo.alelos[y].marcador.nombre + '_alelo_1';
	        				var marcador_alelo_2P = '.' + perfiles_compatibles[x].perfil_objetivo.alelos[y].marcador.nombre + '_alelo_2';
	        				$(''+ marcador_alelo_1P).val(perfiles_compatibles[x].perfil_objetivo.alelos[y].alelo_1);
	        				$(''+ marcador_alelo_2P).val(perfiles_compatibles[x].perfil_objetivo.alelos[y].alelo_2);
	        			}

	        			// console.log(perfiles_compatibles[x].perfil_subordinado.alelos);
	        			for(y in perfiles_compatibles[x].perfil_subordinado.alelos){
	        				var marcador_alelo_1P = '.' + perfiles_compatibles[x].perfil_subordinado.alelos[y].marcador.nombre + '_alelo_1';
	        				var marcador_alelo_2P = '.' + perfiles_compatibles[x].perfil_subordinado.alelos[y].marcador.nombre + '_alelo_2';
	        				var marcador_alelo_1 = '.' + perfiles_compatibles[x].perfil_subordinado.alelos[y].marcador.nombre + '_alelo_1_1';
	        				var marcador_alelo_2 = '.' + perfiles_compatibles[x].perfil_subordinado.alelos[y].marcador.nombre + '_alelo_2_2';
	        				$(''+ marcador_alelo_1).val(perfiles_compatibles[x].perfil_subordinado.alelos[y].alelo_1);
	        				$(''+ marcador_alelo_2).val(perfiles_compatibles[x].perfil_subordinado.alelos[y].alelo_2);


	        				if($(''+ marcador_alelo_1P).val() == $(''+ marcador_alelo_1).val() || $(''+ marcador_alelo_2P).val() == $(''+ marcador_alelo_1).val()){
	        					$(''+ marcador_alelo_1).addClass('bg-success text-white')
	        				}
	        				if($(''+ marcador_alelo_1P).val() == $(''+ marcador_alelo_2).val() || $(''+ marcador_alelo_2P).val() == $(''+ marcador_alelo_2).val() ){
	        					$(''+ marcador_alelo_2).addClass('bg-success text-white')	
	        				}

	        				if($(''+ marcador_alelo_1P).val() != $(''+ marcador_alelo_1).val()){
	        					if($(''+ marcador_alelo_1P).val() != $(''+ marcador_alelo_2).val()){
	        						if($(''+ marcador_alelo_2P).val() != $(''+ marcador_alelo_1).val()){
	        							if($(''+ marcador_alelo_2P).val() != $(''+ marcador_alelo_2).val()){
	        								if($(''+ marcador_alelo_1P).val() != "" &&  $(''+ marcador_alelo_2P).val() != ""){
	        									$(''+ marcador_alelo_1).addClass('bg-danger text-white');
	        									$(''+ marcador_alelo_2).addClass('bg-danger text-white');
	        								}
	        							}
	        						}
	        					}
	        				}

	        				// if($(''+ marcador_alelo_1P).val() != $(''+ marcador_alelo_1).val() && $(''+ marcador_alelo_1P).val() != $(''+ marcador_alelo_2).val() && $(''+ marcador_alelo_2P).val() != $(''+ marcador_alelo_1).val() && $(''+ marcador_alelo_2P).val() != $(''+ marcador_alelo_2).val()){
	        				// 	$(''+ marcador_alelo_1).addClass('bg-danger text-white')
	        				// 	$(''+ marcador_alelo_2).addClass('bg-danger text-white');
	        				// }
	        			}
	        		}	
	        	}	 

	        	$('#exampleModalLong').modal('show')
	    	});
			
			
		});
	</script>
@endsection