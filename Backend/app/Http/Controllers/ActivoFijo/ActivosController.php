<?php



namespace App\Http\Controllers\ActivoFijo;

use App\Models\ActivoFijo\Activos;
use App\Models\ActivoFijo\ActivosHistoricos;
use App\Models\ActivoFijo\Cierres;
use App\Models\ActivoFijo\Colores;
use App\Models\ActivoFijo\Cuentas;
use App\Models\ActivoFijo\Depreciaciones;
use App\Models\ActivoFijo\Propietarios;
use App\Models\ActivoFijo\TiposActivos;
use App\Models\ActivoFijo\Traslados;
use App\Models\ActivoFijo\Vehiculos;
use App\Models\ActivoFijo\VehiculosHistoricos;
use App\Models\Admon\Departamentos;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\Admon\Ajustes;
use App\Models\Contabilidad\CentrosCostosIngresos;
use App\Models\Contabilidad\DocumentosContables;
use App\Models\Contabilidad\DocumentosCuentas;
use App\Models\Contabilidad\PeriodosFiscales;
use App\Models\Contabilidad\PeriodosMeses;
use App\Models\Contabilidad\TiposDocumentos;
use App\Models\Admon\Areas;
use App\Models\Admon\Sucursales;
use App\Models\RRHH\RRHHTrabajadores;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPJasper\PHPJasper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class ActivosController extends Controller
{
    public function obtenerCierres(Request $request, Cierres $activos)
    {
        $activos = $activos->obtenerCierres($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $activos->total(),
                'rows' => $activos->items()
            ],
            'messages' => null
        ]);
    }

    public function obtener(Request $request, Activos $activos)
    {
        $activos = $activos->obtenerActivos($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $activos->total(),
                'rows' => $activos->items()
            ],
            'messages' => null
        ]);
    }

    public function obtenerSolicitudAutorizacion(Request $request, Activos $activos)
    {
        $activos = $activos->obtenerSolicitudAutorizacion($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $activos->total(),
                'rows' => $activos->items()
            ],
            'messages' => null
        ]);
    }

    public function obtenerActivosTrabajador(Request $request, Activos $activos)
    {
        $activos = $activos->obtenerActivosTrabajador($request);
        return response()->json([
            'status' => 'success',
            'result' => $activos,
            'messages' => null
        ]);
    }

    public function obtenerActivosSucursal(Request $request, Activos $activos)
    {
        $activos = $activos->obtenerActivosSucursal($request);
        return response()->json([
            'status' => 'success',
            'result' => $activos,
            'messages' => null
        ]);
    }


    public function obtenerTodas(Request $request, Activos $activos)
    {
        $activos = Activos::select('id_activo','id_sucursal','codigo','descripcion','id_area','id_trabajador','marca',
            'serie','fecha_adquisicion','valor_adquisicion','depreciacion_mensual','depreciacion_acumulada','valor_libro','id_fondo','estado',
            'id_grupo_activo','id_tipo_activo','meses_depreciarse','mes_depreciado','anio_depreciado','id_color','activo')->where('estado', 1)->get();
        return response()->json([
            'status' => 'success',
            'result' => $activos,
            'messages' => null
        ]);
    }

    public function obtenerActivoFijo(Request $request)
    {
        $rules = [
            'id_activo' => 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $activo = Activos::select('id_activo','id_sucursal','id_centro','codigo','descripcion','id_area','id_trabajador','marca',
                'serie','fecha_adquisicion','valor_adquisicion','depreciacion_mensual','depreciacion_acumulada','valor_libro','id_fondo','estado',
                'id_grupo_activo','id_tipo_activo','meses_depreciarse','mes_depreciado','anio_depreciado','id_color','activo','id_propietario','vida_util_politica',
                'depreciacion_mensual_politica','depreciacion_acumulada_politica','valor_libro_politica','mes_depreciado_politica','es_capitalizable','condicion','modelo','observacion')
                ->where('id_activo',$request->id_activo)
                ->with('activoSucursal')
                ->with('activoArea')
                ->with('activoTrabajador')
                ->with('activoTipoActivo')
                ->with('activoPropietario')
                ->with('activoGrupo')
                ->with('activoColor')
                ->with('activoDetalleVehiculo')
                ->with('activoCentro')
                ->first();

            $sucursales = Sucursales::select(['id_sucursal','descripcion'])->get();
            $centros = CentroCostoIngreso::select(['id_centro','descripcion'])->where('estado',1)->get();
            $areas = Areas::select(['id_area','descripcion'])->with('trabajadoresArea')->get();
            $trabajadores = RRHHTrabajadores::select('id_trabajador','primer_apellido','primer_nombre','segundo_apellido','segundo_nombre','codigo',DB::raw("CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido) AS nombre_completo"))->get();
            //$grupos = Grupo::select(['id_grupo_activo','descripcion'])->get();
            $tipos = TiposActivos::select(['id_tipo_activo','descripcion'])->with('gruposActivos')->get();
            $colores = Colores::select(['id_color','descripcion'])->get();
            $propietarios = Propietarios::select(['id_propietario','descripcion'])->get();

            if(!empty($activo)){
                return response()->json([
                    'status' => 'success',
                    'result' => [
                        'activo' => $activo,
                        'sucursales' => $sucursales,
                        'centros' => $centros,
                        'areas' => $areas,
                        'trabajadores' => $trabajadores,
                        //'grupos' => $grupos,
                        'tipos' => $tipos,
                        'colores' => $colores,
                        'propietarios' => $propietarios,
                    ],
                    'messages' => null
                ]);
            }
            else{
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_activo'=>["Datos no encontrados"]),
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

    public function registrar(Request $request)
    {
        $rules = [
            //'activo_sucursal' => 'required|array|min:1',
            //'activo_sucursal.id_sucursal' => 'required|integer|min:1',

            'centro_costo' => 'required|array|min:1',
            'centro_costo.id_centro' => 'required|integer|min:1',

            'activo_area' => 'required|array|min:1',
            'activo_area.id_area' => 'required|integer|min:1',

            'activo_trabajador' => 'required|array|min:1',
            'activo_trabajador.id_trabajador' => 'required|integer|min:1',

            'activo_grupo' => 'required|array|min:1',
            'activo_grupo.id_grupo_activo' => 'required|integer|min:1',

            'activo_tipo' => 'required|array|min:1',
            'activo_tipo.id_tipo_activo' => 'required|integer|min:1',

            'activo_color' => 'required|array|min:1',
            'activo_color.id_color' => 'required|integer|min:1',

            //'activo_propietario' => 'required|array|min:1',
            //'activo_propietario.id_propietario' => 'required|integer|min:1',



            'placa'=> 'required_if:activo_tipo.id_tipo_activo,3|string|max:15|nullable',
            'chasis'=> 'required_if:activo_tipo.id_tipo_activo,3|string|max:20|nullable',
            'numero_motor'=> 'required_if:activo_tipo.id_tipo_activo,3|string|max:20|nullable',
            'id_tipo_combustible' => 'required_if:activo_tipo.id_tipo_activo,3|integer|min:1|nullable',
            'cilindro' => 'required_if:activo_tipo.id_tipo_activo,3|integer|min:1|nullable',
            'anio' => 'required_if:activo_tipo.id_tipo_activo,3|integer|min:1|nullable',
            'capacidad_carga' => 'required_if:activo_tipo.id_tipo_activo,3||numeric|regex:/^\d*(\.\d{1,6})?$/|nullable',
            'pasajeros' => 'required_if:activo_tipo.id_tipo_activo,3|integer|min:1|nullable',


            /*'codigo' => [
                'required',
                'string',
                'max:20',
                Rule::unique('pgsql.activofijo.activos')->ignore($request->id_activo,'id_activo')
            ],*/

            'descripcion' => [
                'required',
                'string',
                'max:100',
                //Rule::unique('pgsql.activofijo.activos')/*->ignore($request->id_activo,'id_activo')*/
            ],

            'marca' => [
                'required',
                'string',
                'max:50',
            ],
            'serie' => [
                //'required',
                'string',
                'max:50',
                //Rule::unique('pgsql.activofijo.activos')/*->ignore($request->id_activo,'id_activo')*/
            ],

            'valor_adquisicion' => 'numeric|regex:/^\d*(\.\d{1,2})?$/|min:0',
            'depreciacion_mensual' => 'numeric|regex:/^\d*(\.\d{1,2})?$/|min:0',
            //'depreciacion_mensual_politica' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/|min:0',
            'depreciacion_acumulada' => 'numeric|regex:/^\d*(\.\d{1,2})?$/|min:0',
            //'depreciacion_acumulada_politica' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/|min:0',
            'valor_libro' => 'numeric|regex:/^\d*(\.\d{1,2})?$/|min:0',
            //'valor_libro_politica' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/|min:0',

            'meses_depreciarse' => 'integer|min:0',
            //'vida_util_politica' => 'required|integer|min:0',
            'mes_depreciado' => 'numeric|min:0',
            //'mes_depreciado_politica' => 'required|integer|min:0',
            'anio_depreciado' => 'required|integer|min:0',

            'fecha_adquisicion' => 'required|date',
            'es_capitalizable' => 'boolean',

        ];


        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $activo = new Activos;

            //$activo->id_sucursal = $request->activo_sucursal['id_sucursal'];
            $activo->id_sucursal = 1;
            $activo->id_area = $request->activo_area['id_area'];
            $activo->id_trabajador = $request->activo_trabajador['id_trabajador'];
            $activo->id_grupo_activo = $request->activo_grupo['id_grupo_activo'];
            $activo->id_tipo_activo = $request->activo_tipo['id_tipo_activo'];
            //$activo->id_propietario = $request->activo_propietario['id_propietario'];
            $activo->id_color = $request->activo_color['id_color'];
            $activo->es_capitalizable = $request->es_capitalizable;
            $activo->modelo = $request->modelo;
            $activo->condicion = $request->condicion;
            $activo->observacion = $request->observacion;
            $activo->id_centro = $request->centro_costo['id_centro'];

            $codigo_nuevo = $activo->obtenerCodigoActivo($activo->id_tipo_activo,$activo->id_grupo_activo);

            $str_length = 4;
            $str = substr("000{$codigo_nuevo['secuencia']}", -$str_length);
            $activo->secuencia = $codigo_nuevo['secuencia'];
            if($activo->es_capitalizable == true)
            {
                $cod_capitalizable = 1;
            }else
            {
                $cod_capitalizable = 2;
            }

            //$activo->codigo = substr("00{$activo->id_tipo_activo}",-3).substr("00{$cod_capitalizable}",-3).$str;
            $activo->codigo = $activo->id_tipo_activo.$cod_capitalizable.$str;

            //$activo->codigo = $request->codigo;
            $activo->descripcion = $request->descripcion;
            $activo->marca = $request->marca;
            $activo->serie = $request->serie;

            $activo->valor_adquisicion = $request->valor_adquisicion;
            if($activo->id_tipo_activo!=1){

                $activo->depreciacion_mensual = $request->depreciacion_mensual;
                $activo->depreciacion_acumulada = $request->depreciacion_acumulada;
                $activo->valor_libro = $request->valor_libro;
                $activo->meses_depreciarse = $request->meses_depreciarse;
                $activo->mes_depreciado = $request->mes_depreciado;
                $activo->anio_depreciado = $request->anio_depreciado;

                $activo->vida_util_politica = $request->vida_util_politica;
                $activo->depreciacion_mensual_politica = $request->depreciacion_mensual_politica;
                $activo->depreciacion_acumulada_politica = $request->depreciacion_acumulada_politica;
                $activo->valor_libro_politica = $request->valor_libro_politica;
                $activo->mes_depreciado_politica = $request->mes_depreciado_politica;

            }else{

                $activo->depreciacion_mensual = 0;
                $activo->depreciacion_acumulada = 0;
                $activo->valor_libro = $request->valor_adquisicion;
                $activo->meses_depreciarse = 0;
                $activo->mes_depreciado = 0;
                $activo->anio_depreciado = 0;
            }

            $activo->fecha_adquisicion = $request->fecha_adquisicion;

            $activo->id_cuenta_depreciacion = $request->id_cuenta_depreciacion;
            $activo->id_cuenta_depreciado = $request->id_cuenta_depreciado;
            $activo->id_fondo = $request->id_fondo;

            $activo->activo = 1;
            $activo->estado = 1;
            $activo->u_grabacion = Auth::user()->usuario;
            $activo->save();

            if($activo->id_tipo_activo==3){
                $detalles_vehiculo = new Vehiculos();
                $detalles_vehiculo->placa = $request->placa;
                $detalles_vehiculo->color = $request->color;
                $detalles_vehiculo->chasis = $request->chasis;
                $detalles_vehiculo->numero_motor = $request->numero_motor;
                $detalles_vehiculo->id_tipo_combustible = $request->id_tipo_combustible;
                $detalles_vehiculo->cilindro = $request->cilindro;
                $detalles_vehiculo->anio = $request->anio;
                $detalles_vehiculo->color = $activo->id_color;
                $detalles_vehiculo->id_activo = $activo->id_activo;
                $detalles_vehiculo->capacidad_carga = $request->capacidad_carga;
                $detalles_vehiculo->pasajeros = $request->pasajeros;
                $detalles_vehiculo->save();
            }


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


    public function actualizar(Request $request)
    {   // 'descripcion' => 'required|string|max:100|unique:pgsql.admon.roles,descripcion',
        $rules = [
            'id_activo' => 'required|integer',

            //'activo_sucursal' => 'required|array|min:1',
            //'activo_sucursal.id_sucursal' => 'required|integer|min:1',


            // 'centro_costo' => 'required|array|min:1',
            // 'centro_costo.id_centro' => 'required|integer|min:1',

            'activo_area' => 'required|array|min:1',
            'activo_area.id_area' => 'required|integer|min:1',

            'activo_trabajador' => 'required|array|min:1',
            'activo_trabajador.id_trabajador' => 'required|integer|min:1',

            'activo_grupo' => 'required|array|min:1',
            'activo_grupo.id_grupo_activo' => 'required|integer|min:1',

            'activo_tipo_activo' => 'required|array|min:1',
            'activo_tipo_activo.id_tipo_activo' => 'required|integer|min:1',

            'activo_color' => 'required|array|min:1',
            'activo_color.id_color' => 'required|integer|min:1',

            //'activo_propietario' => 'required|array|min:1',
            //'activo_propietario.id_propietario' => 'required|integer|min:1',

            'codigo' => [
                'required',
                'string',
                'max:20',
                Rule::unique('pgsql.activofijo.activos')->ignore($request->id_activo,'id_activo')
            ],

            'descripcion' => [
                'required',
                'string',
                'max:100',
                //Rule::unique('pgsql.activofijo.activos')->ignore($request->id_activo,'id_activo')
            ],

            'marca' => [
                'required',
                'string',
                'max:50',
            ],
            'serie' => [
                'required',
                'string',
                'max:50',
                //Rule::unique('pgsql.activofijo.activos')->ignore($request->id_activo,'id_activo')
            ],


            'activo_detalle_vehiculo' => 'required_if:activo_tipo.id_tipo_activo,3|array|min:1|nullable',
            'activo_detalle_vehiculo.placa'=> 'required_if:activo_tipo.id_tipo_activo,3|string|max:15|nullable',
            'activo_detalle_vehiculo.chasis'=> 'required_if:activo_tipo.id_tipo_activo,3|string|max:20|nullable',
            'activo_detalle_vehiculo.numero_motor'=> 'required_if:activo_tipo.id_tipo_activo,3|string|max:20|nullable',
            'activo_detalle_vehiculo.id_tipo_combustible' => 'required_if:activo_tipo.id_tipo_activo,3|integer|min:1|nullable',
            'activo_detalle_vehiculo.cilindro' => 'required_if:activo_tipo.id_tipo_activo,3|integer|min:1|nullable',
            'activo_detalle_vehiculo.anio' => 'required_if:activo_tipo.id_tipo_activo,3|integer|min:1|nullable',
            'activo_detalle_vehiculo.capacidad_carga' => 'required_if:activo_tipo.id_tipo_activo,3||numeric|regex:/^\d*(\.\d{1,6})?$/|nullable',
            'activo_detalle_vehiculo.pasajeros' => 'required_if:activo_tipo.id_tipo_activo,3|integer|min:1|nullable',

            'valor_adquisicion' => 'numeric|regex:/^\d*(\.\d{1,2})?$/|min:0',
            'depreciacion_mensual' => 'numeric|regex:/^\d*(\.\d{1,2})?$/|min:0',
            //'depreciacion_mensual_politica' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/|min:0',
            'depreciacion_acumulada' => 'numeric|regex:/^\d*(\.\d{1,2})?$/|min:0',
            //'depreciacion_acumulada_politica' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/|min:0',
            'valor_libro' => 'numeric|regex:/^\d*(\.\d{1,2})?$/|min:0',
            //'valor_libro_politica' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/|min:0',

            'meses_depreciarse' => 'numeric|min:0',
            //'vida_util_politica' => 'required|integer|min:0',
            'mes_depreciado' => 'numeric|min:0',
            //'mes_depreciado_politica' => 'required|integer|min:0',
            'anio_depreciado' => 'required|integer|min:0',

            'fecha_adquisicion' => 'required|date',
            'es_capitalizable' => 'boolean',

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            try{
                DB::beginTransaction();

                $activo = Activos::find($request->id_activo);

                //$activo->id_sucursal = $request->activo_sucursal['id_sucursal'];
                //$activo->id_centro = $request->activo_centro['id_centro'];
                //$activo->id_area = $request->activo_area['id_area'];
                //$activo->id_trabajador = $request->activo_trabajador['id_trabajador'];
                //$activo->id_grupo_activo = $request->activo_grupo['id_grupo_activo'];
                //$activo->id_tipo_activo = $request->activo_tipo['id_tipo_activo'];
                $activo->id_color = $request->activo_color['id_color'];
                //$activo->id_propietario = $request->activo_propietario['id_propietario'];

                //$activo->codigo = $request->codigo;
                $activo->descripcion = $request->descripcion;
                $activo->marca = $request->marca;
                $activo->serie = $request->serie;
                $activo->modelo = $request->modelo;
                $activo->condicion = $request->condicion;
                $activo->observacion = $request->observacion;
                $activo->es_capitalizable = $request->es_capitalizable;

                //$activo->valor_adquisicion = $request->valor_adquisicion;
                //$activo->depreciacion_mensual = $request->depreciacion_mensual;
                //$activo->depreciacion_acumulada = $request->depreciacion_acumulada;
                //$activo->valor_libro = $request->valor_libro;

                //$activo->meses_depreciarse = $request->meses_depreciarse;
                //$activo->mes_depreciado = $request->mes_depreciado;
                //$activo->anio_depreciado = $request->anio_depreciado;
                //$activo->fecha_adquisicion = $request->fecha_adquisicion;

                //$activo->id_cuenta_depreciacion = $request->id_cuenta_depreciacion;
                //$activo->id_cuenta_depreciado = $request->id_cuenta_depreciado;
                //$activo->id_fondo = $request->id_fondo;
                $activo->save();


                if($activo->id_tipo_activo==3){
                    $detalles_vehiculo = Vehiculos::find($request->activo_detalle_vehiculo['id_vehiculo']);
                    $detalles_vehiculo->placa = $request->activo_detalle_vehiculo['placa'];
                    $detalles_vehiculo->color = $request->activo_detalle_vehiculo['color'];
                    $detalles_vehiculo->chasis = $request->activo_detalle_vehiculo['chasis'];
                    $detalles_vehiculo->numero_motor = $request->activo_detalle_vehiculo['numero_motor'];
                    $detalles_vehiculo->id_tipo_combustible = $request->activo_detalle_vehiculo['id_tipo_combustible'];
                    $detalles_vehiculo->cilindro = $request->activo_detalle_vehiculo['cilindro'];
                    $detalles_vehiculo->anio = $request->activo_detalle_vehiculo['anio'];
                    $detalles_vehiculo->capacidad_carga = $request->activo_detalle_vehiculo['capacidad_carga'];
                    $detalles_vehiculo->pasajeros = $request->activo_detalle_vehiculo['pasajeros'];
                    $detalles_vehiculo->save();
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


    public function reasignar(Request $request)
    {   // 'descripcion' => 'required|string|max:100|unique:pgsql.admon.roles,descripcion',
        $rules = [
            'id_activo' => 'required|integer',

            'activo_area_destino' => 'required|array|min:1',
            'activo_area_destino.id_area' => 'required|integer|min:1',

            'activo_trabajador_destino' => 'required|array|min:1',
            'activo_trabajador_destino.id_trabajador' => 'required|integer|min:1',

            //'fecha_traslado' => 'required|date',

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            try{
                DB::beginTransaction();

                $activo = Activos::find($request->id_activo);

                $traslado =  new Traslados();

                $traslado->id_activo = $activo->id_activo;
                $traslado->id_area_origen = $activo->id_area;
                $traslado->id_area_destino = $request->activo_area_destino['id_area'];
                $traslado->id_trabajador_origen = $activo->id_trabajador;
                $traslado->id_trabajador_destino = $request->activo_trabajador_destino['id_trabajador'];
                $traslado->fecha_traslado = date('Y-m-d H:i:s');
                $traslado->mes = 0;
                $traslado->anio = 0;
                $traslado->u_creacion = Auth::user()->usuario;
                $traslado->f_creacion = date('Y-m-d H:i:s');
                $traslado->id_cuenta_depreciacion =  $activo->id_cuenta_depreciacion;
                $traslado->estado = 1;
                $traslado->save();

                $activo->id_area = $request->activo_area_destino['id_area'];
                $activo->id_trabajador = $request->activo_trabajador_destino['id_trabajador'];
                //$activo->fecha_adquisicion = $request->fecha_adquisicion;

                $activo->save();

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
    public function revaluar(Request $request)
    {
        $rules = [
            'id_activo' => 'required|integer',
            'vida_util_adicional' => 'required|integer',
            'depreciacion_mensual_revaluada' => 'required|numeric',
            'depreciacion_acumulada_revaluada' => 'required|numeric',
            'valor_libro_revaluado' => 'required|numeric',



        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            try{
                DB::beginTransaction();

                $activo = Activos::find($request->id_activo);

                $activo_historico =  new ActivosHistoricos();

                $activo_historico->id_activo = $request->id_activo;
                $activo_historico->id_sucursal = $request->activo_sucursal['id_sucursal'];
                $activo_historico->id_area = $request->activo_area['id_area'];
                $activo_historico->id_trabajador = $request->activo_trabajador['id_trabajador'];
                $activo_historico->id_grupo_activo = $request->activo_grupo['id_grupo_activo'];
                $activo_historico->id_tipo_activo = $request->activo_tipo_activo['id_tipo_activo'];
                $activo_historico->id_color = $request->activo_color['id_color'];
                $activo_historico->id_propietario = $request->id_propietario;



                $activo_historico->codigo = $request->codigo ;

                $activo_historico->codigo = $request->codigo;
                $activo_historico->descripcion = $request->descripcion;
                $activo_historico->marca = $request->marca;
                $activo_historico->serie = $request->serie;
                $activo->es_capitalizable = $request->es_capitalizable;

                $activo_historico->valor_adquisicion = $request->valor_adquisicion;
                $activo_historico->depreciacion_mensual = $request->depreciacion_mensual;
                $activo_historico->depreciacion_acumulada = $request->depreciacion_acumulada;
                $activo_historico->valor_libro = $request->valor_libro;

                $activo_historico->meses_depreciarse = $request->meses_depreciarse;
                $activo_historico->mes_depreciado = $request->mes_depreciado;
                $activo_historico->anio_depreciado = $request->anio_depreciado;
                $activo_historico->fecha_adquisicion = $request->fecha_adquisicion;

                $activo_historico->id_cuenta_depreciacion = $request->id_cuenta_depreciacion;
                $activo_historico->id_cuenta_depreciado = $request->id_cuenta_depreciado;
                $activo_historico->id_fondo = $request->id_fondo;

                $activo_historico->vida_util_politica = $request->vida_util_politica;
                $activo_historico->depreciacion_mensual_politica = $request->depreciacion_mensual_politica;
                $activo_historico->depreciacion_acumulada_politica = $request->depreciacion_acumulada_politica;
                $activo_historico->valor_libro_politica = $request->valor_libro_politica;
                $activo_historico->mes_depreciado_politica = $request->mes_depreciado_politica;

                $activo_historico->valor_activo_revaluado = $request->valor_revaluado;
                $activo_historico->meses_vida_revaluacion = $request->vida_util_adicional;
                $activo_historico->depreciacion_mensual_revaluada = $request->depreciacion_mensual_revaluada;
                $activo_historico->depreciacion_acumulada_revaluada = $request->depreciacion_acumulada_revaluada;
                $activo_historico->valor_libro_revaluado = $request->valor_libro_revaluado;

                $activo_historico->activo = 1;
                $activo_historico->estado = 1;
                $activo_historico->u_grabacion = Auth::user()->usuario;
                $activo_historico->save();


                if($activo_historico->id_tipo_activo==3){
                    $detalles_vehiculo = new VehiculosHistoricos();
                    $detalles_vehiculo->id_activo = $request->activo_detalle_vehiculo['id_activo'];
                    $detalles_vehiculo->placa = $request->activo_detalle_vehiculo['placa'];
                    $detalles_vehiculo->id_vehiculo = $request->activo_detalle_vehiculo['id_vehiculo'];
                    $detalles_vehiculo->color = $request->activo_detalle_vehiculo['color'];
                    $detalles_vehiculo->chasis = $request->activo_detalle_vehiculo['chasis'];
                    $detalles_vehiculo->numero_motor = $request->activo_detalle_vehiculo['numero_motor'];
                    $detalles_vehiculo->id_tipo_combustible = $request->activo_detalle_vehiculo['id_tipo_combustible'];
                    $detalles_vehiculo->cilindro = $request->activo_detalle_vehiculo['cilindro'];
                    $detalles_vehiculo->anio = $request->activo_detalle_vehiculo['anio'];
                    $detalles_vehiculo->capacidad_carga = $request->activo_detalle_vehiculo['capacidad_carga'];
                    $detalles_vehiculo->pasajeros = $request->activo_detalle_vehiculo['pasajeros'];
                    $detalles_vehiculo->save();
                }

                $activo = Activos::find($request->id_activo);

                $activo->meses_vida_revaluacion = $request->vida_util_adicional;
                $activo->depreciacion_mensual_revaluada = $request->depreciacion_mensual_revaluada;
                $activo->depreciacion_acumulada_revaluada = $request->depreciacion_acumulada_revaluada;
                $activo->valor_libro_anterior = $request->valor_libro_politica;
                $activo->valor_libro_revaluado = $request->valor_libro_revaluado;
                //$activo->valor_libro_politica = $request->valor_libro_revaluado;
                $activo->save();
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

    public function generarCierre(Request $request)
    {   // 'descripcion' => 'required|string|max:100|unique:pgsql.admon.roles,descripcion',
        $rules = [

            'activos' => 'required|array|min:1',
            'activos.*.id_activo' => 'required|integer',

            'id_sucursal' => 'required|integer|min:1',
            'id_periodo_mes' => 'required|integer|min:1',

            'mes' => 'required|integer|min:1',
            'anio' => 'required|integer|min:1',

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            try{
                DB::beginTransaction();

                $cierre = new Cierres();
                $cierre->id_sucursal = $request->id_sucursal;
                $cierre->id_periodo_mes = $request->id_periodo_mes;
                $cierre->mes = $request->mes;
                $cierre->anio = $request->anio;
                $cierre->f_grabacion = date('Y-m-d H:i:s');
                $cierre->u_grabacion = Auth::user()->usuario;
                $cierre->save();

                foreach ($request->activos as $activox) {
                    $activo = Activos::find($activox['id_activo']);

                    $depreciacion =  new Depreciaciones();

                    $cuentaContable = Cuentas::select('id_cuenta_depreciacion')->where('id_tipo_activo',$activo->id_tipo_activo)->first();

                    $depreciacion->id_cierre_activo = $cierre->id_cierre_activo;
                    $depreciacion->id_activo = $activo->id_activo;
                    $depreciacion->depreciacion_mensual = $activo->depreciacion_mensual;
                    $depreciacion->depreciacion_acumulada = $activox['depreciacion_acumulada_actual'];
                    $depreciacion->valor_libro = $activox['depreciacion_valor_libros_actual'];
                    $depreciacion->meses = $activox['mes_depreciado_actual'];
                    $depreciacion->fecha_apliacion = date('Y-m-d H:i:s');
                    $depreciacion->id_trabajador = $activo->id_trabajador;
                    $depreciacion->id_area = $activo->id_area;
                    $depreciacion->id_cuenta_contable = $cuentaContable['id_cuenta_depreciacion'];
                    $depreciacion->fecha_adquisicion = $activo->fecha_adquisicion;
                    $depreciacion->valor_adquisicion = $activo->valor_adquisicion;
                    $depreciacion->save();

                    $activo->depreciacion_acumulada = $depreciacion->depreciacion_acumulada;
                    $activo->valor_libro = $depreciacion->valor_libro;
                    $activo->mes_depreciado=$depreciacion->meses;

                    if($activo->mes_depreciado==$activo->meses_depreciarse){
                        $activo->estado=2;
                    }

                    $activo->save();
                }

                $documento = new DocumentosContables;
                $tipo = TiposDocumentos::find(2);

                $documento->num_documento = $tipo->prefijo.'-'.$tipo->secuencia;

                date_default_timezone_set('America/Managua');

                $documento->fecha_emision = date('Y-m-d');

                $date = DateTime::createFromFormat("Y-m-d", $documento->fecha_emision);
                $meses = config('global.meses');
                //echo date('Y-m-d h:i:s');
                $periodo = PeriodosFiscales::where('periodo','=',$date->format("Y"))->first();

                if(empty($periodo)){
                    return response()->json([
                        'status' => 'error',
                        'result' =>   array('fecha_emision'=>["El periodo ".$date->format("Y")." no existe, por favor consulte al administrador"]),
                        'messages' => null
                    ]);
                    exit;
                }

                if($periodo->estado){
                    return response()->json([
                        'status' => 'error',
                        'result' =>   array('fecha_emision'=>["El periodo ".$date->format("Y")." es inválido, ya que se encuentra en estado COMPLETADO"]),
                        'messages' => null
                    ]);
                    exit;
                }
                $periodo_mes = PeriodosMeses::where('id_periodo_fiscal',$periodo->id_periodo_fiscal)->where('mes',$date->format("n"))->first();

                if(empty($periodo_mes)){
                    return response()->json([
                        'status' => 'error',
                        'result' =>   array('fecha_deposito'=>["El mes ".$date->format("F")." no se encuentra registrado, por favor consulte al administrador"]),
                        'messages' => null
                    ]);
                    exit;
                }

                if($periodo_mes->estado == 2 ){
                    return response()->json([
                        'status' => 'error',
                        'result' =>   array('fecha_deposito'=>["El mes ".$meses[$periodo_mes->mes-1]." es inválido, ya que se encuentra en estado COMPLETADO"]),
                        'messages' => null
                    ]);
                    exit;
                }

                $total_comprobante = $request->total_comprobante['total'];
                $valor = $total_comprobante?$total_comprobante : 0;

                $documento->id_periodo_fiscal = $periodo->id_periodo_fiscal;
                $documento->id_tipo_doc = 2;
                $documento->valor = $valor;
                $documento->concepto = 'Contabilizando depreciación de activos fijos registrados al costo mes '. $request->mes .'. del '.$request->anio;
                $documento->id_moneda = 1;
                $documento->u_creacion = Auth::user()->usuario;
                $documento->estado = 1;

                $documento->save();

                $cierre->id_documento_contable = $documento->id_documento;
                $cierre->save();

                foreach ($request->doc_contable as $movimientoCuenta) {
                    $documento_cuenta_contable = new DocumentosCuentas();

                    $documento_cuenta_contable->id_documento = $documento->id_documento;
                    $documento_cuenta_contable->concepto = $documento->concepto;
                    $documento_cuenta_contable->id_moneda = 1;
                    $documento_cuenta_contable->debe = $movimientoCuenta['debe']?$movimientoCuenta['debe']:0;
                    $documento_cuenta_contable->debe_org = $movimientoCuenta['debe']?$movimientoCuenta['debe']:0;
                    $documento_cuenta_contable->haber =  $movimientoCuenta['haber']?$movimientoCuenta['haber']:0;
                    $documento_cuenta_contable->haber_org =  $movimientoCuenta['haber']?$movimientoCuenta['haber']:0;
                    $documento_cuenta_contable->id_centro =  null;
                    $documento_cuenta_contable->id_cuenta_contable = $movimientoCuenta['id_cuenta_contable'];
                    $documento_cuenta_contable->cta_contable = $movimientoCuenta['cuentas'];
                    $documento_cuenta_contable->cta_contable_padre = $movimientoCuenta['cuentas'];
                    $documento_cuenta_contable->save();
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


    public function reasignarLote(Request $request)
    {   // 'descripcion' => 'required|string|max:100|unique:pgsql.admon.roles,descripcion',
        $rules = [

            'activos' => 'required|array|min:1',
            'activos.*.id_activo' => 'required|integer',

            'activo_trabajador_destino' => 'required|array|min:1',
            'activo_trabajador_destino.id_trabajador' => 'required|integer|min:1',
            'activo_trabajador_destino.id_area' => 'required|integer|min:1',

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            try{
                DB::beginTransaction();

                $contador = 0;
                $validacion = true;
                $errores = [];

                foreach ($request->activos as $activox) {
                    $activo = Activos::find($activox['id_activo']);

                    $ultimo_traslado = Traslados::select('tipo_traslado')
                        ->where('id_activo',$activo->id_activo)
                        ->orderby('fecha_traslado','desc')->first();
                    if (!empty($ultimo_traslado))
                    {
                        if($ultimo_traslado->tipo_traslado == 1 || $ultimo_traslado->tipo_traslado == 3)
                        {
                            if($activox['tipo_traslado'] == 1 || $activox['tipo_traslado'] == 2)
                            {
                                $traslado =  new Traslados();
                                $traslado->id_activo = $activo->id_activo;
                                $traslado->id_area_origen = $activo->id_area;
                                $traslado->id_area_destino = $request->activo_trabajador_destino['id_area'];
                                $traslado->id_trabajador_origen = $activo->id_trabajador;
                                $traslado->id_trabajador_destino = $request->activo_trabajador_destino['id_trabajador'];
                                $traslado->tipo_traslado = $activox['tipo_traslado'];
                                $traslado->fecha_traslado = date('Y-m-d H:i:s');
                                $traslado->mes = 0;
                                $traslado->anio = 0;
                                $traslado->u_creacion = Auth::user()->usuario;
                                $traslado->f_creacion = date('Y-m-d H:i:s');
                                $traslado->id_cuenta_depreciacion =  $activo->id_cuenta_depreciacion;
                                $traslado->estado = 1;
                                $traslado->save();

                                $activo->id_area = $request->activo_trabajador_destino['id_area'];
                                $activo->id_trabajador = $request->activo_trabajador_destino['id_trabajador'];
                                $activo->save();
                            }else {
                                $validacion = false;
                                $errores = $errores + ['activos_trabajador.'.$contador.'.tipo_traslado'=>['Tipo de traslado no permitido.']];
                            }
                        }
                        if($ultimo_traslado->tipo_traslado == 2)
                        {
                            if($activox['tipo_traslado'] == 1 || $activox['tipo_traslado'] == 3)
                            {
                                $traslado =  new Traslados();
                                $traslado->id_activo = $activo->id_activo;
                                $traslado->id_area_origen = $activo->id_area;
                                $traslado->id_area_destino = $request->activo_trabajador_destino['id_area'];
                                $traslado->id_trabajador_origen = $activo->id_trabajador;
                                $traslado->id_trabajador_destino = $request->activo_trabajador_destino['id_trabajador'];
                                $traslado->tipo_traslado = $activox['tipo_traslado'];
                                $traslado->fecha_traslado = date('Y-m-d H:i:s');
                                $traslado->mes = 0;
                                $traslado->anio = 0;
                                $traslado->u_creacion = Auth::user()->usuario;
                                $traslado->f_creacion = date('Y-m-d H:i:s');
                                $traslado->id_cuenta_depreciacion =  $activo->id_cuenta_depreciacion;
                                $traslado->estado = 1;
                                $traslado->save();

                                $activo->id_area = $request->activo_trabajador_destino['id_area'];
                                $activo->id_trabajador = $request->activo_trabajador_destino['id_trabajador'];
                                $activo->save();
                            }else {
                                $validacion = false;
                                $errores = $errores + ['activos_trabajador.'.$contador.'.tipo_traslado'=>['Tipo de traslado no permitido.']];
                            }
                        }
                    }else{
                        if($activox['tipo_traslado'] == 1 || $activox['tipo_traslado'] ==2)
                        {
                            $traslado =  new Traslados();
                            $traslado->id_activo = $activo->id_activo;
                            $traslado->id_area_origen = $activo->id_area;
                            $traslado->id_area_destino = $request->activo_trabajador_destino['id_area'];
                            $traslado->id_trabajador_origen = $activo->id_trabajador;
                            $traslado->id_trabajador_destino = $request->activo_trabajador_destino['id_trabajador'];
                            $traslado->tipo_traslado = $activox['tipo_traslado'];
                            $traslado->fecha_traslado = date('Y-m-d H:i:s');
                            $traslado->mes = 0;
                            $traslado->anio = 0;
                            $traslado->u_creacion = Auth::user()->usuario;
                            $traslado->f_creacion = date('Y-m-d H:i:s');
                            $traslado->id_cuenta_depreciacion =  $activo->id_cuenta_depreciacion;
                            $traslado->estado = 1;
                            $traslado->save();

                            $activo->id_area = $request->activo_trabajador_destino['id_area'];
                            $activo->id_trabajador = $request->activo_trabajador_destino['id_trabajador'];
                            $activo->save();
                        }
                        else{
                            $validacion = false;
                            $errores = $errores + ['activos_trabajador.'.$contador.'.tipo_traslado'=>['Tipo de traslado no permitido.']];
                        }
                    }
                    $contador++;
                }

                if(!$validacion)
                {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'result' => $errores,
                        'messages' => null
                    ]);
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

    public function desactivar(Request $request)
    {
        $rules = [
            'id_activo' => 'required|integer',
            'justificacion' => [
                'required',
                'string',
                'max:300',
            ],
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            try{

                DB::beginTransaction();
                $activo = Activos::find($request->id_activo);

                $activo->id_activo = $activo->id_activo;
                $activo->justificacion = $request->justificacion;
                $activo->f_modificacion = date('Y-m-d');
                $activo->u_modificacion=Auth::user()->usuario;
                $activo->estado= 0;
                $activo->save();

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

    public function nuevo(Request $request)
    {
        //$sucursales = PublicSucursales::select(['id_sucursal','descripcion'])->get();
        $centros = CentrosCostosIngresos::select(['id_centro','descripcion','codigo',
            DB::raw("concat(codigo,' - ',descripcion) AS text")])
            ->where('estado',1)->where('tipo_centro',2)->get();
        $areas = Areas::select(['id_area','descripcion'])->with('trabajadoresArea')->get();
        //$trabajadores = Trabajadores::select('id_trabajador','primer_apellido','primer_nombre','segundo_apellido','segundo_nombre','codigo',DB::raw("CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido) AS nombre_completo"))->get();
        //$grupos = Grupo::select(['id_grupo_activo','descripcion'])->get();
        $tipos = TiposActivos::select(['id_tipo_activo','descripcion'])->with('gruposActivos')->get();
        $colores = Colores::select(['id_color','descripcion'])->get();
        $propietarios = Propietarios::select(['id_propietario','descripcion'])->get();
        $periodos = PeriodosFiscales::select('id_periodo_fiscal','periodo')->with('mesesPeriodo')->orderby('periodo','desc')->get();

        return response()->json([
            'status' => 'success',
            'result' => [
                'centros' => $centros,
                'areas' => $areas,
                //'trabajadores' => $trabajadores,
                //'grupos' => $grupos,
                'tipos' => $tipos,
                'colores' => $colores,
                'propietarios'=>$propietarios,
                'periodos' => $periodos
            ],
            'messages' => null
        ]);

    }

    public function nuevoCierre(Request $request)
    {
        $sucursales = Sucursales::select(['id_sucursal','descripcion'])->get();
        $periodos = PeriodosFiscales::select('id_periodo_fiscal','periodo')->with('mesesPeriodo')->orderby('periodo','desc')->get();

        return response()->json([
            'status' => 'success',
            'result' => [
                'sucursales' => $sucursales,
                'periodos' => $periodos,
            ],
            'messages' => null
        ]);

    }

    public function activar(Request $request)
    {
        $rules = [
            'id_activo' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $activo = Activos::find($request->id_activo);
            $activo->estado = 1;
            $activo->save();

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
    public function obtenerUbicacionActivo(Request $request)
    {
        $messages = [
            'codigo.exists' => 'No hay registros previos de este código de activo.',
        ];
        $rules = [
            'codigo_activo'  => 'required|string|max:50',
        ];
        //echo $request->fecha;
        $validator = Validator::make($request->all(), $rules,$messages);
        if (!$validator->fails()) {

            $activo = Activos::WhereRaw("upper(codigo) = upper(?)",[$request->codigo_activo])
                ->with('activoArea','activoTrabajador')->first();
            if(!empty($activo)){


                return response()->json([
                    'status' => 'success',
                    'result' => $activo,
                    'messages' => null
                ]);


            }else{
                return response()->json([
                    'status' => 'error',
                    'result' => array('codigo_activo'=>["No hay registros previos de este código de activo."]),
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

    public function cambiarEstadoSolicitudBaja(Request $request)
    {
        $rules = [
            'id_activo' => 'required|integer|min:0',
            'estado' => 'required|integer|min:0|max:3'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $activo = Activos::find($request->id_activo);
            if($activo->estado == 99 && $request->estado==0){
                $activo->estado = $request->estado;
                $activo->u_autoriza = Auth::user()->usuario;
                $activo->f_autoriza = date('Y-m-d h:i:s');
                $activo->save();
            }elseif($activo->estado == 99 && $request->estado==1){
                $activo->estado = $request->estado;
                $activo->u_rechaza = Auth::user()->usuario;
                $activo->f_rechaza = date('Y-m-d h:i:s');
                $activo->save();
            }elseif($activo->estado == 0 && $request->estado==1){
                $activo->estado = $request->estado;
                $activo->u_rechaza = Auth::user()->usuario;
                $activo->f_rechaza = date('Y-m-d h:i:s');
                $activo->save();
            }
            else{
                return response()->json([
                    'status' => 'error',
                    'result' => 'La solicitud de baja no puede ser autorizada',
                    'messages' => null
                ]);
            }

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

    public function generarReporteAltaActivo(Request $request)
    {
        // echo $ext;
        $rules = [
            'fechaInicio' => 'required|date',
            'fechaFinal' => 'required|date',
            'extension' => 'required|string|max:3'

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $os = array("pdf", "xls");
            if (in_array($request->extension, $os)) {
                $hora_actual = time();
                //$input = 'C:/xampp/htdocs/resources/reports/ReporteAltaActivo';
                //$output = 'C:/xampp/htdocs/resources/reports/' . $hora_actual . 'ReporteAltaActivo';
                $input = '/var/www/html/resources/reports/ReporteAltaActivo';
                $output = '/var/www/html/resources/reports/' . $hora_actual . 'ReporteAltaActivo';
                $nombre_empresa = Ajustes::where('id_ajuste',4)->select('valor')->first();
                $logo_empresa = Ajustes::where('id_ajuste',3)->select(DB::raw("substr(valor, 2, length(valor) - 2)::json->>'file_thumbnail' as file_thumbnail"))->first();
                $url = '/var/www/html/resources/reports/';

                $options = [
                    'format' => [$request->extension],
                    'locale' => 'en',
                    'params' => [
                        'fechaInicio' => $request->fechaInicio,
                        'fechaFinal' => $request->fechaFinal,
                        'empresa_nombre' => $nombre_empresa->valor,
                        'empresa_logo' =>  env('APP_URL_REPORTS').$logo_empresa->file_thumbnail
                    ],
                    'db_connection' => [
                        'driver' => 'postgres',
                        'username' => env('DB_USERNAME'),
                        'password' => env('DB_PASSWORD'),
                        'host' => env('DB_HOST'),
                        'database' => env('DB_DATABASE'),
                        'port' => env('DB_PORT')
                    ]
                ];

                $jasper = new PHPJasper;

                $jasper->process($input, $output, $options)->execute();



                $headers = [
                    'Content-Type' => 'application/pdf',
                ];

                /*exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
                print_r($output);*/

                return response()->download($output . '.' . $request->extension, $hora_actual . 'ReporteAltaActivo.' . $request->extension, $headers);

            } else {
                return response()->json([
                    'status' => 'error',
                    'result' => $validator->messages(),
                    'messages' => null
                ]);
            }
        } else {
            return '';
        }
    }
    public function generarReporteBajaActivo(Request $request)
    {
        // echo $ext;
        $rules = [
            'fechaInicial' => 'required|date',
            'fechaFinal' => 'required|date',
            'extension' => 'required|string|max:3'

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $os = array("pdf", "xls");
            if (in_array($request->extension, $os)) {
                $hora_actual = time();
                //$input = 'C:/xampp/htdocs/resources/reports/ReporteBajaActivo';
                //$output = 'C:/xampp/htdocs/resources/reports/' . $hora_actual . 'ReporteBajaActivo';
                $input = '/var/www/html/resources/reports/ReporteBajaActivo';
                $output = '/var/www/html/resources/reports/' . $hora_actual . 'ReporteBajaActivo';
                $nombre_empresa = Ajustes::where('id_ajuste',4)->select('valor')->first();
                $logo_empresa = Ajustes::where('id_ajuste',3)->select(DB::raw("substr(valor, 2, length(valor) - 2)::json->>'file_thumbnail' as file_thumbnail"))->first();
                $url = '/var/www/html/resources/reports/';

                $options = [
                    'format' => [$request->extension],
                    'locale' => 'en',
                    'params' => [
                        'fechaInicial' => $request->fechaInicial,
                        'fechaFinal' => $request->fechaFinal,
                        'empresa_nombre' => $nombre_empresa->valor,
                        'empresa_logo' =>  env('APP_URL_REPORTS').$logo_empresa->file_thumbnail
                    ],
                    'db_connection' => [
                        'driver' => 'postgres',
                        'username' => env('DB_USERNAME'),
                        'password' => env('DB_PASSWORD'),
                        'host' => env('DB_HOST'),
                        'database' => env('DB_DATABASE'),
                        'port' => env('DB_PORT')
                    ]
                ];

                $jasper = new PHPJasper;

                $jasper->process($input, $output, $options)->execute();



                $headers = [
                    'Content-Type' => 'application/pdf',
                ];

                /* exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
                 print_r($output);*/

                return response()->download($output . '.' . $request->extension, $hora_actual . 'ReporteBajaActivo.' . $request->extension, $headers);

            } else {
                return response()->json([
                    'status' => 'error',
                    'result' => $validator->messages(),
                    'messages' => null
                ]);
            }
        } else {
            return '';
        }
    }
    public function generarReporteActivoDepreciandose(Request $request)
    {
        // echo $ext;
        $rules = [
            'fechaInicial' => 'required|date',
            'fechaFinal' => 'required|date',
            'extension' => 'required|string|max:3'

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $os = array("pdf", "xls");
            if (in_array($request->extension, $os)) {
                $hora_actual = time();
                //$input = 'C:/xampp/htdocs/resources/reports/ReporteActivoDepreciandose';
                //$output = 'C:/xampp/htdocs/resources/reports/' . $hora_actual . 'ReporteActivoDepreciandose';
                $input = '/var/www/html/resources/reports/ReporteActivoDepreciandose';
                $output = '/var/www/html/resources/reports/' . $hora_actual . 'ReporteActivoDepreciandose';
                $nombre_empresa = Ajustes::where('id_ajuste',4)->select('valor')->first();
                $logo_empresa = Ajustes::where('id_ajuste',3)->select(DB::raw("substr(valor, 2, length(valor) - 2)::json->>'file_thumbnail' as file_thumbnail"))->first();
                $url = '/var/www/html/resources/reports/';

                $options = [
                    'format' => [$request->extension],
                    'locale' => 'en',
                    'params' => [
                        'fechaInicial' => $request->fechaInicial,
                        'fechaFinal' => $request->fechaFinal,
                        'empresa_nombre' => $nombre_empresa->valor,
                        'empresa_logo' =>  env('APP_URL_REPORTS').$logo_empresa->file_thumbnail
                    ],
                    'db_connection' => [
                        'driver' => 'postgres',
                        'username' => env('DB_USERNAME'),
                        'password' => env('DB_PASSWORD'),
                        'host' => env('DB_HOST'),
                        'database' => env('DB_DATABASE'),
                        'port' => env('DB_PORT')
                    ]
                ];

                $jasper = new PHPJasper;

                $jasper->process($input, $output, $options)->execute();



                $headers = [
                    'Content-Type' => 'application/pdf',
                ];

                /* exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
                 print_r($output);*/

                return response()->download($output . '.' . $request->extension, $hora_actual . 'ReporteActivoDepreciandose.' . $request->extension, $headers);

            } else {
                return response()->json([
                    'status' => 'error',
                    'result' => $validator->messages(),
                    'messages' => null
                ]);
            }
        } else {
            return '';
        }
    }
    public function generarReporteActivoDepreciadoEnUso(Request $request)
    {
        // echo $ext;
        $rules = [
            'fechaInicial' => 'required|date',
            'fechaFinal' => 'required|date',
            'extension' => 'required|string|max:3'

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $os = array("pdf", "xls");
            if (in_array($request->extension, $os)) {
                $hora_actual = time();
                //$input = 'C:/xampp/htdocs/resources/reports/ReporteActivosDepreciadoEnUso';
                //$output = 'C:/xampp/htdocs/resources/reports/' . $hora_actual . 'ReporteActivosDepreciadoEnUso';
                $input = '/var/www/html/resources/reports/ReporteActivosDepreciadoEnUso';
                $output = '/var/www/html/resources/reports/' . $hora_actual . 'ReporteActivosDepreciadoEnUso';
                $nombre_empresa = Ajustes::where('id_ajuste',4)->select('valor')->first();
                $logo_empresa = Ajustes::where('id_ajuste',3)->select(DB::raw("substr(valor, 2, length(valor) - 2)::json->>'file_thumbnail' as file_thumbnail"))->first();
                $url = '/var/www/html/resources/reports/';

                $options = [
                    'format' => [$request->extension],
                    'locale' => 'en',
                    'params' => [
                        'fechaInicial' => $request->fechaInicial,
                        'fechaFinal' => $request->fechaFinal,
                        'empresa_nombre' => $nombre_empresa->valor,
                        'empresa_logo' =>  env('APP_URL_REPORTS').$logo_empresa->file_thumbnail
                    ],
                    'db_connection' => [
                        'driver' => 'postgres',
                        'username' => env('DB_USERNAME'),
                        'password' => env('DB_PASSWORD'),
                        'host' => env('DB_HOST'),
                        'database' => env('DB_DATABASE'),
                        'port' => env('DB_PORT')
                    ]
                ];

                $jasper = new PHPJasper;

                $jasper->process($input, $output, $options)->execute();



                $headers = [
                    'Content-Type' => 'application/pdf',
                ];

                /* exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
                 print_r($output);*/

                return response()->download($output . '.' . $request->extension, $hora_actual . 'ReporteActivosDepreciadoEnUso.' . $request->extension, $headers);

            } else {
                return response()->json([
                    'status' => 'error',
                    'result' => $validator->messages(),
                    'messages' => null
                ]);
            }
        } else {
            return '';
        }
    }
    public function generarReporteTrasladosActivo(Request $request)
    {
        // echo $ext;
        $rules = [
            'fechaInicio' => 'required|date',
            'fechaFinal' => 'required|date',
            'extension' => 'required|string|max:3'

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $os = array("pdf", "xls");
            if (in_array($request->extension, $os)) {
                $hora_actual = time();
                //$input = 'C:/xampp/htdocs/resources/reports/ReporteTrasladoActivo';
                //$output = 'C:/xampp/htdocs/resources/reports/' . $hora_actual . 'ReporteTrasladoActivo';
                $input = '/var/www/html/resources/reports/ReporteTrasladoActivo';
                $output = '/var/www/html/resources/reports/' . $hora_actual . 'ReporteTrasladoActivo';
                $nombre_empresa = Ajustes::where('id_ajuste',4)->select('valor')->first();
                $logo_empresa = Ajustes::where('id_ajuste',3)->select(DB::raw("substr(valor, 2, length(valor) - 2)::json->>'file_thumbnail' as file_thumbnail"))->first();
                $url = '/var/www/html/resources/reports/';

                $options = [
                    'format' => [$request->extension],
                    'locale' => 'en',
                    'params' => [
                        'fechaInicio' => $request->fechaInicio,
                        'fechaFinal' => $request->fechaFinal,
                        'empresa_nombre' => $nombre_empresa->valor,
                        'empresa_logo' =>  env('APP_URL_REPORTS').$logo_empresa->file_thumbnail
                    ],
                    'db_connection' => [
                        'driver' => 'postgres',
                        'username' => env('DB_USERNAME'),
                        'password' => env('DB_PASSWORD'),
                        'host' => env('DB_HOST'),
                        'database' => env('DB_DATABASE'),
                        'port' => env('DB_PORT')
                    ]
                ];

                $jasper = new PHPJasper;

                $jasper->process($input, $output, $options)->execute();



                $headers = [
                    'Content-Type' => 'application/pdf',
                ];

                /* exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
                 print_r($output);*/

                return response()->download($output . '.' . $request->extension, $hora_actual . 'ReporteTrasladoActivo.' . $request->extension, $headers);

            } else {
                return response()->json([
                    'status' => 'error',
                    'result' => $validator->messages(),
                    'messages' => null
                ]);
            }
        } else {
            return '';
        }
    }

    public function generarReporteBienesDepreciados(Request $request)
    {
        // echo $ext;
        $rules = [
            'mes' => 'required|integer',
            'anio' => 'required|integer',
            'extension' => 'required|string|max:3'

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $os = array("pdf", "xls");
            if (in_array($request->extension, $os)) {
                $hora_actual = time();
                //$input = 'C:/xampp/htdocs/resources/reports/ConsolidadoBienesDepreciados';
                //$output = 'C:/xampp/htdocs/resources/reports/' . $hora_actual . 'ConsolidadoBienesDepreciados';
                $input = '/var/www/html/resources/reports/ConsolidadoBienesDepreciados';
                $output = '/var/www/html/resources/reports/' . $hora_actual . 'ConsolidadoBienesDepreciados';
                $nombre_empresa = Ajustes::where('id_ajuste',4)->select('valor')->first();
                $logo_empresa = Ajustes::where('id_ajuste',3)->select(DB::raw("substr(valor, 2, length(valor) - 2)::json->>'file_thumbnail' as file_thumbnail"))->first();
                $url = '/var/www/html/resources/reports/';

                $options = [
                    'format' => [$request->extension],
                    'locale' => 'en',
                    'params' => [
                        'mes' => $request->mes,
                        'anio' => $request->anio,
                        //'id_sucursal' => $request->id_sucursal,
                        'empresa_nombre' => $nombre_empresa->valor,
                        'empresa_logo' =>  env('APP_URL_REPORTS').$logo_empresa->file_thumbnail
                    ],
                    'db_connection' => [
                        'driver' => 'postgres',
                        'username' => env('DB_USERNAME'),
                        'password' => env('DB_PASSWORD'),
                        'host' => env('DB_HOST'),
                        'database' => env('DB_DATABASE'),
                        'port' => env('DB_PORT')
                    ]
                ];

                $jasper = new PHPJasper;

                $jasper->process($input, $output, $options)->execute();



                $headers = [
                    'Content-Type' => 'application/pdf',
                ];

                /*exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
                print_r($output);*/

                return response()->download($output . '.' . $request->extension, $hora_actual . 'ConsolidadoBienesDepreciados.' . $request->extension, $headers);

            } else {
                return response()->json([
                    'status' => 'error',
                    'result' => $validator->messages(),
                    'messages' => null
                ]);
            }
        } else {
            return '';
        }
    }

    public function generarReporteAdquisicionActivos(Request $request)
    {
        // echo $ext;
        $rules = [
            'fechaInicio' => 'required|date',
            'fechaFinal' => 'required|date',
            'extension' => 'required|string|max:3'

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $os = array("pdf", "xls");
            if (in_array($request->extension, $os)) {
                $hora_actual = time();
                //$input = 'C:/xampp/htdocs/resources/reports/ReporteValorAdquisicion';
                //$output = 'C:/xampp/htdocs/resources/reports/' . $hora_actual . 'ReporteValorAdquisicion';
                $input = '/var/www/html/resources/reports/ReporteValorAdquisicion';
                $output = '/var/www/html/resources/reports/' . $hora_actual . 'ReporteValorAdquisicion';
                $nombre_empresa = Ajustes::where('id_ajuste',4)->select('valor')->first();
                $logo_empresa = Ajustes::where('id_ajuste',3)->select(DB::raw("substr(valor, 2, length(valor) - 2)::json->>'file_thumbnail' as file_thumbnail"))->first();
                $url = '/var/www/html/resources/reports/';

                $options = [
                    'format' => [$request->extension],
                    'locale' => 'en',
                    'params' => [
                        'fechaInicio' => $request->fechaInicio,
                        'fechaFinal' => $request->fechaFinal,
                        'id_sucursal' => $request->id_sucursal,
                        'empresa_nombre' => $nombre_empresa->valor,
                        'empresa_logo' =>  env('APP_URL_REPORTS').$logo_empresa->file_thumbnail
                    ],
                    'db_connection' => [
                        'driver' => 'postgres',
                        'username' => env('DB_USERNAME'),
                        'password' => env('DB_PASSWORD'),
                        'host' => env('DB_HOST'),
                        'database' => env('DB_DATABASE'),
                        'port' => env('DB_PORT')
                    ]
                ];

                $jasper = new PHPJasper;

                $jasper->process($input, $output, $options)->execute();



                $headers = [
                    'Content-Type' => 'application/pdf',
                ];

                /*exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
                print_r($output);*/

                return response()->download($output . '.' . $request->extension, $hora_actual . 'ReporteValorAdquisicion.' . $request->extension, $headers);

            } else {
                return response()->json([
                    'status' => 'error',
                    'result' => $validator->messages(),
                    'messages' => null
                ]);
            }
        } else {
            return '';
        }
    }

    public function generarReporteHistorialActivos(Request $request)
    {
        // echo $ext;
        $rules = [
            'fechaInicio' => 'required|date',
            'fechaFinal' => 'required|date',
            'extension' => 'required|string|max:3'

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $os = array("pdf", "xls");
            if (in_array($request->extension, $os)) {
                $hora_actual = time();
                //$input = 'C:/xampp/htdocs/resources/reports/HistorialComprasActivos';
                //$output = 'C:/xampp/htdocs/resources/reports/' . $hora_actual . 'HistorialComprasActivos';
                $input = '/var/www/html/resources/reports/HistorialComprasActivos';
                $output = '/var/www/html/resources/reports/' . $hora_actual . 'HistorialComprasActivos';
                $nombre_empresa = Ajustes::where('id_ajuste',4)->select('valor')->first();
                $logo_empresa = Ajustes::where('id_ajuste',3)->select(DB::raw("substr(valor, 2, length(valor) - 2)::json->>'file_thumbnail' as file_thumbnail"))->first();
                $url = '/var/www/html/resources/reports/';

                $options = [
                    'format' => [$request->extension],
                    'locale' => 'en',
                    'params' => [
                        'fechaInicio' => $request->fechaInicio,
                        'fechaFinal' => $request->fechaFinal,
                        'id_sucursal' => $request->id_sucursal,
                        'empresa_nombre' => $nombre_empresa->valor,
                        'empresa_logo' =>  env('APP_URL_REPORTS').$logo_empresa->file_thumbnail
                    ],
                    'db_connection' => [
                        'driver' => 'postgres',
                        'username' => env('DB_USERNAME'),
                        'password' => env('DB_PASSWORD'),
                        'host' => env('DB_HOST'),
                        'database' => env('DB_DATABASE'),
                        'port' => env('DB_PORT')
                    ]
                ];

                $jasper = new PHPJasper;

                $jasper->process($input, $output, $options)->execute();



                $headers = [
                    'Content-Type' => 'application/pdf',
                ];

                /*exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
                print_r($output);*/

                return response()->download($output . '.' . $request->extension, $hora_actual . 'HistorialComprasActivos.' . $request->extension, $headers);

            } else {
                return response()->json([
                    'status' => 'error',
                    'result' => $validator->messages(),
                    'messages' => null
                ]);
            }
        } else {
            return '';
        }
    }

    public function generarReporteVidaUtil(Request $request)
    {
        // echo $ext;
        $rules = [
            'extension' => 'required|string|max:3'

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $os = array("pdf", "xls");
            if (in_array($request->extension, $os)) {
                $hora_actual = time();
                //$input = 'C:/xampp/htdocs/resources/reports/ReporteVidaUtil';
                //$output = 'C:/xampp/htdocs/resources/reports/' . $hora_actual . 'ReporteVidaUtil';
                $input = '/var/www/html/resources/reports/ReporteVidaUtil';
                $output = '/var/www/html/resources/reports/' . $hora_actual . 'ReporteVidaUtil';
                $nombre_empresa = Ajustes::where('id_ajuste',4)->select('valor')->first();
                $logo_empresa = Ajustes::where('id_ajuste',3)->select(DB::raw("substr(valor, 2, length(valor) - 2)::json->>'file_thumbnail' as file_thumbnail"))->first();
                $url = '/var/www/html/resources/reports/';

                $options = [
                    'format' => [$request->extension],
                    'locale' => 'en',
                    'params' => [
                        'empresa_nombre' => $nombre_empresa->valor,
                        'empresa_logo' =>  env('APP_URL_REPORTS').$logo_empresa->file_thumbnail
                    ],
                    'db_connection' => [
                        'driver' => 'postgres',
                        'username' => env('DB_USERNAME'),
                        'password' => env('DB_PASSWORD'),
                        'host' => env('DB_HOST'),
                        'database' => env('DB_DATABASE'),
                        'port' => env('DB_PORT')
                    ]
                ];

                $jasper = new PHPJasper;

                $jasper->process($input, $output, $options)->execute();



                $headers = [
                    'Content-Type' => 'application/pdf',
                ];

                /*exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
                print_r($output);*/

                return response()->download($output . '.' . $request->extension, $hora_actual . 'ReporteVidaUtil.' . $request->extension, $headers);

            } else {
                return response()->json([
                    'status' => 'error',
                    'result' => $validator->messages(),
                    'messages' => null
                ]);
            }
        } else {
            return '';
        }
    }
    public function generarReporteGeneralActivo(Request $request)
    {
        // echo $ext;
        $rules = [
            'extension' => 'required|string|max:3',
            'id_tipo_activo' => 'required|integer'

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $os = array("pdf", "xls");
            if (in_array($request->extension, $os)) {
                $hora_actual = time();
                //$input = 'C:/xampp/htdocs/resources/reports/ReporteGeneralActivoPorTipo';
                //$output = 'C:/xampp/htdocs/resources/reports/' . $hora_actual . 'ReporteGeneralActivoPorTipo';
                $input = '/var/www/html/resources/reports/ReporteGeneralActivoPorTipo';
                $output = '/var/www/html/resources/reports/' . $hora_actual . 'ReporteGeneralActivoPorTipo';
                $nombre_empresa = Ajustes::where('id_ajuste',4)->select('valor')->first();
                $logo_empresa = Ajustes::where('id_ajuste',3)->select(DB::raw("substr(valor, 2, length(valor) - 2)::json->>'file_thumbnail' as file_thumbnail"))->first();
                $url = '/var/www/html/resources/reports/';

                $options = [
                    'format' => [$request->extension],
                    'locale' => 'en',
                    'params' => [
                        'id_tipo' => $request->id_tipo_activo,
                        'empresa_nombre' => $nombre_empresa->valor,
                        'empresa_logo' =>  env('APP_URL_REPORTS').$logo_empresa->file_thumbnail
                    ],
                    'db_connection' => [
                        'driver' => 'postgres',
                        'username' => env('DB_USERNAME'),
                        'password' => env('DB_PASSWORD'),
                        'host' => env('DB_HOST'),
                        'database' => env('DB_DATABASE'),
                        'port' => env('DB_PORT')
                    ]
                ];

                $jasper = new PHPJasper;

                $jasper->process($input, $output, $options)->execute();



                $headers = [
                    'Content-Type' => 'application/pdf',
                ];

                /*exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
                print_r($output);*/

                return response()->download($output . '.' . $request->extension, $hora_actual . 'ReporteVidaUtil.' . $request->extension, $headers);

            } else {
                return response()->json([
                    'status' => 'error',
                    'result' => $validator->messages(),
                    'messages' => null
                ]);
            }
        } else {
            return '';
        }
    }

    public function generarActaEntrega(Request $request)
    {
        // echo $ext;
        $rules = [
            'extension' => 'required|string|max:3',
            'id_trabajador'=>'required|integer|min:1',

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $os = array("pdf", "xls");
            if (in_array($request->extension, $os)) {
                $hora_actual = time();
                //$input = 'C:/xampp/htdocs/resources/reports/ActaEntregaActivo';
                //$output = 'C:/xampp/htdocs/resources/reports/' . $hora_actual . 'ActaEntregaActivo';
                $input = '/var/www/html/resources/reports/ActaEntregaActivo';
                $output = '/var/www/html/resources/reports/' . $hora_actual . 'ActaEntregaActivo';
                $nombre_empresa = Ajustes::where('id_ajuste',4)->select('valor')->first();
                $logo_empresa = Ajustes::where('id_ajuste',3)->select(DB::raw("substr(valor, 2, length(valor) - 2)::json->>'file_thumbnail' as file_thumbnail"))->first();
                $url = '/var/www/html/resources/reports/';

                /*  $param_activos = [];
                  foreach($request->activos_trabajador as $activo ){
                      $param_activos[]=$activo['id_activo'];
                  }
                 // print_r($param_activos);*/

                $options = [
                    'format' => [$request->extension],
                    'locale' => 'en',
                    'params' => [
                        'id_trabajador' => $request->id_trabajador,
                        'empresa_nombre' => $nombre_empresa->valor,
                        'empresa_logo' =>  env('APP_URL_REPORTS').$logo_empresa->file_thumbnail
                    ],
                    'db_connection' => [
                        'driver' => 'postgres',
                        'username' => env('DB_USERNAME'),
                        'password' => env('DB_PASSWORD'),
                        'host' => env('DB_HOST'),
                        'database' => env('DB_DATABASE'),
                        'port' => env('DB_PORT')
                    ]
                ];

                $jasper = new PHPJasper;

                $jasper->process($input, $output, $options)->execute();



                $headers = [
                    'Content-Type' => 'application/pdf',
                ];

                /*exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
                print_r($output);*/

                return response()->download($output . '.' . $request->extension, $hora_actual . 'ReporteVidaUtil.' . $request->extension, $headers);

            } else {
                return response()->json([
                    'status' => 'error',
                    'result' => $validator->messages(),
                    'messages' => null
                ]);
            }
        } else {
            return '';
        }
    }



    public function generarSticker(Request $request)
    {
        // echo $ext;
        $rules = [
            'extension' => 'required|string|max:3',
            'id_activo'=>'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $os = array("pdf", "xls");
            if (in_array($request->extension, $os)) {
                $hora_actual = time();

                $input = ENV('URL_REPORTES').'ActivoFijoCodigo';
                $output = ENV('URL_REPORTES').$hora_actual.'ActivoFijoCodigo';
                $url = ENV('URL_REPORTES');

                $options = [
                    'format' => [$request->extension],
                    'locale' => 'en',
                    'params' => [
                        'id_activo' => $request->id_activo,
                    ],
                    'db_connection' => [
                        'driver' => 'postgres',
                        'username' => env('DB_USERNAME'),
                        'password' => env('DB_PASSWORD'),
                        'host' => env('DB_HOST'),
                        'database' => env('DB_DATABASE'),
                        'port' => env('DB_PORT')
                    ]
                ];

                $jasper = new PHPJasper;

                $jasper->process($input, $output, $options)->execute();



                $headers = [
                    'Content-Type' => 'application/pdf',
                ];

                /*exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
                print_r($output);*/

                return response()->download($output . '.' . $request->extension, $hora_actual . 'ReporteSticker.' . $request->extension, $headers);

            } else {
                return response()->json([
                    'status' => 'error',
                    'result' => $validator->messages(),
                    'messages' => null
                ]);
            }
        } else {
            return '';
        }
    }
}
