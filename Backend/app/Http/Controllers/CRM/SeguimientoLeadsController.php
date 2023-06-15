<?php



namespace App\Http\Controllers\CRM;

use App\Models\Admon\UsuariosEmpresas;
use App\Models\CRM\ClasificacionLlamadas;
use App\Models\CRM\Leads;
use App\Models\CRM\SeguimientosLeads;
use App\Models\CRM\OrigenesLeads;
use App\Models\CRM\ServiciosOferAcor;
use App\Models\Ventas\Vendedores;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;
use PHPJasper\PHPJasper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use function Psr\Log\error;

class SeguimientoLeadsController    extends Controller
{
    /**
     * Obtener una lista de los origenes de leads
     *
     * @access  public
     * @param Request $request
     * @param SeguimientosLeads $seguimientosleads
     * @return JsonResponse
     * @author rsequeira
     * @copyright rsequeira
     */

    public function obtener(Request $request, SeguimientosLeads $seguimientosleads)
    {
        $seguimientosleads = $seguimientosleads->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $seguimientosleads->total(),
                'rows' => $seguimientosleads->items()
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

    public function obtenerSeguimientoLead(Request $request)
    {
        $rules = [
            'id_lead' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $seguimiento = SeguimientosLeads::where('id_lead',$request->id_lead)
                ->with('seguimientoClasificacionLlamada', 'seguimientoServicios','responsableProspecto')
                ->where('estado',1)
                ->orderBy('id_seguimiento_lead','desc')
                ->get();
            if(!empty($seguimiento)){
                return response()->json([
                    'status' => 'success',
                    'result' => [
                        'seguimiento' => $seguimiento,
                        ],
                    'messages' => null
                ]);
            }
            else{
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_seguimiento'=>["Datos no encontrados"]),
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

    public function obtenerSeguimentoEspecifico(Request $request){
        $rules= [
            'id_seguimiento_lead' => 'required|integer|min:1'
        ];
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()){
            $seguimientoLead = SeguimientosLeads::where('id_seguimiento_lead',$request->id_seguimiento_lead)->where('estado',1)->with('seguimientoClasificacionLlamada', 'seguimientoServicios','responsableProspecto')->first();
            $str_servicios = SeguimientosLeads::select('servicios_ofertados')->where('id_seguimiento_lead', $request->id_seguimiento_lead)->first();
            $map = [];
            foreach ($str_servicios->toArray() as $arr1){
                $map= $arr1;
            }
            $arr_servicios_ofertados = array_map('intval',explode(",",(string)$map));
            $serviciosOfertado=ServiciosOferAcor::select('id_servicio_ofr_acor','descripcion')->whereIn('id_servicio_ofr_acor',$arr_servicios_ofertados)->get();
            if (!empty($seguimientoLead)){
                return response()->json([
                   'status'  => 'success',
                   'result' => [
                       'seguimientoLead' => $seguimientoLead,
                       'serviciosOfertados' => $serviciosOfertado
                   ] ,
                    'messages' => null
                ]);
            }else{
                return response()->json([
                   'status' => 'error',
                   'result' => array('id_seguimiento_lead'=>["Datos no encontrados"]),
                   'messages' => null
                ]);
            }
        }else{
            return response()->json([
                'status' => 'error',
                'result' => $validator->messages(),
                'messages' => null
            ]);
        }
    }

    public function nuevo(Request $request){
        $vendedores = Vendedores::select('id_vendedor', 'nombre_completo')->get();
        $clasificacion_llamada = ClasificacionLlamadas::select('id_clasificacion_llamada', 'descripcion')->get();
        return response()->json([
           'status' => 'success',
           'result' => [
               'vendedores' => $vendedores,
               'clasificacion_llamada' => $clasificacion_llamada,
           ],
            'messages' => null
        ]);
}

    /**
     * Crear un nuevo origen de lead
     * @author rsequeira
     * @copyright rsequeira
     * @access  public
     * @param Request $request
     * @return JsonResponse
     */

    public function registrar(Request $request)
    {
        $messages = [
            /*'estado.*.id_estado' => 'Debe seleccionar un estado' ,
            'ciudad.*.id_ciudad' => 'Debe seleccionar una ciudad' ,
            'nombre_compañia' => 'Esta compañia ya ha sido registrada',
            'telefono_compañia' => 'Digite un número de teléfono'*/
        ];
        $rules = [
            /*'seguimiento_lead.*.' => ['required', 'integer','min:1'] ,
            'seguimiento_lead.*.' => ['required', 'integer','min:1'] ,
            'seguimiento_lead.*.' =>  ['required','integer','min:1'],
            'seguimiento_lead.*.' => ['required', 'array','min:1'],
            'seguimiento_lead.*.' => ['required', 'integer', 'min:1'],
            'seguimiento_lead.*.' => ['required', 'integer', 'min: 1'],
            'seguimiento_lead.*.' => ['required', 'array', 'min:1'],
            'seguimiento_lead.*.'*/
        ];

        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
        $validator = Validator::make($request->all(), $rules, $messages);
        if (!$validator->fails()) {
             try {
                 DB::beginTransaction();

                 foreach ($request->seguimientoDetalle as $seguimiento_lead){
                     $seguimiento = new SeguimientosLeads();
                     $seguimiento->estado_seguimiento = $seguimiento_lead['estado_seguimiento'];
                     $seguimiento->tipo_contacto = $seguimiento_lead['tipo_contacto'];
                     $seguimiento->valoracion_contacto = $seguimiento_lead['valoracion_contacto'];
                     $seguimiento->idioma_llamada = $seguimiento_lead['idioma_contacto'];
                     $seguimiento->estado_comunicacion = $seguimiento_lead['estado_comunicacion'];
                     $seguimiento->id_clasificacion_llamada = $seguimiento_lead['clasificacion_llamada']['id_clasificacion_llamada'];
                     $seguimiento->u_responsable = $seguimiento_lead['responsable_prospecto']['name'];
                     $seguimiento->comentario_seguimiento = $seguimiento_lead['comentario_comunicacion'];
                     $seguimiento->f_actividad = $seguimiento_lead['proximo_contacto'];
                     $seguimiento->asunto_actividad = $seguimiento_lead['asunto_actividad'];
                     $seguimiento->descripcion_actividad = $seguimiento_lead['descripcion_actividad'];
                     $seguimiento->id_lead = $seguimiento_lead['id_lead'];
                     $seguimiento->servicios_ofertados = $seguimiento_lead['servicios_ofertados'];
                     $seguimiento->descripcion_servicios = $seguimiento_lead['descripcion_servicios'];
                     $seguimiento->id_vendedor = $seguimiento_lead['responsable_prospecto']['id'];
                     $seguimiento->u_creacion = Auth::user()->name;
                     $seguimiento->id_empresa = $usuario_empresa->id_empresa;
                     $seguimiento->save();
                     $lead = Leads::find($seguimiento_lead['id_lead']);
                     $lead->u_responsable = $seguimiento_lead['responsable_prospecto']['name'];
                     $lead->estado = 2;
                     $lead->f_modificacion = date("Y-m-d H:i:s");
                     $lead->save();
                     $new_seguimiento = SeguimientosLeads::where('id_seguimiento_lead',$seguimiento->id_seguimiento_lead)->first();
                 }

                 DB::commit();
                 return response()->json([
                    'status' => 'success',
                    'result' => [
                        'new_seguimiento' => $new_seguimiento,
                    ],
                    'messages' => null
                 ]);
             }catch (Exception $e) {
                 DB::rollBack();
                 return response()->json([
                     'status' => 'error',
                     'result' => 'Error',
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
     * Actualizar estado seguimiento
     * @author rsequeira
     * @copyright rsequeira
     * @access  public
     * @param Request $request
     * @return JsonResponse
     */

    public function actualizar(Request $request)
    {
        $rules = [
            'id_seguimiento_lead' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            try {
                DB::beginTransaction();
                $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
                $seguimiento = SeguimientosLeads::find($request->id_seguimiento_lead);
                $seguimiento->estado_seguimiento = $request->estado_seguimiento;
                $seguimiento->tipo_contacto = $request->tipo_contacto;
                $seguimiento->valoracion_contacto = $request->valoracion_contacto;
                $seguimiento->idioma_llamada = $request->idioma_llamada;
                $seguimiento->estado_comunicacion = $request->estado_comunicacion;
                $seguimiento->id_clasificacion_llamada = $request->seguimiento_clasificacion_llamada['id_clasificacion_llamada'];
                $seguimiento->u_responsable = $request->responsable_prospecto['name'];
                $seguimiento->comentario_seguimiento = $request->comentario_seguimiento;
                $seguimiento->f_actividad = $request->f_actividad;
                $seguimiento->asunto_actividad = $request->asunto_actividad;
                $seguimiento->descripcion_actividad = $request->descripcion_actividad;
                $seguimiento->id_lead = $request->id_lead;
                $seguimiento->servicios_ofertados = $request->id_servicios_ofertados;
                $seguimiento->descripcion_servicios = $request->descripcion_servicios;
                $seguimiento->id_vendedor = $request->responsable_prospecto['id'];
                $seguimiento->u_creacion = Auth::user()->name;
                $seguimiento->id_empresa = $usuario_empresa->id_empresa;
                $seguimiento->save();



                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'result' => null,
                    'messages' => null
                ]);
            }catch (Exception $e){
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'result' => 'Error',
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
     * @param Request $request
     * @return JsonResponse
     * @author rsequeira
     * @copyright rsequiera
     */
    public function registrarSeguimiento(Request $request){
        $messages = [
            'descripcion_led'=> 'El tema de prospectación es requerido',
            'estado.*.id_estado' => 'Debe seleccionar un estado' ,
            'ciudad.*.id_ciudad' => 'Debe seleccionar una ciudad' ,
            'nombre_compañia' => 'Esta compañia ya ha sido registrada',
        ];
        $rules = [
            'descripcion_lead' => ['required','string', 'min:10'],
            'estados.*.id_estado' => ['required', 'array','min:1'] ,
            'cuidades.*.id_ciudad' => ['required', 'array','min:1'] ,
            'nombre_compañia' =>  ['required','string','max:50',Rule::unique('pgsql.crm.leads')],
            'telefono_trabajo' => ['required', 'string','max:14']
        ];

        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
        $validator = Validator::make($request->all(), $rules, $messages);
        if (!$validator->fails()) {
            $lead = new Leads();
            $lead->descripcion_lead = $request->descripcion_lead;
            $lead->nombre = $request->nombre;
            $lead->apellido = $request->apellido;
            $lead->telefono_trabajo = $request->telefono_trabajo;
            $lead->nombre_compañia = $request->nombre_compañia;
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
     * Desactivar
     * @author rsequeira
     * @param Request $request
     * @return JsonResponse
     * @access  public
     * @copyright rsequeira
     */

    public function desactivar(Request $request)
    {
        $rules = [
            'id_seguimiento_lead' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $lead = SeguimientosLeads::find($request->id_seguimiento_lead);
            $lead->estado = 0;
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
     * @author rsequeira
     * @copyright rsequeira
     * @param Request $request
     * @return JsonResponse
     */

    public function activar(Request $request)
    {
        $rules = [
            'id_origen_lead' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $origen_lead = OrigenesLeads::find($request->id_origen_lead);
            $origen_lead->estado = 1;
            $origen_lead->u_modificacion = Auth::user()->name;
            $origen_lead->save();

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

    public function generarReporte($ext)
    {
        // echo $ext;

        $os = array("xls", "pdf");
        //echo gethostname();
        if (in_array($ext, $os)) {
            /*$input = 'C:/xampp7/htdocs/resources/reports/Blank_A4.jrxml';
              echo $input;
              $jasper = new PHPJasper;
              $jasper->compile($input)->execute();
            */
            $hora_actual = time() ;
            $input = env('APP_URL_REPORTES') . 'TipoSalidas';
            $output = env('APP_URL_REPORTES') . $hora_actual . 'TipoSalidas';

            // Rutas de descarga de Reportes en servidor
//            $input = env('APP_URL_REPORTES') . 'TipoSalidas';
//            $output = env('APP_URL_REPORTES') . $hora_actual .  'TipoSalidas';

            if($ext == 'xls'){
                $input = $input.'XLS.jasper';
            }

            //Ajustes generales del sistema
            $logo_empresa = Ajustes::where('id_ajuste', 3)->select('valor')->first();
            $nombre_empresa = Ajustes::where('id_ajuste', 4)->select('valor')->first();

            $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
            $options = [
                'format' => [$ext],
                'locale' => 'es',
                'params' => [
                    'logo_empresa' => env('APP_URL_IMAGES').$logo_empresa->valor,
                    'nombre_empresa' => $nombre_empresa->valor,
                    'id_empresa' => $usuario_empresa->id_empresa,
                ],
                'db_connection' => [
                    'driver' => 'postgres',
                    'username' => env('DB_USERNAME'),
                    'password' => env('DB_PASSWORD'),
                    'host' => env('DB_HOST'),
                    'database' => env('DB_DATABASE'),
                    'port' => env('DB_PORT')
                ]
            ];

            $jasper = new PHPJasper;

            $jasper->process($input, $output, $options)->execute();
            /*header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $hora_actual. 'CuentasContables.' . $ext);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Content-Length: ' . filesize($output . '.' . $ext));
            flush();
            readfile($output . '.' . $ext);
            unlink($output . '.' . $ext);*/

            /*print_r( env('APP_URL_REPORTS').$logo_empresa->valor);*/
            $headers = [
                'Content-Type' => 'application/pdf',
            ];

            return response()->download($output . '.' . $ext ,$hora_actual. 'TipoSalidas.' . $ext, $headers);

            /* exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
                print_r($output);*/
        }
    }
}
