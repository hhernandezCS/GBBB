<?php


namespace App\Http\Controllers\CRM;


use App\Models\Admon\Ajustes;
use App\Models\Admon\Departamentos;
use App\Models\Admon\Estados;
use App\Models\Admon\Paises;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\CRM\Cargos;
use App\Models\CRM\ClasificacionContactos;
use App\Models\CRM\CompaniaContactoDetalle;
use App\Models\CRM\Companias;
use App\Models\CRM\Contactos;
use App\Models\CRM\MediosContacto;
use App\Models\CRM\TipoContactos;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use phpDocumentor\Reflection\Types\Object_;
use PHPJasper\PHPJasper;
use Validator, Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule, DB;
use App\Http\Controllers\Controller;

class ContactosController extends Controller
{
    /**
     * Obtener una lista de servicios
     * @access  public
     * @param Request $request
     * @param Contactos $contactos
     * @return JsonResponse
     * @author hgm7
     */

    public function buscar(Request $request, Contactos $contactos)
    {
        $contactos = $contactos->buscar($request);
        return response()->json([
            'result' => $contactos
        ]);
    }

    public function obtener(Request $request, Contactos $contactos)
    {
        $contactos = $contactos->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $contactos->total(),
                'rows' => $contactos->items()
            ],
            'messages' => null
        ]);
    }

    //Esta funcion sera proximamente para cargar los detalles de los leads
    public function obtenerDetalles(Request $request)
    {
        $rules = [
            'id_contacto' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
        $detalles = CompaniaContactoDetalle::select('url_origen', 'servicios_necesitados', 'detalle_servicio', 'comentario_lead')->where('id_contacto', $request->id_contacto);
        $companias = Companias::select('nombre_compania', 'telefono_compania', 'email')->where('id_compania', $request->id_compania);

            return response()->json([
                'status' => 'success',
                'result' => [
                    'detalles' => $detalles,
                    'companias' => $companias
                ],
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
     * obtener servicio
     * @param Request $request
     * @return JsonResponse
     * @author hgm7
     * @access  public
     */

    public function obtenerContacto(Request $request)
    {
        $rules = [
            'id_contacto' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $contacto = Contactos::where('id_contacto', $request->id_contacto)
                ->with('medioContacto')
                ->with('tipoContacto')
                ->with('clasificacionContacto')
                ->with('paisContacto')
                ->with('departamentoContacto')
                ->with('municipioContacto')
                ->with('estadoContacto')
                ->with('ciudadContacto')
                ->with('cargos')
                ->with('responsableProspecto')->first();
            if (!empty($contacto)) {
                return response()->json([
                    'status' => 'success',
                    'result' => [
                        'contacto' => $contacto,
                    ],
                    'messages' => null
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_contacto' => ["Datos no encontrados"]),
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

    public function obtenerCompania(Request $request)
    {
        $rules = [
            'id_contacto' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $mapId = [];
            $contacto_compania = Contactos::select('id_compania')->where('id_contacto', $request->id_contacto)->first();
            foreach ($contacto_compania->toArray() as $contactos) {
                $mapId = $contactos;
            }
            $identificadores_companias = array_map('intval', explode(',', (string)$mapId));
            $contacto_companias = Companias::select('nombre_compania', 'id_compania')->whereIn('id_compania', $identificadores_companias)->get();

            return response()->json([
                'status' => 'success',
                'result' => [
                    'companias' => $contacto_companias,
                ],
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

    public function nuevo()
    {
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();

        $medio_contacto = MediosContacto::where('estado', 1)
            ->where('id_empresa', $usuario_empresa->id_empresa)
            ->get();
        $tipo_contacto = TipoContactos::where('estado', 1)
            ->where('id_empresa', $usuario_empresa->id_empresa)
            ->get();
        $clasificacion_contacto = ClasificacionContactos::where('estado', 1)
            ->where('id_empresa', $usuario_empresa->id_empresa)
            ->get();
        $cargos = Cargos::where('estado', 1)->get();

        $responsable = User::select('id', 'name')->get();
        return response()->json([
            'status' => 'success',
            'result' => [
                'medio_contacto' => $medio_contacto,
                'tipo_contacto' => $tipo_contacto,
                'clasificacion_contacto' => $clasificacion_contacto,
                'responsable' => $responsable,
                'cargos' => $cargos,
            ],
            'messages' => 'null'
        ]);
    }

    /**
     * Crear un nuevo servicio
     * @param Request $request
     * @return JsonResponse
     * @author hgm7
     * @access  public
     */

    public function registrar(Request $request)
    {
        $rules = [
            'nombre' => ['required', 'string', 'max:50'],
            'apellido' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email:rfc,dns'],
            'telefono_trabajo' => ['required', 'string'],
            'telefono_movil' => ['required', 'string']
        ];

        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $contacto = new Contactos();
            $contacto->nombre = $request->nombre;
            $contacto->apellido = $request->apellido;
            $contacto->email = $request->email;
            $contacto->telefono_trabajo = $request->telefono_trabajo;
            $contacto->telefono_movil = $request->telefono_movil;
            $contacto->id_empresa = $usuario_empresa->id_empresa;
            $contacto->estado = 1;
            if(empty($request->mapCompanies) ){
                $contacto->id_compania = 1;
            }else{
            $contacto->id_compania = $request->mapCompanies;
            }
            $contacto->direccion = $request->direccion;
            $contacto->codigo_postal = $request->codigo_postal;
            $contacto->id_medio_contacto = $request->medio_contacto['id_medio_contacto'];
            $contacto->id_tipo_contacto = $request->tipo_contacto['id_tipo_contacto'];
            $contacto->id_clasificacion_contacto = $request->clasificacion_contacto['id_clasificacion_contacto'];
            $contacto->u_responsable = Auth::user()->name;
            $contacto->id_pais = $request->pais['id_pais'];
            if ($contacto->id_pais === 42) { // solo para Nicaragua
                $contacto->id_departamento = $request->departamento['id_departamento'];
                $contacto->id_municipio = $request->municipio['id_municipio'];
            } else {
                $contacto->id_ciudad = $request->ciudad['id_ciudad'];
                $contacto->id_estado = $request->estado['id_estado'];
            }
            $contacto->id_cargo = $request->cargo['id_cargo'];
            $contacto->u_creacion = Auth::user()->name;
            $contacto->save();

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
     * Actualizar servicios
     * @param Request $request
     * @return JsonResponse
     * @author hgm7
     * @access  public
     */

    public function actualizar(Request $request)
    {
        $rules = [
            'nombre' => ['required', 'string', 'max:50'],
            'apellido' => ['required', 'string', 'max:50'],
            'email' => ['required'],
            'telefono_trabajo' => ['required', 'string'],
            'telefono_movil' => ['required', 'string']
        ];

        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $contacto = Contactos::find($request->id_contacto);
            $contacto->nombre = $request->nombre;
            $contacto->apellido = $request->apellido;
            $contacto->email = $request->email;
            $contacto->telefono_trabajo = $request->telefono_trabajo;
            $contacto->telefono_movil = $request->telefono_movil;
            $contacto->id_empresa = $usuario_empresa->id_empresa;
            $contacto->estado = 1;
            $contacto->id_compania = $request->mapCompanies;
            $contacto->direccion = $request->direccion;
            $contacto->codigo_postal = $request->codigo_postal;
            $contacto->id_medio_contacto = $request->medio_contacto['id_medio_contacto'];
            $contacto->id_tipo_contacto = $request->tipo_contacto['id_tipo_contacto'];
            $contacto->id_clasificacion_contacto = $request->clasificacion_contacto['id_clasificacion_contacto'];
            $contacto->u_responsable = $request->responsable_prospecto['name'];
            $contacto->id_pais = $request->pais_contacto['id_pais'];
            if ($contacto->id_pais === 42) { // solo para Nicaragua
                $contacto->id_departamento = $request->departamento_contacto['id_departamento'];
                $contacto->id_municipio = $request->municipio_contacto['id_municipio'];
            } else {
                $contacto->id_ciudad = $request->ciudad_contacto['id_ciudad'];
                $contacto->id_estado = $request->estado_contacto['id_estado'];
            }
            $contacto->id_cargo = $request->cargos['id_cargo'];
            $contacto->save();

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
     * @param Request $request
     * @return JsonResponse
     * @access  public
     * @author hgm7
     */

    public function desactivar(Request $request)
    {
        $rules = [
            'id_contacto' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $servicio = Contactos::find($request->id_contacto);
            $servicio->estado = 0;
            $servicio->u_modificacion = Auth::user()->name;
            $servicio->save();

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
     * @param Request $request
     * @return JsonResponse
     * @author hgm7
     */

    public function activar(Request $request)
    {
        $rules = [
            'id_contacto' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $servicio = Contactos::find($request->id_contacto);
            $servicio->estado = 1;
            $servicio->u_modificacion = Auth::user()->name;
            $servicio->save();

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
