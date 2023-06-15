<?php

namespace App\Http\Controllers\Tesoreria\CajaChica;

use App\Http\Controllers\Controller;
use App\Models\Tesoreria\CajaChica\Normativa;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPJasper\PHPJasper;
use App\Models\PublicZonas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class NormativaController extends Controller
{
    /**
     * Obtener una lista de Normativas (con opcion de busqueda y paginacion)
     *
     * @access  public
     * @param Request $request
     * @param Normativa $normativa
     * @return JsonResponse
     */

    public function obtener(Request $request, Normativa $normativa)
    {
        $normativa = $normativa->obtenerNormativa($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $normativa->total(),
                'rows' => $normativa->items()
            ],
            'messages' => null
        ]);
    }

    /**
     * Obtener una lista de Normativas sin paginacion
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function obtenerTodas(Request $request, Normativa $normativa)
    {
        $normativa = Normativa::orderby('id_normativa')->get();
        return response()->json([
            'status' => 'success',
            'result' => $normativa,
            'messages' => null
        ]);
    }

    /**
     * obtener normativa Especifica
     *
     * @param Request $request
     * @return JsonResponse
     * @access  public
     */

    public function obtenerNormativa(Request $request)
    {
        $rules = [
            'id_normativa'=> 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $normativa = Normativa::find($request->id_normativa);
          //  $normativa = Normativa::where('id_normativa', $request->id_normativa)-first();

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
     * Crear un nuevo normativa
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function registrar(Request $request)
    {
        $rules = [
            'concepto' => 'required|string|max:100|unique:pgsql.tesoreria.normativas,concepto',
            'monto_minimo' => 'numeric|min:1',
            'monto_maximo' => 'numeric|min:1',
            'tiempo' => 'required|string|max:100'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $normativa = new Normativa;
            $normativa->concepto = $request->concepto;
            $normativa->monto_minimo = $request->monto_minimo;
            $normativa->monto_maximo = $request->monto_maximo;
            $normativa->tiempo = $request -> tiempo;
            $normativa->u_creacion = Auth::user()->usuario;
            $normativa->estado = 1;
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
            'concepto' => [
                'required',
                'string',
                'max:100',

                Rule::unique('pgsql.tesoreria.normativas')->ignore($request->id_normativa,'id_normativa')
            ],
            'monto_minimo' => 'numeric|min:1',
            'monto_maximo' => 'numeric|min:1',
            'tiempo' => 'required|string|max:100'
        ];



        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $normativa = Normativa::find($request->id_normativa);
            $normativa->concepto = $request->concepto;
            $normativa->monto_minimo = $request->monto_minimo;
            $normativa->monto_maximo = $request->monto_maximo;
            $normativa->tiempo = $request -> tiempo;
            $normativa->u_modificacion = Auth::user()->usuario;
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
     * Desactiva normativa
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
            $normativa = Normativa::find($request->id_normativa);
            $normativa->estado = 0;
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
     * Activa normativa
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function activar(Request $request)
    {
        $rules = [
            'id_normativa' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $normativa = Normativa::find($request->id_normativa);
            $normativa->estado = 1;
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
            //$input = 'C:/xampp/htdocs/resources/reports/Reporte_normativas';
            //$output = 'C:/xampp/htdocs/resources/reports/' .$hora_actual . 'Reporte_normativas';
             $input = '/var/www/html/resources/reports/Reporte_normativas';
             $output = '/var/www/html/resources/reports/'.$hora_actual.'Reporte_normativas';

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

            return response()->download($output . '.' . $ext ,$hora_actual. 'Reporte_normativas.' . $ext, $headers);

            /*exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
               print_r($output);*/
        }
    }

}
