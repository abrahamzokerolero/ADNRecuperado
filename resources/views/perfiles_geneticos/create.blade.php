@extends('layouts.app')

@section('title')
    ADN México | Captura manual
@endsection

@section('script')
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>	
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')
	<div class="card-block mb-3">
		<div class="container">
			<div class="card-title p-3 mb-3 card-header">
				<img src="{{asset('images/genotipos.png')}}" alt="" width="80" height="70" class=""><span class="h4 ml-3 font-weight-bold"> CAPTURA MANUAL</span>
				
				<div class="float-right">
					@can('perfiles_geneticos.index')
					<a href="{{route('perfiles_geneticos.index')}}" class="btn btn-danger float-right mb-2"><i class="fa fa-chevron-left mr-2"></i> Ir a la lista de perfiles geneticos</a>
					@endcan
				</div>
			</div>
				{!!Form::open(['route'=>'perfiles_geneticos.comprobacion'], ['method' => 'POST'])!!}
				<div class="d-flex justify-content-lg-between">

					<div class="card w-25">

						{!!Form::submit('Capturar nuevamente', ['class' => 'btn btn-primary m-3 float-right'])!!}		
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
										$alelo_1 = $marcador->nombre.'_alelo_1';
										$alelo_2 = $marcador->nombre.'_alelo_2';
									?>	
						
									<td>{{$marcador->nombre}}</td>
									<td>{!!Form::text($alelo_1, null, ['class' => "text-center w-100 $alelo_1"])!!}</td>
									@if($marcador->id_tipo_de_marcador == 2 &&  $marcador->nombre <> 'dys385' || $marcador->id_tipo_de_marcador == 3 || $marcador->nombre == 'yindel')
										<td>{!!Form::text($alelo_2, null, ['class' => "text-center w-100 $alelo_2", 'disabled'])!!}</td>
									@else
										<td>{!!Form::text($alelo_2, null, ['class' => "text-center w-100 $alelo_2"])!!}</td>
									@endif
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="card h-50 float-right instrucciones_captura_manual">
						<div class="card card-header bg-danger text-white">
							<h5>Instrucciones</h5>
						</div>
						<div class="card-body text-justify">
							<p>La captura manual de un perfil genetico requiere que se ingresen primeramente los alelos correspondientes a cada marcador, posteriormente debera hacer clic en el boton <b>"Capturar nuevamente"</b> donde, debera volver a ingresar cada uno de los alelos ,esto mejorara la veracidad de la informacion registrada y le ayudara a detectar aquellos campos que no coincidan en su primer ingreso. El sistema indicara en color verde cuando un alelo sea igual a su primer ingreso, y en rojo si el valor no coincide con su primera captura, si el marcador a ingresar solo posee un alelo debera dejar el campo vacio para dicho alelo.</p> 
							<hr>
							<div class="text text-center w-100">
								<img src="{{asset('images/instrucciones_captura_perfil_genetico_primera_captura.png')}}">
							</div>
							<hr>
							<div class="text text-center mt-3 w-100">
								<img src="{{asset('images/instrucciones_captura_perfil_genetico_comprobacion_captura_alelos.png')}}">
							</div>
							<hr>

							<hr>
							<p class="pt-2 pb-2">Los metadatos podran ser ingresados al sistema unicamente en la se ingresar en la segunda captguda captura de alelos, para ello encontrar un panel con todos campos de llenado relacionados al perfil ya sea de por datos de cadaveres o restos oseos o de familiares. Tambien debera seleccionar una fuente y etiquetar los perfiles de ser necesario, El etiquetado de cada perfil le ayudara a mejorar la calidad de sus busquedas.</p>

							<div class="text text-center mt-3 w-100">
								<img src="{{asset('images/instrucciones_captura_perfil_genetico_comprobacion_captura_metadatos.png')}}">
							</div>

							<hr>
							<div class="text text-center mt-3 w-100">
								<img src="{{asset('images/instrucciones_captura_perfil_genetico_comprobacion_captura_metadatos2.png')}}">
							</div>
							<hr>
							<div class="text text-center mt-3 w-100">
								<img src="{{asset('images/instrucciones_captura_perfil_genetico_comprobacion_captura_fuentes_etiquetas.png')}}">
							</div>	

						</div>
					</div>
				</div>
				{!!Form::close()!!}
			</div>
		</div>
	</div>
@endsection