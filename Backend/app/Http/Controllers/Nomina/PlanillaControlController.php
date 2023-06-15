<?php

namespace App\Http\Controllers\Nomina;

use App\Models\Admon\Zonas;
use App\Models\nomina\ContratoTipos;
use App\Models\nomina\DatosMedicos;
use App\Models\nomina\PlanillaControl;
use Illuminate\Http\JsonResponse;
use PHPJasper\PHPJasper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\nomina\NivelesAcademicos;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
class PlanillaControlController extends Controller
{
    /**
     * Obtener una lista de Roles (con opcion de busqueda y paginacion)
     *
     * @access  public
     * @param Request $request
     * @param PlanillaControl $datos
     * @return  JsonResponse(array)
     */

    public function obtener(Request $request, PlanillaControl $datos)
    {
        $datos = $datos->obtenerPlanillaControl($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $datos->total(),
                'rows' => $datos->items()
            ],
            'messages' => null
        ]);
    }

    /**
     * Obtener una lista de zonas sin paginacion
     *
     * @access  public
     * @param Request $request
     * @param PlanillaControl $datos
     * @return  JsonResponse(array)
     */

    public function obtenerTodas(Request $request, PlanillaControl $datos)
    {
        $datos = PlanillaControl::orderby('id_planilla_control')->get();
        return response()->json([
            'status' => 'success',
            'result' => $datos,
            'messages' => null
        ]);
    }

    /**
     * obtener Rol Especifico
     *
     * @access  public
     * @param
     * @return  JsonResponse(array)
     */

    public function obtenerControlPlanilla(Request $request)
    {
        $rules = [
            'id_planilla_control'=> 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $datos = PlanillaControl::find($request->id_planilla_control);

            if(!empty($datos)){
            return response()->json([
                'status' => 'success',
                'result' => $datos,
                'messages' => null
            ]);

        }
		else{
		  return response()->json([
				'status' => 'error',
				'result' => array('id_planilla_control'=>["Datos no encontrados"]),
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
     * Crear un nuevo rol
     *
     * @access  public
     * @param
     * @return  JsonResponse(string)
     */

    public function registrar(Request $request)
    {
        $rules = [
            'descripcion' => 'required|string|max:100|unique:pgsql.public.zonas,descripcion'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $zona = new Zonas;
            $zona->descripcion = $request->descripcion;

            $codigo_nuevo = $zona->obtenerCodigoZona();
            $str_length = 3;
            $str = substr("00{$codigo_nuevo['secuencia']}", -$str_length);

            $zona->codigo = $str;

            $zona->u_grabacion = Auth::user()->usuario;
            $zona->activo = 1;
            $zona->save();

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
     * @return  JsonResponse(string)
     */

    public function actualizar(Request $request)
    {
        $rules = [
            'id_zona' => 'required|integer|min:1',
            'descripcion' => [
                'required',
                'string',
                'max:100',
                Rule::unique('pgsql.public.zonas')->ignore($request->id_zona,'id_zona')
            ],
        ];



        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $zona = Zonas::find($request->id_zona);
            $zona->descripcion = $request->descripcion;
            $zona->u_modificacion = Auth::user()->usuario;
            $zona->save();

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
     * @return  JsonResponse(string)
     */

    public function desactivar(Request $request)
    {
        $rules = [
            'id_zona' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $zona = Zonas::find($request->id_zona);
            $zona->activo = 0;
            $zona->save();

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
     * Activa rol
     *
     * @access  public
     * @param
     * @return  JsonResponse(string)
     */

    public function activar(Request $request)
    {
        $rules = [
            'id_zona' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $rol = Zonas::find($request->id_zona);
            $rol->activo = 1;
            $rol->save();

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
            //$input = 'C:/xampp/htdocs/resources/reports/ReporteZonas';
            //$output = 'C:/xampp/htdocs/resources/reports/' .$hora_actual . 'ReporteZonas';
             $input = '/var/www/html/resources/reports/ReporteZonas';
             $output = '/var/www/html/resources/reports/'.$hora_actual.'ReporteZonas';

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

            return response()->download($output . '.' . $ext ,$hora_actual. 'ReporteZonas.' . $ext, $headers);

            /*exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
               print_r($output);*/
        }
    }

}
