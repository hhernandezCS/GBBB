<?php



namespace App\Http\Controllers\CRM;


use App\Models\Admon\Ajustes;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\CRM\ClasificacionContactos;
use Illuminate\Http\JsonResponse;
use phpDocumentor\Reflection\Types\Object_;
use PHPJasper\PHPJasper;
use Validator,Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule,DB;
use App\Http\Controllers\Controller;
use function Symfony\Component\Translation\t;

class ClasificacionContactosController extends Controller
{
    /**
     * Obtener una lista de servicios
     * @access  public
     * @param Request $request
     * @param ClasificacionContactos $clasificacion_contactos
     * @return JsonResponse
     * @author rsequeira
     */

    public function obtener(Request $request, ClasificacionContactos $clasificacion_contactos)
    {
        $clasificacion_contactos = $clasificacion_contactos->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $clasificacion_contactos->total(),
                'rows' => $clasificacion_contactos->items()
            ],
            'messages' => null
        ]);
    }


    /**
     * obtener servicio
     * @param Request $request
     * @return JsonResponse
     * @author rsequeira
     * @access  public
     */

    public function obtenerClasificacionContacto(Request $request)
    {
        $rules = [
            'id_clasificacion_contacto' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $clasificacion_contactos = ClasificacionContactos::where('id_clasificacion_contacto',$request->id_clasificacion_contacto)->first();
            if(!empty($clasificacion_contactos)){
                return response()->json([
                    'status' => 'success',
                    'result' => $clasificacion_contactos,
                    'messages' => null
                ]);
            }
            else{
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_clasificacion_contacto'=>["Datos no encontrados"]),
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

    public function nuevo(){
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
        $companies = Companias::select('id_compania','nombre_compania')
            ->where('estado',1)
            ->where('id_empresa', $usuario_empresa->id_empresa)
            ->where('u_responsable', Auth::user()->name)
            ->get();
        return response()->json([
            'status' => 'success',
            'result' => [
                'companies' => $companies
            ],
            'messages' => 'null'
        ]);
    }

    /**
     * Crear un nuevo servicio
     * @author rsequeira
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
                'max:50'],
        ];

        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $clasificacion_contactos = new ClasificacionContactos();
            $clasificacion_contactos->descripcion = $request->descripcion;
            $clasificacion_contactos->id_empresa = $usuario_empresa->id_empresa;
            $clasificacion_contactos->estado = 1;
            $clasificacion_contactos->u_creacion = Auth::user()->name;
            $clasificacion_contactos->save();

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
     * Actualizar Tipo contacto
     * @author rsequeira
     * @access  public
     * @param Request $request
     * @return JsonResponse
     */

    public function actualizar(Request $request)
    {
        $rules = [
            'id_clasificacion_contacto' => 'required|integer|min:1',
            'descripcion' =>  [
                'required',
                'string',
                'max:50',
                Rule::unique('pgsql.crm.clasificacion_contactos')->ignore($request->id_clasificacion_contacto,'id_clasificacion_contacto')],
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $clasificacion_contactos = ClasificacionContactos::find($request->id_clasificacion_contacto);
            $clasificacion_contactos->descripcion = $request->descripcion;
            $clasificacion_contactos->u_modificacion = Auth::user()->name;
            $clasificacion_contactos->save();

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
     * @author rsequeira
     * @param Request $request
     * @return JsonResponse
     * @access  public
     */

    public function desactivar(Request $request)
    {
        $rules = [
            'id_clasificacion_contacto' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $clasificacion_contactos = ClasificacionContactos::find($request->id_clasificacion_contacto);
            $clasificacion_contactos->estado = 0;
            $clasificacion_contactos->u_modificacion = Auth::user()->name;
            $clasificacion_contactos->save();

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
     * @author rsequeira
     * @param Request $request
     * @return JsonResponse
     */

    public function activar(Request $request)
    {
        $rules = [
            'id_clasificacion_contacto' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $tipo_contacto = ClasificacionContactos::find($request->id_clasificacion_contacto);
            $tipo_contacto->estado = 1;
            $tipo_contacto->u_modificacion = Auth::user()->name;
            $tipo_contacto->save();

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
