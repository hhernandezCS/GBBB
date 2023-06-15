<?php



namespace App\Http\Controllers\CRM;


use App\Models\Admon\Ajustes;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\CRM\Cargos;
use Illuminate\Http\JsonResponse;
use PHPJasper\PHPJasper;
use Validator,Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule,DB;
use App\Http\Controllers\Controller;
class CargosController extends Controller
{
    /**
     * Obtener una lista de los giros de negocios
     * @access  public
     * @param Request $request
     * @param Cargos $cargos
     * @return JsonResponse
     * @author hgm7
     */

    public function obtener(Request $request, Cargos $cargos)
    {
        $cargos = $cargos->obtenerCargos($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $cargos->total(),
                'rows' => $cargos->items()
            ],
            'messages' => null
        ]);
    }


    /**
     * obtener giro de negocio Especifico
     * @param Request $request
     * @return JsonResponse
     * @author hgm7
     * @access  public
     */

    public function obtenerCargos(Request $request)
    {
        $rules = [
            'id_cargo' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $cargo = Cargos::where('id_cargo',$request->id_cargo)
                ->get();
            if(!empty($cargo[0])){
                return response()->json([
                    'status' => 'success',
                    'result' => $cargo[0],
                    'messages' => null
                ]);
            }
            else{
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_cargo'=>["Datos no encontrados"]),
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
     * Crear un nuevo giro de negocio
     * @author hgm7
     * @access  public
     * @param Request $request
     * @return JsonResponse
     */

    public function registrar(Request $request)
    {
        $rules = [
            'descripcion' =>  [
                'required',
                'string',
                'max:50',
                Rule::unique('pgsql.crm.cargos')],
        ];

        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $cargo = new Cargos();
            $cargo->descripcion = $request->descripcion;
            $cargo->id_empresa = $usuario_empresa->id_empresa;
            $cargo->estado = 1;
            $cargo->u_creacion = Auth::user()->name;
            $cargo->save();

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
     * Actualizar giro de negocio existente
     * @author hgm7
     * @access  public
     * @param Request $request
     * @return JsonResponse
     */

    public function actualizar(Request $request)
    {
        $rules = [
            'id_cargo' => 'required|integer|min:1',
            'descripcion' =>  [
                'required',
                'string',
                'max:50',
                Rule::unique('pgsql.crm.cargos')->ignore($request->id_cargo,'id_cargo')],
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $cargo = Cargos::find($request->id_cargo);
            $cargo->descripcion = $request->descripcion;
            $cargo->u_modificacion = Auth::user()->name;
            $cargo->save();

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
     * Desactivar
     * @author hgm7
     * @param Request $request
     * @return JsonResponse
     * @access  public
     */

    public function desactivar(Request $request)
    {
        $rules = [
            'id_cargo' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $cargo = Cargos::find($request->id_cargo);
            $cargo->estado = 0;
            $cargo->u_modificacion = Auth::user()->name;
            $cargo->save();

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
     * Activar
     * @author hgm7
     * @param Request $request
     * @return JsonResponse
     */

    public function activar(Request $request)
    {
        $rules = [
            'id_cargo' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $cargo = Cargos::find($request->id_cargo);
            $cargo->estado = 1;
            $cargo->u_modificacion = Auth::user()->name;
            $cargo->save();

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
