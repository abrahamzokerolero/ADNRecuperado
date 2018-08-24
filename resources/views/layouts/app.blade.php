<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>


    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> @yield('title', 'ADN México')</title>

    <!-- Scripts -->
    @yield('script')

    <!-- Fonts Default-->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">


    <!-- Styles Boostrap-->
    <link rel="stylesheet" href="{{asset('css/bootstrap/bootstrap.min.css')}}" >

    <!-- Styles Default-->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Font awesome fonts-->
    <link href="{{asset('css/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link rel="shortcut icon" href="{{{ asset('images/favicon.png') }}}">

</head>
<body>
    <div id="app bg-dark">
        <nav class="navbar navbar-expand-md fixed-top navbar-dark bg-dark navbar-laravel">

                    <div class="d-flex flex-column">
                      <a href="/" class="ml-3"><img class="img-responsive2" width="180" height="60"       
                      src="{{ asset('images/segob-logo.png') }}"></a>
                      <p class="text-center text-white m-0 p-0 text-muted texto-logo">Comision Nacional de Busqueda</p>
                      <p class="text-center text-white m-0 p-0 text-muted texto-logo">Para Personas Desaparecidas</p>
                    </div>
                    <ul class="navbar-nav ml-auto"> 
                        <!-- Authentication Links -->
                        @guest
                            {{-- <li><a class="nav-link" href="{{ route('login') }}">{{ __('Acceder') }}</a></li> --}}
                            
                            {{-- <li><a class="nav-link" href="{{ route('register') }}">{{ __('Registrar') }}</a></li> --}}

                        @else
                            <?php 
                              $usuario = App\User::find(Illuminate\Support\Facades\Auth::id());
                              $mensajes = App\Mensaje::where('id_estado_recibe', $usuario->estado->id)
                              ->where('revisado', 0)
                              ->get();
                            ;?>
                            <li class="nav-item">
                                @if($mensajes->isEmpty())
                                  <a class="nav-link" href="{{route('mensajes.recibidos')}}"> 
                                      <img src="{{asset('images/notificacion_gris.png')}}" width="40" height="40">
                                  </a>
                                @else
                                  <a class="nav-link" href="{{route('mensajes.recibidos')}}"> 
                                      <img src="{{asset('images/notificacion_amarillo.png')}}" width="40" height="40">
                                  </a>
                                @endif
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="{{route('users.personal_edit')}}"> 
                                    <img src="{{asset('images/configuracion.png')}}" width="40" height="40">
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <img src="{{asset('images/default-user.png')}}" width="40" height="40" class="mr-3">{{ Auth::user()->username }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Salir') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        @guest
            <main class="container mt-5 mb-5">
                @yield('content')
            </main>
        @else    
        <div class="d-flex flex-row clearfix side-menu ml-0">
            <div id="menu-lateral" class="container">
              <div id="accordion" class="nav nav-pills flex-column mb-5">
                 <div class="card">
                    <div class="card-header  id="headingOne">
                      <h5 class="mb-0">
                        <a href="/" class="btn btn-link text-white"><i class="fa fa-home"></i> Home</a>
                      </h5>
                    </div>
                 </div>
                 <div class="card">
                    @can('busquedas.index')
                      <div class="card-header  id="headingOne">
                        <h5 class="mb-0">
                          <a href="{{route('busquedas.index')}}" class="btn btn-link text-white"><i class="fa fa-home"></i> Busquedas</a>
                        </h5>
                      </div>
                    @endcan
                  </div>
                  @if($usuario->estado->nombre == 'CNB')
                  <div class="card">
                    <div class="card-header text-white" id="headingSeven">
                      <h5 class="mb-0">
                        <button class="btn btn-link text-white" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="true" aria-controls="collapseOne">
                          <i class="fa fa-search"></i> Mensajes Enviados
                        </button>
                      </h5>
                    </div>
                    <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion">
                        <ul class="nav flex-column m-0">
                            @can('busquedas.index')
                            <li class="nav-item">
                              <a class="nav-link pl-5" href="{{route('mensajes.index')}}">Mensajes</a>
                            </li>
                            @endcan
                        </ul>           
                    </div>
                  </div>
                  @endif
                  <div class="card">
                    <div class="card-header text-white" id="headingTwo">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed text-white" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                         <i class="fa fa-user-o"></i> Genotipos
                        </button>
                      </h5>
                    </div>                    
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <ul class="nav flex-column m-0">
                            @can('perfiles_geneticos.create')
                            <li class="nav-item">
                              <a class="nav-link pl-5" href="{{route('perfiles_geneticos.create')}}">Captura Manual</a>
                            </li>
                            @endcan
                            @can('perfiles_geneticos.index')
                            <li class="nav-item">
                              <a class="nav-link pl-5" href="{{route('perfiles_geneticos.index')}}">Lista de Genotipos</a>
                            </li>
                            @endcan
                            @can('perfiles_geneticos.revision')
                            <li class="nav-item">
                              <a class="nav-link pl-5" href="{{route('perfiles_geneticos.revision')}}">Perfiles para revision <b class=" border p-1 bg-warning rounded disabled">(
                                

                                @if($usuario->estado->nombre == 'CNB')
                                  {{App\PerfilGenetico::where('requiere_revision','=',1)->where('es_perfil_repetido','=',0)->where('desestimado','=',0)->count()}} )
                                @else
                                  {{App\PerfilGenetico::where('requiere_revision','=',1)->where('es_perfil_repetido','=',0)->where('desestimado','=',0)->where('id_estado','=', $usuario->estado->id)->count()}} )
                                @endif
                              </b></a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link pl-5" href="{{route('perfiles_geneticos.duplicados')}}">Perfiles duplicados <b class=" border p-1 bg-warning rounded disabled ml-1">(
                               <?php $usuario = App\User::find(Illuminate\Support\Facades\Auth::id());?>

                                @if($usuario->estado->nombre == 'CNB')
                                  {{App\PerfilGenetico::where('es_perfil_repetido','=',1)->where('desestimado','=',0)->count()}} )
                                @else
                                  {{App\PerfilGenetico::where('id_estado', '=', $usuario->id_estado)->where('es_perfil_repetido','=',1)->where('id_estado_perfil_original','=',$usuario->id_estado)->where('desestimado', '=', 0)->count()}} )
                                @endif
                                </b></a>
                            </li>
                            @endcan
                            @can('perfiles_geneticos.index')
                            <li class="nav-item">
                              <a class="nav-link pl-5" href="{{route('perfiles_geneticos.desestimados')}}">Perfiles Desestimados <b class=" border p-1 bg-warning rounded disabled ml-1">(
                               <?php $usuario = App\User::find(Illuminate\Support\Facades\Auth::id());?>
                                @if($usuario->estado->nombre == 'CNB')
                                  {{App\PerfilGenetico::where('desestimado','=',1)->count()}} )
                                @else
                                  {{App\PerfilGenetico::where('id_estado', '=', $usuario->id_estado)->where('desestimado','=',1)->count()}} )
                                @endif
                                </b></a>
                            </li>
                            @endcan
                        </ul>  
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-header text-white" id="headingThree">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed text-white" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                          <i class="fa fa-th-large"></i> Catalogos
                        </button>
                      </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <ul class="nav flex-column m-0">  
                            @can('fuentes.index')
                            <li class="nav-item">
                              <a class="nav-link pl-5" href="{{route('fuentes.index')}}">Fuentes</a>
                            </li>
                            @endcan
                            @can('categorias.index')
                            <li class="nav-item">
                              <a class="nav-link pl-5" href="{{route('categorias.index')}}">Categorias y etiquetas</a>
                            </li>
                            @endcan
                            @can('importaciones_frecuencias.index')
                            <li class="nav-item">
                              <a class="nav-link pl-5" href="{{route('importaciones_frecuencias.index')}}">Tablas de frecuencias</a>
                            </li>
                            @endcan
                            @can('marcadores.index')
                            <li class="nav-item">
                              <a class="nav-link pl-5" href="{{route('marcadores.index')}}">Marcadores</a>
                            </li>
                            @endcan
                            @can('etiquetas.index')
                            <li class="nav-item">
                              <a class="nav-link pl-5" href="{{route('etiquetas.index')}}">Etiquetas sin asignar <b class="btn btn-danger btn-sm disabled"> ({{App\Etiqueta::where('categoria_id', '=', null)->where('desestimado', '=', 0)->orWhere('categoria_id', '=', 9)->where('desestimado', '=', 0)->count()}})</b></a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-header text-white" id="headingFour">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed text-white" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                          <i class="fa fa-arrow-circle-o-up"></i> Importaciones
                        </button>
                      </h5>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                        <ul class="nav flex-column m-0">
                          @can('importaciones_perfiles.create')
                            <li class="nav-item">
                              <a class="nav-link pl-5" href="{{route('importaciones_perfiles.create')}}">Nueva importación</a>
                            </li>
                          @endcan
                          @can('importaciones_perfiles.index')  
                            <li class="nav-item">
                              <a class="nav-link pl-5" href="{{route('importaciones_perfiles.index')}}">Lista de importaciones</a>
                            </li>
                          @endcan
                        </ul>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-header text-white" id="headingFive">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed text-white" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                          <i class="fa fa-arrow-circle-o-down"></i> Exportaciones
                        </button>
                      </h5>
                    </div>
                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                        <ul class="nav flex-column m-0">
                          @can('exportaciones.index')  
                            <li class="nav-item">
                              <a class="nav-link pl-5" href="{{route('exportaciones.index')}}">Exportar genotipos</a>
                            </li>
                          @endcan
                        </ul>
                    </div>
                  </div>
                  @if($usuario->estado->nombre == 'CNB')
                  <div class="card">
                    <div class="card-header text-white" id="headingSix">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed text-white" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                          <i class="fa fa-key"></i> Auditoria
                        </button>
                      </h5>
                    </div>
                    <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
                        <ul class="nav flex-column m-0">
                            @can('users.index')
                            <li class="nav-item">
                              <a class="nav-link pl-5" href="{{route('users.index')}}">Usuarios</a>
                            </li>
                            @endcan
                            @can('roles.index')
                            <li class="nav-item">
                              <a class="nav-link pl-5" href="{{route('roles.index')}}">Roles</a>
                            </li>
                            @endcan
                            @can('logs.index')
                            <li class="nav-item">
                              <a class="nav-link pl-5" href="{{route('logs.index')}}">Logs</a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                  </div>
                  @endif
                </div>
            </div> 
            <main  id="contenido-pagina" class="container ml-0 p-2 bg-light">
                  @include('flash::message')
                  @yield('content')
            </main>
        @endguest
    </div>
    <!--<footer class="text-center bg-dark text-muted p-3"> Siscon Systems S.A. de C.V. 2018</footer>-->
    <!-- Script Boostrap -->    
    <script src="{{asset('js/bootstrap/bootstrap.min.js')}}"></script>
</body>
</html>
