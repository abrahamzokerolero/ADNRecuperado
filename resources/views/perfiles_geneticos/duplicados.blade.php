@extends('layouts.app')

@section('title')
    ADN México | Lista de perfiles duplicados
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')
	
	<?php 
		$usuario = App\User::find(Illuminate\Support\Facades\Auth::id());
	?>

	<div class="card-block">
		<link rel="stylesheet" href="{{asset('css/datatables/dataTables.min.css')}}">
		<div class="container">
			<div class="card-title p-3 card-header">
				<img src="{{asset('images/genotipos.png')}}" alt="" width="80" height="70" class=""><span class="h4 ml-3 font-weight-bold"> LISTA DE PERFILES DUPLICADOS</span>
			</div>
			
			@if($usuario->estado->nombre <> 'CNB')
				@if($perfiles_geneticos->where('es_perfil_repetido', '=', 1)->where('desestimado', '=', 0)->where('id_estado_perfil_original', '<>', $usuario->estado->id)->count() > 0 )
					<div class="card-header bg-success text-center text-white mt-2"> Se encontraron perfiles geneticos duplicados que pertenecen a otros estados, CNB se encargara de validarlos, favor de no volver a cargar la importacion.</div>
				@endif
			@endif

			<table id="myTable" class="table">
				<thead class="card-header bg-danger text-white">
					<td hidden>Id</td>
					<td>Perfil repetido</td>
					<td>Id externo</td>
					<td>Estado perfil repetido</td>
					<td>Perfil original</td>
					<td>Estado perfil repetido</td>
					<td class="text-center">Usuario subio</td>
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
					  var usuario = <?php echo $usuario?>;
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
							    		
								        if(row.perfil_original.id_estado == usuario.id_estado){
					    					return '<a  href="../perfiles/'+ row.id +'/validar_duplicado">'+ data + '</a>';
					    				}
					    				else{
					    					return data;
					    				}	
								    }
							    },
							    { data: 'id_externo'},
						        { data: 'estado' ,
						        	render: function ( data, type, row ) {
								        return data.nombre;
								    }
						    	},
						        { data: 'perfil_original',
						        	render: function ( data, type, row ) {
								        return '<span class=" border border-success rounded p-1">'+ data.identificador + '</span>';
								    }
						        },
						        { data: 'estado_perfil_original',
						        	render: function ( data, type, row ) {
								        return data.nombre ;
								    }
								},
						        { data: 'usuario' ,
						        	render: function ( data, type, row ) {
								        return data.name ;
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
					});
				});
			</script>
		</div>
	</div>
@endsection