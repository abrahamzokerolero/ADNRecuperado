@extends('layouts.app')

@section('title')
    ADN México | Lista de Perfiles
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')

	<div class="card-block">
		<link rel="stylesheet" href="{{asset('css/datatables/dataTables.min.css')}}">
		<div class="container">
			<div class="card-title p-3 card-header">
				<img src="{{asset('images/genotipos.png')}}" alt="" width="80" height="70" class=""><span class="h4 ml-3 font-weight-bold"> LISTA DE PERFILES PARA REVISION</span>
			</div>
	
			<table id="myTable" class="table">
				<thead class="card-header bg-danger text-white">
					<td hidden>Id</td>
					<td>ID interno</td>
					<td>ID externo</td>
					<td class="text-center">Marcadores importados</td>
					<td class="text-center">Homocigotos</td>
					<td class="text-center">Usuario</td>
					<td class="text-center">Revision</td>
					<td class="text-center">Fecha de creacion</td>
				</thead>
				<tbody>
					
				</tbody>
			</table>

			<script src="{{asset('js/jquery-3.3.1.js')}}"></script>
			<script src="{{asset('js/datatables/dataTables.min.js')}}"></script>
			<script>
				$(document).ready(function() {
				  var data = <?php echo $perfiles_geneticos;?>;
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
							        return '<a href="../perfiles_geneticos/'+ row.id +'">'+ data + '</a>';
							    }
						    },
					        { data: 'id_externo' },
					        { data: 'numero_de_marcadores',
					        render: function ( data, type, row ) {
							        return '<span class=" border border-success rounded p-1">'+ data + '</span>';
							    }
					        },
					        { data: 'numero_de_homocigotos', 
					        render: function ( data, type, row ) {
							        return '<span class=" border border-info rounded p-1">'+ data + '</span>';
							    }
					        },
					        { data: 'name' },
					        { data: 'requiere_revision', render: function ( data, type, row ) {
					        		if(data == 1){data = 'si';}
							        return '<span class="btn btn-danger btn-sm rounded p-1 disabled">'+ data + '</span>';
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
			</script>
		</div>
	</div>
@endsection