<?php

namespace App\Http\Controllers\CuentasXPagar;

use App\Http\Controllers\Controller;
use App\Models\Contabilidad\CuentasContablesVista;
use Validator,Auth;
use App\Models\CuentasXPagar\CPTiposNotasCredito;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class TiposNotaCreditoControllerCP extends Controller
{
    /**
     * Obtener una lista de Afectaciones (con opcion de busqueda y paginacion)
     *
     * @access  public
     * @param
     * @return  json(array)
     */

    public function obtener(Request $request, CPTiposNotasCredito $tipos_nc)
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
     * @return  json(array)
     */

    public function obtenerTodos(Request $request, CPTiposNotasCredito $tipos_nc)
    {
        $tipos_nc = CPTiposNotasCredito::orderby('id_tipo_nota_credito')->get();
        return response()->json([
            'status' => 'success',
            'result' => $tipos_nc,
            'messages' => null
        ]);
    }

    public function nuevo(Request $request)
    {
        $cuentas_contables = ContabilidadCuentasContablesVista::orderBy('cta_contable')->where('estado',1)->select('id_cuenta_contable','cta_contable','nombre_cuenta','nombre_cuenta_completo')->get();
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
            $tipos_nc = CPTiposNotasCredito::where('id_tipo_nota_credito',$request->id_tipo_nota_credito)->with('tipoCuentaContable')->get();
            $cuentas_contables = ContabilidadCuentasContablesVista::orderBy('cta_contable')->where('estado',1)->select('id_cuenta_contable','cta_contable','nombre_cuenta','nombre_cuenta_completo')->get();

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
     * @return  json(string)
     */

    public function registrar(Request $request)
    {
        $rules = [
            'descripcion' => 'required|string|max:100|unique:pgsql.cjabnco.facturas_afectaciones,descripcion',
            'tipo_nota_credito_cuenta_contable' => 'required|array|min:1',
            'tipo_nota_credito_cuenta_contable.id_cuenta_contable' => 'required|integer|min:1',

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $tipo_nota_credito = new CPTiposNotasCredito;
            $tipo_nota_credito->descripcion = $request->descripcion;
            $tipo_nota_credito->id_cuenta_contable = $request->tipo_nota_credito_cuenta_contable['id_cuenta_contable'];

            $tipo_nota_credito->u_creacion = Auth::user()->usuario;
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
     * @return  json(string)
     */

    public function actualizar(Request $request)
    {
        $rules = [
            'id_tipo_nota_credito' => 'required|integer',
            'descripcion' => [
                'required',
                'string',
                'max:100',
                Rule::unique('pgsql.cuentasxpagar.tipos_nota_credito')->ignore($request->id_tipo_nota_credito,'id_tipo_nota_credito')
            ],
            'tipo_cuenta_contable' => 'required|array|min:1',
            'tipo_cuenta_contable.id_cuenta_contable' => 'required|integer|min:1',

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $tipo_nota_credito = CPTiposNotasCredito::find($request->id_tipo_nota_credito);
            $tipo_nota_credito->descripcion = $request->descripcion;
            $tipo_nota_credito->id_cuenta_contable = $request->tipo_cuenta_contable['id_cuenta_contable'];

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
            $grupo = CPTiposNotasCredito::find($request->id_tipo_nota_credito);
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
            $grupo = CPTiposNotasCredito::find($request->id_tipo_nota_credito);
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
