@extends('layouts.app')

@section('title')
    ADN México | Lista de perfiles duplicados
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')

	<div class="card-block">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
		<div class="container">
			<div class="card-title p-3 card-header">
				<img src="{{asset('images/genotipos.png')}}" alt="" width="80" height="70" class=""><span class="h4 ml-3 font-weight-bold"> LISTA DE PERFILES DUPLICADOS</span>
			</div>
	
			<table id="myTable" class="table">
				<thead class="card-header bg-danger text-white">
					<td>Perfil repetido</td>
					<td>Estado perfil repetido</td>
					<td>Perfil original</td>
					<td>Estado perfil repetido</td>
					<td class="text-center">Usuario subio</td>
					<td class="text-center">Fecha de creacion</td>
				</thead>
				<tbody>
					
				</tbody>
			</table>
			<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
			<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
			<script>
				$(document).ready(function() {
				  $(function() {
					  var data = <?php echo $perfiles_geneticos;?>;
					  console.log(data);
					  var oTable = $('#myTable').DataTable({
				            data:data,
					        columnDefs: [{"className": "dt-center", "targets": "_all"}],
				            columns: [
						        { data: 'identificador',
							    	render: function ( data, type, row ) {
								        return '<a class="border border-danger rounded p-1" href="../perfiles/'+ row.id +'/validar_duplicado">'+ data + '</a>';
								    }
							    },
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
						        { data: 'created_at'},
						    ]
				        });
					});
				});
			</script>
		</div>
	</div>
@endsection