<?php



namespace App\Http\Controllers\CRM;


use App\Models\Admon\Ajustes;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\CRM\ClasificacionClientes;
use Illuminate\Http\JsonResponse;
use phpDocumentor\Reflection\Types\Object_;
use PHPJasper\PHPJasper;
use Validator,Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule,DB;
use App\Http\Controllers\Controller;
use function Symfony\Component\Translation\t;

class ClasificacionClientesController extends Controller
{
    /**
     * Obtener una lista de servicios
     * @access  public
     * @param Request $request
     * @param ClasificacionClientes $clasificacion_clientes
     * @return JsonResponse
     * @author rsequeira
     */

    public function obtener(Request $request, ClasificacionClientes $clasificacion_clientes)
    {
        $clasificacion_clientes = $clasificacion_clientes->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $clasificacion_clientes->total(),
                'rows' => $clasificacion_clientes->items()
            ],
            'messages' => null
        ]);
    }


    /**
     * obtener servicio
     * @param Request $request
     * @return JsonResponse
     * @author rsequeira
     * @access  public
     */

    public function obtenerClasificacionCliente(Request $request)
    {
        $rules = [
            'id_clasificacion_cliente' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $clasificacion_cliente = ClasificacionClientes::where('id_clasificacion_cliente',$request->id_clasificacion_cliente)->first();
            if(!empty($clasificacion_cliente)){
                return response()->json([
                    'status' => 'success',
                    'result' => $clasificacion_cliente,
                    'messages' => null
                ]);
            }
            else{
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_clasificacion_cliente'=>["Datos no encontrados"]),
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

    public function nuevo(){
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
        $companies = Companias::select('id_compania','nombre_compania')
            ->where('estado',1)
            ->where('id_empresa', $usuario_empresa->id_empresa)
            ->where('u_responsable', Auth::user()->name)
            ->get();
        return response()->json([
            'status' => 'success',
            'result' => [
                'companies' => $companies
            ],
            'messages' => 'null'
        ]);
    }

    /**
     * Crear un nuevo servicio
     * @author rsequeira
     * @access  public
     * @param Request $request
     * @return JsonResponse
     */

    public function registrar(Request $request)
    {
        $rules = [
            'descripcion' =>  [
                'required',
                'string',
                'max:50'],
        ];

        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $clasificacion_clientes = new ClasificacionClientes();
            $clasificacion_clientes->descripcion = $request->descripcion;
            $clasificacion_clientes->id_empresa = $usuario_empresa->id_empresa;
            $clasificacion_clientes->estado = 1;
            $clasificacion_clientes->u_creacion = Auth::user()->name;
            $clasificacion_clientes->save();

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
     * Actualizar Tipo contacto
     * @author rsequeira
     * @access  public
     * @param Request $request
     * @return JsonResponse
     */

    public function actualizar(Request $request)
    {
        $rules = [
            'id_clasificacion_cliente' => 'required|integer|min:1',
            'descripcion' =>  [
                'required',
                'string',
                'max:50',
                Rule::unique('pgsql.crm.clasificacion_clientes')->ignore($request->id_clasificacion_cliente,'id_clasificacion_cliente')],
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $clasificacion_cliente = ClasificacionClientes::find($request->id_clasificacion_cliente);
            $clasificacion_cliente->descripcion = $request->descripcion;
            $clasificacion_cliente->u_modificacion = Auth::user()->name;
            $clasificacion_cliente->save();

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
     */

    public function desactivar(Request $request)
    {
        $rules = [
            'id_clasificacion_cliente' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $clasificacion_cliente = ClasificacionClientes::find($request->id_clasificacion_cliente);
            $clasificacion_cliente->estado = 0;
            $clasificacion_cliente->u_modificacion = Auth::user()->name;
            $clasificacion_cliente->save();

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
     * @param Request $request
     * @return JsonResponse
     */

    public function activar(Request $request)
    {
        $rules = [
            'id_clasificacion_cliente' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $tipo_contacto = ClasificacionClientes::find($request->id_clasificacion_cliente);
            $tipo_contacto->estado = 1;
            $tipo_contacto->u_modificacion = Auth::user()->name;
            $tipo_contacto->save();

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
