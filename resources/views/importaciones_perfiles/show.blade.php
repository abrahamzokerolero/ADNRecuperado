@extends('layouts.app')

@section('title')
    ADN México | Importaciones Perfiles
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')
	<?php 
	    use Carbon\Carbon;
		$usuario = App\User::find(Illuminate\Support\Facades\Auth::id());
	?>

	<div class="card-block">
		<link rel="stylesheet" href="{{asset('css/datatables/dataTables.min.css')}}">
		<div class="container">
			<div class="card-title p-3 card-header mb-3">
				<img src="{{asset('images/importar.png')}}" alt="" width="80" height="70" class=""><span class="h4 ml-3 font-weight-bold"> DETALLES DE IMPORTACION</span>
				
				<div class="float-right">
					@can('importaciones_perfiles.index')
					<a href="{{route('importaciones_perfiles.index')}}" class="btn btn-warning float-right mb-2"><i class="fa fa-chevron-left mr-2"></i> Regresar a la lista de importaciones</a>
					@endcan
				</div>
				
			</div>
			
			<div class="d-flex flex-row card">
				<table class="table w-75">
					<thead class="card-header bg-warning ">
						<td class="text-center">ID de importacion</td>
						<td class="text-center">Fuente</td>
						<td class="text-center">Genotipos Importados</td>
						<td class="text-center">Fecha de importacion</td>
						<td class="text-center">Tipo de muestra</td>
						<td class="text-center">Observaciones</td>
					</thead>
					<tbody>
						<tr>
							<td class="text-center">{{$importacion_perfiles->identificador}}</td>
							<td class="text-center">{{$importacion_perfiles->fuente->nombre}}</td>
							<td class="text-center">{{$importacion_perfiles->numero_de_perfiles}}</td>
							<td>{{Carbon::parse($importacion_perfiles->created_at)->format('d/m/Y')}}</td>
							<td>{{$importacion_perfiles->tipo_de_muestra}}</td>
							<td class="text-center">{{$importacion_perfiles->observaciones}}</td>
						</tr>
					</tbody>
				</table>
				<div class=" w-25 ml-3 card">
					<div class="card-header bg-success text-white text-center">Etiquetas </div>
					<div class="p-2">
						@foreach($perfiles_geneticos[0]->etiquetas as $etiqueta)
							<span class="btn btn-success btn-sm m-1 disabled"> {{$etiqueta->etiqueta->nombre}}</span>
						@endforeach
					</div>	
				</div>
			</div>
			@if($usuario->estado->nombre <> 'CNB')
				@if($perfiles_geneticos->where('es_perfil_repetido', '=', 1)->where('desestimado', '=', 0)->where('id_estado_perfil_original', '<>', $usuario->estado->id)->count() > 0 )
					<div class="card-header bg-success text-center text-white mt-2"> Se encontraron perfiles geneticos duplicados que pertenecen a otros estados, CNB se encargara de validarlos, favor de no volver a cargar la importacion.</div>
				@endif
			@endif
			<table id="myTable" class="table">
				<thead class="card-header bg-danger text-white">
					<td hidden>Id</td>
					<td>ID interno</td>
					<td>ID externo</td>
					<td class="text-center">Marcadores importados</td>
					<td class="text-center">Homocigotos</td>
					<td class="text-center">Usuario</td>
					<td class="text-center">Requiere revision</td>
					<td class="text-center">Fecha de creacion</td>
				</thead>
				<tbody>

				</tbody>
			</table>
			<script src="{{asset('js/jquery-3.3.1.js')}}"></script>
			<script src="{{asset('js/datatables/dataTables.min.js')}}"></script>
			<script>
				$(document).ready(function() {
				  $(function() {
					  var data = <?php echo $perfiles_geneticos;?>;
					  var usuario = <?php echo $usuario;?>;
					  var oTable = $('#myTable').DataTable({
					  		"order": [ 0 , 'desc'],
					  		"language": {
						      "url": "http://cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
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
							    		if(row.es_perfil_repetido == 0 || row.desestimado == 1){
							    			return '<a  href="../importaciones_perfiles/'+ row.id +'/perfil_genetico">'+ data + '</a>';
							    		}
							    		else{
							    			if(row.es_perfil_repetido == 1 && row.desestimado == 0){
							    				if(row.perfil_original.id_estado == usuario.id_estado){
							    					return '<a  href="../importaciones_perfiles/'+ row.id +'/validar_duplicado">'+ data + '</a>';
							    				}
							    				else{
							    					return data;
							    				}							    				
							    			}
							    		}
								    }
							    },
						        { data: 'id_externo' },
						        { data: 'numero_de_marcadores'},
						        { data: 'numero_de_homocigotos'},
						        { data: 'usuario' ,
						        	render: function ( data, type, row ) {
								        return data.name ;
								    }
						        },
						        { data: 'requiere_revision',
						        	render: function ( data, type, row ) {
								        if(row.desestimado == 0){
								        	if(row.es_perfil_repetido == 0){
									        	if(data == 0){
										        	return '<span class="btn btn-sm btn-success disabled" >no</span>' ;
										        }
										        else{
										        	return '<span class="btn btn-sm btn-warning disabled" >si</span>'
										        }	
									        }
									        else{
									        	return '<span class="btn btn-sm btn-danger disabled" >duplicado</span>' ;
									        }
								        }
								        else{
								        	return '<span class="btn btn-sm btn-danger disabled" >desestimado</span>'
								        }
								    }
						    	},
						        { data: 'created_at', 
						        	render: function (data, type, row){
						        		var date = new Date(data);
						        		return date.toLocaleDateString()
						        	}
						    	},
						    ]
				        });

					  // oTable.column( '0:visible' )
						 //    .order( 'desc' )
						 //    .draw();
					});
				});


			</script>
		</div>
	</div>
@endsection