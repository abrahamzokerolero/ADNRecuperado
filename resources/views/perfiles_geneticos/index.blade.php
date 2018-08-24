@extends('layouts.app')

@section('title')
    ADN México | Lista de Perfiles
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->
@section('script')
	<link rel="stylesheet" href="{{asset('css/datatables/dataTables.min.css')}}">
	<script src="{{asset('js/jquery-3.3.1.js')}}"></script>
	<link rel="stylesheet" href="{{asset('css/choices.min.css?version=3.0.4')}}">
  	<script src="{{asset('js/choices.min.js?version=3.0.4s')}}"></script> 
@endsection

@section('content')
	<div class="card-block">
		<div class="container">
			<div class="card-title p-3 card-header">
				<img src="{{asset('images/genotipos.png')}}" alt="" width="80" height="70" class=""><span class="h4 ml-3 font-weight-bold"> LISTA DE PERFILES GENETICOS</span>
				<div class="float-right">
					@can('perfiles_geneticos.create')
					<a href="{{route('perfiles_geneticos.create')}}" class="mr-3 mt-2 btn btn-danger "><i class="fa fa-list-ul"></i> Ingresar nuevo perfil</a>
					@endcan
					@can('importaciones_perfiles.create')
					<a href="{{route('importaciones_perfiles.create')}}" class="mt-2 btn btn-danger "><i class="fa fa-pencil"></i> Importar perfiles</a>
					@endcan
				</div>	
			</div>
			
			<ul class="nav nav-tabs mt-2 justify-content-center" id="myTab" role="tablist">

				<li class="nav-item">
			  	  <a class="nav-link btn btn-primary restablecer text-white" href="{{route('perfiles_geneticos.restablecer')}}">Quitar Filtros</a>
			  	</li>
			  	<li class="nav-item">
			  	  <a class="nav-link" id="metadato-tab" data-toggle="tab" href="#metadato" role="tab" aria-controls="metadato" aria-selected="true">Filtro por metadato</a>
			  	</li>
			  	<li class="nav-item">
			  	  <a class="nav-link" id="etiqueta-tab" data-toggle="tab" href="#etiqueta" role="tab" aria-controls="etiqueta" aria-selected="false">Filtro por etiquetas</a>
			  	</li>
			  	<li class="nav-item">
			  	  <a class="nav-link" id="fuente-tab" data-toggle="tab" href="#fuente" role="tab" aria-controls="fuente" aria-selected="false">Filtro por fuente</a>
			  	</li>
			  	<li class="nav-item">
			  	  <a class="nav-link" id="id_importacion-tab" data-toggle="tab" href="#id_importacion" role="tab" aria-controls="id_importacion" aria-selected="false">Filtro por ID de importacion</a>
			  	</li>
			  	<li class="nav-item">
			  	  <a class="nav-link" id="combinado-tab" data-toggle="tab" href="#combinado" role="tab" aria-controls="combinado" aria-selected="false">Filtros Combinados</a>
			  	</li>
			</ul>
			<div class="tab-content" id="myTabContent">
			  <div class="tab-pane fade border" id="metadato" role="tabpanel" aria-labelledby="metadato-tab">			  	
				<div class="card-header bg-dark text-center text-white mt-1">Filtro avanzado</div>
				<span class="bg-warning form-control mensaje_de_error text-center mt-2 mb-2">Mensaje de error</span>
				<script type="text/javascript"> $('.mensaje_de_error').hide();</script>
			  	<div class="d-flex justify-content-center p-2">
			  		{!! Form::open(array('route' => ['perfiles_geneticos.filtro_por_metadato'], 'method' => 'POST')) !!}﻿
					<div class="row pb-3">
						<div class="col">
							<select required name="id_tipo_de_metadato" class="form-control" placeholder = 'Buscar por metadato'>
							  <option disabled selected><b>Seleccionar metadato</b></option>
							  @foreach($tipos_de_metadatos as $tipo_de_metadato)
							  	<option value="{{$tipo_de_metadato->id}}">{{str_replace("_", " ", strtoupper($tipo_de_metadato->nombre))}}</option>
							  @endforeach
							</select>
						</div>
						<div class="col">
							{!!Form::text('filtro_por_metadato', null, ['class' => 'form-control'])!!}			
						</div>
						<div class="col-3">
							<button class="btn btn-primary buscar">Buscar</button>
						</div>
					</div>
				{!!Form::close()!!}
				</div>
			  </div>
			  <div class="tab-pane fade border" id="etiqueta" role="tabpanel" aria-labelledby="etiqueta-tab">
				<div class="card-header bg-dark text-center text-white mt-1">Filtro avanzado</div>
				<span class="bg-warning form-control mensaje_de_error2 text-center mt-2 mb-2">Mensaje de error</span>
				<script type="text/javascript"> $('.mensaje_de_error2').hide();</script>
			  	<div class="d-flex justify-content-around">
			  		<div class="p-2 w-75">
					  	{!! Form::open(array('route' => ['perfiles_geneticos.filtro_por_etiquetas'], 'method' => 'POST')) !!}﻿
					  	<div class="row pb-3">
					  		<div class="col">
					  			<select class="form-control" name="etiquetas[]" id="etiquetas" multiple>
									@foreach($categorias as $categoria)
										<optgroup label="{{ strtoupper($categoria->nombre)}}">
											@foreach($categoria->etiquetas as $etiqueta)
												<option value="{{$etiqueta->id}}">{{$etiqueta->nombre}} <b>(
													{{$etiqueta->perfiles_geneticos_asociados->count()}}
												)</b></option>
											@endforeach	
										</optgroup>
									@endforeach
								</select>
					  		</div>
					  		<div class="col">
					  			<button class="btn btn-primary buscar2">Buscar</button>
					  		</div>
					  	</div>
					  	{!!Form::close()!!}
					</div>
			  	</div>
			  </div>
			  <div class="tab-pane fade" id="fuente" role="tabpanel" aria-labelledby="fuente-tab">
				<div class="card-header bg-dark text-center text-white mt-1">Filtro avanzado</div>
				<span class="bg-warning form-control mensaje_de_error3 text-center mt-2 mb-2">Mensaje de error</span>
				<script type="text/javascript"> $('.mensaje_de_error3').hide();</script>
			  	<div class="d-flex justify-content-around">
			  		<div class="p-2 w-75">
					  	{!! Form::open(array('route' => ['perfiles_geneticos.filtro_por_fuentes'], 'method' => 'POST')) !!}﻿
					  	<div class="row pb-3">
					  		<div class="col">
					  			<select name="id_fuente" class="form-control" required>
								  <option disabled selected>Seleccione una Fuente</option>
								  @foreach($fuentes as $fuente)
								  	<option value="{{$fuente->id}}">{{$fuente->nombre}}</option>
								  @endforeach
								</select>
					  		</div>
					  		<div class="col">
					  			<button class="btn btn-primary buscar3">Buscar</button>
					  		</div>
					  	</div>
					  	{!!Form::close()!!}
					</div>
			  	</div>
			  </div>
			  <div class="tab-pane fade" id="id_importacion" role="tabpanel" aria-labelledby="id_importacion-tab">
				<div class="card-header bg-dark text-center text-white mt-1">Filtro avanzado</div>
				<span class="bg-warning form-control mensaje_de_error4 text-center mt-2 mb-2">Mensaje de error</span>
				<script type="text/javascript"> $('.mensaje_de_error4').hide();</script>
			  	<div class="d-flex justify-content-center">
			  		<div class="p-2 w-75">
					  	{!! Form::open(array('route' => ['perfiles_geneticos.filtro_por_id_importacion'], 'method' => 'POST')) !!}﻿
					  	
						<div class="form-group row">
						    <label for="id_importacion" class="col-sm-2 col-form-label">Importacion</label>
						    <div class="col-sm-10">
						      {!!Form::text('id_importacion', null, ['class'=>'form-control id_importacion', 'placeholder'=>"Identificador de la importacion"])!!}
						    </div>
						</div>
						<fieldset class="form-group">
						    <div class="row">
						      <legend class="col-form-label col-sm-2 pt-0">Opcion</legend>
						      <div class="col-sm-10">
						        <div class="form-check">
						          <input class="form-check-input" type="checkbox" name="id_importacion_exacto" id="id_importacion_exacto">
						          <label class="form-check-label" for="id_importacion_exacto">
						            Buscar ID exacto
						          </label> <button class="btn btn-primary btn-sm ml-5 buscar4">Buscar</button> 	
						        </div>
						      </div>
						    </div>
						</fieldset>
					  	{!!Form::close()!!}
					</div>
			  	</div>
			  </div>


			  {{-- Filtros combinados --}}
			


			  <div class="tab-pane fade" id="combinado" role="tabpanel" aria-labelledby="combinado-tab">
				<div class="container">
					<div class="accordion" id="accordionExample2">
					  <div class="card">
					    <div class="card-header bg-dark" id="headingfiltro1">
					      <h5 class="mb-0 text-center">
					        <button class="btn btn-link text-white" type="button" data-toggle="collapse" data-target="#collapsefiltro1" aria-expanded="true" aria-controls="collapsefiltro1">
					          Perfiles geneticos filtrados por id de importacion y tipo de metadato
					        </button>
					      </h5>
					    </div>

					    <div id="collapsefiltro1" class="collapse" aria-labelledby="headingfiltro1" data-parent="#accordionExample2">
					      <div class="d-flex flex-wrap">
							<div class="container">
								<span class="bg-warning form-control mensaje_de_error5 text-center mt-3 mb-2">Mensaje de error</span>
								<script type="text/javascript"> $('.mensaje_de_error5').hide();</script>
								{!! Form::open(array('route' => ['perfiles_geneticos.filtro_combinado'], 'method' => 'POST')) !!}﻿
								<div class="row">
									<div class="col">
										<div class="form-group">
									    <label for="id_importacion">Importacion</label>
									    <div class="">
									      {!!Form::text('id_importacion', null, ['class'=>'form-control id_importacion2', 'placeholder'=>"Identificador de la importacion", 'required'])!!}
									    </div>
										<fieldset class="form-group mt-3">
										    <div class="row">
										      <legend class="col-form-label col-sm-2 pt-0">Opcion</legend>
										      <div class="col-sm-10">
										        <div class="form-check">
										          <input class="form-check-input" type="checkbox" name="id_importacion_exacto" id="id_importacion_exacto">
										          <label class="form-check-label" for="id_importacion_exacto">
										            Buscar ID exacto
										          </label> <button class="btn btn-primary btn-sm ml-5 buscar5">Buscar</button> 	
										        </div>
										      </div>
										    </div>
										</fieldset>
									  </div>			
									</div>
									<div class="col">
										<div class="form-group">
									      <div class="row pb-3">
											<div class="col">
												<label for="id_tipo_de_metadato" class="">T.METADATO</label>
												<select required name="id_tipo_de_metadato" class="form-control id_tipo_de_metadato2" placeholder = 'Buscar por metadato'>
												  <option disabled selected><b>Seleccionar</b></option>
												  @foreach($tipos_de_metadatos as $tipo_de_metadato)
												  	<option value="{{$tipo_de_metadato->id}}">{{str_replace("_", " ", strtoupper($tipo_de_metadato->nombre))}}</option>
												  @endforeach
												</select>
											</div>
											<div class="col">
												<label for="filtro_por_metadato" class="">METADATO</label>
												{!!Form::text('filtro_por_metadato', null, ['class' => 'form-control metadato2', 'required'])!!}
											</div>
										  </div>
										</div>	
									</div>
								</div>
							{!!Form::close()!!}
							</div>
						</div>
					    </div>
					  </div>
					  <div class="card">
					    <div class="card-header bg-dark" id="headingfiltro2">
					      <h5 class="mb-0 text-center">
					        <button class="btn btn-link text-white collapsed" type="button" data-toggle="collapse" data-target="#collapsefiltro2" aria-expanded="false" aria-controls="collapsefiltro2">
					          Perfiles geneticos filtrados por fuente y  tipo de metadato
					        </button>
					      </h5>
					    </div>
					    <div id="collapsefiltro2" class="collapse" aria-labelledby="headingfiltro2" data-parent="#accordionExample2">
					      <span class="bg-warning form-control mensaje_de_error6 text-center mt-3 mb-2">Mensaje de error</span>
						  <script type="text/javascript"> $('.mensaje_de_error6').hide();</script>
						  {!! Form::open(array('route' => ['perfiles_geneticos.filtro_combinado2'], 'method' => 'POST')) !!}﻿
					      <div class="container">
					      	<div class="row mt-3">
					      		<div class="col">
					      			<label for="id_fuente" class="">FUENTE</label>
  							      	<select name="id_fuente" class="form-control id_fuente2" required="">
  									  <option disabled selected>Seleccione una Fuente</option>
  									  @foreach($fuentes as $fuente)
  									  	<option value="{{$fuente->id}}">{{$fuente->nombre}}</option>
  									  @endforeach
  									</select>
					      		</div>
					      		<div class="col">
					      			<div class="row pb-3">
	  									<div class="col">
	  										<label for="id_tipo_de_metadato" class="">T.METADATO</label>
	  										<select required name="id_tipo_de_metadato" class="form-control id_tipo_de_metadato3" placeholder = 'Buscar por metadato'>
	  										  <option disabled selected><b>Seleccionar</b></option>
	  										  @foreach($tipos_de_metadatos as $tipo_de_metadato)
	  										  	<option value="{{$tipo_de_metadato->id}}">{{str_replace("_", " ", strtoupper($tipo_de_metadato->nombre))}}</option>
	  										  @endforeach
	  										</select>
	  									</div>
	  									<div class="col">
	  										<label for="filtro_por_metadato" class="">METADATO</label>
	  										{!!Form::text('filtro_por_metadato', null, ['class' => 'form-control metadato3', 'required'])!!}			
	  									</div>
	  								</div>	
					      		</div>
					      		<div class="col">
					      			<button class="btn btn-primary mt-4 buscar6">Buscar</button>
					      		</div>
					      		{!!Form::close()!!}
					      	</div>
						</div>
					    </div>
					  </div>
					  <div class="card">
					    <div class="card-header bg-dark" id="headingfiltro3">
					      <h5 class="mb-0 text-center">
					        <button class="btn btn-link text-white collapsed" type="button" data-toggle="collapse" data-target="#collapsefiltro3" aria-expanded="false" aria-controls="collapsefiltro3">
					          Perfiles geneticos filtrados por fuente y etiquetas
					        </button>
					      </h5>
					    </div>
					    <div id="collapsefiltro3" class="collapse" aria-labelledby="headingfiltro3" data-parent="#accordionExample2">
					    	{!! Form::open(array('route' => ['perfiles_geneticos.filtro_combinado3'], 'method' => 'POST')) !!}﻿
					    	<span class="bg-warning form-control mensaje_de_error7 text-center mt-3 mb-2">Mensaje de error</span>
							<script type="text/javascript"> $('.mensaje_de_error7').hide();</script>
					        <div class="container mt-3 mb-3">
					        	<div class="row">
					        		<div class="col">
					        			<label for="etiquetas2" class="">ETIQUETAS</label>
							  			<select class="form-control etiquetas2" name="etiquetas2[]" id="etiquetas2" multiple>
											@foreach($categorias as $categoria)
												<optgroup label="{{ strtoupper($categoria->nombre)}}">
													@foreach($categoria->etiquetas as $etiqueta)
														<option value="{{$etiqueta->id}}">{{$etiqueta->nombre}} <b>(
															{{$etiqueta->perfiles_geneticos_asociados->count()}}
														)</b></option>
													@endforeach	
												</optgroup>
											@endforeach
										</select>	
					        		</div>
					        		<div class="col">
										<label for="id_fuente" class="">FUENTE</label>
								      	<select name="id_fuente" class="form-control id_fuente3" required="">
										  <option disabled selected>Seleccione una Fuente</option>
										  @foreach($fuentes as $fuente)
										  	<option value="{{$fuente->id}}">{{$fuente->nombre}}</option>
										  @endforeach
										</select>
					        		</div>
					        		<div class="col">
						      			<button class="btn btn-primary mt-4 buscar7">Buscar</button>
						      		</div>
					        	</div>
					        	{!!Form::close()!!}
					        </div>
					    </div>
					  </div>
					</div>
				</div>
			  </div>
			</div>
			<div class="container">
				<span class="bg-warning form-control mensaje_de_error8 text-center mt-3 mb-2">Mensaje de error</span>
				<script type="text/javascript"> $('.mensaje_de_error8').hide();</script>
				<div class="row mt-3">
					<div class="col">
						<button class="btn" type="button" data-toggle="collapse" data-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2">
						    Agregar etiquetas
						</button>

						<button class="btn ml-2" type="button" data-toggle="collapse" data-target="#collapseExample3" aria-expanded="false" aria-controls="collapseExample3">
						    Eliminar etiquetas
						</button>
						
						<div class="collapse mt-1" id="collapseExample2">
						  {!! Form::open(array('route' => ['perfiles_geneticos.etiquetar'], 'method' => 'POST')) !!}﻿
						  <div class="card card-body">
						  		<div class="card-header text-success mb-2"> Seleccione etiquetas para agregar</div>
						  		<div class="row">
						  			<div class="col-8">
						  				<select class="form-control etiquetas3" name="etiquetas3[]" id="etiquetas3" multiple>
											@foreach($categorias as $categoria)
												<optgroup label="{{ strtoupper($categoria->nombre)}}">
													@foreach($categoria->etiquetas as $etiqueta)
														<option value="{{$etiqueta->id}}">{{$etiqueta->nombre}} <b>(
															{{$etiqueta->perfiles_geneticos_asociados->count()}}
														)</b></option>
													@endforeach	
												</optgroup>
											@endforeach
										</select>			
						  			</div>
						  			<div class="col-2">
						  				<button id="guardar_etiquetas" class="btn btn-primary m-1">Guardar</button>		
						  			</div>
						  		</div>
						  </div>
						  {!!Form::close()!!}
						</div>

						<div class="collapse mt-1" id="collapseExample3">
						  {!! Form::open(array('route' => ['perfiles_geneticos.desetiquetar'], 'method' => 'POST')) !!}﻿
						  <div class="card card-body">
						  		<div class="card-header text-danger mb-2"> Seleccione etiquetas a eliminar</div>
						  		<div class="row">
						  			<div class="col-8">
						  				<select class="form-control etiquetas4" name="etiquetas4[]" id="etiquetas4" multiple>
											@foreach($categorias as $categoria)
												<optgroup label="{{ strtoupper($categoria->nombre)}}">
													@foreach($categoria->etiquetas as $etiqueta)
														<option value="{{$etiqueta->id}}">{{$etiqueta->nombre}} <b>(
															{{$etiqueta->perfiles_geneticos_asociados->count()}}
														)</b></option>
													@endforeach	
												</optgroup>
											@endforeach
										</select>			
						  			</div>
						  			<div class="col-2">
						  				<button id="borrar_etiquetas" class="btn btn-primary m-1">Borrar</button>		
						  				{{-- <input type="submit" name="boton9" value="Borrar"> --}}
						  			</div>
						  		</div>
						  </div>
						  {!!Form::close()!!}
						</div>
					</div>
					<div class="col">
						<div class="d-flex justify-content-end">
							<input type="button" id ="seleccionarAll" value="Seleccionar todo" class="btn mr-3">
							<input type="button" id ="seleccionarNone" value="Borrar seleccion" class="btn">
						</div>
					</div>
				</div>
			</div>
			<table id="myTable" class="table">
				<thead class="card-header bg-danger text-white">
					<td hidden>Id</td>
					<td>ID interno</td>
					<td>ID externo</td>
					<td>Marcadores</td>
					<td>Homocigotos</td>
					<td>Usuario</td>
					<td>Fecha de creacion</td>
				</thead>
				<tbody>
					
				</tbody>
			</table>
			<script src="{{asset('js/datatables/dataTables.min.js')}}"></script>
			<script>
				$(document).ready(function() {

				  var data = <?php echo $perfiles_geneticos;?>;
				  var oTable = $('#myTable').DataTable({
				  		"order": [ 0 , 'desc'],
				  		"language": {
						  "url": "http://cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
						},
						select: {
				            style: 'multi'
				        },
			            data:data,
				        columnDefs: [{"className": "dt-center", "targets": "_all"}, {
			                "targets": [ 0 ],
			                "visible": false,
			                "searchable": false,
			            },],
			            columns: [
			            	{ data: 'id'},	
					        { data: 'identificador',
						    render: function ( data, type, row ) {
							        return '<a href="perfiles_geneticos/'+ row.id +'">'+ data + '</a>';
							    }
						    },
					        { data: 'id_externo' },
					        { data: 'numero_de_marcadores',
					        render: function ( data, type, row ) {
							        return '<span class=" border border-success rounded p-1">'+ data + '</span>';
							    }
					        },
					        { data: 'numero_de_homocigotos', 
					        render: function ( data, type, row ) {
							        return '<span class=" border border-info rounded p-1">'+ data + '</span>';
							    }
					        },
					        { data: 'name' },
					        { data: 'created_at', 
					        	render: function (data, type, row){
					        		var date = new Date(data);
					        		return date.toLocaleDateString()
					        	}
					    	},
					    	
					    ]
			        });

				  	 $('#seleccionarAll').click( function () {
				  	 	var oTable = $('#myTable').DataTable();
				  	 	oTable.rows().select();
				      });

				  	 $('#seleccionarNone').click( function (){
				  	 	var oTable = $('#myTable').DataTable();
				  	 	oTable.rows().deselect();
				  	 });

				  	 $('#guardar_etiquetas').click( function (e) {
				  	 	e.preventDefault();
						$('button').addClass('disabled');										  	 	
				  	 	var seleccionados = [];

				  	 	for (var i = 0; i < oTable.rows('.selected').data().length; i++) {						 
						    seleccionados.push(oTable.rows('.selected').data()[i].id);
						}
						if(seleccionados.length == 0){
							// mandar mensaje de error
							mensaje_error('.mensaje_de_error8', 'No selecciono ningun perfil genetico'); 
							$('button').removeClass('disabled');
						}
						else{
							if($('.etiquetas3').val() != ''){
								var form = $(this).parents('form');
					        	var url = form.attr('action');

					        	var data = form.serializeArray();
								data.push({name: 'seleccionados', value: seleccionados});

					        	$.ajax({
								    type: "POST",
								    url: url,
								    data: data,
								    dataType: "json",
								    success: function(data) {
								        if(data.newData == 0){
								        	mensaje_exitoso('.mensaje_de_error8', 'Las etiquetas ya habian sido asignadas a los perfiles seleccionados');
								        }
								        else{

								        	multipleDefault1.destroy();
								        	multipleDefault2.destroy();
								        	multipleDefault3.destroy();
								        	multipleDefault4.destroy();

								        	for(i in data.categorias){
							                	$("select[name='etiquetas[]']").append('<optgroup id="' + data.categorias[i].nombre + '1" label="'+ data.categorias[i].nombre +'"></optgroup>');
							                	for(x in data.categorias[i].etiquetas){
							                		$('optgroup[id="'+ data.categorias[i].nombre +'1"]').append('<option value='+ data.categorias[i].etiquetas[x].id +'>'+ '\t' + data.categorias[i].etiquetas[x].nombre +  ' (' +  data.categorias[i].etiquetas[x].perfiles_geneticos_asociados.length   +    ')</option>');	
							                	}

							                	$("select[name='etiquetas2[]']").append('<optgroup id="' + data.categorias[i].nombre  + '2" label="'+ data.categorias[i].nombre +'"></optgroup>');
							                	for(x in data.categorias[i].etiquetas){
							                		$('optgroup[id="'+ data.categorias[i].nombre +'2"]').append('<option value='+ data.categorias[i].etiquetas[x].id +'>'+ '\t' + data.categorias[i].etiquetas[x].nombre +  ' (' +  data.categorias[i].etiquetas[x].perfiles_geneticos_asociados.length   +    ')</option>');	
							                	}

							                	$("select[name='etiquetas3[]']").append('<optgroup id="' + data.categorias[i].nombre + '3" label="'+ data.categorias[i].nombre +'"></optgroup>');
							                	for(x in data.categorias[i].etiquetas){
							                		$('optgroup[id="'+ data.categorias[i].nombre +'3"]').append('<option value='+ data.categorias[i].etiquetas[x].id +'>'+ '\t' + data.categorias[i].etiquetas[x].nombre +  ' (' +  data.categorias[i].etiquetas[x].perfiles_geneticos_asociados.length   +    ')</option>');	
							                	}

							                	$("select[name='etiquetas4[]']").append('<optgroup id="' + data.categorias[i].nombre + '4" label="'+ data.categorias[i].nombre +'"></optgroup>');
							                	for(x in data.categorias[i].etiquetas){
							                		$('optgroup[id="'+ data.categorias[i].nombre +'4"]').append('<option value='+ data.categorias[i].etiquetas[x].id +'>'+ '\t' + data.categorias[i].etiquetas[x].nombre +  ' (' +  data.categorias[i].etiquetas[x].perfiles_geneticos_asociados.length   +    ')</option>');	
							                	}
							                }

							              multipleDefault1 = new Choices(document.getElementById('etiquetas'), {
										    searchResultLimit: 100,
										    resetScrollPosition: false,
										    position: 'button',
									      });

									      multipleDefault2 = new Choices(document.getElementById('etiquetas2'), {
										    searchResultLimit: 100,
										    resetScrollPosition: false,
										    position: 'button',
									      });

									      multipleDefault3 = new Choices(document.getElementById('etiquetas3'), {
										    searchResultLimit: 100,
										    resetScrollPosition: false,
										    position: 'button',
									      });

									      multipleDefault4 = new Choices(document.getElementById('etiquetas4'), {
										    searchResultLimit: 100,
										    resetScrollPosition: false,
										    position: 'button',
									      });
									      $('button').removeClass('disabled');
								          mensaje_exitoso('.mensaje_de_error8', 'Se asignaron correctamente las etiquetas');
								        }
								    },
								    error: function() {
								    	$('button').removeClass('disabled');
								    	mensaje_error('.mensaje_de_error8', 'Las etiquetas no pudieron ser asignadas, intentelo de nuevo'); 
								    }
								});	
							}
							else{
								//mandar mensaje en pantala de error
								$('button').removeClass('disabled');
								mensaje_error('.mensaje_de_error8', 'Debe seleccionar al menos una etiqueta'); 
							}						
							

							// $.post(url, {form.serialize(), seleccionados}, function(result){
				   //      		console.log(result.newData)
				   //      	}).fail(function(){
				   //      		alert('Fallo la consulta');
				   //      	});
						}	
				    });


				  	$('#borrar_etiquetas').click( function (e) {
				  	 	e.preventDefault();
				  	 	$('button').addClass('disabled');
				  	 	var seleccionados = [];

				  	 	for (var i = 0; i < oTable.rows('.selected').data().length; i++) {						 
						    seleccionados.push(oTable.rows('.selected').data()[i].id);
						}

						if(seleccionados.length == 0){
							// mandar mensaje de error
							mensaje_error('.mensaje_de_error8', 'No selecciono ningun perfil genetico'); 
							$('button').removeClass('disabled');
						}
						else{
							if($('.etiquetas4').val() != ''){

								var form = $(this).parents('form');
					        	var url = form.attr('action');
					        	var data = form.serializeArray();
								data.push({name: 'seleccionados', value: seleccionados});

					        	$.ajax({
								    type: "POST",
								    url: url,
								    data: data,
								    dataType: "json",

								    success: function(data) {
								        if(data.contador == 0){
								        	mensaje_exitoso('.mensaje_de_error8', 'No existen las etiquetas en los perfiles seleccionados');
								        	$('button').removeClass('disabled');
								        }
								        else{
								        	multipleDefault1.destroy();
								        	multipleDefault2.destroy();
								        	multipleDefault3.destroy();
								        	multipleDefault4.destroy();

								        	for(i in data.categorias){
							                	$("select[name='etiquetas[]']").append('<optgroup id="' + data.categorias[i].nombre + '1" label="'+ data.categorias[i].nombre +'"></optgroup>');
							                	for(x in data.categorias[i].etiquetas){
							                		$('optgroup[id="'+ data.categorias[i].nombre +'1"]').append('<option value='+ data.categorias[i].etiquetas[x].id +'>'+ '\t' + data.categorias[i].etiquetas[x].nombre +  ' (' +  data.categorias[i].etiquetas[x].perfiles_geneticos_asociados.length   +    ')</option>');	
							                	}

							                	$("select[name='etiquetas2[]']").append('<optgroup id="' + data.categorias[i].nombre  + '2" label="'+ data.categorias[i].nombre +'"></optgroup>');
							                	for(x in data.categorias[i].etiquetas){
							                		$('optgroup[id="'+ data.categorias[i].nombre +'2"]').append('<option value='+ data.categorias[i].etiquetas[x].id +'>'+ '\t' + data.categorias[i].etiquetas[x].nombre +  ' (' +  data.categorias[i].etiquetas[x].perfiles_geneticos_asociados.length   +    ')</option>');	
							                	}

							                	$("select[name='etiquetas3[]']").append('<optgroup id="' + data.categorias[i].nombre + '3" label="'+ data.categorias[i].nombre +'"></optgroup>');
							                	for(x in data.categorias[i].etiquetas){
							                		$('optgroup[id="'+ data.categorias[i].nombre +'3"]').append('<option value='+ data.categorias[i].etiquetas[x].id +'>'+ '\t' + data.categorias[i].etiquetas[x].nombre +  ' (' +  data.categorias[i].etiquetas[x].perfiles_geneticos_asociados.length   +    ')</option>');	
							                	}

							                	$("select[name='etiquetas4[]']").append('<optgroup id="' + data.categorias[i].nombre + '4" label="'+ data.categorias[i].nombre +'"></optgroup>');
							                	for(x in data.categorias[i].etiquetas){
							                		$('optgroup[id="'+ data.categorias[i].nombre +'4"]').append('<option value='+ data.categorias[i].etiquetas[x].id +'>'+ '\t' + data.categorias[i].etiquetas[x].nombre +  ' (' +  data.categorias[i].etiquetas[x].perfiles_geneticos_asociados.length   +    ')</option>');	
							                	}
							                }							             

							              multipleDefault1 = new Choices(document.getElementById('etiquetas'), {
										    searchResultLimit: 100,
										    resetScrollPosition: false,
										    position: 'button',
									      });

									      multipleDefault2 = new Choices(document.getElementById('etiquetas2'), {
										    searchResultLimit: 100,
										    resetScrollPosition: false,
										    position: 'button',
									      });

									      multipleDefault3 = new Choices(document.getElementById('etiquetas3'), {
										    searchResultLimit: 100,
										    resetScrollPosition: false,
										    position: 'button',
									      });

									      multipleDefault4 = new Choices(document.getElementById('etiquetas4'), {
										    searchResultLimit: 100,
										    resetScrollPosition: false,
										    position: 'button',
									      });

								          mensaje_exitoso('.mensaje_de_error8', 'Se eliminaron exitosamente lass etiquetas en los perfiles seleccionados');
								          $('button').removeClass('disabled');
								        }
								    },
								    error: function() {
								    	mensaje_error('.mensaje_de_error8', 'Las etiquetas no pudieron ser eliminadas, intentelo de nuevo');
								    	$('button').removeClass('disabled'); 
								    }
								});	
							}
							else{
								//mandar mensaje en pantala de error
								mensaje_error('.mensaje_de_error8', 'Debe seleccionar al menos una etiqueta'); 
								$('button').removeClass('disabled');
							}						
						}	
				    }); 

				  	$(".buscar").click(function(e){
				        e.preventDefault();
			        	var form = $(this).parents('form');
			        	var url = form.attr('action');

			        	var tipo_de_metadato = $("select[name='id_tipo_de_metadato']").val();
			        	var filtro_por_metadato = $("input[name='filtro_por_metadato']").val();
			        	var nombre_tipo_de_metadato = $("option[value='"+ tipo_de_metadato +"']").val();	
		

			        	if(tipo_de_metadato != null && filtro_por_metadato != ""){
			        		$('.mensaje_de_error').fadeOut();
			        		$.post(url, form.serialize(), function(result){
				        		oTable.clear();
				        		for (var i = 0, len = result.newData.length; i < len; i++) {

								  oTable.row.add({
								  	'id': result.newData[i].id,
								  	"identificador" : '<a href="perfiles_geneticos/' + result.newData[i].id + '">'+ result.newData[i].identificador +'</a>',
								 	"id_externo": result.newData[i].id_externo,
					        		"numero_de_marcadores": result.newData[i].numero_de_marcadores,
					        		"numero_de_homocigotos": result.newData[i].numero_de_homocigotos,
					        		'name': result.newData[i].name,
					        		'created_at': result.newData[i].created_at
								  });
								}
				        		oTable.draw();
				        		mensaje_exitoso('.mensaje_de_error', 'Busqueda finalizada');

				        	}).fail(function(){
				        		alert('Fallo la consulta');
				        	});
				        	
			        	}
			        	else{
			        		mensaje_error('.mensaje_de_error', 'Debe seleccionar un tipo de metadato y escribir un texto en el filtro');
			        	}
				    });

				    $(".buscar2").click(function(e){
				        e.preventDefault();
			        	var form = $(this).parents('form');
			        	var url = form.attr('action');

			        	var etiquetas = $("select[name='etiquetas[]']").val();	

			        	if(etiquetas.length > 0){
			        		$('.mensaje_de_error2').fadeOut();
			        		$.post(url, form.serialize(), function(result){
				        		oTable.clear();
				        		for (var i = 0, len = result.newData.length; i < len; i++) {

								  oTable.row.add({
								  	'id': result.newData[i].id,
								  	"identificador" : '<a href="perfiles_geneticos/' + result.newData[i].id + '">'+ result.newData[i].identificador +'</a>',
								 	"id_externo": result.newData[i].id_externo,
					        		"numero_de_marcadores": result.newData[i].numero_de_marcadores,
					        		"numero_de_homocigotos": result.newData[i].numero_de_homocigotos,
					        		'name': result.newData[i].name,
					        		'created_at': result.newData[i].created_at
								  });
								}
				        		oTable.draw();
				        		mensaje_exitoso('.mensaje_de_error2', 'Busqueda terminada')

				        	}).fail(function(){
				        		alert('Fallo la consulta');
				        	});
				        	
			        	}
			        	else{
			        		mensaje_error('.mensaje_de_error2', 'Debe seleccionar al menos una etiqueta' );
			        	}
				    });

				    $(".buscar3").click(function(e){
				        e.preventDefault();
			        	var form = $(this).parents('form');
			        	var url = form.attr('action');

			        	var fuente = $("select[name='id_fuente']").val();	

			        	if(fuente != null){
			        		$('.mensaje_de_error3').fadeOut();
			        		$.post(url, form.serialize(), function(result){
				        		oTable.clear();
				        		for (var i = 0, len = result.newData.length; i < len; i++) {

								  oTable.row.add({
								  	'id': result.newData[i].id,
								  	"identificador" : '<a href="perfiles_geneticos/' + result.newData[i].id + '">'+ result.newData[i].identificador +'</a>',
								 	"id_externo": result.newData[i].id_externo,
					        		"numero_de_marcadores": result.newData[i].numero_de_marcadores,
					        		"numero_de_homocigotos": result.newData[i].numero_de_homocigotos,
					        		'name': result.newData[i].name,
					        		'created_at': result.newData[i].created_at
								  });
								}
				        		oTable.draw();
				        		mensaje_exitoso('.mensaje_de_error3', 'Busqueda finalizada');

				        	}).fail(function(){
				        		alert('Fallo la consulta');
				        	});
				        	
			        	}
			        	else{
			        		mensaje_error('.mensaje_de_error3', 'Debe seleccionar una fuente');
			        	}
				    });

				    $(".buscar4").click(function(e){
				        e.preventDefault();
			        	var form = $(this).parents('form');
			        	var url = form.attr('action');
			        	var id_importacion = $(".id_importacion").val();
			        	
			        	if(id_importacion != ""){
			        		$.post(url, form.serialize(), function(result){
				        		oTable.clear();
				        		for (var i = 0, len = result.newData.length; i < len; i++) {

								  oTable.row.add({
								  	'id': result.newData[i].id,
								  	"identificador" : '<a href="perfiles_geneticos/' + result.newData[i].id + '">'+ result.newData[i].identificador +'</a>',
								 	"id_externo": result.newData[i].id_externo,
					        		"numero_de_marcadores": result.newData[i].numero_de_marcadores,
					        		"numero_de_homocigotos": result.newData[i].numero_de_homocigotos,
					        		'name': result.newData[i].name,
					        		'created_at': result.newData[i].created_at
								  });
								}
				        		oTable.draw();
				        		mensaje_exitoso('.mensaje_de_error4', 'Busqueda finalizada');

				        	}).fail(function(){
				        		alert('Fallo la consulta');
				        	});
				        	
			        	}
			        	else{
			        		mensaje_error('.mensaje_de_error4', 'Debe escribir almenos un ID' )
			        	}
				    });

				    $(".buscar5").click(function(e){
				        e.preventDefault();
			        	var form = $(this).parents('form');
			        	var url = form.attr('action');
			        	var id_importacion = $(".id_importacion2").val();
			        	var id_tipo_de_metadato = $(".id_tipo_de_metadato2").val();
			        	var metadato = $(".metadato2").val();

			        	if(id_importacion != ''){
			        		if(id_tipo_de_metadato != null){
			        			if(metadato != ''){
					        		$.post(url, form.serialize(), function(result){
						        		mensaje_exitoso('.mensaje_de_error5', 'Busqueda finalizada');	
						        		oTable.clear();
						        		for (var i = 0, len = result.newData.length; i < len; i++) {

										  oTable.row.add({
										  	'id': result.newData[i].id,
										  	"identificador" : '<a href="perfiles_geneticos/' + result.newData[i].id + '">'+ result.newData[i].identificador +'</a>',
										 	"id_externo": result.newData[i].id_externo,
							        		"numero_de_marcadores": result.newData[i].numero_de_marcadores,
							        		"numero_de_homocigotos": result.newData[i].numero_de_homocigotos,
							        		'name': result.newData[i].name,
							        		'created_at': result.newData[i].created_at
										  });
										}
						        		oTable.draw()
						        	}).fail(function(){
						        		alert('Fallo la consulta');
						        	});	
			        			}
			        			else{
			        				mensaje_error('.mensaje_de_error5', 'Debe escribir el metadato');
			        			}
			        		}
			        		else{
			        			mensaje_error('.mensaje_de_error5', 'Debe seleccionar un tipo de metadato');
			        		}
			        	}
			        	else{
			        		mensaje_error('.mensaje_de_error5', 'Debe escribir almenos un ID');			      
			        	}
				    });

				    $(".buscar6").click(function(e){
				        e.preventDefault();
			        	var form = $(this).parents('form');
			        	var url = form.attr('action');

			        	var id_tipo_de_metadato = $(".id_tipo_de_metadato3").val();
			        	var id_fuente = $(".id_fuente2").val();
			        	var metadato = $(".metadato3").val();

			        	if(id_fuente != null){
			        		if(id_tipo_de_metadato != null){
			        			if(metadato != ''){
					        		$.post(url, form.serialize(), function(result){
						        		mensaje_exitoso('.mensaje_de_error6', 'Busqueda finalizada');	
						        		oTable.clear();
						        		for (var i = 0, len = result.newData.length; i < len; i++) {

										  oTable.row.add({
										  	'id': result.newData[i].id,
										  	"identificador" : '<a href="perfiles_geneticos/' + result.newData[i].id + '">'+ result.newData[i].identificador +'</a>',
										 	"id_externo": result.newData[i].id_externo,
							        		"numero_de_marcadores": result.newData[i].numero_de_marcadores,
							        		"numero_de_homocigotos": result.newData[i].numero_de_homocigotos,
							        		'name': result.newData[i].name,
							        		'created_at': result.newData[i].created_at
										  });
										}
						        		oTable.draw()
						        	}).fail(function(){
						        		alert('Fallo la consulta');
						        	});	
			        			}
			        			else{
			        				mensaje_error('.mensaje_de_error6', 'Debe escribir el metadato');
			        			}
			        		}
			        		else{
			        			mensaje_error('.mensaje_de_error6', 'Debe seleccionar un tipo de metadato');
			        		}
			        	}
			        	else{
			        		mensaje_error('.mensaje_de_error6', 'Debe seleccionar una fuente');			      
			        	}
				    });

				    $(".buscar7").click(function(e){
				        e.preventDefault();
			        	var form = $(this).parents('form');
			        	var url = form.attr('action');

			        	var etiquetas = $(".etiquetas2").val();
			        	var id_fuente = $(".id_fuente3").val();
			        	if(id_fuente != null){
			        		if(etiquetas.length > 0){
				        		$.post(url, form.serialize(), function(result){
					        		mensaje_exitoso('.mensaje_de_error7', 'Busqueda finalizada');	
					        		oTable.clear();
					        		for (var i = 0, len = result.newData.length; i < len; i++) {

									  oTable.row.add({
									  	'id': result.newData[i].id,
									  	"identificador" : '<a href="perfiles_geneticos/' + result.newData[i].id + '">'+ result.newData[i].identificador +'</a>',
									 	"id_externo": result.newData[i].id_externo,
						        		"numero_de_marcadores": result.newData[i].numero_de_marcadores,
						        		"numero_de_homocigotos": result.newData[i].numero_de_homocigotos,
						        		'name': result.newData[i].name,
						        		'created_at': result.newData[i].created_at
									  });
									}
					        		oTable.draw()
					        	}).fail(function(){
					        		alert('Fallo la consulta');
					        	});	
			        		}
			        		else{
			        			mensaje_error('.mensaje_de_error7', 'Debe seleccionar al menos una etiqueta');
			        		}
			        	}
			        	else{
			        		mensaje_error('.mensaje_de_error7', 'Debe seleccionar una fuente');			      
			        	}
				    });

				    $(".restablecer").click(function(e){
				        e.preventDefault();
				        $.get('{{route('perfiles_geneticos.restablecer')}}', function(result){
				            mensaje_exitoso('.mensaje_de_error7', 'Busqueda finalizada');	
			        		oTable.clear();
			        		for (var i = 0, len = result.newData.length; i < len; i++) {

							  oTable.row.add({
							  	'id': result.newData[i].id,
							  	"identificador" : '<a href="perfiles_geneticos/' + result.newData[i].id + '">'+ result.newData[i].identificador +'</a>',
							 	"id_externo": result.newData[i].id_externo,
				        		"numero_de_marcadores": result.newData[i].numero_de_marcadores,
				        		"numero_de_homocigotos": result.newData[i].numero_de_homocigotos,
				        		'name': result.newData[i].name,
				        		'created_at': result.newData[i].created_at
							  });
							}
			        		oTable.draw();
			        		ocultar_mensajes();
				        });
					    
				    });

				    function mensaje_exitoso( nombre_clase, mensaje){
				    	$(''+ nombre_clase).fadeOut();
	        			$(''+ nombre_clase).removeClass('bg-warning');
				        $(''+ nombre_clase).addClass('bg-success text-white');
	        			$(''+ nombre_clase).text('' + mensaje);
	        			$(''+ nombre_clase).fadeIn();
				    }

				    function mensaje_error( nombre_clase, mensaje){
				    	$(''+ nombre_clase).fadeOut();
	        			$(''+ nombre_clase).removeClass('bg-success text-white');
				        $(''+ nombre_clase).addClass('bg-warning');
	        			$(''+ nombre_clase).text('' + mensaje);
	        			$(''+ nombre_clase).fadeIn();	
				    }

				    function ocultar_mensajes(){
				    	$('.mensaje_de_error').fadeOut();
				    	$('.mensaje_de_error2').fadeOut();
				    	$('.mensaje_de_error3').fadeOut();
				    	$('.mensaje_de_error4').fadeOut();
				    	$('.mensaje_de_error5').fadeOut();
				    	$('.mensaje_de_error6').fadeOut();
				    	$('.mensaje_de_error7').fadeOut();
				    	$('.mensaje_de_error8').fadeOut();
				    }

				  var multipleDefault1 = new Choices(document.getElementById('etiquetas'), {
				    searchResultLimit: 100,
				    resetScrollPosition: false,
				    position: 'button',
			      });

			      var multipleDefault2 = new Choices(document.getElementById('etiquetas2'), {
				    searchResultLimit: 100,
				    resetScrollPosition: false,
				    position: 'button',
			      });

			      var multipleDefault3 = new Choices(document.getElementById('etiquetas3'), {
				    searchResultLimit: 100,
				    resetScrollPosition: false,
				    position: 'button',
			      });

			      var multipleDefault4 = new Choices(document.getElementById('etiquetas4'), {
				    searchResultLimit: 100,
				    resetScrollPosition: false,
				    position: 'button',
			      });
				});

			</script>
		</div>
	</div>
@endsection