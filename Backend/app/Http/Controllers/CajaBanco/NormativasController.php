<?php



namespace App\Http\Controllers\CajaBanco;

use App\Models\Admon\Departamentos;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\CajaBanco\Normativas;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPJasper\PHPJasper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function PHPUnit\Framework\assertDoesNotMatchRegularExpression;

class NormativasController extends Controller
{
    /**
     * Obtener una lista de bancos
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function obtenerNormativas(Request $request, Normativas $normativas)
    {
        $normativas = $normativas->obtenerNormativas($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $normativas->total(),
                'rows' => $normativas->items()
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

    public function obtenerNormativa(Request $request)
    {
        $rules = [
            'id_normativa' => 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $normativa = Normativas::find($request->id_normativa);

            if(!empty($normativa)){
                return response()->json([
                    'status' => 'success',
                    'result' => $normativa,
                    'messages' => null
                ]);

            }
            else{
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_normativa'=>["Datos no encontrados"]),
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
     * Crear un nuevo tipo de Salida
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function registrar(Request $request)
    {
        $rules = [
            'concepto' => ['required','string','max:50',Rule::unique('pgsql.cjabnco.Normativas')],
            'monto_minimo' => ['numeric', 'min:1'],
            'monto_maximo' => ['numeric', 'min:1'],
            'tiempo' => ['required','string','max:100']
        ];
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $normativa = new Normativas();
            $normativa->concepto = $request->concepto;
            $normativa->monto_minimo = $request->monto_minimo;
            $normativa->monto_maximo = $request->monto_maximo;
            $normativa->tiempo = $request->tiempo;
            $normativa->estado = 1;
            $normativa->u_creacion = Auth::user()->name;
            $normativa->id_empresa = $usuario_empresa->id_empresa;
            $normativa->save();

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
            'id_normativa' => 'required|integer|min:1',
            'concepto' => ['required','string','max:50',Rule::unique('pgsql.cjabnco.Normativas')],
            'monto_minimo' => ['numeric', 'min:1'],
            'monto_maximo' => ['numeric', 'min:1'],
            'tiempo' => ['required','string','max:100']
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $normativa = Normativas::find($request->id_normativa);
            $normativa->concepto = $request->concepto;
            $normativa->monto_minimo = $request->monto_minimo;
            $normativa->monto_maximo = $request->monto_maximo;
            $normativa->tiempo = $request->tiempo;
            $normativa->u_modificacion = Auth::user()->name;
            $normativa->save();

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
            'id_normativa' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $normativas = Normativas::find($request->id_normativa);
            $normativas->estado = 0;
            $normativas->u_modificacion = Auth::user()->name;
            $normativas->save();

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
            'id_normativa' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $normativa = Normativas::find($request->id_normativa);
            $normativa->estado = 1;
            $normativa->u_modificacion = Auth::user()->name;
            $normativa->save();

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
