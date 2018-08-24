@extends('layouts.app')

@section('title')
    ADN México | Subir una importacion al sistema
@endsection

@section('script')
    <!-- Scripts -->
    
    <link rel="stylesheet" href="{{asset('css/choices.min.css?version=3.0.4')}}">
  	<script src="{{asset('js/jquery-3.3.1.js')}}"></script>
	
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')
	<?php $usuario = App\User::find(Illuminate\Support\Facades\Auth::id());?>
	<div class="card-block">
		
		<div class="container">

			<div class="card-title p-3 card-header mb-3">
				<img src="{{asset('images/importar.png')}}" alt="" width="80" height="70" class=""><span class="h4 ml-3 font-weight-bold"> CREAR UNA NUEVA IMPORTACION</span>
				
				<div class="float-right">
					@can('importaciones_perfiles.index.create')
					<a href="{{route('importaciones_perfiles.index')}}" class="btn btn-warning float-right mb-2"><i class="fa fa-chevron-left mr-2"></i> Regresar a la lista de importaciones</a>
					@endcan
				</div>
				
			</div>
			<div class="w-100 float-left mb-2">
			  <div class="card border">
				  <div class="card-header"> 
				    <ul class="nav nav-tabs card-header-tabs pull-left"  id="myTab" role="tablist">
				      <li class="nav-item">
				        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Subir importacion de perfiles</a>
				      </li>
				      <li class="nav-item">
				       <a class="nav-link  " id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Descargar formato de importacion de perfiles</a>
				      </li>
				    </ul>
				  </div>
				  <div class="card-body border-success">
				   <div class="tab-content" id="myTabContent">
				        <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
				        	<div class=" card-header bg-warning">
				        		<b>¡Importante: Requisitos para subir su archivo!</b>
				        	</div>
				        	<div class="card-body text-justify">
				        		<p>Para comenzar el proceso de carga de su archivo al sistema debera descargar el formato ofrecido por la plataforma, El formato para la carga de perfiles se compone de 5 secciones la cuales son explicadas mas adelante. No se recomienda modificar el nombre de las columnas debido a que podrian causar problemas en los filtros de busqueda. Las columnas podran ser eliminadas si no se requieren para ser llenadas. El formato lo podra obtener haciendo clic en el siguiente boton que se presenta a continuacion, el archivo descargado debera ser renombrado con el titulo de su preferencia manteniedo los formatos ofrecios por excel (xls o xlsx).</p>

				        		<div>
				        			<a class="btn btn-primary float-right" href="{{asset('formatos/Importación_perfiles_formato.xlsx')}}">Obtener formato de importacion de perfiles</a>
				        		</div>
								<br>
								<h4 class="mt-3">Seccion 1: Identificador externo</h4>
								<p class="text-justify"> Existen 2 identificadores para cada perfil genetico, El identificador interno sera agregado automaticamente por el sistema mientras que el identificador externo del perfil debera ser ingresado obligatoriamente para la carga del documento ya que es un dato importante para mantener la referencia e integridad de la informacion. En el documento de excel  podra localizado por medio de la primera columna la cual se encuentra resaltada en color verde. Esta sera la unica columna que no debera borrar del documento. A continuacion se remarca en rojo dicha columna</p>

								<div class="text text-center w-100">
									<img src="{{asset('images/instrucciones_perfil_genetico_indentificador.png')}}">
								</div>
								
								<h4 class="mt-3">Seccion 2: Zona de marcadores y alelos</h4>
								<p class="text-justify">Las columnas marcadas en color azul corresponden a cada uno de los marcadores registrados en el sistema hasta el momento. Debajo de cada una de ellas debera colocar los alelos de cada perfil. Si un marcador posee mas de un alelo, debera obligatoriamente separar cada uno de estos por una coma. Para aquellos marcadores de los cuales no se tenga informacion, deberan ser dejados en blanco o bien podran ser eliminadas dichas colummnas para evitar la saturacion visual de su documento. El programa automaticamente descartara los espacios al principio y al final de cada alelo. Si requiere agregar un marcador no existente en el formato del proporcionado por la pagina, debera contactar a la CNB, de otro modo el sistema reconocera el marcador de su excel como un metadato.</p>

								<div class="text text-center w-100">
									<img src="{{asset('images/instrucciones_perfil_genetico_marcadores.png')}}">
								</div>

								<h4 class="mt-3">Seccion 3: Zona de datos comunes</h4>
								<p class="text-justify">A partir de las siguientes columnas los datos ingresados seran denomidados "Metadatos" identidificadas por el color gris, en donde indistintamente para la carga de un perfil genetico, ya sea un perfil por restos de cadaveres/restos oseos o de busqueda de familires, tendra disponibles los campos de clave de la muestra, descripcion de la muestra, observaciones, talla, peso, S. particulares/Malforamciones, tatuje, Sexo, C.I/NUC/A.P., Fecha de desaparición, Lugar de desaparición, y No. de caso relacionado. Cada una de estas columnas podran ser eliminadas de no ser requeridas, sin embargo se recomienda agregar la mayor cantidad de informacion posible para mejorar la calidad de las busquedas</p>

								<div class="text text-center w-100">
									<img src="{{asset('images/instrucciones_perfil_genetico_datos_comunes.png')}}">
								</div>

								<h4 class="mt-3">Seccion 4: Zona de datos de cadaveres o restos oseos</h4>
								<p class="text-justify">Esta seccion corresponde a la informacion de aquellos perfiles cargados al sistema a partir de muestras de cadaveres y restos oseos. Siempre se recomienda llenar dicha informacion en la medida posible de su conocimiento</p>

								<div class="text text-center w-100">
									<img src="{{asset('images/instrucciones_perfil_genetico_restos_cadaveres.png')}}">
								</div>

								<h4 class="mt-3">Seccion 5: Zona de datos de familires</h4>

								<p class="text-justify">La ultima seccion corresponde a los datos de familiares del perfil genetico a ingresar, igual que las demas columnas, estas podran ser eliminadas de no ser requeridas. Las columnas se encuentran identificadas por el color amarillo</p>
								<div class="text text-center w-100">
									<img src="{{asset('images/instrucciones_perfil_genetico_familiares.png')}}">
								</div>

								<h4 class="mt-3">Personalizacion de su archivo excel</h4>

								<p class="text-justify">Como anteriormente se menciono para su comodidad podra eliminar las columnas que no se requieran en la carga de sus perfiles geneticos, e incluso generar sus propios formatos manteniedo el nombre de las columnas del formato original. Sin embargo <b>la primera columna que siempre debera ser ingresada sera la del identificador (identifier) y el orden de los marcadores no debera ser modificado</b>, los metadatos podran ser acomodados en su documento a su conveniencia. Observe los siguientes ejemplos de personalizacion.</p>
								<div class="text text-center w-100">
									<img src="{{asset('images/instrucciones_perfil_genetico_ejemplo_1.png')}}">
								</div>
								<div class="text text-center w-100 mt-3">
									<img src="{{asset('images/instrucciones_perfil_genetico_ejemplo_2.png')}}">
								</div>
								<div class="text text-center w-100 mt-3">
									<img src="{{asset('images/instrucciones_perfil_genetico_ejemplo_3.png')}}">
								</div>

				        	</div>
				        </div>
				  		<div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
							<div class="card-block">
								<div class=" card-header bg-warning">
					        		<b>Cargar archivo</b>
					        	</div>
								<div class="card-body">
									<p>
										Nota: los formatos soportados por el sistema son archivos con extension ".xls" y ".xlsx" correspondientes a la plataforma de excel 
										{{-- Si desea hacer la carga de su archivo al sistema debera seleccionar el boton examinar donde podra elegir su archivo desde su directorio personal. Para concluir la operacion debera hacer click en el boton "Importar". Los formatos soportados por el sistema son xls y xlsx correspondientes a la plataforma de excel. No se aceptan otros formatos. --}}
									</p>
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

										<div class="row">
											<!--formulario para cateorias colapsable-->
											
											<div class="col">
												<div class="collapse mb-2 ml-3" id="collapseCategoria">
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
											</div>
											
											<!--Formulario para etiquetas colapsable-->
											
											<div class="col">
												<div class="collapse  mb-2 mr-3" id="collapseEtiquetas">
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
									</div>
										</div>
										<div class="card">
										{!! Form::open(['route' => 'importaciones_perfiles.store', 'method'=> 'POST', 'enctype' => 'multipart/form-data' ]) !!}
											<span class="bg-danger form-control mensaje_de_error1 text-center mt-2 mb-2">Mensaje de error</span>
    										<script type="text/javascript"> $('.mensaje_de_error1').hide();</script>

											<div class="card-header bg-warning">Datos de la importacion</div>
				  							<div class="d-flex flex-row justify-content-between mb-3 bg-light text-dark p-3">
				  								<div class="w-25">
				  									<label for="id_fuente" class="mt-2">Fuente</label>
													<select name="id_fuente" class="form-control" required="">
													  <option disabled selected>Seleccione una Fuente</option>
													  @foreach($fuentes as $fuente)
													  	<option value="{{$fuente->id}}">{{$fuente->nombre}}</option>
													  @endforeach
													</select>
				  								</div>
												<div class="w-50">
													<label for="etiquetas">Seleccionar etiquetas para los perfiles</label>
													<select required class="form-control etiquetas_ajax" name="etiquetas[]" id="etiquetas" placeholder="Seleccione las etiquetas" multiple>
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
				  							</div>
												
											<div class="mb-3">
												<button class="btn btn-warning ml-3" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-plus-circle"></i> Datos adicionales</button>
												<div class="collapse  m-3 card p-3" id="collapseExample">
													<div class="d-flex flex-row justify-content-between">
														<div class="w-50">
															{!! Form::label('titulo' , 'Titutlo de la importacion')!!}
															{!! Form::text('titulo' , null , [ 'class' => 'form-control', 'placeholder'=> 'Ingrese un titulo a su importacion', 'required'])!!}
														</div>
														
														<div class="ml-5 w-25">
															{!! Form::label('tipo_de_muestra' , 'Tipo de muestra')!!}
															{!! Form::text('tipo_de_muestra' , null , [ 'class' => 'form-control', 'placeholder'=> 'Ingrese el tipo de muestra'])!!}
														</div>
													</div>

													{!! Form::label('observaciones' , 'Observaciones')!!}
													{!! Form::text('observaciones' , null , [ 'class' => 'form-control', 'placeholder'=> 'Ingrese sus observaciones'])!!}

												</div>
											</div>

											<div class="p-3 card-footer">
												{!! Form::file('archivo', ['required', 'files' => 'true' , 'class' => 'archivo'])!!}
												{!! Form::submit('Importar', ['class' => ' btn btn-primary ml-3 importar']) !!}
												<img src="{{asset('images/carga.gif')}}" width="120" height="120" id="carga2">
											</div>
										</div>
										<div class="form-group">
											
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
	</div>
	<script>	
      

    $(document).ready(function() {

    	$('#carga').fadeOut();
    	$('#carga2').fadeOut();

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

	        var etiquetas_seleccionadas = $('#etiquetas').val();

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
	            	// console.log(result.categorias);
	                multipleDefault.destroy();  //
	                for(i in result.categorias){
	                	//result.categorias[i].nombre       Nombre de las categorias
	                	//result.categorias[i].id 			Id de de las categorias
	                	
	                	$("select[name='etiquetas[]']").append('<optgroup id="' + result.categorias[i].nombre + '" label="'+ result.categorias[i].nombre +'"></optgroup>');
	                	for(x in result.categorias[i].etiquetas){
	                		$('optgroup[id="'+ result.categorias[i].nombre +'"]').append('<option value='+ result.categorias[i].etiquetas[x].id +'>'+ '\t' + result.categorias[i].etiquetas[x].nombre +  ' (' +  result.categorias[i].etiquetas[x].perfiles_geneticos_asociados.length   +    ')</option>');	
	                	}
	                }

	                multipleDefault = new Choices(document.getElementById('etiquetas'), {
					    searchResultLimit: 100,
					    resetScrollPosition: false,
				    });
	                
					for( etiqueta_individual in etiquetas_seleccionadas){
		            	multipleDefault.setValueByChoice(etiquetas_seleccionadas[etiqueta_individual]);
		            }

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

	    $('.importar').click(function(e){
	    	
	    	var fuente = $('select[name="id_fuente"]').val();
	    	var archivo = $('.archivo').val();
	    	var etiquetas = $('.etiquetas_ajax').val();

	    	if(fuente != null && String(archivo) != "" && etiquetas.length > 0){
	    		$('.mensaje_de_error1').fadeOut();
	    		$('.mensaje_de_error1').text("Espere mientras su importacion se carga al sistema, esto puede demorar");
                $('.mensaje_de_error1').removeClass('bg-danger');
                $('.mensaje_de_error1').addClass('bg-success text-white');
                $('.mensaje_de_error1').fadeIn();

	    		$('.importar').text("Espere un momento");
	    		$('.importar').addClass('disabled');
	    		$('#carga2').fadeIn();
	    	}
	    	else{
	    		$('.mensaje_de_error1').fadeOut();
	    		$('.mensaje_de_error1').addClass('bg-danger text-white');
                $('.mensaje_de_error1').text('Debe seleccionar una fuente, al menos una etiqueta y un archivo');
                $('.mensaje_de_error1').fadeIn();
	    	}
	    });

	    var multipleDefault = new Choices(document.getElementById('etiquetas'), {
		    searchResultLimit: 100,
		    resetScrollPosition: false,
	    });

    });

  </script>
  <script src="{{asset('js/choices.min.js?version=3.0.4s')}}"></script>
@endsection