@extends('layouts.app')

@section('title')
    ADN México | Lista de perfiles en etiqueta
@endsection

<!-- <script En las vistas de tablas no se inluye el script de laravel ya que causa conflicto con el datatable -->

@section('content')
	
	<div class="card-block">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
		<div class="container">
			<div class="card-title p-3 card-header">
				<img src="{{asset('images/genotipos.png')}}" alt="" width="80" height="70" class=""><span class="h4 ml-3 font-weight-bold"> Perfiles Geneticos en la Etiqueta <b> {{$etiqueta->nombre}} </b></span>
				
				<div class="float-right">
					<a href="{{route('home')}}" class="mr-3 mt-2 btn btn-primary "><i class="fa fa-home"></i></i> Volver al home</a>
				</div>
				
			</div>
	
			<table id="myTable" class="table">
				<thead class="card-header bg-danger text-white">
					<td>ID interno</td>
					<td>ID externo</td>
					<td class="text-center">Marcadores</td>
					<td class="text-center">Homocigotos</td>
					<td class="text-center">Usuario</td>
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
								        return '<a  href="../../perfiles_geneticos/'+ row.id +'">'+ data + '</a>';
								    }
							    },
						        { data: 'id_externo' },
						        { data: 'numero_de_marcadores'},
						        { data: 'numero_de_homocigotos'},
						        { data: 'name' ,
						        	render: function ( data, type, row ) {
								        return data ;
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