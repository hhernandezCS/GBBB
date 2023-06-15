<?php



namespace App\Http\Controllers\ActivoFijo;

use App\Models\Admon\Departamentos;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\ActivoFijo\Colores;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPJasper\PHPJasper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class ColoresController extends Controller
{
    /**
     * Obtener una lista de tipos de activo
     *
     * @access  public
     * @param
     * @return  json(array)
     */

    public function obtener(Request $request, Colores $colores)
    {
        try{
            $colores = $colores->obtener($request);
            return response()->json([
                'status' => 'success',
                'result' => [
                    'total' => $colores->total(),
                    'rows' => $colores->items()
                ],
                'messages' => null
            ]);
        }catch (TokenMismatchException $ex){
            return response()->json([
                'status' => 'error',
                'result' => null,
                'messages' => $ex
            ]);
        }
    }

    /**
     * Obtener una lista de tipos de activo sin ningun filtro
     *
     * @access  public
     * @param
     * @return  json(array)
     */

    public function obtenerTodos(Request $request, Colores $colores)
    {
        $colores = ActivoFijoColores::where('activo', 1)->get();
        return response()->json([
            'status' => 'success',
            'result' => $colores,
            'messages' => null
        ]);
    }

    /**
     * obtener Color Especifico
     *
     * @access  public
     * @param
     * @return  json(array)
     */

    public function obtenerColor(Request $request)
    {
        $rules = [
            'id_color' => 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $color = Colores::find($request->id_color);

            if(!empty($color)){
                return response()->json([
                    'status' => 'success',
                    'result' => $color,
                    'messages' => null
                ]);

            }
            else{
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_color'=>["Datos no encontrados"]),
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
     * @return  json(string)
     */

    public function registrar(Request $request)
    {
        $rules = [
            'descripcion' => [
                'required',
                'string',
                'max:50',
                Rule::unique('pgsql.activofijo.colores')/*->ignore($request->id_color,'id_color')*/
            ]
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $color = new Colores;
            $color->descripcion = $request->descripcion;
            $color->estado = 1;
            $color->u_grabacion = Auth::user()->usuario;
            $color->save();


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
     * Actualizar Color existente
     *
     * @access  public
     * @param
     * @return  json(string)
     */

    public function actualizar(Request $request)
    {
        $rules = [
            'id_color' =>  'required|integer|min:1',
            'descripcion' => [
                'required',
                'string',
                'max:50',
                Rule::unique('pgsql.activofijo.colores')->ignore($request->id_color,'id_color')
            ]
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $color = Colores::find($request->id_color);
            $color->descripcion = $request->descripcion;
            $color->u_modificacion = Auth::user()->usuario;
            $color->save();

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
            'id_color' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $color = Colores::find($request->id_color);
            $color->estado = 0;
            $color->u_modificacion = Auth::user()->usuario;
            $color->save();

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
            'id_color' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $color = Colores::find($request->id_color);
            $color->estado = 1;
            $color->u_modificacion = Auth::user()->usuario;
            $color->save();

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
