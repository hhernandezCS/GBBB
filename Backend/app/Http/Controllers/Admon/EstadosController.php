<?php



namespace App\Http\Controllers\Admon;


use App\Models\Admon\Paises;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\Admon\Estados;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPJasper\PHPJasper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class EstadosController    extends Controller
{
    /**
     * Obtener una lista de los origenes de leads
     *
     * @access  public
     * @param Request $request
     * @param Estados $estados
     * @return JsonResponse
     * @author hgm7
     */

    public function buscarEstados(Request $request, Estados $estados){
        $estados = $estados->buscar($request);
        return response()->json([
            'result' => $estados
        ]);
    }

    public function obtener(Request $request, Estados $estados)
    {
        $estados = $estados->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $estados->total(),
                'rows' => $estados->items()
            ],
            'messages' => null
        ]);
    }


    /**
     * obtener estado de seguimiento
     * @param Request $request
     * @return JsonResponse
     * @author hgm7
     * @copyright hgm7
     * @access  public
     */

    public function obtenerEstado(Request $request)
    {
        $rules = [
            'id_estado' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $estados = Estados::where('id_estado',$request->id_estado)
                ->with('estadosPais')
                ->get();
            if(!empty($estados[0])){
                return response()->json([
                    'status' => 'success',
                    'result' => $estados[0],
                    'messages' => null
                ]);
            }
            else{
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_estado'=>["Datos no encontrados"]),
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

    public function nuevo(Request $request){
        $paises = Paises::select(['id_pais','descripcion'])->where('estado',1)->get();
        return response()->json([
            'status' => 'success',
            'result' => [
                'paises' => $paises,
            ],
            'messages' => null
        ]);
    }

    /**
     * Crear un nuevo origen de lead
     * @author hgm7
     * @copyright hgm7
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
                Rule::unique('pgsql.public.estados')],
        ];

        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $estados = new Estados();
            $estados->descripcion = $request->descripcion;
            $estados->id_pais = $request->pais['id_pais'];
            $estados->id_empresa = $usuario_empresa->id_empresa;
            $estados->estado = 1;
            $estados->u_creacion = Auth::user()->name;
            $estados->save();

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
     * Actualizar estado seguimiento
     * @author hgm7
     * @copyright hgm7
     * @access  public
     * @param Request $request
     * @return JsonResponse
     */

    public function actualizar(Request $request)
    {
        $rules = [
            'id_estado' => 'required|integer|min:1',
            'descripcion' =>  [
                'required',
                'string',
                'max:50',
                Rule::unique('pgsql.public.estados')->ignore($request->id_estado,'id_estado')],
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $estados = Estados::find($request->id_estado);
            $estados->descripcion = $request->descripcion;
            $estados->id_pais = $request->estados_pais['id_pais'];
            $estados->u_modificacion = Auth::user()->name;
            $estados->save();

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
     * @author hgm7
     * @copyright hgm7
     * @param Request $request
     * @return JsonResponse
     * @access  public
     */

    public function desactivar(Request $request)
    {
        $rules = [
            'id_estado' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $estados = Estados::find($request->id_estado);
            $estados->estado = 0;
            $estados->u_modificacion = Auth::user()->name;
            $estados->save();

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
     * @author hgm7
     * @copyright hgm7
     * @param Request $request
     * @return JsonResponse
     */

    public function activar(Request $request)
    {
        $rules = [
            'id_estado' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $estados = Estados::find($request->id_estado);
            $estados->estado = 1;
            $estados->u_modificacion = Auth::user()->name;
            $estados->save();

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
