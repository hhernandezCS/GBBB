<?php


namespace App\Http\Controllers\CRM;


use App\Models\Admon\Departamentos;
use App\Models\Admon\Estados;
use App\Models\Admon\Paises;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\CRM\Companias;
use App\Models\CRM\Contactos;
use App\Models\CRM\GirosNegocios;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompaniasController extends Controller
{

    /**
     * @param Request $request
     * @param Companias $companias
     * @return JsonResponse
     */
    public function buscar(Request $request, Companias $companias)
    {
        $companias = $companias->buscar($request);
        return response()->json([
            'result' => $companias
        ]);
    }

    /**
     * Obtener una lista de servicios
     * @access  public
     * @param Request $request
     * @param companias $companias
     * @return JsonResponse
     * @author hgm7
     */

    public function obtener(Request $request, Companias $companias)
    {
        $companias = $companias->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $companias->total(),
                'rows' => $companias->items()
            ],
            'messages' => null
        ]);
    }


    /**
     * obtener compañia
     * @param Request $request
     * @return JsonResponse
     * @author hgm7
     * @access  public
     */

    public function obtenerCompania(Request $request)
    {
        $rules = [
            'id_compania' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $compania = Companias::where('id_compania', $request->id_compania)
                ->with('companiaContactos')
                ->with('giroNegocio')
                ->with('paisCompania')
                ->with('departamentoCompania')
                ->with('municipioCompania')
                ->with('estadoCompania')
                ->with('ciudadCompania')
                ->with('responsableProspecto')->first();
            if (!empty($compania)) {
                return response()->json([
                    'status' => 'success',
                    'result' => [
                        'compania' => $compania,
                    ],
                    'messages' => null
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_compania' => ["Datos no encontrados"]),
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

    public function obtenerContacto(Request $request)
    {
        $rules = [
            'id_compania' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $mapId = [];
            $contacto_compania = Companias::select('id_contactos')->where('id_compania', $request->id_compania)->first();
            foreach ($contacto_compania->toArray() as $companias) {
                $mapId = $companias;
            }
            $identificadores_contactos = array_map('intval', explode(',', (string)$mapId));
            $contacto_companias = Contactos::select('id_contacto', DB::raw("CONCAT(crm.contactos.nombre, ' ' ,crm.contactos.apellido) as nombreCompleto"))->whereIn('id_contacto', $identificadores_contactos)->get();

            return response()->json([
                'status' => 'success',
                'result' => [
                    'contactos' => $contacto_companias,
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
        $giro_negocio = GirosNegocios::select('id_giro_negocio', 'descripcion')->get();
        return response()->json([
            'status' => 'success',
            'result' => [
                'giro_negocios' => $giro_negocio,
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
            'nombre_compania' => ['required', 'string', 'max:50'],
            'id_giro_negocio' => ['required'],
            'telefono_compania' => ['required', 'string']
        ];

        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $servicio = new companias();
            $servicio->nombre_compania = $request->nombre_compania;
            $servicio->id_giro_negocio = $request->id_giro_negocio['id_giro_negocio'];
            if (empty($request->id_contacto['id_contacto'])) {
                $servicio->id_contacto = 1;
            } else {
                $servicio->id_contacto = $request->id_contacto['id_contacto'];
            }
            if (empty($request->mapContactos)) {
                $servicio->id_contactos = 1;
            } else {
                $servicio->id_contactos = $request->mapContactos;
            }
            $servicio->telefono_compania = $request->telefono_compania;
            $servicio->email = $request->email;
            $servicio->direccion_compania = $request->direccion_compania;
            $servicio->codigo_postal = $request->codigo_postal;
            $servicio->web_site = $request->web_site;
            $servicio->ubicacion_compania = $request->ubicacion_compania;
            $servicio->zona_servicio = $request->zona_servicio;
            $servicio->servicio_destacado = $request->servicio_destacado;
            $servicio->horario_servicio = $request->horario_servicio;
            $servicio->u_responsable = $request->u_responsable['name'];
            $servicio->id_empresa = $usuario_empresa->id_empresa;
            $servicio->estado = 1;
            $servicio->u_creacion = Auth::user()->name;
            $servicio->id_pais = $request->pais['id_pais'];
            if ($servicio->id_pais === 42) { // solo para Nicaragua
                $servicio->id_departamento = $request->departamento['id_departamento'];
                $servicio->id_municipio = $request->municipio['id_municipio'];
            } else {
                $servicio->id_ciudad = $request->ciudad['id_ciudad'];
                $servicio->id_estado = $request->estado['id_estado'];
            }
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
     * Actualizar servicios
     * @param Request $request
     * @return JsonResponse
     * @author hgm7
     * @access  public
     */

    public function actualizar(Request $request)
    {
        $rules = [
            'nombre_compania' => ['required', 'string', 'max:50'],
        ];

        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $servicio = Companias::find($request->id_compania);
            $servicio->nombre_compania = $request->nombre_compania;
            $servicio->id_giro_negocio = $request->giro_negocio['id_giro_negocio'];
            $servicio->id_contacto = $request->compania_contactos['id_contacto'];
            $servicio->id_contactos = $request->mapContactos;
            $servicio->telefono_compania = $request->telefono_compania;
            $servicio->email = $request->email;
            $servicio->direccion_compania = $request->direccion_compania;
            $servicio->codigo_postal = $request->codigo_postal;
            $servicio->web_site = $request->web_site;
            $servicio->ubicacion_compania = $request->ubicacion_compania;
            $servicio->zona_servicio = $request->zona_servicio;
            $servicio->servicio_destacado = $request->servicio_destacado;
            $servicio->horario_servicio = $request->horario_servicio;
            $servicio->u_responsable = $request->responsable_prospecto['name'];
            $servicio->id_empresa = $usuario_empresa->id_empresa;
            $servicio->estado = 1;
            $servicio->u_modificacion = Auth::user()->name;
            $servicio->id_pais = $request->pais_compania['id_pais'];
            if ($servicio->id_pais === 42) { // solo para Nicaragua
                $servicio->id_departamento = $request->departamento_compania['id_departamento'];
                $servicio->id_municipio = $request->municipio_compania['id_municipio'];
            } else {
                $servicio->id_ciudad = $request->ciudad_compania['id_ciudad'];
                $servicio->id_estado = $request->estado_compania['id_estado'];
            }
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
     * Desactivar
     * @param Request $request
     * @return JsonResponse
     * @access  public
     * @author hgm7
     */

    public function desactivar(Request $request)
    {
        $rules = [
            'id_compania' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $servicio = Companias::find($request->id_compania);
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
            'id_compania' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $servicio = Companias::find($request->id_compania);
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
