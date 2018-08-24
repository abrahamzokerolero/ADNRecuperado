@extends('layouts.app')

@section('title')
    ADN México | Busquedas
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')
	<script src="{{asset('js/jquery-3.3.1.js')}}"></script>
	<div class="card-block mt-3">
		<link rel="stylesheet" href="{{asset('css/datatables/dataTables.min.css')}}">
		<div class="container">
			<div class="card-title p-3 card-header">
				<img src="{{asset('images/busquedas_gris.png')}}" alt="" width="80" height="80" class=""><span class="h4 ml-3 font-weight-bold"> Lista de busquedas </span>
				<div class="float-right">
					@can('fuentes.create')
					<a href="{{route('busquedas.create')}}" class="btn btn-info float-right mr-3 mb-2"><i class="fa fa-plus-circle"></i> Nueva busqueda</a>
					@endcan
				</div>
			</div>
			<div class="row mt-3">
				<div class="col">
					{{-- boton que hara que cambie la cambie la ruta dinamicamente y activara el submit --}}
					<input type="button" id="button" class="btn" value="Exportar"/>
					{!! Form::open(array('route' => ['busquedas.busquedas_exportar', 'perfiles'], 'method' => 'POST', 'id' => 'exportar_form')) !!}﻿
				        <input type="submit" id="button2" class="btn d-none" value="Exportar"/>			
				    {!!Form::close()!!}
				</div>
			</div>
			<span class="bg-warning form-control mensaje_de_error8 text-center mt-3 mb-2">Mensaje de error</span>
			<script type="text/javascript"> $('.mensaje_de_error8').hide();</script>
			<table id="myTable" class="table">
				<thead class="card-header bg-info text-white">
					<td hidden>ID</td>
					<td>No.</td>
					<td>Fuente</td>
					<td>Motivo</td>
					<td>Descripcion</td>
					<td>Usuario</td>
					<td>T.BUS</td>
					<td>estatus</td>
					<td>Fecha</td>
				</thead>
				<tbody>
					
				</tbody>
			</table>
			<script src="{{asset('js/jquery-3.3.1.js')}}"></script>
			{{-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script> --}}
			<script src="{{asset('js/datatables/dataTables.min.js')}}"></script>
			<script>
				  var data = <?php echo $busquedas;?>;
				  var oTable = $('#myTable').DataTable({
				  		"order": [ 0 , 'desc'], 
				  		"language": {
						  "url": "http://cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
						},
						select: {
				            style: 'single'
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
							        return '<a href="busquedas/'+ row.id +'">'+ data + '</a>';
							    }
						    },
					        { data: 'id_fuente',  
					        	render: function ( data, type, row ){					  
					        		return row.fuente.nombre;
					        	}
					    	},
					        { data: 'motivo'},
					        { data: 'descripcion' },
					        { data: 'id_usuario',
					        	render: function ( data, type, row ){
					        		return row.usuario.name;
					        	}
					    	},
					        { data: 'id_tipo_busqueda',
					        	render: function ( data, type, row){
					        		if(row.tipo_de_busqueda.id == 1){
								    	return '<td><span class="btn btn-primary btn-sm disabled">IND</span></td>';
					        		}
									else{
										return '<td><span class="btn btn-primary btn-sm disabled">GPL</span></td>';
									}
					        	}
					    	},
					        { data: 'id_estatus_busqueda',
					        	render: function ( data, type, row){
					        		if(row.estatus.id == 1){
								    	return '<td><span class="btn btn-warning btn-sm disabled">PENDIENTE</span></td>';
					        		}
									else{
										if(row.estatus.id == 2){
											return '<td><span class="btn btn-success btn-sm disabled">APROBADA</span></td>';
										}
										else{
											return '<td><span class="btn btn-danger btn-sm disabled">DESESTIMADA</span></td>';
										} 
										
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

				  	 // Cuando se toca el boton visible
				  	$('#button').click( function (e) {
				  	 	var seleccionados = [];

				  	 	// Se obtienen los ids de los perfiles seleccionados
				  	 	for (var i = 0; i < oTable.rows('.selected').data().length; i++) {						 
						    seleccionados.push(oTable.rows('.selected').data()[i].id);
						}

						if(seleccionados.length == 0){
							// mandar mensaje de error
							mensaje_error('.mensaje_de_error8', 'No selecciono ninguna busqueda'); 
						}
						else{
							// se obtiene la ruta del formulario y se sustituye la cadena  perfiles por los ids 
							$('.mensaje_de_error8').fadeOut();
							var form = $('#exportar_form');
				        	var cadena = form.attr('action').split('/');				
				        	$("#exportar_form").attr('action', form.attr('action').replace(cadena[4], seleccionados.toString(), "gi"));
				        	// se activa el submit con la ruta y los ids nuevos
				        	$('#button2').click();				     
						}	
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
			</script>
		</div>
	</div>
@endsection