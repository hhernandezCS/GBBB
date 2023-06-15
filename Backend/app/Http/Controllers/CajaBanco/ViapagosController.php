<?php



namespace App\Http\Controllers\CajaBanco;

use App\Models\Admon\Departamentos;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\CajaBanco\Normativas;
use App\Models\CajaBanco\ViaPagos;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPJasper\PHPJasper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function PHPUnit\Framework\assertDoesNotMatchRegularExpression;

class ViapagosController extends Controller
{
    /**
     * Obtener una lista de bancos
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function obtenerViaPagos(Request $request, ViaPagos $viapagos)
    {
        $viapagos = $viapagos->obtenerViaPagos($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $viapagos->total(),
                'rows' => $viapagos->items()
            ],
            'messages' => null
        ]);
    }


    /**
     * obtener tipo de Salida Especifico
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function obtenerViaPago(Request $request)
    {
        $rules = [
            'id_via_pago' => 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $viapago = ViaPagos::find($request->id_via_pago);

            if(!empty($viapago)){
                return response()->json([
                    'status' => 'success',
                    'result' => $viapago,
                    'messages' => null
                ]);

            }
            else{
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_via_pago'=>["Datos no encontrados"]),
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
     * @copyright rsequeira
     */
    public function nuevo(Request $request){
        $noramtivas = Normativas::select('id_normativa','concepto')->get();

        return response()->json([
        'status' => 'success',
        'result' => [
            'Normativas' => $noramtivas
        ],
        'messagess' => null
        ]);
    }

    /**
     * Crear un nuevo tipo de Salida
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function registrar(Request $request)
    {
        $rules = [
            'descripcion' => 'required|string|max:100|',Rule::unique('pgsql.public.via_pago'),
        ];
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $viapago = new ViaPagos();
            $viapago->descripcion = $request->descripcion;
            $viapago->estado = 1;
            $viapago->u_creacion = Auth::user()->name;
            $viapago->id_empresa = $usuario_empresa->id_empresa;
            $viapago->save();

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
     * Actualizar Rol existente
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function actualizar(Request $request)
    {
        $rules = [
            'id_via_pago' => 'required|integer|min:1',
            'descripcion' => 'required|string|max:100|',Rule::unique('pgsql.public.via_pago'),
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $viapago = ViaPagos::find($request->id_via_pago);
            $viapago->descripcion = $request->descripcion;
            $viapago->u_modificacion = Auth::user()->name;
            $viapago->save();

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
     * Desactiva rol
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function desactivar(Request $request)
    {
        $rules = [
            'id_via_pago' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $viapago = ViaPagos::find($request->id_viatico);
            $viapago->estado = 0;
            $viapago->u_modificacion = Auth::user()->name;
            $viapago->save();

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


    public function activar(Request $request)
    {
        $rules = [
            'id_via_pago' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $viapago = ViaPagos::find($request->id_viatico);
            $viapago->estado = 1;
            $viapago->u_modificacion = Auth::user()->name;
            $viapago->save();

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
        // $ext = 'pdf';
        $os = array("xls", "pdf");
        if (in_array($ext, $os)) {
            $hora_actual = time() ;
            //$input = 'C:/xampp/htdocs/resources/reports/ReporteBancos';
            //$output = 'C:/xampp/htdocs/resources/reports/' .$hora_actual . 'ReporteBancos';
            $input = '/var/www/html/resources/reports/ReporteBancos';
            $output = '/var/www/html/resources/reports/'.$hora_actual.'ReporteBancos';

            $options = [
                'format' => [$ext],
                'locale' => 'en',
                'params' => [],
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
            header('Content-Type: multipart/form-data;boundary="boundary"');
            header('Content-Disposition: form-data; filename=' . $hora_actual. 'Accesos.' . $ext);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Content-Length: ' . filesize($output . '.' . $ext));
            flush();
            readfile($output . '.' . $ext);*/
            // unlink($output . '.' . $ext);

            $headers = [
                'Content-Type' => 'application/pdf',
            ];

            return response()->download($output . '.' . $ext ,$hora_actual. 'ReporteBancos.' . $ext, $headers);

            /*exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
               print_r($output);*/
        }
    }
}
