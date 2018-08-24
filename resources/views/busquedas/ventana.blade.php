<head>
	 <!-- Styles Boostrap-->
    <link rel="stylesheet" href="{{asset('css/bootstrap/bootstrap.min.css')}}" >
</head>	
<body>
	<div class="card-block">
		<link rel="stylesheet" href="{{asset('css/datatables/dataTables.min.css')}}">
		<div class="container">	
			<table id="myTable" class="table">
				<thead class="card-header bg-danger text-white">
					<td>ID interno</td>
					<td>ID externo</td>
					<td class="text-center">Fuente</td>
					<td class="text-center">Fecha de creacion</td>
				</thead>
				<tbody>
					
				</tbody>
			</table>
			<script src="{{asset('js/jquery-3.3.1.js')}}"></script>
			{{-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script> --}}
			<script src="{{asset('js/datatables/dataTables.min.js')}}"></script>
			<script>

				$(document).ready(function() {
				    
				    var data = <?php echo $perfiles_geneticos;?>;
				  	var oTable = $('#myTable').DataTable({
			            data:data,
			            "initComplete": function () {
				            $( document ).on("click", "tr[role='row']", function(){
				                 a = $(this).children('td:first-child').text()
				                 window.opener.document.busqueda.perfil.value= a, window.close();
				            });
				        },
				        columnDefs: [{"className": "dt-center", "targets": "_all"}],
			            columns: [
					        { data: 'identificador',
						    render: function ( data, type, row ) {
							        return '<button class="btn btn-primary btn-sm" id="'+ data +'">'+ data + '</button>';
							    }
						    },
						    { data: 'id_externo' },
					        { data: 'id_fuente' ,
						    render: function ( data, type, row ) {
						    		
							        return row.fuente.nombre;
							    }
						    },
					        { data: 'created_at'}
					    ]
			        });
				} );

				// $("button").click(function(){
				//     console.log(this.id)
				//     window.opener.document.busqueda.perfil.value=this.id, window.close();
				// });  

			</script>
		</div>
	</div>
	<script src="{{asset('js/bootstrap/bootstrap.min.js')}}"></script>
</body>
	