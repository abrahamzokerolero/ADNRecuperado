@extends('layouts.app')

@section('title')
    ADN México | Detalle de perfil genetico
@endsection

@section('script')
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>	
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')
	<div class="card-block mb-3">
		<div class="container">
			{!! Form::open(array('route' => ['importaciones_perfiles.validar', $perfil_genetico->id], 'method' => 'PUT')) !!}﻿
			<div class="card-title p-3 mb-3 card-header">
				<img src="{{asset('images/genotipos.png')}}" alt="" width="80" height="70" class=""><span class="h4 ml-3 font-weight-bold"> DETALLE DE PERFIL GENETICO</span>
				
				<div class="float-right">
					@can('perfiles_geneticos.index')
					<a href="{{route('perfiles_geneticos.index')}}" class="btn btn-danger float-right mb-2"><i class="fa fa-chevron-left mr-2"></i> Regresar a la lista de perfiles geneticos</a>
					@endcan
				</div>
			</div>
		
			<div class="d-flex justify-content-between">
				<div class="card metadatos">
					<div class="card-header text-center text-muted">
						@can('perfiles_geneticos.edit')
							@if($perfil_genetico->requiere_revision == 1 && $perfil_genetico->desestimado == 0 )	
								<select name="validacion" class="float-left btn border w-25">
								  <option value="aprobar">APROBAR</option>
								  <option value="POR NUMERO DE MARCADORES">DESESTIMAR POR NUMERO DE MARCADORES</option>
								  <option value="POR NUMERO DE HOMOCIGOTOS">DESESTIMAR POR HOMOCIGOTOS</option>
								</select>
								{!!Form::submit('Validar', ['class' => 'btn btn-primary ml-2 float-left'])!!}
							@endif
						@endcan
						<span class="ml-0">ID interno: {{$perfil_genetico->identificador}}</span> <span class="ml-0">ID externo: {{$perfil_genetico->id_externo}}</span>
						@if($perfil_genetico->desestimado == 0)
						<a href="{{route('perfiles_geneticos.edit', $perfil_genetico->id)}}" class="btn btn-warning float-right"><i class="fa fa-pencil mr-3"></i> Editar</a>
						@endif
					</div>
					{!! Form::close() !!}
					<div class="container p-3">
						<p>
						  <button class="btn btn-danger w-100 text-center" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
						    Detalle del Perfil Genetico
						  </button>
						</p>
						{{-- Si quieres que salga desplegado el div del boton descomenta este y comenta el otro --}}
						{{-- <div class="collapse show" id="collapseExample"> --}}
						<div class="collapse" id="collapseExample">
							 <div class="d-flex flex-wrap mt-2">
								<td>
									<div class="form-group metadato_datos">
										<label for="id_importacion">ID de Importacion</label>
										@if($perfil_genetico->importacion_perfil <> null )
											<input type="text" value="{{$perfil_genetico->importacion_perfil->identificador}}" disabled class="form-control">
										@else
											<input type="text" value="CAPTURA MANUAL" disabled class="form-control">
										@endif
									</div>
									<div class="form-group metadato_datos ml-2">
										<label for="id_importacion">Fuente</label>
										<input type="text" value="{{$perfil_genetico->fuente->nombre}}" disabled class="form-control">
									</div>
								</td>
							</div>
							<div class="d-flex flex-wrap mt-2">
								<td>
									<div class="form-group metadato_datos">
										<label for="id_importacion">Numero de Marcadores</label>
										<input type="text" value="{{$perfil_genetico->numero_de_marcadores}}" disabled class="form-control">
									</div>
									<div class="form-group metadato_datos ml-2">
										<label for="id_importacion">Numero de Homocigotos</label>
										<input type="text" value="{{$perfil_genetico->numero_de_homocigotos}}" disabled class="form-control">
									</div>
								</td>
								<td>
									<div class="form-group metadato_datos">
										<label for="etiqueta" class="">Etiquetas</label>
										<br>
										<div class="">
											@foreach($perfil_genetico->etiquetas as $etiqueta)
												<span name="etiqueta" class="btn btn-success disabled m-1">{{$etiqueta->etiqueta->nombre}}</span>
											@endforeach
										</div>
									</div>
									<div class="form-group metadato_datos ml-2">
										<label for="etiqueta" class="">Se actualizo con los perfiles</label>
										<br>
										@if($perfil_genetico->se_actualizo_con_los_perfiles <> null)
										<div class="">
											@foreach(explode(',' , $perfil_genetico->se_actualizo_con_los_perfiles) as $perfil_duplicado)
												<?php $perfil_duplicado = App\PerfilGenetico::where('identificador' , '=', $perfil_duplicado)->first() ?>
												<a href="{{route('perfiles_geneticos.show', $perfil_duplicado->id)}}" class="btn btn-warning m-1"> {{$perfil_duplicado->identificador}}</a>
											@endforeach
										</div>
										@endif
									</div>							
								</td>
							</div>
						</div>
					</div>


					<div class="container">
						<div class="d-flex flex-wrap mt-2">
							<div class="p-2 bg-danger mb-3 disabled text-center text-white w-100"> Detalle de Metadatos </div>
							@foreach($perfil_genetico->metadatos as $metadato)
								<td>
									<?php $tipo_de_metadato = App\TipoDeMetadato::find($metadato->id_tipo_de_metadato) ?>
									<div class="form-group metadato_datos ml-2">
										<label for="{{$tipo_de_metadato->nombre}}" class="">{{str_replace('_',' ',ucwords($tipo_de_metadato->nombre))}}</label>
										<input type="text" name="{{$tipo_de_metadato->nombre}}" value="{{$metadato->dato}}" disabled class="form-control">
									</div>
								</td>
							@endforeach
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
			</div>
		</div>
	</div>
@endsection