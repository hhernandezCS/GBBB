<?php

namespace App\Http\Controllers\CuentasXCobrar;

use App\Http\Controllers\Controller;
use App\Models\Contabilidad\CuentasContablesVista;
use App\Models\CuentasXPagar\TiposNotasDebitoCP;
use Validator,Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class TiposNotaDebitoControllerCP extends Controller
{
    /**
     * Obtener una lista de Afectaciones (con opcion de busqueda y paginacion)
     *
     * @access  public
     * @param Request $request
     * @param TiposNotasDebitoCP $tipos_nd
     * @return  json(array)
     */

    public function obtener(Request $request, TiposNotasDebitoCP $tipos_nd)
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
     * @return  json(array)
     */

    public function obtenerTodos(Request $request, TiposNotasDebitoCP $tipos_nd)
    {
        $tipos_nd = TiposNotasDebitoCP::orderby('id_tipo_nota_debito')->get();
        return response()->json([
            'status' => 'success',
            'result' => $tipos_nd,
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

    public function obtenerTipoND(Request $request)
    {
        $rules = [
            'id_tipo_nota_debito'=> 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $tipos_nd = TiposNotasDebitoCP::where('id_tipo_nota_debito',$request->id_tipo_nota_debito)->with('tipoCuentaContable')->get();
            $cuentas_contables = CuentasContablesVista::orderBy('cta_contable')->where('estado',1)->select('id_cuenta_contable','cta_contable','nombre_cuenta','nombre_cuenta_completo')->get();

            if(!empty($tipos_nd)){
            return response()->json([
                'status' => 'success',
                'result' => [
                    'tipos_nc' => $tipos_nd[0],
                    'cuentas_contables' => $cuentas_contables
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
     * @return  json(string)
     */

    public function registrar(Request $request)
    {
        $rules = [
            'descripcion' => 'required|string|max:100|unique:pgsql.cjabnco.facturas_afectaciones,descripcion',
            'tipo_nota_debito_cuenta_contable' => 'required|array|min:1',
            'tipo_nota_debito_cuenta_contable.id_cuenta_contable' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $tipo_nota_debito = new TiposNotasDebitoCP;
            $tipo_nota_debito->descripcion = $request->descripcion;
            $tipo_nota_debito->id_cuenta_contable = $request->tipo_nota_debito_cuenta_contable['id_cuenta_contable'];
            $tipo_nota_debito->u_creacion = Auth::user()->usuario;
            $tipo_nota_debito->estado = 1;

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
     * @return  json(string)
     */

    public function actualizar(Request $request)
    {
        $rules = [
            'id_tipo_nota_debito' => 'required|integer',
            'descripcion' => [
                'required',
                'string',
                'max:100',
                Rule::unique('pgsql.cuentasxpagar.tipos_nota_debito')->ignore($request->id_tipo_nota_debito,'id_tipo_nota_debito')
            ],
            'tipo_cuenta_contable' => 'required|array|min:1',
            'tipo_cuenta_contable.id_cuenta_contable' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $tipo_nota_debito = TiposNotasDebitoCP::find($request->id_tipo_nota_debito);
            $tipo_nota_debito->descripcion = $request->descripcion;
            $tipo_nota_debito->id_cuenta_contable = $request->tipo_cuenta_contable['id_cuenta_contable'];
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
            $grupo = TiposNotasDebitoCP::find($request->id_tipo_nota_debito);
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
            $grupo = TiposNotasDebitoCP::find($request->id_tipo_nota_debito);
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
