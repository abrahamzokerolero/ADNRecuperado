@extends('layouts.app')

@section('title')
    ADN México | Lista de perfiles desestimados
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')

	<div class="card-block">
		<link rel="stylesheet" href="{{asset('css/datatables/dataTables.min.css')}}">
		<div class="container">
			<div class="card-title p-3 card-header">
				<img src="{{asset('images/genotipos.png')}}" alt="" width="80" height="70" class=""><span class="h4 ml-3 font-weight-bold"> LISTA DE PERFILES DESESTIMADOS</span>
				
				<div class="float-right">
					@can('perfiles_geneticos.create')
					<a href="{{route('perfiles_geneticos.create')}}" class="mr-3 mt-2 btn btn-danger "><i class="fa fa-list-ul"></i> Ingresar nuevo perfil</a>
					@endcan
					@can('importaciones_perfiles.create')
					<a href="{{route('importaciones_perfiles.create')}}" class="mt-2 btn btn-danger "><i class="fa fa-pencil"></i> Importar perfiles</a>
					@endcan
				</div>
				
			</div>
	
			<table id="myTable" class="table">
				<thead class="card-header bg-danger text-white">
					<td hidden>Id</td>
					<td>ID interno</td>
					<td>ID externo</td>
					<td class="text-center">Marcadores</td>
					<td class="text-center">Homocigotos</td>
					<td class="text-center">Usuario Reviso</td>
					<td class="text-center">Motivo</td>
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
					  console.log(data);
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
								        return '<a  href="../perfiles_geneticos/'+ row.id +'">'+ data + '</a>';
								    }
							    },
						        { data: 'id_externo' },
						        { data: 'numero_de_marcadores'},
						        { data: 'numero_de_homocigotos'},
						        { data: 'usuario_reviso' ,
						        	render: function ( data, type, row ) {
								        return data.name ;
								    }
						        },
						        { data: 'motivo_de_desestimacion'},
						        { data: 'created_at'},
						    ]
				        });
					});
				});
			</script>
		</div>
	</div>
@endsection