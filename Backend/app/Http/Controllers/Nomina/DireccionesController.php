<?php

namespace App\Http\Controllers\Nomina;

use App\Models\Admon\Sucursales;
use Illuminate\Routing\Controller;
use PHPJasper\PHPJasper;
use Illuminate\Validation\Validator, Illuminate\Support\Facades\Auth;
use App\Models\Nomina\Direcciones;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class DireccionesController extends Controller
{
    /**
     * Obtener una lista de bancos
     *
     * @access  public
     * @param
     * @return  \Illuminate\Http\JsonResponse(array)
     */

    public function obtenerDirecciones(Request $request, Direcciones $direcciones)
    {
        $direcciones = $direcciones->obtenerDirecciones($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $direcciones->total(),
                'rows' => $direcciones->items()
            ],
            'messages' => null
        ]);
    }

    /**
     * Obtener una lista de Direcciones sin ningun filtro
     *
     * @access  public
     * @param
     * @return  \Illuminate\Http\JsonResponse(array)
     */

    public function obtenerTodasDirecciones(Request $request, Direcciones $direcciones)
    {
        $direcciones = Direcciones::where('estado', 1)->get();
        return response()->json([
            'status' => 'success',
            'result' => $direcciones,
            'messages' => null
        ]);
    }

    /**
     * obtener Direccion Especifica
     *
     * @access  public
     * @param
     * @return  \Illuminate\Http\JsonResponse(array)
     */

    public function obtenerDireccion(Request $request)
    {
        $rules = [
            'id_direccion' => 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $direccion = Direcciones::where('id_direccion',$request->id_direccion)->with('direccionSucursal')->first();
            $sucursal = Sucursales::select('id_sucursal','descripcion','serie')->get();

            if(!empty($direccion)){
                return response()->json([
                    'status' => 'success',
                    'result' => [
                        'direccion' =>   $direccion,
                        'sucursal' => $sucursal
                    ],
                    'messages' => null
                ]);

            }
            else{
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_direccion'=>["Datos no encontrados"]),
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
     * Crear un nueva direccion
     *
     * @access  public
     * @param
     * @return  \Illuminate\Http\JsonResponse(string)
     */

    public function registrar(Request $request)
    {
        $rules = [
            'codigo' => [
                'required',
                'string',
                'max:5',
                Rule::unique('pgsql.public.direcciones')/*->ignore($request->id_direccion,'id_direccion')*/
            ],
            'descripcion' => [
                'required',
                'string',
                'max:50',
                Rule::unique('pgsql.public.direcciones')/*->ignore($request->id_direccion,'id_direccion')*/
            ],
            'sucursal' => 'required|array|min:1',
            'sucursal.id_sucursal' => 'required|integer|min:1',
            'clasificacion_direccion' => 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $direccion = new Direcciones;
            $direccion->descripcion = $request->descripcion;
            $direccion->codigo = $request->codigo;
            $direccion->clasificacion_direccion = $request->clasificacion_direccion;
            $direccion->estado = 1;
            $direccion->id_sucursal = $request->sucursal['id_sucursal'];
            $direccion->u_creacion = Auth::user()->usuario;
            $direccion->save();


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
     * Actualizar Direccion existente
     *
     * @access  public
     * @param
     * @return  \Illuminate\Http\JsonResponse(string)
     */

    public function actualizar(Request $request)
    {
        $rules = [
            'id_direccion' =>  'required|integer|min:1',
            'codigo' => [
                'required',
                'string',
                'max:5',
                Rule::unique('pgsql.public.direcciones')->ignore($request->id_direccion,'id_direccion')
            ],
            'descripcion' => [
                'required',
                'string',
                'max:50',
                Rule::unique('pgsql.public.direcciones')->ignore($request->id_direccion,'id_direccion')
            ],
            'clasificacion_direccion' => 'required|integer|min:1',
            'direccion_sucursal' => 'required|array|min:1',
            'direccion_sucursal.id_sucursal' => 'required|min:1'
        ];


        /*       id_direccion serial,
       codigo character varying(5) NOT NULL,
       descripcion character varying(50) NOT NULL,
       estado boolean DEFAULT '1',
       u_creacion character varying(50),
       u_modificacion character varying(30),
       f_creacion timestamp without time zone,
       f_modificacion timestamp without time zone,*/

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $direccion = Direcciones::find($request->id_direccion);
            $direccion->descripcion = $request->descripcion;
            $direccion->codigo = $request->codigo;
            $direccion->clasificacion_direccion = $request->clasificacion_direccion;
            $direccion->u_modificacion = Auth::user()->usuario;
            $direccion->id_sucursal = $request->direccion_sucursal['id_sucursal'];
            $direccion->save();

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

    public function nuevo(Request $request)
    {
        $sucursales = Sucursales::select('id_sucursal','descripcion','serie')->get();

        return response()->json([
            'status' => 'success',
            'result' => [
                'sucursales' => $sucursales,
            ],
            'messages' => null
        ]);
    }

    /**
     * Desactiva rol
     *
     * @access  public
     * @param
     * @return  \Illuminate\Http\JsonResponse(string)
     */

    public function desactivar(Request $request)
    {
        $rules = [
            'id_direccion' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $direccion = Direcciones::find($request->id_direccion);
            $direccion->estado = 0;
            $direccion->u_modificacion = Auth::user()->usuario;
            $direccion->save();

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
            'id_direccion' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $direccion = Direcciones::find($request->id_direccion);
            $direccion->estado = 1;
            $direccion->u_modificacion = Auth::user()->usuario;
            $direccion->save();

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
            //$input = 'C:/xampp/htdocs/resources/reports/ReporteDirecciones';
            //$output = 'C:/xampp/htdocs/resources/reports/' .$hora_actual . 'ReporteDirecciones';
            $input = '/var/www/html/resources/reports/ReporteDirecciones';
            $output = '/var/www/html/resources/reports/'.$hora_actual.'ReporteDirecciones';

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

            return response()->download($output . '.' . $ext ,$hora_actual. 'ReporteDirecciones.' . $ext, $headers);

            /*exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
               print_r($output);*/
        }
    }

}
