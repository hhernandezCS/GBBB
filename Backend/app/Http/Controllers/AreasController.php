<?php

namespace App\Http\Controllers;

use App\Models\Admon\Sucursales;
use Illuminate\Http\JsonResponse;
use PHPJasper\PHPJasper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Admon\Areas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class AreasController extends Controller
{


    public function buscar(Request $request, Areas $areas): JsonResponse
    {
        $areas = $areas->buscar($request);
        return response()->json([
            'results' => $areas
        ]);
    }

    /**
     * Obtener una lista de Estados Financieros
     *
     * @access  public
     * @param Request $request
     * @param Areas $areas
     * @return  JsonResponse(array)
     */

    public function obtener(Request $request, Areas $areas): JsonResponse
    {
        $areas = $areas->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $areas->total(),
                'rows' => $areas->items()
            ],
            'messages' => null
        ]);
    }

    /**
     * Obtener una lista de Roles sin ningun filtro
     *
     * @access  public
     * @param Request $request
     * @param Areas $areas
     * @return  JsonResponse(array)
     */

    public function obtenerTodasAreas(Request $request, Areas $areas): JsonResponse
    {
        $areas = Areas::where('estado', 1)->get();
        return response()->json([
            'status' => 'success',
            'result' => $areas,
            'messages' => null
        ]);
    }

    /**
     * obtener Estado Finaciero Especifico
     *
     * @access  public
     * @param Request $request
     * @return  JsonResponse(array)
     */

    public function obtenerArea(Request $request): JsonResponse
    {
        $rules = [
            'id_area' => 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $area = Areas::where('id_area',$request->id_area)
                ->with('cuentaContableArea')
                ->with('direccionArea')->get();

            if(!empty($area[0])){
                return response()->json([
                    'status' => 'success',
                    'result' => $area[0],
                    'messages' => null
                ]);

            }
            else{
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_area'=>["Datos no encontrados"]),
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
     * @param Request $request
     * @return  JsonResponse(string)
     */

    public function registrar(Request $request): JsonResponse
    {
        $rules = [
            /*'codigo' => [
                'required',
                'string',
                'max:10',
                Rule::unique('pgsql.public.areas')/*->ignore($request->id_cargo,'id_cargo')],*/
            'descripcion' => [
                'required',
                'string',
                'max:50',
                Rule::unique('pgsql.public.areas')/*->ignore($request->id_cargo,'id_cargo')*/],
            //'cuenta_contable' => 'required|array|min:1',
            'direccion' => 'required|array|min:1',
        ];


        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $area = new Areas;

            $secuencia = Areas::select([DB::raw("COALESCE(max(secuencia),0)+1 as secuencia")])->where('id_direccion',$request->direccion['id_direccion'])->first();

            $sucursal = Sucursales::find($request->direccion['id_sucursal']);

            $str_length = 2;
            $nuevocod = $secuencia['secuencia'];
            $str = substr("0{$nuevocod}", -$str_length);

            $area->codigo = ($sucursal->id_sucursal).'-0-'.$request->direccion['codigo'].'-'.($str);
            //$area->codigo = $request->codigo;
            $area->descripcion = strtoupper($request ->descripcion);
            $area->id_cuenta_contable = 1;
            $area->secuencia = $secuencia['secuencia'];
            $area->id_direccion = $request->direccion['id_direccion'];

            $area->estado = 1;
            $area->u_creacion = Auth::user()->usuario;
            $area->save();

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


    public function actualizar(Request $request): JsonResponse
    {
        $rules = [
            'id_area' => 'required|integer|min:1',
            /*'codigo' => [
                'required',
                'string',
                'max:10',
                Rule::unique('pgsql.public.areas')->ignore($request->id_area,'id_area')],*/
            'descripcion' => [
                'required',
                'string',
                'max:50',
                Rule::unique('pgsql.public.areas')->ignore($request->id_area,'id_area')],
            //'cuenta_contable_area' => 'required|array|min:1',
            //'direccion_area' => 'required|array|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $area = Areas::find($request->id_area);
            //$area->codigo = $request->codigo;
            $area->descripcion = strtoupper($request->descripcion);
            //$area->id_cuenta_contable = 1;
            //$area->secuencia = 1;
            //$area->id_direccion = $request->direccion_area['id_direccion'];
            //$area->estado = 1;
            $area->u_modificacion = Auth::user()->usuario;
            $area->save();

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


    public function desactivar(Request $request): JsonResponse
    {
        $rules = [
            'id_area' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $area = Areas::find($request->id_area);
            $area->estado = 0;
            $area->u_modificacion = Auth::user()->usuario;
            $area->save();

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


    public function activar(Request $request): JsonResponse
    {
        $rules = [
            'id_area' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $area = Areas::find($request->id_area);
            $area->estado = 1;
            $area->u_modificacion = Auth::user()->usuario;
            $area->save();

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
     * Generar Reporte Jasper
     *
     * @access  public
     * @param
     */
    public function reporteAreas($tipo)
    {
        $tipos = array("agrupado", "detallado");
        //echo gethostname();
        if (in_array($tipo, $tipos)) {
            $hora_actual = time();

            /*SERVER URLS*/
            $url = '/var/www/html/resources/reports/';

            /*LOCAL URLS*/
            //$url = 'C:/xampp7/htdocs/resources/reports/';

            if($tipo=='agrupado'){
                $input = $url.'ReporteAreasAgrupado';
            }elseif($tipo=='detallado'){
                $input = $url.'ReporteAreasDetallado';
            }else{
                $input = '';
            }
            $output =$url.$hora_actual.'ReporteAreas.pdf';

            $options = [
                'format' => ['pdf'],
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
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $hora_actual . 'ReporteAreas.pdf');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Content-Length: ' . filesize($output . '.pdf'));
            flush();
            readfile($output . '.pdf');
            unlink($output . '.pdf');


            /*exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
               print_r($output);*/
        }
    }

}
