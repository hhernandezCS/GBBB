<?php

namespace App\Http\Controllers\Nomina;

use App\Http\Controllers\Controller;
use App\Models\Contabilidad\CuentasContables;
use App\Models\Contabilidad\CuentasContablesVista;
use App\Models\Admon\Departamentos;
use App\Models\Admon\Municipios;
use App\Models\Nomina\ConfiguracionIR;
use App\Models\Nomina\ContratoGeneralInterno;
use App\Models\Nomina\ContratoGeneralMerecedor;
use App\Models\Nomina\ContratoSolicitud;
use App\Models\Nomina\IngresosDeducciones;
use App\Models\Nomina\NivelesEstudios;
use App\Models\Nomina\ContratoTipos;
use Illuminate\Support\Facades\DB;
use PHPJasper\PHPJasper;
use Validator,Auth;
use App\Models\Nomina\NivelesAcademicos;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class ConfiguracionIRController extends Controller
{
    /**
     * Obtener una lista de Roles (con opcion de busqueda y paginacion)
     *
     * @access  public
     * @param Request $request
     * @param ConfiguracionIR $configuracion
     * @return  \Illuminate\Http\JsonResponse(array)
     */

    public function obtener(Request $request, ConfiguracionIR $configuracion)
    {
        $configuracion = $configuracion->obtenerConfiguracionIR($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $configuracion->total(),
                'rows' => $configuracion->items()
            ],
            'messages' => null
        ]);
    }

    /**
     * Obtener una lista de zonas sin paginacion
     *
     * @access  public
     * @param Request $request
     * @param IngresosDeducciones $configuracion
     * @return  \Illuminate\Http\JsonResponse(array)
     */

    public function obtenerTodas(Request $request, ConfiguracionIR $configuracion)
    {
        $configuracion = ConfiguracionIR::orderby('id_configuracion_ir')->get();
        return response()->json([
            'status' => 'success',
            'result' => $configuracion,
            'messages' => null
        ]);
    }

    /**
     * obtener Rol Especifico
     *
     * @access  public
     * @param
     * @return  \Illuminate\Http\JsonResponse(array)
     */

    public function obtenerConfiguracionIR(Request $request)
    {
        $rules = [
            'id_configuracion_ir'=> 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $configuracion = ConfiguracionIR::where('id_configuracion_ir',$request->id_configuracion_ir)->first();
            if(!empty($configuracion)){
            return response()->json([
                'status' => 'success',
                'result' => [
                    'configuracion' => $configuracion,
                ],
                'messages' => null
            ]);

        }
		else{
		  return response()->json([
				'status' => 'error',
				'result' => array('id_configuracion_ir'=>["Datos no encontrados"]),
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
     * @return  \Illuminate\Http\JsonResponse(string)
     */

    public function registrar(Request $request)
    {
        $rules = [
                    'monto_inicial' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/|min:0',
                    'monto_final' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/|min:0',
                    'impuesto_base' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/|min:0',
                    'porcentaje' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/|min:0',
                    'sobre_exceso' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            $configuracion = new ConfiguracionIR();
            $configuracion->monto_inicial = $request->monto_inicial;
            $configuracion->monto_final = $request->monto_final;
            $configuracion->impuesto_base = $request->impuesto_base;
            $configuracion->porcentaje = $request->porcentaje;
            $configuracion->sobre_exceso = $request->sobre_exceso;
            $configuracion->u_grabacion = Auth::user()->usuario;
            $configuracion->estado = 1;
            $configuracion->save();

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
     * @return  \Illuminate\Http\JsonResponse(string)
     */

    public function actualizar(Request $request)
    {
        $rules = [
            'id_configuracion_ir' => 'required|integer|min:1'

        ];



        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            $configuracion = ConfiguracionIR::find($request->id_configuracion_ir);
            $configuracion->monto_inicial = $request->monto_inicial;
            $configuracion->monto_final = $request->monto_final;
            $configuracion->impuesto_base = $request->impuesto_base;
            $configuracion->porcentaje = $request->porcentaje;
            $configuracion->sobre_exceso = $request->sobre_exceso;
            $configuracion->u_modificacion = Auth::user()->usuario;
            $configuracion->estado = 1;
            $configuracion->save();

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
     * @return  \Illuminate\Http\JsonResponse(string)
     */

    public function desactivar(Request $request)
    {
        $rules = [
            'id_configuracion_ir' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $configuracion = ConfiguracionIR::find($request->id_configuracion_ir);
            $configuracion->estado = 0;
            $configuracion->save();

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
     * @return  \Illuminate\Http\JsonResponse(string)
     */

    public function activar(Request $request)
    {
        $rules = [
            'id_configuracion_ir' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $rol = ConfiguracionIR::find($request->id_configuracion_ir);
            $rol->estado = 1;
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
    public function nueva(Request $request)
    {
        $cuentas_contables = CuentasContablesVista::select('id_cuenta_contable','cta_contable','nombre_cuenta_completo')->get();


        return response()->json([
            'status' => 'success',
            'result' => [
                'cuentas_contables' => $cuentas_contables,
            ],
            'messages' => null
        ]);

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
