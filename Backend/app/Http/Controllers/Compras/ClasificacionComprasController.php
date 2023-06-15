<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\Controller;
use App\Models\Compras\ClasificacionCompras;
use App\Models\Contabilidad\CentrosCostosIngresos;
use App\Models\Contabilidad\CuentasContables;
use App\Models\Contabilidad\CuentasContablesVista;
use App\Models\CuentasXCobrarCatAuxiliar;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClasificacionComprasController extends Controller
{

    public function nuevo(Request $request)
    {
        $cuentas_contables = CuentasContablesVista::orderBy('cta_contable')->where('estado', 1)->select('id_cuenta_contable', 'cta_contable', 'nombre_cuenta', 'nombre_cuenta_completo', 'requiere_aux')->get();
        $centros_ingresos = CentrosCostosIngresos::where('estado', 1)->where('tipo_centro', 1)->get();
        $centros_costos = CentrosCostosIngresos::where('estado', 1)->where('tipo_centro', 2)->get();
//        $auxiliares = CuentasXCobrarCatAuxiliar::where('estado', 1)->get();

        return response()->json([
            'status' => 'success',
            'result' => [
                'cuentas_contables' => $cuentas_contables,
                'centros_costos' => $centros_costos,
                'centros_ingresos' => $centros_ingresos,
//                'auxiliares' => $auxiliares,
            ],
            'messages' => null
        ]);

    }

    /**
     * Obtener una lista de Afectaciones (con opcion de busqueda y paginacion)
     *
     * @access  public
     * @param Request $request
     * @param ClasificacionCompras $clasificacion
     * @return JsonResponse
     */

    public function obtener(Request $request, ClasificacionCompras $clasificacion): JsonResponse
    {
        $clasificacion = $clasificacion->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $clasificacion->total(),
                'rows' => $clasificacion->items()
            ],
            'messages' => null
        ]);
    }

    /**
     * Obtener una lista de Roles sin paginacion
     *
     * @access  public
     * @param Request $request
     * @param ClasificacionCompras $clasificacion
     * @return JsonResponse
     */

    public function obtenerClasificaciones(Request $request, ClasificacionCompras $clasificacion)
    {
        $clasificacion = ClasificacionCompras::orderby('id_clasificacion_servicio')->get();
        return response()->json([
            'status' => 'success',
            'result' => $clasificacion,
            'messages' => null
        ]);
    }


    /**
     * obtener Rol Especifico
     *
     * @access  public
     * @param Request $request
     * @return JsonResponse
     */

    public function obtenerClasificacion(Request $request)
    {
        $rules = [
            'id_clasificacion_servicio' => 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $clasificacion = ClasificacionCompras::where('id_clasificacion_servicio', $request->id_clasificacion_servicio)->with('clasificacionCompraCuentaContable','clasificacionCentroCosto')->get(); //,'clasificacionAuxiliar'
            $cuentas_contables = CuentasContablesVista::orderBy('cta_contable')->where('estado', 1)->select('id_cuenta_contable', 'cta_contable', 'nombre_cuenta', 'nombre_cuenta_completo','requiere_aux')->get();
            $centros_ingresos = CentrosCostosIngresos::where('estado', 1)->where('tipo_centro', 1)->get();
            $centros_costos = CentrosCostosIngresos::where('estado', 1)->where('tipo_centro', 2)->get();
//            $auxiliares = CuentasXCobrarCatAuxiliar::where('estado', 1)->get();

            if (!empty($clasificacion)) {
                return response()->json([
                    'status' => 'success',
                    'result' => [
                        'clasificacion' => $clasificacion[0],
                        'cuentas_contables' => $cuentas_contables,
                        'centros_costos' => $centros_costos,
                        'centros_ingresos' => $centros_ingresos,
//                        'auxiliares' => $auxiliares,
                    ],
                    'messages' => null
                ]);

            }

            return response()->json([
                'status' => 'error',
                'result' => array('id_clasificacion_servicio' => ["Datos no encontrados"]),
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
     * Crear un nuevo rol
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function registrar(Request $request)
    {
        $rules = [
            'descripcion' => [
                'required',
                'string',
                'max:100',
                Rule::unique('pgsql.compra.clasificacion_compra')/*->ignore($request->id_afectacion,'id_afectacion')*/
            ],
            //'tipo_afectacion' => 'required|integer|min:1|max:3',
            'clasificacion_compra_cuenta_contable' => 'required|array|min:1',
            'clasificacion_compra_cuenta_contable.id_cuenta_contable' => 'required|integer|min:1',

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $clasificacion = new ClasificacionCompras();
            $clasificacion->descripcion = $request->descripcion;
            $clasificacion->id_cuenta_contable = $request->clasificacion_compra_cuenta_contable['id_cuenta_contable'];

            if ($request->clasificacion_compra_cuenta_contable['requiere_aux'] === 0) {
                $clasificacion->id_centro_costo = null;
                $clasificacion->codigo_centro_costo = null;
                $clasificacion->id_cat_auxiliar_cxc = null;
                $clasificacion->codigo_auxiliar = null;
            } else if ($request->clasificacion_compra_cuenta_contable['requiere_aux'] === 1) {
                $clasificacion->id_centro_costo = null;
                $clasificacion->codigo_centro_costo = null;
                $clasificacion->id_cat_auxiliar_cxc = $request->auxiliar['id_cat_auxiliar_cxc'];
                $clasificacion->codigo_auxiliar = $request->auxiliar['codigo'];
            } else if ($request->clasificacion_compra_cuenta_contable['requiere_aux'] === 2) {
                $clasificacion->id_centro_costo = $request->centro_costo['id_centro'];
                $clasificacion->codigo_centro_costo = $request->centro_costo['codigo'];
                $clasificacion->id_cat_auxiliar_cxc = null;
                $clasificacion->codigo_auxiliar = null;
            }else if($request->clasificacion_compra_cuenta_contable['requiere_aux'] === 3){
                $clasificacion->id_centro_costo = $request->centro_ingreso['id_centro'];
                $clasificacion->codigo_centro_costo = $request->centro_ingreso['codigo'];
                $clasificacion->id_cat_auxiliar_cxc = null;
                $clasificacion->codigo_auxiliar = null;
            }

            $clasificacion->concepto_comprobante = $request->concepto_comprobante;

            $clasificacion->activo = true;

            $clasificacion->save();

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
            'id_clasificacion_servicio' => 'required|integer',
            'descripcion' => [
                'required',
                'string',
                'max:100',
                Rule::unique('pgsql.compra.clasificacion_compra')->ignore($request->id_clasificacion_servicio, 'id_clasificacion_servicio')
            ],
            'clasificacion_compra_cuenta_contable' => 'required|array|min:1',
            'clasificacion_compra_cuenta_contable.id_cuenta_contable' => 'required|integer|min:1',

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $clasificacion = ClasificacionCompras::find($request->id_clasificacion_servicio);
            $clasificacion->descripcion = $request->descripcion;
            $clasificacion->id_cuenta_contable = $request->clasificacion_compra_cuenta_contable['id_cuenta_contable'];

            if ($request->clasificacion_compra_cuenta_contable['requiere_aux'] === 0) {
                $clasificacion->id_centro_costo = null;
                $clasificacion->codigo_centro_costo = null;
                $clasificacion->id_cat_auxiliar_cxc = null;
                $clasificacion->codigo_auxiliar = null;
            } else if ($request->clasificacion_compra_cuenta_contable['requiere_aux'] === 1) {
                $clasificacion->id_centro_costo = null;
                $clasificacion->codigo_centro_costo = null;
                $clasificacion->id_cat_auxiliar_cxc = $request->clasificacion_auxiliar['id_cat_auxiliar_cxc'];
                $clasificacion->codigo_auxiliar = $request->clasificacion_auxiliar['codigo'];
            } else if ($request->clasificacion_compra_cuenta_contable['requiere_aux'] === 2) {
                $clasificacion->id_centro_costo = $request->clasificacion_centro_costo['id_centro'];
                $clasificacion->codigo_centro_costo = $request->clasificacion_centro_costo['codigo'];
                $clasificacion->id_cat_auxiliar_cxc = null;
                $clasificacion->codigo_auxiliar = null;
            }else if($request->clasificacion_compra_cuenta_contable['requiere_aux'] === 3){
                $clasificacion->id_centro_costo = $request->clasificacion_centro_costo['id_centro'];
                $clasificacion->codigo_centro_costo = $request->clasificacion_centro_costo['codigo'];
                $clasificacion->id_cat_auxiliar_cxc = null;
                $clasificacion->codigo_auxiliar = null;
            }

            $clasificacion->concepto_comprobante = $request->concepto_comprobante;

            $clasificacion->save();

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

    public function desactivar(Request $request)
    {
        $rules = [
            'id_clasificacion_servicio' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $clasificacion = ClasificacionCompras::find($request->id_clasificacion_servicio);
            $clasificacion->activo = 0;
            $clasificacion->save();

            return response()->json([
                'status' => 'success',
                'result' => null,
                'messages' => null
            ]);
        }

        return response()->json([
            'status' => 'error',
            'result' => $validator->messages(),
            'messages' => null
        ]);
    }


    public function activar(Request $request)
    {
        $rules = [
            'id_clasificacion_servicio' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $clasificacion = ClasificacionCompras::find($request->id_clasificacion_servicio);
            $clasificacion->activo = 1;
            $clasificacion->save();

            return response()->json([
                'status' => 'success',
                'result' => null,
                'messages' => null
            ]);
        }

        return response()->json([
            'status' => 'error',
            'result' => $validator->messages(),
            'messages' => null
        ]);
    }

}
