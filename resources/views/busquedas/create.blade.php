@extends('layouts.app')

@section('title')
    ADN México | Busquedas
@endsection

@section('script')
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script language=javascript> 	
	function ventanaSecundaria(URL){
	   var x = screen.width/4;
	   var y = screen.height/3;
	   window.open(URL,"ventana1","width=700,height=300,scrollbars=yes,,toolbar=no,location=no,directories=no,resizable=no,top="+y+",left="+x+"'") 
	} 
	</script>
	<link rel="stylesheet" href="{{asset('css/choices.min.css?version=3.0.4')}}">
  	<script src="{{asset('js/choices.min.js?version=3.0.4s')}}"></script> 
@endsection

@section('content')	
	<?php $usuario = App\User::find(Illuminate\Support\Facades\Auth::id());?>
	<div class="container">
		<div class="card-title p-3 mb-3 card-header">
			<img src="{{asset('images/busquedas_gris.png')}}" alt="" width="80" height="70" class=""><span class="h4 ml-3 font-weight-bold"> Busquedas</span>
		</div>
		<div class="container">
			<nav class=" d-flex justify-content-center">
			  <div class="nav nav-tabs border p-3 mb-3" id="nav-tab" role="tablist">
			    <a class="nav-item nav-link btn btn-primary" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="false">Busqueda Individual</a>
			    <a class="nav-item nav-link ml-3 btn btn-primary" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true">Busqueda Grupal</a>
			  </div>
			</nav>
			<div class="tab-content" id="nav-tabContent">
			  <div class="tab-pane fade" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
			  	<div class="card p-3 busqueda_fondo">
			  		<div class="card-header bg-info text-white text-center"><b>BUSQUEDA INDIVIDUAL</b></div>
			  		{!!Form::open(['route'=>'busquedas.store' , 'name' => 'busqueda'], ['method' => 'POST'])!!}
			  		<div class="row pt-3">
			  			<div class="col">
			  				<label for="genotipo" class="mt-2">Seleccionar perfil objetivo</label>
			  				<div id="genotipo" class="d-flex flex-wrap">
	  							<input type="text" name="perfil" id='perfil' class="form-control w-75" required>
	  							<a href="javascript:ventanaSecundaria('{{route('busquedas.ventana')}}')" class="btn btn-primary w-25"><i class="fa fa-search"></i></a>	
			  				</div>
				  		</div>	
			  			<div class="col">
							<label for="etiquetas">Buscar en perfiles etiquetados como:</label>
							<select class="form-control" name="etiquetas[]" id="etiquetas" multiple required>
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
							<label for="id_tabla_de_frecuencias" class="mt-2">Tabla de Frecuencias a usar</label>
							<select name="id_tabla_de_frecuencias" class="form-control" required="">
							  @foreach($tablas_de_frecuencias as $tabla_de_frecuencias)
							  	@if($tabla_de_frecuencias->tabla_default == 1)
							  		<option selected value="{{$tabla_de_frecuencias->id}}">{{$tabla_de_frecuencias->nombre_otorgado}}</option>
							  	@else
							  		<option value="{{$tabla_de_frecuencias->id}}">{{$tabla_de_frecuencias->nombre_otorgado}}</option>
							  	@endif
							  @endforeach
							</select>
						</div>
			  		</div>
			  		<div class="row mt-3">
			  			<div class="col-8">
			  				<div class="row">
			  					<div class="col">
			  						<label for="id_fuente" class="">Fuente</label>
									<select name="id_fuente" class="form-control" required="">
									  <option disabled selected>Seleccione una Fuente</option>
									  @foreach($fuentes as $fuente)
									  	<option value="{{$fuente->id}}">{{$fuente->nombre}}</option>
									  @endforeach
									</select>
			  					</div>
								<div class="col">
									{{Form::label('motivo', 'Motivo de la busqueda')}}
						      		{{Form::text('motivo', null , ['class' => 'form-control', 'required'])}}
								</div>
			  				</div>
			  				<div class="row mt-3">
			  					<div class="col">
			  						{{Form::label('descripcion', 'Descripcion de la busqueda')}}
			  						{!! Form::textarea('descripcion',null,['class'=>'form-control', 'rows' => 2, 'cols' => 40]) !!}
			  					</div>
			  				</div>
			  			</div>
			  			<div class="col-4">
			  				{{Form::label('exclusiones', 'Cantidad de exclusiones')}}
						    {{Form::select('exclusiones', ['0' => '0', '1' => '1', '2' => '2'], null, ['class'=> 'form-control'])}}
						    {{Form::label('marcadores_minimos', 'Numero de marcadores minimos', ['class' => 'mt-3'])}}
						    {{Form::number('marcadores_minimos', 12 , ['class' => 'form-control', 'min' => '10', 'required'])}}
						    {!! Form::label('descartar_perfiles_en_revision', 'Descartar perfiles geneticos en revision', ['class' => 'mt-3']) !!}
							{{Form::select('descartar_perfiles_en_revision', ['0' => 'No', '1' => 'Si'], null, ['class'=> 'form-control'])}}
			  			</div>
			  		</div>
			  		<div class="card-footer text-white text-right mt-3">
			  			{!!Form::submit('Iniciar Busqueda', ['class' => 'btn btn-primary float-right'])!!}
			  		</div>
			  	</div>
			  	{!! Form::close() !!}
			  </div>
			  <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
				{!!Form::open(['route'=>'busquedas.store2'], ['method' => 'POST'])!!}
			  		<div class="row pt-3">
			  			<div class="col">
			  				<label for="etiquetasObjetivo">Buscar en perfiles etiquetados como:</label>
							<select class="form-control" name="etiquetasObjetivo[]" id="etiquetasObjetivo" multiple required">
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
							<label for="etiquetasSubordinadas">Buscar en perfiles etiquetados como:</label>
							<select class="form-control" name="etiquetasSubordinadas[]" id="etiquetasSubordinadas" multiple required>
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
							<label for="id_tabla_de_frecuencias" class="mt-2">Tabla de Frecuencias a usar</label>
							<select name="id_tabla_de_frecuencias" class="form-control" required="">
							  @foreach($tablas_de_frecuencias as $tabla_de_frecuencias)
							  	<option value="{{$tabla_de_frecuencias->id}}">{{$tabla_de_frecuencias->nombre}}</option>
							  @endforeach
							</select>
						</div>
			  		</div>
			  		<div class="row mt-3">
			  			<div class="col-8">
			  				<div class="row">
			  					<div class="col">
			  						<label for="id_fuente" class="">Fuente</label>
									<select name="id_fuente" class="form-control" required="">
									  <option disabled selected>Seleccione una Fuente</option>
									  @foreach($fuentes as $fuente)
									  	<option value="{{$fuente->id}}">{{$fuente->nombre}}</option>
									  @endforeach
									</select>
			  					</div>
								<div class="col">
									{{Form::label('motivo', 'Motivo de la busqueda')}}
						      		{{Form::text('motivo', null , ['class' => 'form-control', 'required'])}}
								</div>
			  				</div>
			  				<div class="row mt-3">
			  					<div class="col">
			  						{{Form::label('descripcion', 'Descripcion de la busqueda')}}
			  						{!! Form::textarea('descripcion',null,['class'=>'form-control', 'rows' => 2, 'cols' => 40]) !!}
			  					</div>
			  				</div>
			  			</div>
			  			<div class="col-4">
			  				{{Form::label('exclusiones', 'Cantidad de exclusiones')}}
						    {{Form::select('exclusiones', ['0' => '0', '1' => '1', '2' => '2'], null, ['class'=> 'form-control'])}}
						    {{Form::label('marcadores_minimos', 'Numero de marcadores minimos', ['class' => 'mt-3'])}}
						    {{Form::number('marcadores_minimos', 12 , ['class' => 'form-control', 'min' => '10'])}}
			  			</div>
			  		</div>
			  		<div class="card-footer text-white text-right mt-3">
			  			{!!Form::submit('Iniciar Busqueda', ['class' => 'btn btn-primary float-right'])!!}
			  		</div>
			  	</div>
			  	{!! Form::close() !!}			  	
			  </div>
			</div>				
		</div>
	</div>
<script>
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

      var multipleDefault = new Choices(document.getElementById('etiquetasObjetivo'));

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
      var multipleDefault = new Choices(document.getElementById('etiquetasSubordinadas'));

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
  </script>
	
@endsection