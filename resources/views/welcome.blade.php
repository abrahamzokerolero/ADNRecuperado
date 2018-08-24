@extends('layouts.app')

@section('title')
    ADN México | Inicio
@endsection

@section('script')
    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">

      
    </script>
@endsection

@section('content')
    @guest
        <div class="container text-center">
            <img src="{{asset('images/adn-logo.png')}}" class="p-2 mt-5 pt-5" alt="" width="460" height="240">
            {{-- <div class="container form-auth"> --}}
            <div>
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">{{ __('Acceder al sistema') }}</div>

                            <div class="card-body">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="form-group row">
                                        <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('Correo electrónico') }}</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Contraseña') }}</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6 offset-md-4">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Recordar mi contraseña') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Acceder') }}
                                            </button>

                                            {{-- <a class="btn btn-link" href="{{ route('password.request') }}">
                                                {{ __('¿Olvidaste tu contraseña?') }}
                                            </a> --}}
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        @if(!Illuminate\Support\Facades\Auth::guest())
        <div class="card-title p-3 card-header">
                <img src="{{asset('images/home.png')}}" alt="" width="30" height="30" class="m-0"><span class="h5 ml-3 text-muted">Home->Dashboard</span> <div class="float-right h5 text-muted border p-2"><img src="{{asset('images/calendario.png')}}" width="25" height="25" class="mr-2" alt="">{!!Form::date('name', \Carbon\Carbon::now())!!}</div>
            </div>
        <div class="container mt-2">
            <div class="d-flex flex-row justify-content-between text-white mt-3">
                <div class="card accesos_rapidos">
                    <div class="acceso_rapido_contenido genotipos_gris d-flex flex-column align-items-end">
                        <span class="mr-3 h2 m-0 p-0">{{$numero_perfiles}}</span>
                        <p class="mr-3 mt-0 p-0">Genotipos Registrados</p>
                    </div>
                    @can('perfiles_geneticos.index')
                    <div class="card-footer verde float-left pl-4 pt-2 pb-0">
                        <p><a href="{{route('perfiles_geneticos.index')}}">MAS INFORMACION</a></p>
                    </div>
                    @else
                    <div class="card-footer verde float-left pl-4 pt-2 pb-0">
                        <p><a href="">MAS INFORMACION</a></p>
                    </div>
                    @endcan
                </div>
                <div class="card accesos_rapidos text-muted">
                    <div class="acceso_rapido_contenido busquedas_gris d-flex flex-column align-items-end">
                        <span class="mr-3 h2 m-0 p-0">0</span>
                        <p class="mr-3 mt-0 p-0 ">Busquedas realizadas</p>
                    </div>
                    @can('busquedas.index')
                    <div class="card-footer blanco float-left pl-4 pt-2 pb-0">
                        <p><a href="{{route('busquedas.index')}}" class="text-muted">MAS INFORMACION</a></p>
                    </div>
                    @else
                    <div class="card-footer blanco float-left pl-4 pt-2 pb-0">
                        <p><a href="" class="text-muted">MAS INFORMACION</a></p>
                    </div>
                    @endcan
                </div>
                <div class="card accesos_rapidos">
                    <div class="acceso_rapido_contenido fuentes_gris d-flex flex-column align-items-end">
                        <span class="mr-3 h2 m-0 p-0">{{$numero_fuentes}}</span>
                        <p class="mr-3 mt-0 p-0">Fuentes Registradas</p>
                    </div>
                    @can('fuentes.index')
                    <div class="card-footer rojo float-left pl-4 pt-2 pb-0">
                        <p><a href="{{route('fuentes.index')}}">MAS INFORMACION</a></p>
                    </div>
                    @else
                    <div class="card-footer rojo float-left pl-4 pt-2 pb-0">
                        <p><a href="">MAS INFORMACION</a></p>
                    </div>
                    @endcan
                </div>
                <div class="card accesos_rapidos">
                    <div class="acceso_rapido_contenido usuarios_gris d-flex flex-column align-items-end">
                        <span class="mr-3 h2 m-0 p-0">{{$numero_usuarios}}</span>
                        <p class="mr-3 mt-0 p-0">Usuarios registrados</p>
                    </div>                    
                    <div class="card-footer float-left verde pl-4 pt-2 pb-0">
                        <?php $usuario = App\User::find(Illuminate\Support\Facades\Auth::id());?>
                        @if($usuario->estado->nombre == 'CNB')
                        <p><a href="{{route('users.index')}}">MAS INFORMACION</a></p>
                        @else
                        <p><a href="">MAS INFORMACION</a></p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="d-flex flex-row justify-content-between text-white mt-3">
                <div class="card accesos_rapidos text-muted">
                    <div class="acceso_rapido_contenido importaciones_gris d-flex flex-column align-items-end">
                        <span class="mr-3 h2 m-0 p-0">{{$numero_importaciones}}</span>
                        <p class="mr-3 mt-0 p-0">Importaciones Registradas</p>
                    </div>
                    @can('importaciones_perfiles.index')
                    <div class="card-footer float-left pl-4 pt-2 pb-0">
                        <p><a href="{{route('importaciones_perfiles.index')}}" class="text-muted">MAS INFORMACION</a></p>
                    </div>
                    @else
                    <div class="card-footer float-left pl-4 pt-2 pb-0">
                        <p><a href="" class="text-muted">MAS INFORMACION</a></p>
                    </div>
                    @endcan
                </div>
                <div class="card accesos_rapidos">
                    <div class="acceso_rapido_contenido exportaciones_gris d-flex flex-column align-items-end">
                        <span class="mr-3 h2 m-0 p-0">0</span>
                        <p class="mr-3 mt-0 p-0">Exportaciones Registradas</p>
                    </div>
                    <div class="card-footer rojo float-left pl-4 pt-2 pb-0">
                        <p><a href="">MAS INFORMACION</a></p>
                    </div>
                </div>
                <div class="card accesos_rapidos">
                    <div class="acceso_rapido_contenido grupos_gris d-flex flex-column align-items-end">
                        <span class="mr-3 h2 m-0 p-0">{{$numero_categorias}}</span>
                        <p class="mr-3 mt-0 p-0">Grupos Registrados</p>
                    </div>
                    @can('categorias.index')
                    <div class="card-footer verde  float-left pl-4 pt-2 pb-0">
                        <p><a href="{{route('categorias.index')}}">MAS INFORMACION</a></p>
                    </div>
                    @else
                    <div class="card-footer verde  float-left pl-4 pt-2 pb-0">
                        <p><a href="">MAS INFORMACION</a></p>
                    </div>
                    @endcan
                </div>
                <div class="card accesos_rapidos text-muted">
                    <div class="acceso_rapido_contenido etiquetas_gris d-flex flex-column align-items-end">
                        <span class="mr-3 h2 m-0 p-0">{{$numero_etiquetas}}</span>
                        <p class="mr-3 mt-0 p-0">Etiquetas registrados</p>
                    </div>
                    @can('etiquetas.index')
                    <div class="card-footer float-left pl-4 pt-2 pb-0">
                        <p><a href="{{route('etiquetas.index')}}" class="text-muted">MAS INFORMACION</a></p>
                    </div>
                    @else
                    <div class="card-footer float-left pl-4 pt-2 pb-0">
                        <p><a href="" class="text-muted">MAS INFORMACION</a></p>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
        <div class="mt-3">
            <hr>
        </div>
        <div class="container mt-5">
            {!! Form::open(['route' => 'estadisticas', 'method'=> 'GET' ]) !!}
            <label for="categoria_id" class="mt-2">Categoria</label>
            <select name="categoria_id" class="form-control">
              <option disabled selected>Seleccione una categoria</option>
              @foreach($categorias as $categoria)
                <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
              @endforeach
            </select>
            {!! Form::close() !!}
        </div>
        @can('perfiles_geneticos.index')
        <div class=" d-flex justify-content-center mt-3">
             <div id="chart_div" style="width:1000; height:500"></div>
        </div>

        <div class=" d-flex justify-content-center mt-3">
             <div id="chart_div2" style="width:1000; height:500"></div>
        </div>
        @endcan

        <script type="text/javascript">
            
        </script>
         <script type="text/javascript">
            $(document).ready(function(){                
                $('select').on('change', function() {
                    $.ajax({
                        url: 'estadisticas',
                        dataType: 'json',
                        type: 'GET',
                        // This is query string i.e. country_id=123
                        data: {fuente : $('select').val()},
                        success: function(datos) {
                          var data;
                          var chart;

                          // Load the Visualization API and the piechart package.
                          google.charts.load('current', {'packages':['corechart']});

                          // Set a callback to run when the Google Visualization API is loaded.
                          google.charts.setOnLoadCallback(drawChart);

                          // Callback that creates and populates a data table,
                          // instantiates the pie chart, passes in the data and
                          // draws it.
                          function drawChart() {

                            // Create our data table.
                            data = new google.visualization.DataTable();
                            data.addColumn('string', 'Topping');
                            data.addColumn('number', 'Perfiles Geneticos');
                            var paso;
                            for (paso = 0; paso < datos.newData.length; paso++) {
                              data.addRows([[""+datos.newData[paso][0], datos.newData[paso][1] ]]);
                            };
                            

                            // Set chart options
                            var options = {'title':'Perfiles Geneticos por etiquetas',
                                           'width':920,
                                           'height':500,
                                            animation:{
                                            duration: 3000,
                                            easing: 'out',
                                            }
                            };

                            // Instantiate and draw our chart, passing in some options. ColumnChart PieChart
                            chart = new google.visualization.BarChart(document.getElementById('chart_div'));
                            google.visualization.events.addListener(chart, 'select', selectHandler);
                            chart.draw(data, options);

                            chart2 = new google.visualization.PieChart(document.getElementById('chart_div2'));
                            google.visualization.events.addListener(chart2, 'select', selectHandler2);
                            chart2.draw(data, options);
                          }

                          function selectHandler() {
                            var selectedItem = chart.getSelection()[0];
                            var value = data.getValue(selectedItem.row, 0);
                            window.location.replace("perfiles/" + value + "/estadisticas");
                          }

                          function selectHandler2() {
                            var selectedItem = chart2.getSelection()[0];
                            var value = data.getValue(selectedItem.row, 0);
                            window.location.replace("perfiles/" + value + "/estadisticas");
                          }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert('Ocurrio un error, intente nuevamente');
                        }
                    });
                });
            });    
                
        </script>
        @endif
    @endguest
@endsection