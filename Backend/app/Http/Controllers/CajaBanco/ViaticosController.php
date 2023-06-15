<?php



namespace App\Http\Controllers\CajaBanco;

use App\Models\Admon\Departamentos;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\CajaBanco\Normativas;
use App\Models\CajaBanco\Viaticos;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPJasper\PHPJasper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function PHPUnit\Framework\assertDoesNotMatchRegularExpression;

class ViaticosController extends Controller
{
    /**
     * Obtener una lista de bancos
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function obtenerViaticos(Request $request, Viaticos $viaticos)
    {
        $viaticos = $viaticos->obtenerViaticos($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $viaticos->total(),
                'rows' => $viaticos->items()
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

    public function obtenerViatico(Request $request)
    {
        $rules = [
            'id_viatico' => 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $viatico = Viaticos::find($request->id_viatico);

            if(!empty($viatico)){
                return response()->json([
                    'status' => 'success',
                    'result' => $viatico,
                    'messages' => null
                ]);

            }
            else{
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_viatico'=>["Datos no encontrados"]),
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
            'descripcion' => 'required|string|max:100|',Rule::unique('pgsql.cjabnco.viaticos'),
            'normativa' => 'required|array|min:1',
            'normativa.id_normativa' => 'required|integer|min:1',
            'monto' => 'numeric|min:1',
        ];
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $viatico = new Viaticos();
            $viatico->descripcion = $request->descripcion;
            $viatico->id_normativa = $request->normativa['id_normativa'];
            $viatico->monto = $request->monto;
            $viatico->estado = 1;
            $viatico->u_creacion = Auth::user()->name;
            $viatico->id_empresa = $usuario_empresa->id_empresa;
            $viatico->save();

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
            'id_viatico' => 'required|integer|min:1',
            'descripcion' => 'required|string|max:100|',Rule::unique('pgsql.cjabnco.viaticos'),
            'normativa' => 'required|array|min:1',
            'normativa.id_normativa' => 'required|integer|min:1',
            'monto' => 'numeric|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $viatico = Viaticos::find($request->id_viatico);
            $viatico->descripcion = $request->descripcion;
            $viatico->id_normativa = $request->normativa['id_normativa'];
            $viatico->monto = $request->monto;
            $viatico->u_modificacion = Auth::user()->name;
            $viatico->save();

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
            'id_viatico' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $viatico = Viaticos::find($request->id_viatico);
            $viatico->estado = 0;
            $viatico->u_modificacion = Auth::user()->name;
            $viatico->save();

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
            'id_viatico' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $viatico = Viaticos::find($request->id_viatico);
            $viatico->estado = 1;
            $viatico->u_modificacion = Auth::user()->name;
            $viatico->save();

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
