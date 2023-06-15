<?php

namespace App\Http\Controllers\CuentasXPagar;

use App\Http\Controllers\Controller;
use App\Models\CajaBanco\Bancos;
use App\Models\Contabilidad\CentrosCostosIngresos;
use App\Models\Contabilidad\CuentasContables;
use App\Models\Contabilidad\CuentasContablesVista;
use App\Models\CuentasXPagar\CatAuxiliarCP;
use App\Models\Inventario\Proveedores;
use App\Models\RRHHTrabajadores;
use App\Models\Ventas\Clientes;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator, Illuminate\Support\Facades\Auth;
use App\Models\CuentasXCobrar\TiposNotasDebito;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CatOtrasCuentasControllerCP extends Controller
{
    /**
     * Obtener una lista de Afectaciones (con opcion de busqueda y paginacionCuentasXPagarCargoAbonoTrabajadorController.php)
     *
     * @access  public
     * @param Request $request
     * @param CatAuxiliarCP $cat_auxiliar
     * @return JsonResponse
     */

    public function obtener(Request $request, CatAuxiliarCP $cat_auxiliar)
    {
        $cat_auxiliar = $cat_auxiliar->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $cat_auxiliar->total(),
                'rows' => $cat_auxiliar->items()
            ],
            'messages' => null
        ]);
    }

    /**
     * Obtener una lista de Roles sin paginacion
     *
     * @access  public
     * @param Request $request
     * @param CatAuxiliarCP $cat_auxiliar
     * @return JsonResponse
     */

    public function obtenerTodos(Request $request, CatAuxiliarCP $cat_auxiliar)
    {
        $cat_auxiliar = CatAuxiliarCP::orderby('id_cat_auxiliar_cxc')->get();
        return response()->json([
            'status' => 'success',
            'result' => $cat_auxiliar,
            'messages' => null
        ]);
    }

    public function nuevo(Request $request)
    {
        /*$cuentas_contables = ContabilidadCuentasContablesVista::orderBy('cta_contable')->where('estado', 1)->select('id_cuenta_contable', 'cta_contable', 'nombre_cuenta', 'nombre_cuenta_completo', 'requiere_aux')->get();
        $centros_ingresos = ContabilidadCentroCostoIngreso::where('estado', 1)->where('tipo_centro', 1)->get();
        $centros_costos = ContabilidadCentroCostoIngreso::where('estado', 1)->where('tipo_centro', 2)->get();
        $auxiliares = CuentasXCobrarCatAuxiliar::where('estado', 1)->get();*/
        $proveedores = Proveedores:: select('id_proveedor', 'numero_ruc', 'nombre_comercial', 'codigo', DB::raw("substr(codigo,2,7) as extCodigo"))->where('clasificacion_contable', 1)->where('activo', true)->get();
        $acreedores = Proveedores:: select('id_proveedor', 'numero_ruc', 'nombre_comercial', 'codigo', DB::raw("substr(codigo,2,7) as extCodigo"))->where('clasificacion_contable', 2)->where('activo', true)->get();
        $pasivos_laborales = CuentasContables:: select('*', 'codigo_cuenta as extCodigo')->whereIn('id_cuenta_contable', array(167, 168, 170, 171, 172, 80))->where('estado', true)->get();
        $prestamos_bancarios = Bancos:: select('*', 'id_banco as extCodigo')->whereIn('id_banco', array(2, 4))->where('activo', true)->get();

        return response()->json([
            'status' => 'success',
            'result' => [
                'proveedores' => $proveedores,
                'acreedores' => $acreedores,
                'pasivos_laborales' => $pasivos_laborales,
                'prestamos_bancarios' => $prestamos_bancarios,
            ],
            'messages' => null
        ]);
    }

    public function obtenerOCuentasPagar(Request $request)
    {
        $rules = [
            'id_cat_auxiliar_cxc' => 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $cat_auxiliar = CatAuxiliarCP::where('id_cat_auxiliar_cxc', $request->id_cat_auxiliar_cxc)->with('proveedor_auxiliar', 'pasivo_auxiliar', 'prestamos_auxiliar')->first();//Por definir
            /*$cuentas_contables = ContabilidadCuentasContablesVista::orderBy('cta_contable')->where('estado',1)->select('id_cuenta_contable','cta_contable','nombre_cuenta','nombre_cuenta_completo','requiere_aux')->get();
            $centros_ingresos = ContabilidadCentroCostoIngreso::where('estado', 1)->where('tipo_centro', 1)->get();
            $centros_costos = ContabilidadCentroCostoIngreso::where('estado', 1)->where('tipo_centro', 2)->get();
            $auxiliares = CuentasXCobrarCatAuxiliar::where('estado', 1)->get();*/


            if (!empty($cat_auxiliar)) {
                return response()->json([
                    'status' => 'success',
                    'result' => [
                        'cat_auxiliar' => $cat_auxiliar,
                        /*'cuentas_contables' => $cuentas_contables,
                        'centros_costos' => $centros_costos,
                        'centros_ingresos' => $centros_ingresos,
                        'auxiliares' => $auxiliares,*/
                    ],
                    'messages' => null
                ]);

            } else {
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_cat_auxiliar_cxc' => ["Datos no encontrados"]),
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
            'tipo_auxiliar' => 'required|string|max:10|',
            'descripcion' => 'required|string|max:250',
            'id_trabajador' => 'id_trabajador|integer|min:1',
            'id_proveedor' => 'id_proveedor|integer|min:1',

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $otras_cxc = new CatAuxiliarCP();
            $otras_cxc->tipo_auxiliar = $request->tipo_auxiliar;
            $otras_cxc->descripcion = $request->descripcion;
            $otras_cxc->codigo = $request->codigo;
            $otras_cxc->tipo_auxiliar_clasificacion = $request->tipo_auxiliar_select;
            $otras_cxc->clasificacion = 2; // otras cuentas por cobrar
            if ($request->tipo_auxiliar_select === '1') {
                $otras_cxc->id_proveedor = $request->proveedorx['id_proveedor'];
            } else if ($request->tipo_auxiliar_select === '2') {
                $otras_cxc->id_proveedor = $request->acreedorx['id_proveedor'];
            } else if ($request->tipo_auxiliar_select === '3') {
                $otras_cxc->id_cuenta_contable = $request->pasivox['id_cuenta_contable'];
            } else if ($request->tipo_auxiliar_select === '4') {
                $otras_cxc->id_banco = $request->prestamosx['id_banco'];
            }
            $otras_cxc->u_registra = Auth::user()->usuario;
            $otras_cxc->estado = 1;

            $otras_cxc->save();

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
            'id_cat_auxiliar_cxc' => 'required|integer',
            'tipo_auxiliar' => 'required|string|max:10',
            'descripcion' => 'required|string|max:50',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $otras_cxc = CatAuxiliarCP::find($request->id_cat_auxiliar_cxc);
            $otras_cxc->tipo_auxiliar = $request->tipo_auxiliar;
            $otras_cxc->descripcion = $request->descripcion;
            $otras_cxc->codigo = $request->codigo;
            $otras_cxc->tipo_auxiliar_clasificacion = $request->tipo_auxiliar_select;
            if ($request->tipo_auxiliar_select === '1' || $request->tipo_auxiliar_select === 1) {
                $otras_cxc->id_proveedor = $request->proveedor_auxiliar['id_proveedor'];
            } else if (($request->tipo_auxiliar_select === '2' || $request->tipo_auxiliar_select === 2)) {
                $otras_cxc->id_proveedor = $request->proveedor_auxiliar['id_proveedor'];
            }else if (($request->tipo_auxiliar_select === '3' || $request->tipo_auxiliar_select === 3)) {
                $otras_cxc->id_proveedor = $request->pasivo_auxiliar['id_cuenta_contable'];
            }else if (($request->tipo_auxiliar_select === '4' || $request->tipo_auxiliar_select === 4)) {
                $otras_cxc->id_proveedor = $request->prestamos_auxiliar['id_banco'];
            }
            $otras_cxc->u_registra = Auth::user()->usuario;
            $otras_cxc->estado = 1;


            $otras_cxc->save();

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
            'id_cat_auxiliar_cxc' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $grupo = CatAuxiliarCP::find($request->id_cat_auxiliar_cxc);
            $grupo->estado = 0;
            $grupo->u_modifica = Auth::user()->usuario;
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
            'id_cat_auxiliar_cxc' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $grupo = CatAuxiliarCP::find($request->id_cat_auxiliar_cxc);
            $grupo->estado = 1;
            $grupo->u_modifica = Auth::user()->usuario;
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
