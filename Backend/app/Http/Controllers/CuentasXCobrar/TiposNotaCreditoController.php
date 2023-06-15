<?php

namespace App\Http\Controllers\CuentasXCobrar;

use App\Http\Controllers\Controller;
use App\Models\Contabilidad\CuentasContablesVista;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator, Illuminate\Support\Facades\Auth;
use App\Models\CuentasXCobrar\TiposNotasCredito;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class TiposNotaCreditoController extends Controller
{
    /**
     * Obtener una lista de Afectaciones (con opcion de busqueda y paginacion)
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function obtener(Request $request, TiposNotasCredito $tipos_nc)
    {
        $tipos_nc = $tipos_nc->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $tipos_nc->total(),
                'rows' => $tipos_nc->items()
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

    public function obtenerTodos(Request $request, TiposNotasCredito $tipos_nc)
    {
        $tipos_nc = TiposNotasCredito::orderby('id_tipo_nota_credito')->get();
        return response()->json([
            'status' => 'success',
            'result' => $tipos_nc,
            'messages' => null
        ]);
    }

    public function nuevo(Request $request)
    {
        $cuentas_contables = CuentasContablesVista::orderBy('cta_contable')->where('estado',1)->select('id_cuenta_contable','cta_contable','nombre_cuenta','nombre_cuenta_completo')->get();
        return response()->json([
            'status' => 'success',
            'result' => [
                'cuentas_contables' => $cuentas_contables
            ],
            'messages' => null
        ]);
    }

    public function obtenerTipoNC(Request $request)
    {
        $rules = [
            'id_tipo_nota_credito'=> 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $tipos_nc = TiposNotasCredito::where('id_tipo_nota_credito',$request->id_tipo_nota_credito)->with('tipoCuentaContable')->get();
            $cuentas_contables = CuentasContablesVista::orderBy('cta_contable')->where('estado',1)->select('id_cuenta_contable','cta_contable','nombre_cuenta','nombre_cuenta_completo')->get();

            if(!empty($tipos_nc)){
            return response()->json([
                'status' => 'success',
                'result' => [
                    'tipos_nc' => $tipos_nc[0],
                    'cuentas_contables' => $cuentas_contables
                ],
                'messages' => null
            ]);

        }
		else{
		  return response()->json([
				'status' => 'error',
				'result' => array('id_tipo_nota_credito'=>["Datos no encontrados"]),
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
            'tipo_nota_credito_cuenta_contable' => 'required|array|min:1',
            'tipo_nota_credito_cuenta_contable.id_cuenta_contable' => 'required|integer|min:1',
            'aplica_comision'=>'required|boolean'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $tipo_nota_credito = new TiposNotasCredito;
            $tipo_nota_credito->descripcion = $request->descripcion;
            $tipo_nota_credito->id_cuenta_contable = $request->tipo_nota_credito_cuenta_contable['id_cuenta_contable'];
            $tipo_nota_credito->aplica_comision = $request->aplica_comision;
            $tipo_nota_credito->u_creacion = Auth::user()->name;
            $tipo_nota_credito->estado = 1;

            $tipo_nota_credito->save();

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
            'id_tipo_nota_credito' => 'required|integer',
            'descripcion' => [
                'required',
                'string',
                'max:100',
                Rule::unique('pgsql.cuentasxcobrar.tipos_nota_credito')->ignore($request->id_tipo_nota_credito,'id_tipo_nota_credito')
            ],
            'tipo_cuenta_contable' => 'required|array|min:1',
            'tipo_cuenta_contable.id_cuenta_contable' => 'required|integer|min:1',
            'aplica_comision'=>'required|boolean'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $tipo_nota_credito = TiposNotasCredito::find($request->id_tipo_nota_credito);
            $tipo_nota_credito->descripcion = $request->descripcion;
            $tipo_nota_credito->id_cuenta_contable = $request->tipo_cuenta_contable['id_cuenta_contable'];
            $tipo_nota_credito->aplica_comision = $request->aplica_comision;
            $tipo_nota_credito->save();

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
            'id_tipo_nota_credito' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $grupo = TiposNotasCredito::find($request->id_tipo_nota_credito);
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
            'id_tipo_nota_credito' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $grupo = TiposNotasCredito::find($request->id_tipo_nota_credito);
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
