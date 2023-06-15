<?php

namespace App\Http\Controllers\Inventario;

use App\Models\Admon\UsuariosEmpresas;
use App\Models\Inventario\Marcas;
use App\Models\Inventario\Grupos;
use App\Models\Inventario\UnidadMedida;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GruposController
{
    public function obtener(Request $request, Grupos $grupo)
    {
        $grupo = $grupo->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $grupo->total(),
                'rows' => $grupo->items()
            ],
            'messages' => null
        ]);
    }
    /**
     * Obtener una todos los rubros
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function obtenerTodos(Request $request, Grupos $grupo)
    {
        $grupo = Grupos::where('estado',1)->get();
        return response()->json([
            'status' => 'success',
            'result' => $grupo,
            'messages' => null
        ]);
    }

    public function obtenerGrupo(Request $request)
    {
        $rules = [
            'id_grupo' => 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $cargo = Grupos::find($request->id_grupo);

            if(!empty($cargo)){
                return response()->json([
                    'status' => 'success',
                    'result' => $cargo,
                    'messages' => null
                ]);

            }
            else{
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_grupo'=>["Datos no encontrados"]),
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
     * Registrar nuevo rubro
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function registrar(Request $request)
    {
        $rules = [

            'descripcion' => [
                'required',
                'string',
                'max:100',
                Rule::unique('pgsql.inventario.grupos')],
        ];
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $grupo = new Grupos;
            $grupo->codigo = $request->codigo;
            $grupo->descripcion = $request->descripcion;
            $grupo->estado = 1;
            $grupo->u_grabacion = Auth::user()->name;
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

    /**
     * Actualizar Rubro
     *
     * @access  public
     * @param Request $request
     * @return JsonResponse
     */

    public function actualizar(Request $request)
    {
        $rules = [
            'id_grupo' => 'required|integer|min:1',
            'descripcion' => [
                'required',
                'string',
                'max:100',
                Rule::unique('pgsql.inventario.grupos')->ignore($request->id_grupo,'id_grupo')]
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $grupo = Grupos::find($request->id_grupo);
            $grupo->descripcion = $request->descripcion;
            $grupo->codigo = $request->codigo;
            $grupo->u_modificacion = Auth::user()->name;
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
