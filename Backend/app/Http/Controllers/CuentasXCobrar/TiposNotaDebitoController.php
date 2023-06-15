<?php

namespace App\Http\Controllers\CuentasXCobrar;

use App\Http\Controllers\Controller;
use App\Models\Contabilidad\CentrosCostosIngresos;
use App\Models\Contabilidad\CuentasContablesVista;
use App\Models\CuentasXCobrar\CatAuxiliar;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator, Illuminate\Support\Facades\Auth;
use App\Models\CuentasXCobrar\TiposNotasDebito;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class TiposNotaDebitoController extends Controller
{
    /**
     * Obtener una lista de Afectaciones (con opcion de busqueda y paginacion)
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function obtener(Request $request, TiposNotasDebito $tipos_nd)
    {
        $tipos_nd = $tipos_nd->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $tipos_nd->total(),
                'rows' => $tipos_nd->items()
            ],
            'messages' => null
        ]);
    }

    /**
     * Obtener una lista de Roles sin paginacion
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function obtenerTodos(Request $request, TiposNotasDebito $tipos_nd)
    {
        $tipos_nd = TiposNotasDebito::orderby('id_tipo_nota_debito')->get();
        return response()->json([
            'status' => 'success',
            'result' => $tipos_nd,
            'messages' => null
        ]);
    }

    public function nuevo(Request $request)
    {
        $cuentas_contables = CuentasContablesVista::orderBy('cta_contable')->where('estado', 1)->select('id_cuenta_contable', 'cta_contable', 'nombre_cuenta', 'nombre_cuenta_completo', 'requiere_aux')->get();
        $centros_ingresos = CentrosCostosIngresos::where('estado', 1)->where('tipo_centro', 1)->get();
        $centros_costos = CentrosCostosIngresos::where('estado', 1)->where('tipo_centro', 2)->get();
        $auxiliares = CatAuxiliar::where('estado', 1)->get();
        return response()->json([
            'status' => 'success',
            'result' => [
                'cuentas_contables' => $cuentas_contables,
                'centros_costos' => $centros_costos,
                'centros_ingresos' => $centros_ingresos,
                'auxiliares' => $auxiliares,
            ],
            'messages' => null
        ]);
    }

    public function obtenerTipoND(Request $request)
    {
        $rules = [
            'id_tipo_nota_debito'=> 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $tipos_nd = TiposNotasDebito::where('id_tipo_nota_debito',$request->id_tipo_nota_debito)->with('tipoCuentaContable','clasificacionCompraCuentaContable','clasificacionCentroCosto','clasificacionAuxiliar')->get();
            $cuentas_contables = CuentasContablesVista::orderBy('cta_contable')->where('estado',1)->select('id_cuenta_contable','cta_contable','nombre_cuenta','nombre_cuenta_completo','requiere_aux')->get();
            $centros_ingresos = CentrosCostosIngresos::where('estado', 1)->where('tipo_centro', 1)->get();
            $centros_costos = CentrosCostosIngresos::where('estado', 1)->where('tipo_centro', 2)->get();
            $auxiliares = CatAuxiliar::where('estado', 1)->get();

            if(!empty($tipos_nd)){
            return response()->json([
                'status' => 'success',
                'result' => [
                    'tipos_nc' => $tipos_nd[0],
                    'cuentas_contables' => $cuentas_contables,
                    'centros_costos' => $centros_costos,
                    'centros_ingresos' => $centros_ingresos,
                    'auxiliares' => $auxiliares,
                ],
                'messages' => null
            ]);

        }
		else{
		  return response()->json([
				'status' => 'error',
				'result' => array('id_tipo_nota_debito'=>["Datos no encontrados"]),
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
     * @return JsonResponse
     */

    public function registrar(Request $request)
    {
        $rules = [
            'descripcion' => 'required|string|max:100|unique:pgsql.cjabnco.facturas_afectaciones,descripcion',
            'clasificacion_compra_cuenta_contable' => 'required|array|min:1',
            'clasificacion_compra_cuenta_contable.id_cuenta_contable' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $tipo_nota_debito = new TiposNotasDebito;
            $tipo_nota_debito->descripcion = $request->descripcion;
            $tipo_nota_debito->id_cuenta_contable = $request->clasificacion_compra_cuenta_contable['id_cuenta_contable'];
            $tipo_nota_debito->u_creacion = Auth::user()->name;
            $tipo_nota_debito->estado = 1;

            if ($request->clasificacion_compra_cuenta_contable['requiere_aux'] === 0) {
                $tipo_nota_debito->id_centro_costo = null;
                $tipo_nota_debito->codigo_centro_costo = null;
                $tipo_nota_debito->id_cat_auxiliar_cxc = null;
                $tipo_nota_debito->codigo_auxiliar = null;
            } else if ($request->clasificacion_compra_cuenta_contable['requiere_aux'] === 1) {
                $tipo_nota_debito->id_centro_costo = null;
                $tipo_nota_debito->codigo_centro_costo = null;
                $tipo_nota_debito->id_cat_auxiliar_cxc = $request->auxiliar['id_cat_auxiliar_cxc'];
                $tipo_nota_debito->codigo_auxiliar = $request->auxiliar['codigo'];
            } else if ($request->clasificacion_compra_cuenta_contable['requiere_aux'] === 2) {
                $tipo_nota_debito->id_centro_costo = $request->centro_costo['id_centro'];
                $tipo_nota_debito->codigo_centro_costo = $request->centro_costo['codigo'];
                $tipo_nota_debito->id_cat_auxiliar_cxc = null;
                $tipo_nota_debito->codigo_auxiliar = null;
            }else if($request->clasificacion_compra_cuenta_contable['requiere_aux'] === 3){
                $tipo_nota_debito->id_centro_costo = $request->centro_ingreso['id_centro'];
                $tipo_nota_debito->codigo_centro_costo = $request->centro_ingreso['codigo'];
                $tipo_nota_debito->id_cat_auxiliar_cxc = null;
                $tipo_nota_debito->codigo_auxiliar = null;
            }

            $tipo_nota_debito->concepto_comprobante = $request->concepto_comprobante;
            $tipo_nota_debito->comision = $request->comision;

            $tipo_nota_debito->save();

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
            'id_tipo_nota_debito' => 'required|integer',
            'descripcion' => [
                'required',
                'string',
                'max:100',
                Rule::unique('pgsql.cuentasxcobrar.tipos_nota_debito')->ignore($request->id_tipo_nota_debito,'id_tipo_nota_debito')
            ],
            'tipo_cuenta_contable' => 'required|array|min:1',
            'tipo_cuenta_contable.id_cuenta_contable' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $tipo_nota_debito = TiposNotasDebito::find($request->id_tipo_nota_debito);
            $tipo_nota_debito->descripcion = $request->descripcion;
            $tipo_nota_debito->id_cuenta_contable = $request->tipo_cuenta_contable['id_cuenta_contable'];

            if ($request->clasificacion_compra_cuenta_contable['requiere_aux'] === 0) {
                $tipo_nota_debito->id_centro_costo = null;
                $tipo_nota_debito->codigo_centro_costo = null;
                $tipo_nota_debito->id_cat_auxiliar_cxc = null;
                $tipo_nota_debito->codigo_auxiliar = null;
            } else if ($request->clasificacion_compra_cuenta_contable['requiere_aux'] === 1) {
                $tipo_nota_debito->id_centro_costo = null;
                $tipo_nota_debito->codigo_centro_costo = null;
                $tipo_nota_debito->id_cat_auxiliar_cxc = $request->auxiliar['id_cat_auxiliar_cxc'];
                $tipo_nota_debito->codigo_auxiliar = $request->auxiliar['codigo'];
            } else if ($request->clasificacion_compra_cuenta_contable['requiere_aux'] === 2) {
                $tipo_nota_debito->id_centro_costo = $request->centro_costo['id_centro'];
                $tipo_nota_debito->codigo_centro_costo = $request->centro_costo['codigo'];
                $tipo_nota_debito->id_cat_auxiliar_cxc = null;
                $tipo_nota_debito->codigo_auxiliar = null;
            }else if($request->clasificacion_compra_cuenta_contable['requiere_aux'] === 3){
                $tipo_nota_debito->id_centro_costo = $request->centro_ingreso['id_centro'];
                $tipo_nota_debito->codigo_centro_costo = $request->centro_ingreso['codigo'];
                $tipo_nota_debito->id_cat_auxiliar_cxc = null;
                $tipo_nota_debito->codigo_auxiliar = null;
            }

            $tipo_nota_debito->concepto_comprobante = $request->concepto_comprobante;
            $tipo_nota_debito->comision = $request->comision;

            $tipo_nota_debito->save();

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
            'id_tipo_nota_debito' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $grupo = TiposNotasDebito::find($request->id_tipo_nota_debito);
            $grupo->estado = 0;
            $grupo->save();

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
            'id_tipo_nota_debito' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $grupo = TiposNotasDebito::find($request->id_tipo_nota_debito);
            $grupo->estado = 1;
            $grupo->save();

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
}
