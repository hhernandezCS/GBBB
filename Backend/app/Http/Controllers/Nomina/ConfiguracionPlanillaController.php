<?php

namespace App\Http\Controllers\Nomina;

use App\Http\Controllers\Controller;
use App\Models\Contabilidad\CuentasContablesVista;
use App\Models\Nomina\ConfiguracionCdPlanilla;
use Hash, Validator,Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ConfiguracionPlanillaController extends Controller
{
    /**
     * Get List of Importaciones
     *
     * @access  public
     * @param Request $request
     * @param RRHHConfiguracionCdPlanilla $planilla_config
     * @return  \Illuminate\Http\JsonResponse(array)
     */

    public function obtener(Request $request, RRHHConfiguracionCdPlanilla $planilla_config)
    {
        $cuentas_contables = CuentasContablesVista::orderBy('cta_contable')->where('estado',1)->select('id_cuenta_contable','cta_contable','nombre_cuenta','nombre_cuenta_completo')->get();
        $planilla_config = $planilla_config->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $planilla_config->total(),
                'rows' => $planilla_config->items(),
                'cuentas_contables' => $cuentas_contables
            ],
            'messages' => null
        ]);
	}



    public function actualizar(Request $request)
    {
        $messages = [
            'ajustes.required' => 'Se requiere agregar al menos un registro.',
            'ajustes.min' => 'Se requiere agregar al menos un registro.',
            'ajustes.*.id_cuenta_contaable.required' => 'Seleccione una cuenta contable valida',
            'ajustes.*.descripcion_movimiento.required' => 'Se requiere una descripcion del movimiento',
        ];

        $rules = [

            'ajustes' => 'required|array|min:5',
            'ajustes.*.id_configuracion_cd_planilla' => 'required|integer',
            'ajustes.*.id_cuenta_contable_admin' => 'required|integer|exists:pgsql.contabilidad.cuentas_contables,id_cuenta_contable',
            'ajustes.*.id_cuenta_contable_comercial' => 'required|integer|exists:pgsql.contabilidad.cuentas_contables,id_cuenta_contable',
            'ajustes.*.concepto' => 'required|string|max:100',

        ];

        $validator = Validator::make($request->all(), $rules,$messages);
        if (!$validator->fails()) {

            try {

                    DB::beginTransaction();

                    foreach ($request->ajustes as $configuracion) {
                        $planilla_config = RRHHConfiguracionCdPlanilla::find($configuracion['id_configuracion_cd_planilla']);
                        $planilla_config->id_cuenta_contable_admin = $configuracion['configuracion_planilla_contable_admin']['id_cuenta_contable'];
                        $planilla_config->id_cuenta_contable_comercial = $configuracion['configuracion_planilla_contable_comercial']['id_cuenta_contable'];
                        $planilla_config->concepto = $configuracion['concepto'];
                        $planilla_config->save();
                    }


                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'result' => null,
                    'messages' => null
                ]);


            } catch (Exception $e){
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'result' => 'Error de base de datos',
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

}

