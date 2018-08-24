<?php 
	namespace App\Http\Controllers;
	use Illuminate\Http\Request;
	use App\ImportacionPerfil;
	use App\PerfilGenetico;
	use App\TipoDeMetadato;
	use App\Metadato;
	use App\EtiquetaAsignada;
	use App\Etiqueta;
	use App\User;
	use App\Alelo;
	use App\Fuente;
	use App\Categoria;
	use App\Marcador;
	use Validator;  // Para validar el formulario de carga del excel
	use Maatwebsite\Excel; // Para la lectura del excel
	use Illuminate\Support\Facades\Input;   // Para saber el nombre del archivo recibido
	use Illuminate\Support\Facades\Auth;    // Para obtener datos del usuario en la session
	use App\Log;

	class ImportacionesPerfilesController extends Controller
	{
		/**    
		* Display a listing of the resource     
		*
	    * @return \Illuminate\Http\Response     
	    */
		public function index(){
		    $usuario = User::find(Auth::id());
		    if($usuario->estado->nombre == "CNB"){            
		    	$importaciones = ImportacionPerfil::where('desestimado', 0)->get();        
		    }        
		    else{            
		    	$importaciones = ImportacionPerfil::where('desestimado', 0)
		    	->where('id_estado', '=', $usuario->id_estado)->get();
		    }        
		    return view('importaciones_perfiles.index', [            
		    	'importaciones' => $importaciones,
		   	]);
		}   
		/**     
		* Show the form for creating a new resource     
		*     
		* @return \Illuminate\Http\Response     
		*/    

		public function create(){        
			$fuentes = Fuente::where('desestimado', 0)->get();  
			$usuario = User::find(Auth::id());      
			if($usuario->estado->nombre == "CNB"){
	            $categorias = Categoria::with(array('etiquetas' => function($query){
	              $query->with(array('perfiles_geneticos_asociados' => function($query){ 
	                $query->join('perfiles_geneticos', 'etiquetas_asignadas.id_perfil_genetico', '=', 'perfiles_geneticos.id')
	                ->where('perfiles_geneticos.desestimado', 0)
	                ->where('perfiles_geneticos.es_perfil_repetido', 0);
	              }))->where('desestimado', 0);
	            }))->where('desestimado', '=', 0)->get();            
	        }
	        else{
	            $categorias = Categoria::with(array('etiquetas' => function($query) use( &$usuario ){
	              $query->with(array('perfiles_geneticos_asociados' => function($query) use( &$usuario ){ 
	                $query->join('perfiles_geneticos', 'etiquetas_asignadas.id_perfil_genetico', '=', 'perfiles_geneticos.id')
	                ->where('perfiles_geneticos.desestimado', 0)
	                ->where('perfiles_geneticos.es_perfil_repetido', 0)
	                ->where('perfiles_geneticos.id_estado', '=', $usuario->estado->id);
	              }))->where('desestimado', 0);
	            }))->where('desestimado', '=', 0)->get();
	        }

			return view('importaciones_perfiles.create',[            
				'fuentes' => $fuentes,            
				'categorias' => $categorias,        
			]);
		}

		public function crear_categoria(Request $request){

		  if($request->ajax()){
		    
		    $this->validate($request, [
	            'nombre' =>'min:3|max:90|required|unique:categorias' 
	        ],[
	            'nombre.min' => 'El tamaño minimo del nombre de la categoria es de 3 caracteres',
	            'nombre.max' => 'El tamaño maximo del nombre de la categoria deber de ser de 90 caracteres',
	            'nombre.required' => 'El campo debe ser llenado',
	            'nombre.unique' => 'El nombre de la categoria asigando ya se encuentra en uso'
	        ]);


		    
		    $categoria = Categoria::create([
	            'nombre' => $request->input('nombre'),
	        ]);

	        $usuario = User::find(Auth::id());
			$log = Log::create([
	            'id_usuario' => $usuario->id,
	            'id_estado' => $usuario->estado->id,
	            'actividad' => 'Creo la categoria: ' . $categoria->nombre,
	        ]);

		    return response()->json([
		      'categoria' => $categoria,
		    ]); 
		  }
		}

		public function crear_etiquetas(Request $request){
		  if($request->ajax()){
		    
		    $etiquetas = explode("," , $request->nombre);

			foreach ($etiquetas as $key => $value) {
				$request2 = new \Illuminate\Http\Request();
				$request2->replace(['nombre' => trim($value)]);
				$this->validate($request2, [
		            'nombre' => 'min:3|max:90|required|unique:etiquetas'
		        ],[
		            'nombre.min' => 'El tamaño minimo del nombre de la etiqueta es de 3 caracteres',
		            'nombre.max' => 'El tamaño maximo del nombre de la etiqueta deber de ser de 90 caracteres',
		            'nombre.required' => 'El campo debe ser llenado',
		            'nombre.unique' => 'No pudieron ser agregadas las etiquetas por que alguna ya existe, favor de revisar.'
		        ]);
			}

			$etiquetas_nombres = '';

			foreach ($etiquetas as $etiqueta) {
				$etiqueta = Etiqueta::create([
	                /* la funcion trim elimina los espacios en blanco al principio y al final de la cadena*/
	                'nombre' => trim($etiqueta),
	                'categoria_id' => $request->categoria_id,
	            ]);

	            $etiquetas_nombres = $etiquetas_nombres . ',' . $etiqueta->nombre;
			}

			$categorias = Categoria::with(array('etiquetas' => function($query){
				$query->with(array('perfiles_geneticos_asociados' => function($query){ 
					$query->join('perfiles_geneticos', 'etiquetas_asignadas.id_perfil_genetico', '=', 'perfiles_geneticos.id')
					->where('perfiles_geneticos.desestimado', 0)
					->where('perfiles_geneticos.es_perfil_repetido', 0);
				}))->where('desestimado', 0);
			}))->where('desestimado', '=', 0)->get();

			$usuario = User::find(Auth::id());
			$log = Log::create([
	            'id_usuario' => $usuario->id,
	            'id_estado' => $usuario->estado->id,
	            'actividad' => 'Creo las etiquetas: ' . $etiquetas_nombres,
	        ]);

		    return response()->json([
		      'categorias' => $categorias,
		    ]); 
		  }
		}      
		/**     
		* Store a newly created resource in storage     
		*     
		* @param  \Illuminate\Http\Request  $request     
		* @return \Illuminate\Http\Response     
		*/    
		public function store(Request $request){
		    $this->validate($request, [            
		    	'id_fuente' => 'required',],
		    	['id_fuente.required' => 'Debe seleccionar una fuente',
			]);        
			
			$validator = Validator::make(            
				['archivo' => Input::file('archivo')],            
				['archivo' => 'mimes:xls,xlsx']        
			);

			// Comprobacion de validacion        
			if($validator->passes()){
			    
			    // Guardado del archivo de excel // ruta storage y nombre            
			    $ruta_archivo = $request->file('archivo')->storeAs('public',$request->file('archivo')->getClientOriginalName());            
			    
			    // Obtencion del nombre original del archivo            
			    $nombreDocumento = $request->file('archivo')->getClientOriginalName();            
			    $usuario = User::find(Auth::id());            
			    $consecutivo = ImportacionPerfil::where('id_estado', '=',$usuario->id_estado)->count() + 1;
			    
			    //Creacion de los datos de importacion            
			    $importacion_perfiles = ImportacionPerfil::create([                
			    	'nombre' => $nombreDocumento,                
			    	'identificador' => 'I-'. $usuario->id_estado . '-' . $consecutivo,                
			    	'id_fuente' => $request->id_fuente,                
			    	'id_usuario' => Auth::id(),                
			    	'numero_de_perfiles' => 0,                
			    	'numero_de_marcadores' => 0,                
			    	'tipo_de_muestra' => $request->tipo_de_muestra,                
			    	'observaciones' => $request->observaciones,                
			    	'titulo' => $request->titulo,                
			    	'id_estado' => $usuario->id_estado,            
			    ]);            

			    //Lectura del archivo Excel            
			    \Excel::load('storage/app/'.$ruta_archivo, function ($reader) use(&$request) {                
			    	$filas = $reader->get();                
			    	$contador = 0;  // Contador para determinar el numero de marcadores maximos usados por cada perfil
			    	$numero_de_marcadores = 0; // aumentara en uno cada vez que el contador le supere
			    	$importacion = ImportacionPerfil::all();                
			    	$id_importacion = $importacion->last()->id; // se busca el id de la importacion creada                
			    	$perfil_genetico;   // se mantiene el valor hasta que se cambie de fila                
			    	$usuario = User::find(Auth::id());
			    	
			    	/* Recorrido de cada fila del documento*/                
			    	foreach ($filas as $fila) {                    
			    		$contador = 0;  // Se inicializa el contador que enumera los marcadores por perfil                    
			    		$consecutivoPerfil = PerfilGenetico::where('id_estado', '=',$usuario->id_estado)->count() + 1;
			    		$numero_de_homocigotos = 0;
			    		/*Extraccion del valor de la columna y su valor*/                    
			    		foreach ($fila as $key => $value) {                        
			    			$value = trim($value);                        
			    			// si el valor de una celda esta vacio no se guarda nada                        
			    			if($value <> null){                            
			    			// se busca el marcador entre los existentes para determinar si es o no un metadato                            
			    				$marcador = Marcador::where('nombre', '=', $key)->first();                            
			    				// si existe se pasa a la creacion de los alelos del perfil genetico                            
			    				if( $marcador <> null){                                
			    					// se obtienen los 2 valores posibles de los alelos del perfil                                
			    					$alelos = explode(',', $value);                                
			    					if($marcador->id_tipo_de_marcador == 1 && $marcador->nombre <> 'yindel' || $marcador->nombre == 'dys385' ){                                    
				    					if(count($alelos) == 1){                                        
				    						$value = $value . ',' . $value;                                        
				    						$alelos = explode(',', $value);
				    					}                                
				    				}                                
				    				// si hay mas de un valor en los alelos se crean los 2 registros en la base                                
				    				                                
				    				if(count($alelos)>1){                                    
				    					if(count($alelos)==2){                                        
					    					$alelo = Alelo::create([                                            
					    						'id_perfil_genetico' => $perfil_genetico->id,                                            
					    						'id_marcador' => $marcador->id,                                            
					    						// 'alelo_1' => trim($alelos[0]),                                            
					    						// 'alelo_2' => trim($alelos[1]),
					    						'alelo_1' => trim(strtoupper($alelos[0])),                                            
					    						'alelo_2' => trim(strtoupper($alelos[1])),                                        
					    					]);
					    					if(trim($alelos[0]) == trim($alelos[1]) && $marcador->id_tipo_de_marcador == 1 && $marcador->nombre <> 'yindel' || $marcador->nombre == 'dys385'){
					    						$numero_de_homocigotos++;
					    					}                                    
					    				}                                   
					    				else{                                        
					    					if(count($alelos)==3){                                            
					    						$alelo = Alelo::create([                                                
						    						'id_perfil_genetico' => $perfil_genetico->id,                                                
						    						'id_marcador' => $marcador->id,                                                
						    						'alelo_1' => trim($alelos[0]),                                                
						    						'alelo_2' => trim($alelos[1]),                                                
						    						'alelo_3' => trim($alelos[2]),                            
						    					]);                                        
						    				}                                        
						    				else{                                            
						    					if(count($alelos)==4){                                                
							    					$alelo = Alelo::create([                                                    
							    						'id_perfil_genetico' => $perfil_genetico->id,
							    						'id_marcador' => $marcador->id,                                                    
							    						'alelo_1' => trim($alelos[0]),                                                    
							    						'alelo_2' => trim($alelos[1]),                                                    
							    						'alelo_3' => trim($alelos[2]),                                                    
							    						'alelo_4' => trim($alelos[3]),
							    					]);                                            
							    				}                                            
							    				else{                                                
							    					$alelo = Alelo::create([                                                    
							    						'id_perfil_genetico' => $perfil_genetico->id,
							    						'id_marcador' => $marcador->id,
							    						'alelo_1' => trim($alelos[0]),                                                    
							    						'alelo_2' => trim($alelos[1]),                                                    
							    						'alelo_3' => trim($alelos[2]),                                                    
							    						'alelo_4' => trim($alelos[3]),                                                    
							    						'alelo_5' => trim($alelos[4]),                                                
							    					]);                                               
							    				}
							    			}                                    
							    		}                                
							    	}                               
							    	else{                                    
							    		$alelo = Alelo::create([                                        
							    			'id_perfil_genetico' => $perfil_genetico->id,                                        
							    			'id_marcador' => $marcador->id,                                        
							    			'alelo_1' => trim($alelos[0]),                                    
							    		]);                                
							    	}

							    	// suma 1 el contador por cada marcador no vacio detectado en el perfil
							    	$contador++;

							    	// si el contador supera al valor de numero de marcadores suma 1                                
							    	if($numero_de_marcadores < $contador){
	                                    $numero_de_marcadores++;                                
	                                }
	                            }        

	                            // si no es marcador se verifica si la columna es el identificador externo
								else{                                
									// si es el identificador se registra el perfil genetico                                
									if($key == 'identifier'){                                    
										$perfil_genetico = PerfilGenetico::create([                                        
										'id_importacion' => $id_importacion,                                        
										'identificador' => 'CNB-'. $usuario->id_estado . '-' . $consecutivoPerfil,
										'id_usuario' => Auth::id(),
										'id_fuente' => $request->id_fuente,                                        
										'id_externo' => $value,                                        
										'id_estado' => $usuario->id_estado,                                    
									]);

									if($request->etiquetas<>null){
										foreach($request->etiquetas as $etiqueta){                                            
											$id_etiqueta = Etiqueta::find($etiqueta);                                            
											$etiqueta_asignada = EtiquetaAsignada::create([                                                
												'id_etiqueta' => $id_etiqueta->id,                                                
												'id_perfil_genetico' => $perfil_genetico->id,            
											]);                                        
										}                                    
									}                                
								}                                
								// si no es el identificador se determina que es un metadato                         
								else{                                    
									// se busca si existe el tipo de metadato en la base                                    
									$tipo_de_metadato = TipoDeMetadato::where('nombre','=',$key)->first();                                    
									
									// si no existe se crea un nuevo tipo de metadato                                    
									if($tipo_de_metadato == null){							
										$tipo_de_metadato = TipoDeMetadato::create([                                            
											'nombre' => $key,                                        
									]);                                        
								}                                    

								// si existe unicamente si obtiene su id para generar el metadato del perfil                                    
								$metadato = Metadato::create([                                        
									'id_perfil_genetico' => $perfil_genetico->id,                                        
									'id_tipo_de_metadato' => $tipo_de_metadato->id,                                        
									'dato' => $value,                                    
								]);                                
							}                            
						}                        
					}                    
				}                    

				$perfil_genetico->numero_de_homocigotos = $numero_de_homocigotos;                    
				$perfil_genetico->save();                    
				
				if($perfil_genetico->numero_de_homocigotos>5){                        
					$perfil_genetico->requiere_revision = 1;                        
					$perfil_genetico->save();                                            
				}                   
				$perfil_genetico->numero_de_marcadores = $contador;                    
				$perfil_genetico->save();                    
				
				if($perfil_genetico->numero_de_marcadores<13){                       
					$perfil_genetico->requiere_revision = 1;                        
					$perfil_genetico->save();                    
				}                    

				$datos_perfil = array();

		        foreach ($perfil_genetico->alelos->sortBy('id_marcador') as $perfil) {
		          array_push( $datos_perfil, $perfil->id_marcador, $perfil->alelo_1, $perfil->alelo_2); 
		        }

		        $datos_perfil = implode($datos_perfil);
		        $perfil_genetico->cadena_unica = $datos_perfil;
		        $perfil_genetico->save();

		        // d = 0 && r =0
		        $genotipo_repetido = PerfilGenetico::where('desestimado', '=', 0)->where('cadena_unica', '=', $perfil_genetico->cadena_unica)->orWhere('es_perfil_repetido', '=' , 0)->where('cadena_unica', '=', $perfil_genetico->cadena_unica)->where('desestimado', '=', 0)->first(); 
		        
		        if($genotipo_repetido <> null){
		          if($genotipo_repetido->id <> $perfil_genetico->id){
		            $perfil_genetico->es_perfil_repetido = 1;
		            $perfil_genetico->id_perfil_original = $genotipo_repetido->id;
		            $perfil_genetico->id_estado_perfil_original = $genotipo_repetido->id_estado;
		            $perfil_genetico->save();  
		          }
		        }                
			}

			$importacion = ImportacionPerfil::find($id_importacion);                
			$importacion->numero_de_perfiles = $importacion->perfiles_geneticos->count();                
			$importacion->numero_de_marcadores = $numero_de_marcadores;                
			$importacion->save();

	        $log = Log::create([
	            'id_usuario' => $usuario->id,
	            'id_estado' => $usuario->estado->id,
	            'actividad' => 'Importo los perfiles desde el archivo: ' . $importacion->nombre,
	        ]);                

			flash('El archivo fue importado', 'success');                           
		});            
		return redirect()->route('importaciones_perfiles.index');        
		}        
		else{            
			flash('El formato del archivo no es correcto', 'warning');            
			return redirect()->route('importaciones_perfiles.index');
		}    
	}   
	/**     
	* Display the specified resource     
	*     
	* @param  int  $id     
	* @return \Illuminate\Http\Response     
	*/    
	public function show($id){        
		$importacion_perfiles = ImportacionPerfil::find($id);        
		$perfiles_geneticos = PerfilGenetico::with('usuario')
			->with('perfil_original')
			->where('id_importacion','=',$id)->get();        
		return view('importaciones_perfiles.show', [            
			'importacion_perfiles' => $importacion_perfiles,            
			'perfiles_geneticos' =>$perfiles_geneticos,        
		]);    
	}

	public function show_perfil($id)
    {
        $perfil_genetico = PerfilGenetico::find($id);
        return view('importaciones_perfiles.show_perfil', [
            'perfil_genetico' => $perfil_genetico,
        ]);
    }

    public function validar(Request $request, $id){
      $perfil_genetico = PerfilGenetico::find($id);
      $validacion = $request->validacion;
      if($validacion == 'aprobar'){
        $perfil_genetico->requiere_revision = 0;
        $perfil_genetico->id_usuario_reviso = Auth::id();
        flash('El perfil <b>' . $perfil_genetico->identificador . '</b> fue validado');
      }
      else{
          if($validacion == 'POR NUMERO DE MARCADORES'){
            $perfil_genetico->desestimado = 1;
            $perfil_genetico->motivo_de_desestimacion = 'POR NUMERO DE MARCADORES';
            $perfil_genetico->requiere_revision = 1;
            $perfil_genetico->id_usuario_reviso = Auth::id(); 
            flash('El perfil fue <b>' . $perfil_genetico->identificador . '</b> fue desestimado');
          }
          else{
            $perfil_genetico->desestimado = 1;
            $perfil_genetico->motivo_de_desestimacion = 'POR NUMERO DE HOMOCIGOTOS';
            $perfil_genetico->requiere_revision = 1;
            $perfil_genetico->id_usuario_reviso = Auth::id(); 
            flash('El perfil fue <b>' . $perfil_genetico->identificador . '</b> fue desestimado');
          }
      }
      $perfil_genetico->save();

      $usuario = User::find(Auth()->id());  
      $log = Log::create([
          'id_usuario' => $usuario->id,
          'id_estado' => $usuario->estado->id,
          'actividad' => 'Comprobo el numero de homocigotos y marcadores del perfil_genetico: ' . $perfil_genetico->identificador,
      ]);
      
      return redirect()->route('importaciones_perfiles.show', $perfil_genetico->id_importacion);
    }

    public function validar_duplicado($id){
        $fuentes = Fuente::get();
        
        $usuario = User::find(Auth::id());   
        if($usuario->estado->nombre == "CNB"){
            $categorias = Categoria::with(array('etiquetas' => function($query){
              $query->with(array('perfiles_geneticos_asociados' => function($query){ 
                $query->join('perfiles_geneticos', 'etiquetas_asignadas.id_perfil_genetico', '=', 'perfiles_geneticos.id')
                ->where('perfiles_geneticos.desestimado', 0)
                ->where('perfiles_geneticos.es_perfil_repetido', 0);
              }))->where('desestimado', 0);
            }))->where('desestimado', '=', 0)->get();            
        }
        else{
            $categorias = Categoria::with(array('etiquetas' => function($query) use( &$usuario ){
              $query->with(array('perfiles_geneticos_asociados' => function($query) use( &$usuario ){ 
                $query->join('perfiles_geneticos', 'etiquetas_asignadas.id_perfil_genetico', '=', 'perfiles_geneticos.id')
                ->where('perfiles_geneticos.desestimado', 0)
                ->where('perfiles_geneticos.es_perfil_repetido', 0)
                ->where('perfiles_geneticos.id_estado', '=', $usuario->estado->id);
              }))->where('desestimado', 0);
            }))->where('desestimado', '=', 0)->get();
        }
        
        $marcadores = Marcador::get();
        $perfil_genetico_repetido = PerfilGenetico::find($id);
        $perfil_genetico = PerfilGenetico::find($perfil_genetico_repetido->id_perfil_original);
        // metadatos
        $fecha_de_hallazgo= null;
        $lugar= null;
        $paraje= null;
        $fosa= null;
        $nombre_del_donante= null;
        $nombre_del_desaparecido = null;
        $curp_del_desaparecido = null;
        $parentesco_con_el_desaparecido= null;
        $curp_del_familiar = null;
        $clave_de_muestra = $perfil_genetico->id_externo;
        $descripcion_de_la_muestra= null;
        $observaciones= null;
        $talla= null;
        $peso= null;
        $s_particulares_o_malformaciones= null;
        $tatuaje= null;
        $sexo= null;
        $ci_nuc_ap= null;
        $fecha_desaparicion= null;
        $lugar_de_desaparicion= null;
        $no_de_caso_relacionado= null;
        $curp = null;

        

        foreach($perfil_genetico->metadatos as $metadato){
          
          if($metadato->tipo_de_metadato->nombre == 'fecha_de_hallazgo'){$fecha_de_hallazgo = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'lugar'){$lugar = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'paraje'){$paraje = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'fosa'){$fosa = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'nombre_del_donante'){$nombre_del_donante = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'nombre_del_desaparecido'){$nombre_del_desaparecido = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'curp_del_desaparecido'){$curp_del_desaparecido = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'parentesco_con_el_desaparecido'){$parentesco_con_el_desaparecido = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'curp_del_familiar'){$curp_del_familiar = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'descripcion_de_la_muestra'){$descripcion_de_la_muestra = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'observaciones'){$observaciones = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'talla'){$talla = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'peso'){$peso = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 's_particulares_o_malformaciones'){$s_particulares_o_malformaciones = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'tatuaje'){$tatuaje = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'sexo'){$sexo = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'ci_nuc_ap'){$ci_nuc_ap = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'fecha_desaparicion'){$fecha_desaparicion = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'lugar_de_desaparicion'){$lugar_de_desaparicion = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'no_de_caso_relacionado'){$no_de_caso_relacionado = $metadato->dato;}
          if($metadato->tipo_de_metadato->nombre == 'curp'){$curp = $metadato->dato;}
        }

        return view('importaciones_perfiles.validar_duplicado', [
            'perfil_genetico_repetido' => $perfil_genetico_repetido,
            'perfil_genetico' => $perfil_genetico,
            'marcadores' => $marcadores,
            'fuentes' => $fuentes,
            'categorias' => $categorias,
            //metadatos
            'fecha_de_hallazgo' => $fecha_de_hallazgo,
            'lugar' => $lugar,
            'paraje' => $paraje,
            'fosa'  => $fosa,
            'nombre_del_donante' => $nombre_del_donante,
            'nombre_del_desaparecido' => $nombre_del_desaparecido,
            'curp_del_desaparecido' => $curp_del_desaparecido,
            'parentesco_con_el_desaparecido' => $parentesco_con_el_desaparecido,
            'curp_del_familiar' => $curp_del_familiar,
            'clave_de_muestra' => $clave_de_muestra,
            'descripcion_de_la_muestra' => $descripcion_de_la_muestra,
            'observaciones' => $observaciones,
            'talla' => $talla,
            'peso' => $peso,
            's_particulares_o_malformaciones' => $s_particulares_o_malformaciones,
            'tatuaje' => $tatuaje,
            'sexo' => $sexo,
            'ci_nuc_ap' => $ci_nuc_ap,
            'fecha_desaparicion' => $fecha_desaparicion,
            'lugar_de_desaparicion' => $lugar_de_desaparicion,
            'no_de_caso_relacionado' => $no_de_caso_relacionado,
            'curp' => $curp,
        ]);
    }

    public function guardar_validacion_de_duplicado(Request $request, $id){
      $usuario_reviso = User::find(Auth()->id());
      $perfil_genetico_repetido = PerfilGenetico::find($id);
      $perfil_genetico_original = PerfilGenetico::find($perfil_genetico_repetido->id_perfil_original);
      $perfil_genetico_repetido->desestimado = 1;
      $perfil_genetico_repetido->motivo_de_desestimacion = 'DUPLICADO CON ' . $perfil_genetico_original->identificador;
      $perfil_genetico_repetido->id_usuario_reviso = $usuario_reviso->id;
      $perfil_genetico_repetido->save();
      $perfil_genetico_original->id_externo = $request->clave_de_muestra;
      $perfil_genetico_original->id_fuente = $request->id_fuente;
      $perfil_genetico_original->save();

      if($perfil_genetico_original->se_actualizo_con_los_perfiles == null){
          $perfil_genetico_original->se_actualizo_con_los_perfiles = $perfil_genetico_repetido->identificador;
      }
      else{
          $perfil_genetico_original->se_actualizo_con_los_perfiles = $perfil_genetico_original->se_actualizo_con_los_perfiles . ',' .  $perfil_genetico_repetido->identificador; 
      }

      $perfil_genetico_original->save();

        foreach ($perfil_genetico_original->etiquetas as $etiqueta){
            $etiqueta->delete();
        }
        foreach ($perfil_genetico_original->metadatos as $metadato) {
            $metadato->delete();          
        }

        foreach ($request->all() as $key => $value) {
            if($value<>null){
                if(is_array($value)){
                    foreach ($value as $key => $value) {
                        $etiqueta = EtiquetaAsignada::create([
                            'id_etiqueta' => $value,
                            'id_perfil_genetico' => $perfil_genetico_original->id,
                        ]);
                    }
                }
                else{
                    if($key <> '_token'){ // descartamos el token de la consulta
                        if($key <> 'id_fuente'){    // descartamos la fuente
                          if($key <> 'clave_de_muestra'){
                            if($key <> '_method'){
                              $primera_palabra = explode('_', $key);    // dividimos el $key en palabras separadas
                              $verifica_marcador = Marcador::where('nombre', '=', $primera_palabra[0])->first(); // buscamos si la palabra existe en marcadores

                              if(empty($verifica_marcador)){  // si la busqueda devuelve un arreglo vacio, es un metadato
                                $id_tipo_de_metadato = TipoDeMetadato::where('nombre','=', $key)->first();
                                if(empty($id_tipo_de_metadato)){    // si no existe el tipo de metadato lo creamos y guardamos
                                    $tipo_de_metadato = TipoDeMetadato::create([
                                        'nombre' => $key
                                    ]);

                                    $metadato = Metadato::create([
                                        'id_perfil_genetico' => $perfil_genetico_original->id,
                                        'id_tipo_de_metadato' => $tipo_de_metadato->id,
                                        'dato' => $value
                                    ]);                                    
                                }   
                                else{   // si existe solo creamos el metadato asociado al perfil
                                    $metadato = Metadato::create([
                                        'id_perfil_genetico' => $perfil_genetico_original->id,
                                        'id_tipo_de_metadato' => $id_tipo_de_metadato->id,
                                        'dato' => $value
                                    ]);   
                                }
                              }
                            }
                          }
                        }
                    }
                }
            }
        }

    	$usuario = User::find(Auth()->id());  
        $log = Log::create([
            'id_usuario' => $usuario->id,
            'id_estado' => $usuario->estado->id,
            'actividad' => 'Valido la informacion del perfil genetico ' . $perfil_genetico_original->identificador . ' debido a que se ingreso el perfil genetico duplicado: ' . $perfil_genetico_repetido->identificador,
        ]);

        flash('El perfil duplicado fue desestimado  y el perfil original fue actualizado', 'success');
        return redirect()->route('importaciones_perfiles.show', $perfil_genetico_repetido->id_importacion); 
    }
	/**     
	* Show the form for editing the specified resource     
	*     
	* @param  int  $id     
	* @return \Illuminate\Http\Response     
	*/    
	public function edit($id){        
		//    
	}    
	/**     
	* Update the specified resource in storage     
	*     
	* @param  \Illuminate\Http\Request  $request     
	* @param  int  $id     
	* @return \Illuminate\Http\Response     
	*/   
	public function update(Request $request, $id){        
		//   
 	}    
 	/**     
 	* Remove the specified resource from storage     
 	*     
 	* @param  int  $id     
 	* @return \Illuminate\Http\Response     
 	*/    
 	public function destroy($id){        

 		$usuario = User::find(Auth::id());
 		$importacion = ImportacionPerfil::find($id);
 		$perfiles_geneticos = PerfilGenetico::where('id_importacion', '=' , $importacion->id )->get();
 		
 		foreach ($perfiles_geneticos as $perfil_genetico) {
 			$perfil_genetico->desestimado = 1;
 			$perfil_genetico->id_usuario_reviso = $usuario->id;
 			$perfil_genetico->save();
 		}

 		$importacion->desestimado = 1;
 		$importacion->save();

 		$log = Log::create([
            'id_usuario' => $usuario->id,
            'id_estado' => $usuario->estado->id,
            'actividad' => 'Elimino la importacion  y los perfiles asociados cargados en el archivo: ' . $importacion->nombre,
	    ]);                


 		        
 		Flash('Se elimino correctamente la importacion seleccionada', 'success');        
 		return redirect()->route('importaciones_perfiles.index');
	}
}