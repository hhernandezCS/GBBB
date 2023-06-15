<?php



namespace App\Http\Controllers\Admon;


use App\Models\Admon\Estados;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\Admon\Ciudades;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPJasper\PHPJasper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use function PHPUnit\Framework\containsIdentical;

class CiudadesController    extends Controller
{
    /**
     * Obtener una lista de los origenes de leads
     *
     * @access  public
     * @param Request $request
     * @param Ciudades $ciudades
     * @return JsonResponse
     * @author hgm7
     */

    public function buscarCiudades(Request $request, Ciudades $ciudades){
        $ciudades = $ciudades->buscar($request);
        return response()->json([
            'result' => $ciudades
        ]);
    }

    public function obtener(Request $request, Ciudades $ciudades)
    {
        $ciudades = $ciudades->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $ciudades->total(),
                'rows' => $ciudades->items()
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

    public function obtenerCiudades(Request $request)
    {
        $rules = [
            'id_ciudad' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $ciudades = Ciudades::where('id_ciudad',$request->id_ciudad)
                ->with('estadoCiudades')
                ->get();
            if(!empty($ciudades[0])){
                return response()->json([
                    'status' => 'success',
                    'result' => $ciudades[0],
                    'messages' => null
                ]);
            }
            else{
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_ciudad'=>["Datos no encontrados"]),
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
        $estados = Estados::select(['id_estado','descripcion'])->where('estado',1)->orderby('id_estado')->get();
        return response()->json([
            'status' => 'success',
            'result' => [
                'estados' => $estados,
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
                'max:50',]
        ];

        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $ciudades = new Ciudades();
            $ciudades->descripcion = $request->descripcion;
            $ciudades->id_estado = $request->estado['id_estado'];
            $ciudades->id_empresa = $usuario_empresa->id_empresa;
            $ciudades->estado = 1;
            $ciudades->u_creacion = Auth::user()->name;
            $ciudades->save();

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
            'id_ciudad' => 'required|integer|min:1',
            'descripcion' =>  [
                'required',
                'string',
                'max:50',
                Rule::unique('pgsql.public.ciudades')->ignore($request->id_ciudad,'id_ciudad')],
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $ciudades = Ciudades::find($request->id_ciudad);
            $ciudades->descripcion = $request->descripcion;
            $ciudades->id_estado = $request->estado_ciudades['id_estado'];
            $ciudades->u_modificacion = Auth::user()->name;
            $ciudades->save();

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
            'id_ciudad' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $ciudades = Ciudades::find($request->id_ciudad);
            $ciudades->estado = 0;
            $ciudades->u_modificacion = Auth::user()->name;
            $ciudades->save();

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
            'id_ciudad' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $ciudades = Ciudades::find($request->id_ciudad);
            $ciudades->estado = 1;
            $ciudades->u_modificacion = Auth::user()->name;
            $ciudades->save();

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
