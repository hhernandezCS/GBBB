<?php



namespace App\Http\Controllers\ActivoFijo;

use App\Models\Admon\Departamentos;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\ActivoFijo\Propietarios;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPJasper\PHPJasper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class PropietariosController extends Controller
{
    /**
     * Obtener una lista de propietarios
     *
     * @access  public
     * @param
     * @return  JsonResponse(array)
     */

    public function obtener(Request $request, Propietarios $propietarios)
    {
        $propietarios = $propietarios->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $propietarios->total(),
                'rows' => $propietarios->items()
            ],
            'messages' => null
        ]);
    }

    /**
     * Obtener una lista de propietarios sin ningun filtro
     *
     * @access  public
     * @param
     * @return  JsonResponse(array)
     */

    public function obtenerTodos(Request $request, Propietarios $propietariosT)
    {
        $propietariosT = Propietarios::where('estado', 1)->get();
        return response()->json([
            'status' => 'success',
            'result' => $propietariosT,
            'messages' => null
        ]);
    }

    /**
     * obtener propietario Especifico
     *
     * @access  public
     * @param
     * @return  JsonResponse(array)
     */

    public function obtenerPropietario(Request $request)
    {
        $rules = [
            'id_propietario' => 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $propietario = Propietarios::find($request->id_propietario);

            if(!empty($propietario)){
                return response()->json([
                    'status' => 'success',
                    'result' => $propietario,
                    'messages' => null
                ]);

            }
            else{
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_propietario'=>["Datos no encontrados"]),
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
     * Crear un nuevo propietario
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
                'max:100',
                Rule::unique('pgsql.activofijo.propietarios')/*->ignore($request->id_propietario,'id_propietario')*/
            ]
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $propietario = new Propietarios;
            // $propietario->id_propietario = $request->id_propietario;
            $propietario->descripcion = $request->descripcion;
            $propietario->estado = 1;
            $propietario->u_creacion = Auth::user()->usuario;
            $propietario->save();


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
     * Actualizar propietario existente
     *
     * @access  public
     * @param
     * @return  JsonResponse(string)
     */

    public function actualizar(Request $request)
    {
        $rules = [
            'id_propietario' =>  'required|integer|min:1',
            'descripcion' => [
                'required',
                'string',
                'max:100',
                Rule::unique('pgsql.activofijo.propietarios')->ignore($request->id_propietario,'id_propietario')
            ]
        ];


        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $propietario = Propietarios::find($request->id_propietario);
            //  $propietario->id_propietario = $request->id_propietario;
            $propietario->descripcion = $request->descripcion;
            $propietario->u_modificacion = Auth::user()->usuario;
            $propietario->save();

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
     * Desactiva propietario
     *
     * @access  public
     * @param
     * @return  JsonResponse(string)
     */

    public function desactivar(Request $request)
    {
        $rules = [
            'id_propietario' =>  'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $propietario = Propietarios::find($request->id_propietario);
            $propietario->estado = 0;
            $propietario->u_modificacion = Auth::user()->usuario;
            $propietario->save();

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
     * Activa propietario
     *
     * @access  public
     * @param
     * @return  JsonResponse(string)
     */
    public function activar(Request $request)
    {
        $rules = [
            'id_propietario' =>  'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $propietario = Propietarios::find($request->id_propietario);
            $propietario->estado = 1;
            $propietario->u_modificacion = Auth::user()->usuario;
            $propietario->save();

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
