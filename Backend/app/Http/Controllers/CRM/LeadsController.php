<?php


namespace App\Http\Controllers\CRM;


use App\Http\Controllers\Controller;
use App\Models\Admon\Departamentos;
use App\Models\Admon\Estados;
use App\Models\Admon\Paises;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\CRM\Cargos;
use App\Models\CRM\ClasificacionLlamadas;
use App\Models\CRM\CompaniaContactoDetalle;
use App\Models\CRM\Companias;
use App\Models\CRM\Contactos;
use App\Models\CRM\GirosNegocios;
use App\Models\CRM\Leads;
use App\Models\CRM\OrigenesLeads;
use App\Models\CRM\SeguimientosLeads;
use App\Models\CRM\ServiciosOferAcor;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function PHPUnit\Framework\isNull;

class LeadsController extends Controller
{
    /**
     * Obtener una lista de los origenes de leads
     *
     * @access  public
     * @param Request $request
     * @param Leads $leads
     * @return JsonResponse
     * @author rsequeira
     * @copyright rsequeira
     */

    public function obtener(Request $request, Leads $leads)
    {
        $leads = $leads->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $leads->total(),
                'rows' => $leads->items(),
            ],
            'messages' => null
        ]);
    }


    /**
     * obtener estado de seguimiento
     * @param Request $request
     * @return JsonResponse
     * @author rsequeira
     * @copyright rsequeira
     * @access  public
     */

    public function obtenerLead(Request $request)
    {
        $rules = [
            'id_lead' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $lead = Leads::select('id_lead', 'nombre', 'apellido', 'id_cargo', 'codigo_postal', 'codigo_prospecto', 'id_giro_negocio', 'comentario_lead', 'descripcion_lead', 'detalle_servicio', 'direccion_compania', 'email_compania', 'email_contacto', 'estado', 'extranjero', 'facebook', 'google_my_business', 'id_ciudad', 'id_departamento', 'id_empresa', 'id_estado', 'id_municipio', 'id_origen_lead', 'id_pais', 'informacion_empresa', 'instagram', 'nombre_compania', 'telefono_compania', 'telefono_movil', 'telefono_trabajo', 'twitter', 'u_responsable', 'url_origen', 'web_site', 'yelp', 'zona_servicio')->where('id_lead', $request->id_lead)
                ->with('ciudadProspecto')
                ->with('giroProspecto')
                ->with('origenProspecto')
                ->with('responsableProspecto')
                ->with('paisProspecto')
                ->with('cargos')
                ->with('departamentoProspecto')
                ->with('municipioProspecto')
                ->first();
            $estado = Estados::with('ciudadesEstado')->whereIn('id_estado', array(3, 5, 43, 13, 51))->get();
            $departamentos = Departamentos::with('municipiosDepartamento')->get();

            if (!empty($lead)) {
                return response()->json([
                    'status' => 'success',
                    'result' => [
                        'lead' => $lead,
                        'estado' => $estado,
                        'departamentos' => $departamentos
                    ],
                    'messages' => null
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_lead' => ["Datos no encontrados"]),
                    'messages' => null
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'result' => $validator->messages(),
                'messages' => null
            ]);
        }
    }

    /**
     * obtener estado de seguimiento
     * @param Request $request
     * @return JsonResponse
     * @author rsequeira
     * @copyright rsequeira
     * @access  public
     */
    public function obtenerServicios(Request $request)
    {
        $rules = [
            'id_lead' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $lead = Leads::where('id_lead', $request->id_lead)->where('estado', 1);
            $str_serv_nec = Leads::select('servicio_necesitado')->where('id_lead', $request->id_lead)->first();
            $map = [];
            foreach ($str_serv_nec->toArray() as $arr1) {
                $map = $arr1;
            }
            $arr_serv_nec = array_map('intval', explode(",", (string)$map));
            $servi_necesitados = ServiciosOferAcor::select('id_servicio_ofr_acor', 'descripcion')->whereIn('id_servicio_ofr_acor', $arr_serv_nec)->get();
            /*print_r($arr_serv_nec);*/
            if (!empty($lead)) {
                return response()->json([
                    'status' => 'success',
                    'result' => [
                        'servNecesitados' => $servi_necesitados,
                    ],
                    'messages' => null
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_lead' => ["Datos no encontrados"]),
                    'messages' => null
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'result' => $validator->messages(),
                'messages' => null
            ]);
        }
    }

    public function nuevo(Request $request)
    {

        $paises = Paises::where('estado', 1)->with(['departamentos' => function ($query) {
            $query->with('municipiosDepartamento');
        }])->whereIn('id_pais', array(21, 42))->get();   // id:21 = USA | id:42 = NIC
//        $lead = Leads::Select('id_lead')->orderBy('id_lead', 'asc')->get();
        $origenes = OrigenesLeads::select(['id_origen_lead', 'descripcion'])->where('estado', 1)->get();
        $estados = Estados::with('ciudadesEstado')->whereIn('id_estado', array(3, 5, 43, 13, 51))->get(); // estados cargados Texas, maimi, otros y sus ciudades
        $departamentos = Departamentos::with('municipiosDepartamento')->get();
        $giro_negocio = GirosNegocios::select('id_giro_negocio', 'descripcion')->get();
        $servicio_ofr_acor = ServiciosOferAcor::select('id_servicio_ofr_acor', 'descripcion')->get();
        $vendedores = User::select('id', 'name')->get();
        $clasificacion_llamada = ClasificacionLlamadas::select('id_clasificacion_llamada', 'descripcion')->get();
        $usuarios = User::where('estado', '!=', 0)->get();
        $cargos = Cargos::where('estado', 1)->get();
        return response()->json([
            'status' => 'success',
            'result' => [
                'paises' => $paises,
                'origenes' => $origenes,
                'estados' => $estados,
                'departamentos' => $departamentos,
                'giros_negocios' => $giro_negocio,
                'servicios' => $servicio_ofr_acor,
                'vendedores' => $vendedores,
                'clasificacion_llamada' => $clasificacion_llamada,
                //'lead' => $lead,
                'usuarios' => $usuarios,
                'cargoslst' => $cargos
            ],
            'messages' => null
        ]);
    }


    /**
     * Crear un nuevo origen de lead
     * @param Request $request
     * @return JsonResponse
     * @author rsequeira
     * @copyright rsequeira
     * @access  public
     */

    public function registrar(Request $request)
    {
        $messages = [
            //'estados.*.id_estado.required' => 'Debe seleccionar un estado' ,
            //'ciudad.*.id_ciudad.required' => 'Debe seleccionar una ciudad' ,
            'nombre_compania' => 'El nombre de la compania es requerido',
            'telefono_compania.required' => 'Digite un número',
            'telefono_compania.unique' => 'Este número ya existe'
        ];
        $rules = [
            //'estado' => ['required', 'array','min:1'] ,
            'nombre_compania' => ['required', 'string', 'max:50'],
            'telefono_compania' => ['required', 'string', 'max:15', Rule::unique('pgsql.crm.leads')]
        ];

        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
        $validator = Validator::make($request->all(), $rules, $messages);
        if (!$validator->fails()) {
            $lead = new Leads();
            // 1: extrajero | 0: Nacional
            if ($lead->id_pais === 42) {
                $lead->extranjero = 0;
            } else {
                $lead->extranjero = 1;
            }
            $codigo_nuevo = $lead->obtenerCodigoProspecto($lead->extrajero);
            $str_length = 6;
            $str_length2 = 2;
            $str = ($lead->extranjero == 1 ? 'E' : 'N') . substr("0{$lead->id_lead}", -$str_length2) . substr("00000{$codigo_nuevo['secuencia']}", -$str_length);
            $lead->secuencia = $codigo_nuevo['secuencia'];
            $lead->codigo_prospecto = 'PR' . $str;
            $lead->descripcion_lead = 'Lead-' . $lead->codigo_prospecto;
            $lead->telefono_compania = $request->telefono_compania;
            $lead->nombre_compania = $request->nombre_compania;
            $lead->id_pais = $request->pais['id_pais'];
            if ($lead->id_pais === 42) { // solo para Nicaragua
                $lead->id_departamento = $request->departamento['id_departamento'];
                $lead->id_municipio = $request->municipio['id_municipio'];
            } else {
                $lead->id_ciudad = $request->ciudad['id_ciudad'];
                $lead->id_estado = $request->estado['id_estado'];
            }
            $lead->estado = 1; //Estado nuevo prospepecto
            $lead->u_creacion = Auth::user()->name;
            $lead->u_responsable = Auth::user()->name;
            $lead->id_empresa = $usuario_empresa->id_empresa;
            $lead->save();
            $id_lead = Leads::select('id_lead', 'descripcion_lead', 'codigo_prospecto')->where('id_lead', $lead->id_lead)->first();
            return response()->json([
                'status' => 'success',
                'result' => [
                    'lead' => $id_lead
                ],
                'messages' => null
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'result' => $validator->messages(),
                'messages' => null
            ]);
        }
    }

    /**
     * Actualizar estado seguimiento
     * @param Request $request
     * @return JsonResponse
     * @author rsequeira
     * @copyright rsequeira
     * @access  public
     */

    public function actualizar(Request $request)
    {
        $rules = [
            'id_lead' => 'required|integer|min:1',
            'direccion_compania' => 'required|string|max:300',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $lead = Leads::find($request->id_lead);
            $lead->descripcion_lead = $request->descripcion_lead;
            $lead->nombre = $request->nombre;
            $lead->apellido = $request->apellido;
            if($request->cargo){

            $lead->id_cargo = $request->cargos['id_cargo'];
            }
            $lead->telefono_trabajo = $request->telefono_trabajo;
            $lead->telefono_movil = $request->telefono_movil;
            $lead->email_contacto = $request->email_contacto;
            $lead->email_compania = $request->email_compania;
            $lead->nombre_compania = $request->nombre_compania;
            $lead->web_site = $request->web_site;

            if ($lead->id_pais === 21) {
                $lead->id_estado = $request->estado_lead['id_estado'];
                $lead->id_ciudad = $request->ciudad_prospecto['id_ciudad'];
            } else {
                $lead->id_departamento = $request->departamento_prospecto['id_departamento'];
                $lead->id_municipio = $request->municipio_prospecto['id_municipio'];
            }
            $lead->codigo_postal = $request->codigo_postal;
            $lead->direccion_compania = $request->direccion_compania;
            $lead->u_modificacion = Auth::user()->name;
            $lead->facebook = $request->facebook;
            $lead->twitter = $request->twitter;
            $lead->instagram = $request->instagram;
            $lead->google_my_business = $request->google_my_business;
            $lead->yelp = $request->yelp;
            $lead->id_origen_lead = $request->origen_prospecto['id_origen_lead'];
            $lead->id_giro_negocio = $request->giro_prospecto['id_giro_negocio'];
            $lead->informacion_empresa = $request->informacion_empresa;
            $lead->comentario_lead = $request->comentario_lead;
            $lead->telefono_compania = $request->telefono_compania;
            $lead->zona_servicio = $request->zona_servicio;
            $lead->servicio_necesitado = $request->servicio_necesitado;
            $lead->url_origen = $request->url_origen;
            $lead->u_responsable = $request->responsable_prospecto['name'];
            $lead->detalle_servicio = $request->detalle_servicio;
            $lead->save();

            return response()->json([
                'status' => 'success',
                'result' => null,
                'messages' => null
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'result' => $validator->messages(),
                'messages' => null
            ]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @author rsequeira
     * @copyright rsequiera
     */
    public function registrarSeguimiento(Request $request)
    {
        $messages = [
            'descripcion_led' => 'El tema de prospectación es requerido',
            'estado.*.id_estado' => 'Debe seleccionar un estado',
            'ciudad.*.id_ciudad' => 'Debe seleccionar una ciudad',
            'nombre_compania' => 'Esta compania ya ha sido registrada',
        ];
        $rules = [
            'descripcion_lead' => ['required', 'string', 'min:10'],
            'estados.*.id_estado' => ['required', 'array', 'min:1'],
            'cuidades.*.id_ciudad' => ['required', 'array', 'min:1'],
            'nombre_compania' => ['required', 'string', 'max:50', Rule::unique('pgsql.crm.leads')],
            'telefono_trabajo' => ['required', 'string', 'max:14']
        ];

        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
        $validator = Validator::make($request->all(), $rules, $messages);
        if (!$validator->fails()) {
            $lead = new Leads();
            $lead->descripcion_lead = $request->descripcion_lead;
            $lead->nombre = $request->nombre;
            $lead->apellido = $request->apellido;
            $lead->telefono_trabajo = $request->telefono_trabajo;
            $lead->nombre_compania = $request->nombre_compania;
            $lead->id_ciudad = $request->ciudad['id_ciudad'];
            $lead->id_estado = $request->estado['id_estado'];
            $lead->id_empresa = $usuario_empresa->id_empresa;
            $lead->estado = 1; //Estado nuevo prospepecto
            $lead->u_creacion = Auth::user()->name;
            $lead->u_responsable = Auth::user()->name;
            $lead->save();

            return response()->json([
                'status' => 'success',
                'result' => null,
                'messages' => null
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'result' => $validator->messages(),
                'messages' => null
            ]);
        }
    }

    /**
     * Descartar lead
     * @param Request $request
     * @return JsonResponse
     * @access  public
     * @author rsequeira
     * @copyright rsequeira
     */

    public function desactivar(Request $request)
    {
        $rules = [
            'id_lead' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $lead = Leads::find($request->id_lead);
            $lead->estado = 4;
            $lead->u_modificacion = Auth::user()->name;
            $lead->f_descarta = now();
            $lead->save();

            return response()->json([
                'status' => 'success',
                'result' => null,
                'messages' => null
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'result' => $validator->messages(),
                'messages' => null
            ]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @author rsequeira
     * @copyright rsequeira
     */

    public function activar(Request $request)
    {
        $rules = [
            'id_lead' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $lead = Leads::find($request->id_lead);
            $lead->estado = 1;
            $lead->u_modificacion = Auth::user()->name;
            $lead->save();

            return response()->json([
                'status' => 'success',
                'result' => null,
                'messages' => null
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'result' => $validator->messages(),
                'messages' => null
            ]);
        }
    }

    /**
     * Obtener leads asignados a un usuario
     * @param Request $request
     * @return JsonResponse
     * @author octaviom
     * @copyright octaviom
     * @access  public
     */

    public function obtenerLeadsPorResponsable(Request $request)
    {
        $rules = [
            'usuario_origen' => 'required|array|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            // Se obtienen los leads que tiene asignado actualmente el usuario solicitado desde el formulario, y no se toman en cuenta los que estan en estado descartado
            $leads = Leads::select('id_lead', 'codigo_prospecto', 'nombre_compania', 'telefono_compania', 'u_responsable as usuario_responsable')
                ->where('u_responsable', $request->usuario_origen['name'])
                ->whereNotIn('estado', array(4))->get();

            //Se retorna en respuesta json, los leads obtenidos
            return response()->json([
                'status' => 'success',
                'result' => [
                    'leads' => $leads,
                ],
                'messages' => null
            ]);
        }

        return response()->json([
            'status' => 'error',
            'result' => $validator->messages(),
            'messages' => null
        ]);
    }

    /**
     * Reasignar leads a nuevo usuario
     * @param Request $request
     * @return JsonResponse
     * @author octaviom
     * @copyright octaviom
     * @access  public
     */

    public function reasignarLeads(Request $request)
    {
        $rules = [
            'usuario_destino' => 'required|array|min:1',
            'selected' => 'required_if:all_leads,false|array|min:1', // si la condicion 'all_leads' es falsa, entonces se requerira el arreglo 'selected' reasignando solamente los registros seleccionados
            'leads' => 'required_if:all_leads,true|array|min:1',// si la condicion 'all_leads' es true, entonces se requerira el arreglo 'leads' reasignando todos los leads al nuevo usuario
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            try {
                DB::beginTransaction();
                $i = 0; // inicializando contador
                // Se ejecuta un foreach para recorrer cada objeto del arreglo de leads y asignarle el nuevo usuario
                if ($request->all_leads) {
                    foreach ($request->leads as $lead) {
                        // se asigna nuevo repsonsable a los leads
                        $leads = Leads::find($lead['id_lead']);
                        $leads->u_responsable = $request['usuario_destino']['name'];
                        $leads->save();

                        // se asigna nuevo resposnable al seguimiento de los leads
                        $leads_seguimiento = SeguimientosLeads::find($lead['id_lead']);
                        $leads_seguimiento->u_responsable = $request['usuario_destino']['name'];
                        $leads_seguimiento->save();

                        $i++;
                    }
                } else {
                    // Se ejecuta un foreach para recorrer cada objeto del arreglo de leads y asignarle el nuevo usuario
                    foreach ($request->selected as $leads_seleccionados) {
                        $leads = Leads::find($leads_seleccionados['id_lead']);
                        $leads->u_responsable = $request['usuario_destino']['name'];
                        $leads->save();

                        // se asigna nuevo resposnable al seguimiento de los leads
                        $leads_seguimiento = SeguimientosLeads::find($leads_seleccionados['id_lead']);
                        $leads_seguimiento->u_responsable = $request['usuario_destino']['name'];
                        $leads_seguimiento->save();

                        $i++;
                    }
                }

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'result' => 'Total de leads actualizados: ' . $i,
                    'messages' => 'Se han actualizados todos los leads correctamente.'
                ]);
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'result' => 'Error de base de datos',
                    'messages' => 'Error de base de datos, favor contacte con los desarrolladores'
                ]);
            }
        }
        //Si se encuentran fallos en las validaciones, se retorna respuesta json con el resultados de las alertas
        return response()->json([
            'status' => 'error',
            'result' => $validator->messages(),
            'messages' => 'Error de validacion, revise que los campos ingresados son los correctos'
        ]);
    }

    public function registroContacto(Request $request)
    {
        $rules = [
            'id_lead' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $leadVl = Leads::select('nombre', 'apellido', 'id_cargo', 'telefono_trabajo', 'id_pais', 'id_departamento',
                'id_municipio', 'id_estado', 'id_ciudad', 'estado')->where('id_lead', $request->id_lead)
                ->first();

                if (is_null($leadVl->nombre) || is_null($leadVl->apellido) || is_null($leadVl->telefono_trabajo) ||
                    is_null($leadVl->id_pais)) {
                    return response()->json([
                        'status' => 'error',
                        'result' => 'Faltan datos de Contacto',
                        'messages' => null
                    ]);
                } else {
                    $lead = Leads::select('id_lead', 'nombre', 'apellido', 'id_cargo', 'email_contacto', 'telefono_trabajo', 'telefono_movil',
                        'id_pais', 'id_departamento', 'id_municipio', 'codigo_postal', 'u_responsable', 'codigo_prospecto',
                        'id_estado', 'id_ciudad', 'direccion_compania')->where('id_lead', $request->id_lead)
                        ->first();


                    $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
                    $contacto = new Contactos();
                    $contacto->id_lead = $lead['id_lead'];
                    $contacto->nombre = $lead['nombre'];
                    $contacto->apellido = $lead['apellido'];
                    $contacto->id_cargo = $lead['id_cargo'];
                    $contacto->email = $lead['email_contacto'];
                    $contacto->telefono_trabajo = $lead['telefono_trabajo'];
                    $contacto->telefono_movil = $lead['telefono_movil'];
                    $contacto->id_pais = $lead['id_pais'];
                    $contacto->id_departamento = $lead['id_departamento'];
                    $contacto->id_municipio = $lead['id_municipio'];
                    $contacto->codigo_postal = $lead['codigo_postal'];
                    $contacto->u_responsable = $lead['u_responsable'];
                    $contacto->codigo_prospecto = $lead['codigo_prospecto'];
                    $contacto->id_estado = $lead['id_estado'];
                    $contacto->id_ciudad = $lead['id_ciudad'];
                    $contacto->direccion = $lead['direccion_compania'];
                    $contacto->u_creacion = Auth::user()->name;
                    $contacto->estado = 1;
                    $contacto->id_empresa = $usuario_empresa->id_empresa;
                    $contacto->id_compania = 1;
                    $contacto->save();

                    $actLead = Leads::find($request->id_lead);
                    $actLead->estado = 3;
                    $actLead->save();
                }

            $this->registroCompaniaContactoDetalle($request);

            return response()->json([
                'status' => 'success',
                'result' => $leadVl,
                'messages' => null
            ]);

        } else {
            return response()->json([
                'status' => 'error',
                'result' => $validator->messages(),
                'messages' => null
            ]);
        }
    }

    public function registroCompania(Request $request)
    {
        $rules = [
            'id_lead' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $leadVl = Leads::select('id_lead', 'nombre_compania', 'email_compania', 'web_site', 'id_pais', 'id_departamento', 'facebook', 'instagram', 'google_my_business',
                'yelp', 'id_giro_negocio', 'informacion_empresa', 'detalle_servicio', 'zona_servicio', 'horario_servicio', 'id_origen_lead', 'u_responsable',
                'codigo_prospecto', 'id_municipio', 'id_estado', 'id_ciudad', 'estado', 'telefono_compania', 'direccion_compania')->where('id_lead', $request->id_lead)
                ->first();

                if (is_null($leadVl->nombre_compania) || is_null($leadVl->id_giro_negocio) || is_null($leadVl->telefono_compania) || is_null($leadVl->direccion_compania) ||
                    is_null($leadVl->id_pais)) {
                    return response()->json([
                        'status' => 'error',
                        'result' => 'Faltan datos de Compañia',
                        'messages' => null
                    ]);
                } else {
                    $lead = Leads::select('id_lead', 'nombre_compania', 'email_compania', 'web_site', 'id_pais', 'id_departamento', 'facebook', 'instagram', 'google_my_business',
                        'yelp', 'id_giro_negocio', 'informacion_empresa', 'detalle_servicio', 'zona_servicio', 'horario_servicio', 'id_origen_lead', 'u_responsable',
                        'codigo_prospecto', 'id_municipio', 'id_estado', 'id_ciudad', 'estado', 'telefono_compania', 'direccion_compania')->where('id_lead', $request->id_lead)
                        ->first();


                    $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
                    $servicio = new Companias();
                    $servicio->id_lead = $lead['id_lead'];
                    $servicio->nombre_compania = $lead['nombre_compania'];
                    $servicio->id_giro_negocio = $lead['id_giro_negocio'];
                    $servicio->id_contacto = 1;
                    $servicio->id_contactos = 1;
                    $servicio->telefono_compania = $lead['telefono_compania'];
                    $servicio->telefono_adicional = $lead['telefono_adicional'];
                    $servicio->email = $lead['email_compania'];
                    $servicio->direccion_compania = $lead['direccion_compania'];
                    $servicio->codigo_postal = $lead['codigo_postal'];
                    $servicio->web_site = $lead['web_site'];
                    $servicio->ubicacion_compania = $lead['ubicacion_compania'];
                    $servicio->zona_servicio = $lead['zona_servicio'];
                    $servicio->servicio_destacado = $lead['servicio_destacado'];
                    $servicio->horario_servicio = $lead['horario_servicio'];
                    $servicio->u_responsable = $lead['u_responsable'];
                    $servicio->id_empresa = $usuario_empresa->id_empresa;
                    $servicio->estado = 1;
                    $servicio->u_creacion = Auth::user()->name;
                    $servicio->id_pais = $lead['id_pais'];;
                    $servicio->id_departamento = $lead['id_departamento'];
                    $servicio->id_municipio = $lead['id_municipio'];
                    $servicio->id_ciudad = $lead['id_ciudad'];
                    $servicio->id_estado = $lead['id_estado'];
                    $servicio->save();

                    $actLead = Leads::find($request->id_lead);
                    $actLead->estado = 3;
                    $actLead->save();
                }

                $this->registroCompaniaContactoDetalle($request);

            return response()->json([
                'status' => 'success',
                'result' => 'este es el servicio '.$servicio->id_compania,
                'messages' => null
            ]);

        } else {
            return response()->json([
                'status' => 'error',
                'result' => $validator->messages(),
                'messages' => null
            ]);
        }
    }
    public function registroContactoyCompania(Request $request)
    {
        $rules = [
            'id_lead' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $leadVl = Leads::select('*')->where('id_lead', $request->id_lead)->first();

            if (is_null($leadVl->nombre_compania) || is_null($leadVl->id_giro_negocio) || is_null($leadVl->telefono_compania) || is_null($leadVl->direccion_compania) ||
                is_null($leadVl->id_pais) || is_null($leadVl->nombre) || is_null($leadVl->apellido) || is_null($leadVl->telefono_trabajo)) {
                return response()->json([
                    'status' => 'error',
                    'result' => 'Faltan datos de Compañia y Contacto',
                    'messages' => null
                ]);
            } else {

                $lead = Leads::select('*')->where('id_lead', $request->id_lead)
                    ->first();


                $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
                $servicio = new Companias();
                $servicio->id_lead = $lead['id_lead'];
                $servicio->nombre_compania = $lead['nombre_compania'];
                $servicio->id_giro_negocio = $lead['id_giro_negocio'];
                $servicio->id_contacto = $lead['id_contacto'];
                $servicio->id_contactos = $lead['id_contacto'];
                $servicio->telefono_compania = $lead['telefono_compania'];
                $servicio->telefono_adicional = $lead['telefono_adicional'];
                $servicio->email = $lead['email_compania'];
                $servicio->direccion_compania = $lead['direccion_compania'];
                $servicio->codigo_postal = $lead['codigo_postal'];
                $servicio->web_site = $lead['web_site'];
                $servicio->ubicacion_compania = $lead['ubicacion_compania'];
                $servicio->zona_servicio = $lead['zona_servicio'];
                $servicio->servicio_destacado = $lead['servicio_destacado'];
                $servicio->horario_servicio = $lead['horario_servicio'];
                $servicio->u_responsable = $lead['u_responsable'];
                $servicio->id_empresa = $usuario_empresa->id_empresa;
                $servicio->estado = 1;
                $servicio->u_creacion = Auth::user()->name;
                $servicio->id_pais = $lead['id_pais'];;
                $servicio->id_departamento = $lead['id_departamento'];
                $servicio->id_municipio = $lead['id_municipio'];
                $servicio->id_ciudad = $lead['id_ciudad'];
                $servicio->id_estado = $lead['id_estado'];
                $servicio->save();

                $contacto = new Contactos();
                $contacto->id_lead = $lead['id_lead'];
                $contacto->nombre = $lead['nombre'];
                $contacto->apellido = $lead['apellido'];
                $contacto->id_cargo = $lead['id_cargo'];
                $contacto->email = $lead['email_contacto'];
                $contacto->telefono_trabajo = $lead['telefono_trabajo'];
                $contacto->telefono_movil = $lead['telefono_movil'];
                $contacto->id_pais = $lead['id_pais'];
                $contacto->id_departamento = $lead['id_departamento'];
                $contacto->id_municipio = $lead['id_municipio'];
                $contacto->codigo_postal = $lead['codigo_postal'];
                $contacto->u_responsable = $lead['u_responsable'];
                $contacto->codigo_prospecto = $lead['codigo_prospecto'];
                $contacto->id_estado = $lead['id_estado'];
                $contacto->id_ciudad = $lead['id_ciudad'];
                $contacto->direccion = $lead['direccion_compania'];
                $contacto->u_creacion = Auth::user()->name;
                $contacto->estado = 1;
                $contacto->id_empresa = $usuario_empresa->id_empresa;
                $contacto->id_compania = $servicio['id_compania'];
                $contacto->save();

                $updateCompania = Companias::find($servicio['id_compania']);
                $updateCompania->id_contacto = $contacto['id_contacto'];
                $updateCompania->id_contactos = $contacto['id_contacto'];
                $updateCompania->save();

                $actLead = Leads::find($lead['id_lead']);
                $actLead->estado = 3;
                $actLead->save();
            }

            $this->registroCompaniaContactoDetalle($request);

            return response()->json([
                'status' => 'success',
                'result' => $contacto,
                'messages' => null
            ]);

        }else {
            return response()->json([
                'status' => 'error',
                'result' => $validator->messages(),
                'messages' => null
            ]);
        }
    }
    public function registroCompaniaContactoDetalle(Request $request){

        $rules = [
            'id_lead' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            $lead = Leads::select('id_lead', 'id_origen_lead', 'url_origen', 'servicio_necesitado', 'detalle_servicio', 'comentario_lead')->where('id_lead', $request->id_lead)
                ->first();

            $compania = Companias::select('id_compania')->where('id_lead', $request->id_lead)
                ->first();

            $contacto = Contactos::select('id_contacto')->where('id_lead', $request->id_lead)
                ->first();

            $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
            $ccd = new CompaniaContactoDetalle();
            $ccd->id_lead = $request->id_lead;
            if (!$contacto){
            $ccd->id_contacto = 1;
            }else{
                $ccd->id_contacto = $contacto['id_contacto'];
            }
            if (!$compania){
            $ccd->id_compania = 1;
            }else{
                $ccd->id_compania = $compania->id_compania;
            }
            $ccd->id_origen_lead = $lead['id_origen_lead'];
            $ccd->url_origen = $lead['url_origen'];
            $ccd->servicio_necesitado = $lead['servicio_necesitado'];
            $ccd->detalle_servicio = $lead['detalle_servicio'];
            $ccd->comentario_lead = $lead['comentario_lead'];
            $ccd->u_creacion = Auth::user()->name;
            $ccd->id_empresa = $usuario_empresa->id_empresa;
            $ccd->save();


            return response()->json([
                'status' => 'success',
                'result' => null,
                'messages' => null
            ]);

        } else {
            return response()->json([
                'status' => 'error',
                'result' => $validator->messages(),
                'messages' => null
            ]);
        }
    }


}
