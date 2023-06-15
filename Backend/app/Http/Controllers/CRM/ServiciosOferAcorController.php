<?php



namespace App\Http\Controllers\CRM;


use App\Models\Admon\Ajustes;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\CRM\ServiciosOferAcor;
use Illuminate\Http\JsonResponse;
use phpDocumentor\Reflection\Types\Object_;
use PHPJasper\PHPJasper;
use Validator,Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule,DB;
use App\Http\Controllers\Controller;
class ServiciosOferAcorController    extends Controller
{
    /**
     * Obtener una lista de servicios
     * @access  public
     * @param Request $request
     * @param ServiciosOferAcor $servicios
     * @return JsonResponse
     * @author rsequeira
     */

    public function obtener(Request $request, ServiciosOferAcor $servicios)
    {
        $servicios = $servicios->obtenerServiciosOfrAcor($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $servicios->total(),
                'rows' => $servicios->items()
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

    public function obtenerServio_ofer_acor(Request $request)
    {
        $rules = [
            'id_servicio_ofr_acor' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $servicio = ServiciosOferAcor::where('id_servicio_ofr_acor',$request->id_servicio_ofr_acor)
                ->get();
            if(!empty($servicio[0])){
                return response()->json([
                    'status' => 'success',
                    'result' => $servicio[0],
                    'messages' => null
                ]);
            }
            else{
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_servicio_ofr_acor'=>["Datos no encontrados"]),
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
                'max:50',
                Rule::unique('pgsql.crm.servicios_ofr_acor')],
        ];

        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $servicio = new ServiciosOferAcor();
            $servicio->descripcion = $request->descripcion;
            $servicio->id_empresa = $usuario_empresa->id_empresa;
            $servicio->estado = 1;
            $servicio->u_creacion = Auth::user()->name;
            $servicio->save();

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
     * Actualizar servicios
     * @author rsequeira
     * @access  public
     * @param Request $request
     * @return JsonResponse
     */

    public function actualizar(Request $request)
    {
        $rules = [
            'id_servicio_ofr_acor' => 'required|integer|min:1',
            'descripcion' =>  [
                'required',
                'string',
                'max:50',
                Rule::unique('pgsql.crm.servicios_ofr_acor')->ignore($request->if_servicio_ofr_acor,'if_servicio_ofr_acor')],
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $servicios = ServiciosOferAcor::find($request->id_servicio_ofr_acor);
            $servicios->descripcion = $request->descripcion;
            $servicios->u_modificacion = Auth::user()->name;
            $servicios->save();

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
            'id_servicio_ofr_acor' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $servicio = ServiciosOferAcor::find($request->id_servicio_ofr_acor);
            $servicio->estado = 0;
            $servicio->u_modificacion = Auth::user()->name;
            $servicio->save();

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
            'id_servicio_ofr_acor' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $servicio = ServiciosOferAcor::find($request->id_servicio_ofr_acor);
            $servicio->estado = 1;
            $servicio->u_modificacion = Auth::user()->name;
            $servicio->save();

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
