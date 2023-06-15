<?php

namespace App\Http\Controllers\CuentasXCobrar;

use App\Http\Controllers\Controller;
use App\Models\Contabilidad\CentrosCostosIngresos;
use App\Models\Contabilidad\CuentasContablesVista;
use App\Models\CuentasXCobrar\CatAuxiliar;
use App\Models\RRHHTrabajadores;
use App\Models\Ventas\Clientes;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator, Illuminate\Support\Facades\Auth;
use App\Models\CuentasXCobrar\TiposNotasDebito;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class CatOtrasCuentasController extends Controller
{
    /**
     * Obtener una lista de Afectaciones (con opcion de busqueda y paginacion)
     *
     * @access  public
     * @param Request $request
     * @param CatAuxiliar $cat_auxiliar
     * @return JsonResponse
     */

    public function obtener(Request $request, CatAuxiliar $cat_auxiliar)
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
     * @param CatAuxiliar $cat_auxiliar
     * @return JsonResponse
     */

    public function obtenerTodos(Request $request, CatAuxiliar $cat_auxiliar)
    {
        $cat_auxiliar = CatAuxiliar::orderby('id_cat_auxiliar_cxc')->where('estado',1)->get();
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
        $trabajadores = Trabajadores::select('id_trabajador','primer_apellido','primer_nombre','segundo_apellido','segundo_nombre',
            'id_area','id_cargo','codigo',
            DB::raw("CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido) AS nombre_completo"),
            DB::raw("substr(codigo,2,8) as simplify_code"))->where('activo',true)->get();

        $deudores = Clientes::where('es_deudor',true)->get();

        return response()->json([
            'status' => 'success',
            'result' => [
                'trabajadores' => $trabajadores,
                'deudores' => $deudores,
                /*'cuentas_contables' => $cuentas_contables,
                'centros_costos' => $centros_costos,
                'centros_ingresos' => $centros_ingresos,
                'auxiliares' => $auxiliares,*/
            ],
            'messages' => null
        ]);
    }

    public function obtenerOCuentasCobrar(Request $request)
    {
        $rules = [
            'id_cat_auxiliar_cxc'=> 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $cat_auxiliar = CatAuxiliar::where('id_cat_auxiliar_cxc',$request->id_cat_auxiliar_cxc)->with('trabajadorAuxiliar','deudorAuxiliar')->first();

            if(!empty($cat_auxiliar)){
            return response()->json([
                'status' => 'success',
                'result' => [
                    'cat_auxiliar' => $cat_auxiliar,
                ],
                'messages' => null
            ]);

        }
		else{
		  return response()->json([
				'status' => 'error',
				'result' => array('id_cat_auxiliar_cxc'=>["Datos no encontrados"]),
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
            'tipo_auxiliar' => 'required|string|max:10|unique:pgsql.cuentasxcobrar.cat_auxiliar_cxc,tipo_auxiliar',
            'descripcion' => 'required|string|max:250|unique:pgsql.cuentasxcobrar.cat_auxiliar_cxc,descripcion',
            'id_trabajador' => 'id_trabajador|integer|min:1',
            'id_proveedor' => 'id_proveedor|integer|min:1',

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $otras_cxc = new CatAuxiliar();
            $otras_cxc->tipo_auxiliar = $request->tipo_auxiliar;
            $otras_cxc->descripcion = $request->descripcion;
            $otras_cxc->codigo = $request->codigo;
            $otras_cxc->clasificacion = 1; // otras cuentas por cobrar
            if($request->tipo_auxiliar_select === '1')
            {
                $otras_cxc->id_trabajador = $request->trabajadorx['id_trabajador'];
            }else{
                $otras_cxc->id_cliente = $request->deudorx['id_cliente'];
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
            'codigo' => 'required|string|max:50',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $otras_cxc = CatAuxiliar::findOrFail($request->id_cat_auxiliar_cxc);
            $otras_cxc->tipo_auxiliar = $request->tipo_auxiliar;
            $otras_cxc->descripcion = $request->descripcion;
            $otras_cxc->codigo = $request->codigo;

            if($request->tipo_auxiliar_clasificacion === '1' || $request->tipo_auxiliar_clasificacion === 1)
            {
                $otras_cxc->id_trabajador = $request->trabajador_auxiliar['id_trabajador'];
            }else{
                $otras_cxc->id_cliente = $request->deudor_auxiliar['id_cliente'];
            }
            $otras_cxc->u_modifica = Auth::user()->usuario;
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
            $grupo = CatAuxiliar::find($request->id_cat_auxiliar_cxc);
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
            $grupo = CatAuxiliar::find($request->id_cat_auxiliar_cxc);
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
