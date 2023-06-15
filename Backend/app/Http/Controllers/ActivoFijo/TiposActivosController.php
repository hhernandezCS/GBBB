<?php



namespace App\Http\Controllers\ActivoFijo;

use App\Models\Admon\Departamentos;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\ActivoFijo\TiposActivos;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPJasper\PHPJasper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class TiposActivosController extends Controller
{
    /**
     * Obtener una lista de tipos de activo
     *
     * @access  public
     * @param
     * @return  JsonResponse(array)
     */

    public function obtener(Request $request, TiposActivos $tipos)
    {
        $tipos = $tipos->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $tipos->total(),
                'rows' => $tipos->items()
            ],
            'messages' => null
        ]);
    }

    /**
     * Obtener una lista de tipos de activo sin ningun filtro
     *
     * @access  public
     * @param
     * @return  JsonResponse(array)
     */

    public function obtenerTodos(Request $request, TiposActivos $tiposT)
    {
        $tiposT = TiposActivos::where('activo', 1)->get();
        return response()->json([
            'status' => 'success',
            'result' => $tiposT,
            'messages' => null
        ]);
    }

    /**
     * obtener Tipo activo Especifica
     *
     * @access  public
     * @param
     * @return  JsonResponse(array)
     */

    public function obtenerTipo(Request $request)
    {
        $rules = [
            'id_tipo_activo' => 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $tipo = TiposActivos::find($request->id_tipo_activo);

            if(!empty($tipo)){
                return response()->json([
                    'status' => 'success',
                    'result' => $tipo,
                    'messages' => null
                ]);

            }
            else{
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_tipo_activo'=>["Datos no encontrados"]),
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
     * Crear un nueva direccion
     *
     * @access  public
     * @param
     * @return  JsonResponse(string)
     */

    public function registrar(Request $request)
    {
        $rules = [
            'descripcion' => [
                'required',
                'string',
                'max:50',
                Rule::unique('pgsql.activofijo.tipos_activos')/*->ignore($request->id_tipo_activo,'id_tipo_activo')*/
            ]
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $tipo = new TiposActivos();
            $tipo->descripcion = $request->descripcion;
            $tipo->estado = 1;
            $tipo->u_creacion = Auth::user()->usuario;
            $tipo->save();


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
     * Actualizar Tipo activo existente
     *
     * @access  public
     * @param
     * @return  JsonResponse(string)
     */

    public function actualizar(Request $request)
    {
        $rules = [
            'id_tipo_activo' =>  'required|integer|min:1',
            'descripcion' => [
                'required',
                'string',
                'max:50',
                Rule::unique('pgsql.activofijo.tipos_activos')->ignore($request->id_tipo_activo,'id_tipo_activo')
            ]
        ];


        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $tipo = TiposActivos::find($request->id_tipo_activo);
            $tipo->descripcion = $request->descripcion;
            $tipo->u_modificacion = Auth::user()->usuario;
            $tipo->save();

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
     * Desactiva rol
     *
     * @access  public
     * @param
     * @return  json(string)
     */

    public function desactivar(Request $request)
    {
        $rules = [
            'id_tipo_activo' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $tipo = TiposActivos::find($request->id_tipo_activo);
            $tipo->estado = 0;
            $tipo->u_modificacion = Auth::user()->usuario;
            $tipo->save();

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
            'id_tipo_activo' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $tipo = TiposActivos::find($request->id_tipo_activo);
            $tipo->estado = 1;
            $tipo->u_modificacion = Auth::user()->usuario;
            $tipo->save();

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
