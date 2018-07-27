@extends('layouts.app')

@section('title')
    ADN México | Validar perfil duplicado
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->
@section('script')
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="stylesheet" href="{{asset('css/choices.min.css?version=3.0.4')}}">
  	<script src="{{asset('js/choices.min.js?version=3.0.4s')}}"></script>	
@endsection

@section('content')
	<div class="card-block">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
		<div class="container">
			<div class="card-title p-3 card-header">
				<img src="{{asset('images/genotipos.png')}}" alt="" width="80" height="70" class=""><span class="h4 ml-3 font-weight-bold"> VALIDAR PERFIL  GENETICO DUPLICADO</span>
			</div>
		</div>
	</div>
	<div class=" container mt-2">
		<div class="d-flex justify-content-between">
			<div class="card w-25">
				<table id="myTable" class="table">
					<thead class="card-header bg-dark text-white">
						<td>Marcadores</td>
						<td>Alelo 1</td>
						<td>Alelo 2</td>
					</thead>
					<tbody>
						<?php $marcador_anterior = '' ?>
						@foreach($perfil_genetico->alelos as $alelo)
								<tr>
									<td><b>{{$alelo->marcador->nombre}}</b></td>
									<td>{{$alelo->alelo_1}}</td>
									@if($alelo->alelo_2 == null)
										<td></td>
									@else
										<td>{{$alelo->alelo_2}}</td>	
									@endif
								</tr>	
						@endforeach
					</tbody>
				</table>
			</div>			
			<div class="card w-25">
				<div class="card-footer bg-warning"> Perfil Duplicado: <b>{{$perfil_genetico_repetido->identificador}}</b></div>
				
				<div class="mt-2 form-group">
					@foreach($perfil_genetico_repetido->metadatos as $metadato)
					<div class="row pl-3 pr-3 pt-3">
					    <div class="col">
					      <label for="{{$metadato->tipo_de_metadato->nombre}}" class="">{{str_replace('_',' ',ucwords($metadato->tipo_de_metadato->nombre))}}</label>
						  <input type="text" name="{{$metadato->tipo_de_metadato->nombre}}" value="{{$metadato->dato}}" disabled class="form-control">
					    </div>
					</div>
					@endforeach
				</div>
			</div>
			<div class="card w-50 ">
				<div class="card-footer bg-danger text-white"> Perfil Original: <b>{{$perfil_genetico->identificador}}</b></div>
				{!! Form::open(array('route' => ['perfiles_geneticos.guardar_validacion_de_duplicado',$perfil_genetico_repetido->id], 'method' => 'PUT')) !!}﻿	
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
								<select class="form-control" name="etiquetas[]" id="etiquetas" placeholder="Seleccione las etiquetas" multiple>
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
  	</script>
@endsection