@extends('layouts.app')

@section('title')
    ADN México | Detalle de perfil genetico
@endsection

@section('script')
    <!-- Scripts -->
    <link rel="stylesheet" href="{{asset('css/choices.min.css?version=3.0.4')}}">
  	<script src="{{asset('js/choices.min.js?version=3.0.4s')}}"></script>
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>	
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')
	<?php $usuario = App\User::find(Illuminate\Support\Facades\Auth::id());?>
	<div class="card-block mb-3">
		<div class="container">
			<div class="card-title p-3 mb-3 card-header">
				<img src="{{asset('images/genotipos.png')}}" alt="" width="80" height="70" class=""><span class="h4 ml-3 font-weight-bold"> EDICION DE PERFIL GENETICO</span>
				
				<div class="float-right">
					@can('perfiles_geneticos.show')
					<a href="{{route('perfiles_geneticos.index')}}" class="btn btn-danger float-right mb-2"><i class="fa fa-chevron-left mr-2"></i> Regresar a la lista de perfiles geneticos</a>
					@endcan
				</div>
			</div>	
		</div>	
	</div>

	<div class="container">
		<div class="card mb-5">
							<!--Botones colapsables-->
							
							<span class="bg-warning form-control mensaje_de_error2 text-center mt-2 mb-2">Mensaje de error</span>
							<script type="text/javascript"> $('.mensaje_de_error2').hide();</script>

							@if($usuario->estado->nombre == 'CNB')

							<div class="d-flex flex-row justify-content-between">
								@can('categorias.store')
								<div class="p-2">
									<button class="btn btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapseCategoria" aria-expanded="false" aria-controls="collapseExample">
										<i class="fa fa-th-large"></i> Agregar categoria
									</button>
								</div>
								@endcan
								@can('etiquetas.store')
								<div class="p-2">
									<button class="btn btn-success" type="button" data-toggle="collapse" data-target="#collapseEtiquetas" aria-expanded="false" aria-controls="collapseExample">
										<i class="fa fa-bookmark"></i> Agregar Etiquetas
									</button>
								</div>
								@endcan
							</div>

							@endif

							<div class="">
								<!--formulario para cateorias colapsable-->
								
								<div class="collapse float-left mb-2 ml-3" id="collapseCategoria">
								    <div class="card-header bg-secondary text-white">
								  		Crear nueva categoria
								  	</div>
								  <div class="card">
								  	{!! Form::open(['route' => 'importaciones_perfiles.crear_categoria', 'method'=> 'POST' ]) !!}
									<div class="p-3">
											<div class="form-group">
												{!! Form::label('nombre' , 'Nombre')!!}
												{!! Form::text('nombre' , null , [ 'class' => 'form-control input_categoria', 'placeholder'=> 'Ingrese una categoria' , 'required'])!!}
											</div>
											<div class="form-group">
												{!! Form::submit('Guardar', ['class' => 'btn btn-primary mt-2 GuardarCategoria']) !!}
											</div>
									</div>
									{!! Form::close() !!}
								  </div>
								</div>
								
								<!--Formulario para etiquetas colapsable-->
								
								<div class="collapse w-50 float-right mb-2 mr-3" id="collapseEtiquetas">
								  	<div class="card">
								  		<div class="card-header bg-success text-white">
									  		Crear etiquetas
									  	</div>
								  		{!! Form::open(['route' => 'importaciones_perfiles.crear_etiquetas', 'method'=> 'POST' ]) !!}
											<div class="p-3">
												<div class="form-group">
													<p class="text-info ">Pueden ser asignadas multiples etiquetas separandolas por una coma</p>
													{!! Form::label('nombre' , 'Nombre')!!}
													{!! Form::text('nombre' , null , [ 'class' => 'form-control EtiquetasAjax', 'placeholder'=> 'Ejemplo 1, Ejemplo 2, Ejemplo 3' , 'required'])!!}
													<label for="categoria_id" class="mt-2">Categoria</label>
													<select name="categoria_id" class="form-control select_categoria" required>
													  <option disabled selected>Seleccione una categoria</option>
													  @foreach($categorias as $categoria)
													  	<option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
													  @endforeach
													</select>
												</div>
												
												<div class="form-group">
													{!! Form::submit('Guardar', ['class' => 'btn btn-primary mr-3 GuardarEtiquetas']) !!}
													<img src="{{asset('images/carga.gif')}}" width="120" height="120" id="carga">
												</div>
											</div>
										{!! Form::close() !!}
									</div>
							</div>
						</div>
	

		{!! Form::open(array('route' => ['perfiles_geneticos.update',$perfil_genetico->id], 'method' => 'PUT')) !!}﻿
		<div class="d-flex justify-content-between">
				<div class="card h-50 float-right instrucciones_captura_manual">
						<div class="card-header bg-danger text-white d-flex justify-content-center">
							<div class="">ID interno: {{$perfil_genetico->identificador}}</div> <div class="ml-2">ID externo: {{$perfil_genetico->id_externo}}</div>
						</div>

						<div class="card-body text-justify">
							<ul class="nav nav-tabs" id="myTab" role="tablist">
							  <li class="nav-item">
							    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">DATOS DE CADAVERES O RESTOS OSEOS</a>
							  </li>
							  <li class="nav-item">
							    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">DATOS DE FAMILIARES</a>
							  </li>
							</ul>
							<div class="tab-content" id="myTabContent">
							  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
								<div class="card-header text-center text-white bg-danger mt-3">Datos de hallazgo</div>
								  <div class="row pl-3 pr-3 pt-3">
								    <div class="col">
								      {{Form::label('fecha_de_hallazgo', 'Fecha de hallazgo')}}
								      {{Form::date('fecha_de_hallazgo', $fecha_de_hallazgo , ['class' => 'form-control'])}}
								    </div>
								    <div class="col">
								      {{Form::label('lugar', 'Lugar')}}
								      {{Form::text('lugar', $lugar , ['class' => 'form-control'])}}
								    </div>
								  </div>
								  <div class="row pl-3 pr-3 pt-3">
								    <div class="col">
								      {{Form::label('paraje', 'Paraje')}}
								      {{Form::text('paraje', $paraje , ['class' => 'form-control'])}}
								    </div>
								    <div class="col">
								      {{Form::label('fosa', 'Fosa')}}
								      {{Form::text('fosa', $fosa , ['class' => 'form-control'])}}
								    </div>
								  </div>
								  <hr>
							  </div>
							  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
							  	<div class="card-header text-center text-white bg-danger mt-3">Datos de Familiares</div>
								  <div class="row pl-3 pr-3 pt-3">
								   <div class="col">
								      {{Form::label('nombre_del_donante', 'Nombre del donante')}}
								      {{Form::text('nombre_del_donante', $nombre_del_donante , ['class' => 'form-control'])}}
								   </div>
								   <div class="col">
								      {{Form::label('curp_del_familiar', 'CURP del familiar')}}
								      {{Form::text('curp_del_familiar', $curp_del_familiar , ['class' => 'form-control curp'])}}
								    </div>
								   </div>
								   <div class="row pl-3 pr-3 pt-3">
								   		<div class="col">
									      {{Form::label('nombre_del_desaparecido', 'Nombre del desaparecido')}}
									      {{Form::text('nombre_del_desaparecido', $nombre_del_desaparecido , ['class' => 'form-control'])}}
									    </div>
									    <div class="col">
									      {{Form::label('curp_del_desaparecido', 'CURP del desaparecido')}}
									      {{Form::text('curp_del_desaparecido', $curp_del_desaparecido , ['class' => 'form-control curp'])}}
									    </div>
									</div>
									<div class="row pl-3 pr-3 pt-3">
									    <div class="col">
									      {{Form::label('parentesco_con_el_desaparecido', 'Parentesco con el desaparecido')}}
									      {{Form::text('parentesco_con_el_desaparecido', $parentesco_con_el_desaparecido , ['class' => 'form-control'])}}
									    </div>
									</div>
								 	<hr>
							  </div>
							  <div class="row pl-3 pr-3 pt-3">
							    <div class="col">
							      {{Form::label('clave_de_muestra', 'Clave de muestra')}}
							      {{Form::text('clave_de_muestra', $clave_de_muestra , ['class' => 'form-control', 'required'])}}
							    </div>
							    <div class="col">
							      {{Form::label('descripcion_de_la_muestra', 'Descripcion de la muestra')}}
							      {{Form::text('descripcion_de_la_muestra', $descripcion_de_la_muestra , ['class' => 'form-control'])}}
							    </div>
							</div>
							<div class="row pl-3 pr-3">
							    <div class="col">
							      {{Form::label('observaciones', 'Observaciones')}}
							      {{Form::text('observaciones', $observaciones , ['class' => 'form-control'])}}
							    </div>
							    <div class="col">
							      {{Form::label('talla', 'Talla')}}
							      {{Form::text('talla', $talla , ['class' => 'form-control'])}}
							    </div>
							</div>
							<div class="row pl-3 pr-3">
							    <div class="col">
							      {{Form::label('peso', 'Peso')}}
							      {{Form::text('peso', $peso , ['class' => 'form-control'])}}
							    </div>
							    <div class="col">
							      {{Form::label('s_particulares_o_malformaciones', 'S. Particulares/Malformaciones')}}
							      {{Form::text('s_particulares_o_malformaciones', $s_particulares_o_malformaciones , ['class' => 'form-control'])}}
							    </div>
							</div>
							<div class="row pl-3 pr-3">
							    <div class="col">
							      {{Form::label('tatuaje', 'Tatuaje')}}
							      {{Form::text('tatuaje', $tatuaje , ['class' => 'form-control'])}}
							    </div>
							    <div class="col">
							      {{Form::label('sexo', 'Sexo')}}
							      {{Form::text('sexo', $sexo , ['class' => 'form-control'])}}
							    </div>
							</div>
							<div class="row pl-3 pr-3">
							    <div class="col">
							      {{Form::label('ci_nuc_ap', 'C.I/NUC/A.P.')}}
							      {{Form::text('ci_nuc_ap', $ci_nuc_ap , ['class' => 'form-control'])}}
							    </div>
							    <div class="col">
							      {{Form::label('fecha_desaparicion', 'Fecha de desaparicion')}}
							      <br>
							      {{Form::date('fecha_desaparicion', $fecha_desaparicion, ['class'=> 'form-control'])}}
							    </div>
							</div>
							<div class="row pl-3 pr-3">
							    <div class="col">
							      {{Form::label('lugar_de_desaparicion', 'Lugar de desaparicion')}}
							      {{Form::text('lugar_de_desaparicion', $lugar_de_desaparicion , ['class' => 'form-control'])}}
							    </div>
							    <div class="col">
							      {{Form::label('no_de_caso_relacionado', 'No. de caso relacionado')}}
							      <br>
							      {{Form::text('no_de_caso_relacionado', $no_de_caso_relacionado, ['class'=>'form-control'])}}
							    </div>
							</div>
							<hr>
						   </div>
						   <div class="d-flex flex-row justify-content-between mb-3 bg-light text-dark p-3">
  								<div class="row">
  									<div class="col">
  										<label for="id_fuente" class="mt-2">Fuente</label>
										<select name="id_fuente" class="form-control" required="">
										  @foreach($fuentes as $fuente)
										  	@if($perfil_genetico->fuente->id == $fuente->id)
										  	<option selected value="{{$fuente->id}}">{{$fuente->nombre}}</option>
										  	@else
										  	<option value="{{$fuente->id}}">{{$fuente->nombre}}</option>
										  	@endif
										  @endforeach
										</select>
  									</div>
									<div class="col">
										<label for="etiquetas">Seleccionar etiquetas para los perfiles</label>
										<select class="form-control etiquetas_ajax" name="etiquetas[]" id="etiquetas" placeholder="Seleccione las etiquetas" multiple>
										<?php $etiqueta_asignada?>	
										@foreach($categorias as $categoria)
											<optgroup label="{{ strtoupper($categoria->nombre)}}">
												@foreach($categoria->etiquetas as $etiqueta)
													<?php 
													$etiqueta_asignada = App\EtiquetaAsignada::where('id_perfil_genetico', '=', $perfil_genetico->id)->where('id_etiqueta', '=', $etiqueta->id)->first(); 
													?>
														@if(empty($etiqueta_asignada))
															<option value="{{$etiqueta->id}}">{{$etiqueta->nombre}}</option>
														@else
															<option selected value="{{$etiqueta->id}}">{{$etiqueta->nombre}}</option>
														@endif
												@endforeach	
											</optgroup>
										@endforeach
										</select>
									</div>
  								</div>
  								<hr>
  							</div>

  							<div class="card-footer">
  								<div class="float-right">
  									{!!Form::submit('Guardar perfil genetico', ['class' => 'btn btn-primary m-3 float-right'])!!}
  								</div>
  							</div>
						</div>
					</div>
				<div class="card w-25">
					<table id="myTable" class="table">
						<thead class="card-header bg-dark text-white">
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
										$marcador_individual = App\Alelo::where('id_perfil_genetico', '=', $perfil_genetico->id)->where('id_marcador', '=', $marcador->id)->first();

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
									<td>{!!Form::text($alelo_1, $marcador_alelo_1, ['class' => "text-center w-100 $alelo_1"])!!}</td>
									@if($marcador->id_tipo_de_marcador == 2 &&  $marcador->nombre <> 'dys385' || $marcador->id_tipo_de_marcador == 3 || $marcador->nombre == 'yindel')
										<td>{!!Form::text($alelo_2, $marcador_alelo_2, ['class' => "text-center w-100 $alelo_2", 'disabled'])!!}</td>
									@else
										<td>{!!Form::text($alelo_2, $marcador_alelo_2, ['class' => "text-center w-100 $alelo_2"])!!}</td>
									@endif
								</tr>
								@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>	
		{!! Form::close() !!}
	</div>
	<script>
      $(document).ready(function() {

    	$('#carga').fadeOut();

		function quitaacentos(t){
	        á="a";é="e";í="i";ó="o";ú="u";ñ="n";ä="a";ë="e";ï= "i";ö="o";ü="u";
	        acentos=/[áéíóúñäëïöü]/g;
	        return t.replace(acentos,function($1){
	            return eval($1);
	        });
	    }

	    $(".GuardarCategoria").click(function(e){
	        e.preventDefault();
	        var form = $(this).parents('form');
	        var url = form.attr('action');  
	        var categoria = quitaacentos($(".input_categoria").val()).toUpperCase();
	        if(categoria != "" ){
	            $('.mensaje_de_error2').fadeOut();
	            $.post(url, form.serialize(), function(result){
	                $('.select_categoria').append('<option value="'+ result.categoria.id +'" selected="selected">'+ result.categoria.nombre  +'</option>');
	                $('.mensaje_de_error2').text("Categoria agregada exitosamente");
	                $('.mensaje_de_error2').removeClass('bg-warning');
	                $('.mensaje_de_error2').addClass('bg-success text-white');
	                $('.mensaje_de_error2').fadeIn();

	            }).fail(function(jqXHR, textStatus, errorThrown){
	                $('.mensaje_de_error2').addClass('bg-warning');
	                $('.mensaje_de_error2').text(jqXHR.responseJSON.errors.nombre[0]);
	                $('.mensaje_de_error2').fadeIn()
	            });
	        }
	        else{
	        	$('.mensaje_de_error2').addClass('bg-warning');
                $('.mensaje_de_error2').text('Debe ingresar un nombre para la categoria');
                $('.mensaje_de_error2').fadeIn()
	        }
	    });

	    $(".GuardarEtiquetas").click(function(e){
	        e.preventDefault();
	        $('.GuardarEtiquetas').text("Espere un momento");
            $('.GuardarEtiquetas').addClass('disabled');
            $('#carga').fadeIn();
	        var form = $(this).parents('form');
	        var url = form.attr('action');  
	        var categoria = $(".select_categoria").val();
	        var etiquetas = $(".EtiquetasAjax").val();
	        if(categoria != null && etiquetas != "" ){
	            $('.mensaje_de_error2').fadeOut();
	            $.post(url, form.serialize(), function(result){
	            	console.log(result.categorias);
	                multipleDefault.destroy();
	                for(i in result.categorias){
	                	//result.categorias[i].nombre       Nombre de las categorias
	                	//result.categorias[i].id 			Id de de las categorias
	                	
	                	$("select[name='etiquetas[]']").append('<optgroup id="' + result.categorias[i].nombre + '" label="'+ result.categorias[i].nombre +'"></optgroup>');
	                	for(x in result.categorias[i].etiquetas){
	                		$('optgroup[id="'+ result.categorias[i].nombre +'"]').append('<option value='+ result.categorias[i].etiquetas[x].id +'>'+ '\t' + result.categorias[i].etiquetas[x].nombre +  ' (' +  result.categorias[i].etiquetas[x].perfiles_geneticos_asociados.length   +    ')</option>');	
	                	}
	                }

	                multipleDefault = new Choices(document.getElementById('etiquetas'));

	                multipleFetch = new Choices('#choices-multiple-remote-fetch', {
					placeholder: true,
					placeholderValue: 'Pick an Strokes record',
					maxItemCount: 5,
					}).ajax(function(callback) {
					fetch('https://api.discogs.com/artists/55980/releases?token=QBRmstCkwXEvCjTclCpumbtNwvVkEzGAdELXyRyW')
					  .then(function(response) {
					    response.json().then(function(data) {
					      callback(data.releases, 'title', 'title');
					    });
					  })
					  .catch(function(error) {
					    console.error(error);
					  });
					});
	                
	                $('.mensaje_de_error2').text("Etiqueta(s) agregada(s) exitosamente");
	                $('.mensaje_de_error2').removeClass('bg-warning');
	                $('.mensaje_de_error2').addClass('bg-success text-white');
	                $('.mensaje_de_error2').fadeIn();

	                $('.GuardarEtiquetas').text("Guardar");
		            $('.GuardarEtiquetas').removeClass('disabled');
		            $('#carga').fadeOut();

	            }).fail(function(jqXHR, textStatus, errorThrown){
	                $('.mensaje_de_error2').addClass('bg-warning');
	                $('.mensaje_de_error2').text(jqXHR.responseJSON.errors.nombre[0]);
	                $('.mensaje_de_error2').fadeIn()
	                $('.GuardarEtiquetas').text("Guardar");
		            $('.GuardarEtiquetas').removeClass('disabled');
		            $('#carga').fadeOut();
	            });
	        }
	        else{
	        	if(categoria == null){
	        		$('.mensaje_de_error2').addClass('bg-warning');
	                $('.mensaje_de_error2').text('Debe seleccinar una categoria para las etiquetas');
	                $('.mensaje_de_error2').fadeIn();
	                $('.GuardarEtiquetas').text("Guardar");
		            $('.GuardarEtiquetas').removeClass('disabled');
		            $('#carga').fadeOut();
	
	        	}
	        	else{
	        		$('.mensaje_de_error2').addClass('bg-warning');
	                $('.mensaje_de_error2').text('Debe ingresar al menos el nombre de una etiqueta');
	                $('.mensaje_de_error2').fadeIn()
	                $('.GuardarEtiquetas').text("Guardar");
		            $('.GuardarEtiquetas').removeClass('disabled');
		            $('#carga').fadeOut();
	
	        	}
	        	
	        }
	    });

	    var multipleDefault = new Choices(document.getElementById('etiquetas'));

		var multipleFetch = new Choices('#choices-multiple-remote-fetch', {
		placeholder: true,
		placeholderValue: 'Pick an Strokes record',
		maxItemCount: 5,
		}).ajax(function(callback) {
		fetch('https://api.discogs.com/artists/55980/releases?token=QBRmstCkwXEvCjTclCpumbtNwvVkEzGAdELXyRyW')
		  .then(function(response) {
		    response.json().then(function(data) {
		      callback(data.releases, 'title', 'title');
		    });
		  })
		  .catch(function(error) {
		    console.error(error);
		  });
		});

    });
  </script>
@endsection