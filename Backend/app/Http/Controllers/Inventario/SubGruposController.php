<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\Inventario\Marcas;
use App\Models\Inventario\SubGrupos;
use App\Imports\SubGruposImport;
use App\Models\Inventario\UnidadMedida;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Imports\DataImport;
use Maatwebsite\Excel\Facades\Excel;

class SubGruposController extends Controller
{
    public function obtener(Request $request, SubGrupos $subgrupo)
    {
        $subgrupo = $subgrupo->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $subgrupo->total(),
                'rows' => $subgrupo->items()
            ],
            'messages' => null
        ]);
    }
    /**
     * Obtener una todos los SubGrupos
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function obtenerTodos(Request $request, SubGrupos $subgrupo)
    {
        $subgrupo = SubGrupos::where('estado',1)->get();
        return response()->json([
            'status' => 'success',
            'result' =>$subgrupo,
            'messages' => null
        ]);
    }

    public function obtenerSubgrupo(Request $request, SubGrupos $subgrupo)
    {
        $rules = [
            'id_subgrupo' => 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            $subgrupo = $subgrupo->obtenerSubgrupo($request);

            if(!empty($subgrupo[0])){
                return response()->json([
                    'status' => 'success',
                    'result' => $subgrupo[0],
                    'messages' => null
                ]);

            }
            else{
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_subgrupo'=>["Datos no encontrados"]),
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
     * Registrar nuevo grupo
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
                Rule::unique('pgsql.inventario.subgrupos')],
        ];
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $subgrupo = new SubGrupos;
            $subgrupo->codigo = $request->codigo;
            $subgrupo->id_grupo = $request->grupo['id_grupo'];
            $subgrupo->descripcion = $request->descripcion;
            $subgrupo->estado = 1;
            $subgrupo->u_creacion = Auth::user()->name;
            $subgrupo->save();

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
     * Actualizar grupo
     *
     * @access  public
     * @param Request $request
     * @return JsonResponse
     */

    public function actualizar(Request $request)
    {
        $rules = [
            'id_subgrupo' => 'required|integer|min:1',
            'descripcion' => [
                'required',
                'string',
                'max:100',
                Rule::unique('pgsql.inventario.subgrupos')->ignore($request->id_subgrupo,'id_subgrupo')]
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $subgrupo = SubGrupos::find($request->id_subgrupo);
            $subgrupo->descripcion = $request->descripcion;
            $subgrupo->codigo = $request->codigo;
            $subgrupo->id_grupo = $request->grupo['id_grupo'];
            $subgrupo->u_creacion = Auth::user()->name;
            $subgrupo->save();

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
     * Obtener recibos de clientes por proyecto
     * @param Request $request
     * @param SubGrupos $subgrupo
     * @return JsonResponse
     */
    public function obtenerGruposSubgrupos(Request $request, SubGrupos $subgrupo)
    {
        $grupoSubgrupo = $subgrupo->obtenerGruposSubgrupos($request);

        return response()->json([
            'status' => 'success',
            'result' => $grupoSubgrupo,
            'messages' => null
        ]);
    }

}
