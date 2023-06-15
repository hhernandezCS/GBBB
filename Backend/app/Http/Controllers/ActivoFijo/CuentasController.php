<?php



namespace App\Http\Controllers\ActivoFijo;

use App\Models\Admon\Departamentos;
use App\Models\Contabilidad\CuentasContablesVista;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\ActivoFijo\Cuentas;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPJasper\PHPJasper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class CuentasController extends Controller
{
    /**
     * Get List of Importaciones
     *
     * @access  public
     * @param
     * @return  json(array)
     */

    public function obtenerCuentasActivos(Request $request, Cuentas $cuentas)
    {
        $cuentas = $cuentas->obtenerCuentasActivos($request);
        $cuentas_contables = CuentasContablesVista::orderBy('cta_contable')->where('estado',1)->select('id_cuenta_contable','cta_contable','nombre_cuenta','nombre_cuenta_completo')->get();

        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $cuentas->total(),
                'rows' => $cuentas->items(),
                'cuentas_contables' => $cuentas_contables
            ],
            'messages' => null
        ]);
    }


    public function actualizar(Request $request)
    {
        $messages = [
            'cuentas.required' => 'Se requiere agregar un producto por lo menos.',
            'cuentas.min' => 'Se requiere agregar un producto por lo menos.',
            'cuentas.*.id_cuenta_contable.required' => 'Seleccione un producto válido',
            'cuentas.*.descripcion_movimiento.required' => 'Se requiere una descripcion del producto',
        ];

        $rules = [

            'cuentas' => 'required|array|min:5',
            'cuentas.*.id_cuenta_activo' => 'required|integer',
            'cuentas.*.cuenta_contable.id_cuenta_contable' => 'required|integer|exists:pgsql.contabilidad.cuentas_contables,id_cuenta_contable',
            'cuentas.*.cuenta_contable_depreciado.id_cuenta_contable' => 'required|integer|exists:pgsql.contabilidad.cuentas_contables,id_cuenta_contable',
            'cuentas.*.descripcion' => 'required|string|max:100',

        ];

        $validator = Validator::make($request->all(), $rules,$messages);
        if (!$validator->fails()) {

            try {

                DB::beginTransaction();

                foreach ($request->cuentas as $cuenta) {
                    $cuentax = Cuentas::find($cuenta['id_cuenta_activo']);
                    $cuentax->id_cuenta_depreciacion = $cuenta['cuenta_contable']['id_cuenta_contable'];
                    $cuentax->id_cuenta_depreciado = $cuenta['cuenta_contable_depreciado']['id_cuenta_contable'];
                    $cuentax->descripcion = $cuenta['descripcion'];
                    $cuentax->save();
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
