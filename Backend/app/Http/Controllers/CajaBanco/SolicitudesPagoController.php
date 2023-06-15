<?php

namespace App\Http\Controllers\CajaBanco;

use App\Http\Controllers\Controller;
use App\Models\Admon\Ajustes;
use App\Models\CajaBanco\Bancos;
use App\Models\CajaBanco\SolicitudesPago;
use App\Models\Contabilidad\Monedas;
use App\Models\CajaBanco\Pagos;
use App\Models\CajaBanco\SolicitudesPagoDetalles;
use App\Models\Contabilidad\TasasCambios;
use App\Models\CajaBanco\TiposSolicitudesPago;
use App\Models\Caja\ChicaCaja;
use App\Models\CajaChica\Comprobante;
use App\Models\CajaChica\Reembolso;
use App\Models\Contabilidad\CentrosCostosIngresos;
use App\Models\Contabilidad\CuentasBancarias;
use App\Models\Contabilidad\CuentasContables;
use App\Models\Contabilidad\CuentasContablesVista;
use App\Models\Contabilidad\DocumentosContables;
use App\Models\Contabilidad\DocumentosMovimientos;
use App\Models\Contabilidad\PeriodosFiscales;
use App\Models\Contabilidad\PeriodosMeses;
use App\Models\Contabilidad\TiposDocumentos;
use App\Models\CuentasXPagar\CuentasXPagar;
use App\Models\Inventario\Proveedores;
use App\Models\Transaction;
use DateTime;
use Illuminate\Http\JsonResponse;
use PHPJasper\PHPJasper;
use Validator, Auth, Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SolicitudesPagoController extends Controller
{
    /**
     * Obtener una lista de Estados Financieros
     *
     * @access  public
     * @param
     * @return  JsonResponse(array)
     */

    public function obtener(Request $request, SolicitudesPago $solicitudes): JsonResponse
    {
        $solicitudes = $solicitudes->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $solicitudes->total(),
                'rows' => $solicitudes->items()
            ],
            'messages' => null
        ]);
    }


    public function obtenerSolicitudesAprobacion(Request $request, SolicitudesPago $solicitudes)
    {
        $solicitudes = $solicitudes->obtenerSolicitudesAprobacion($request);
        $cuentas_bancarias = CuentasBancarias::select('id_cuenta_bancaria', 'id_banco', 'id_moneda', 'id_cuenta_contable',
            DB::raw("concat((select b.descripcion from cjabnco.bancos b where b.id_banco = contabilidad.cuentas_bancarias.id_banco),' ',(select moned.descripcion
                from contabilidad.monedas moned where moned.id_moneda = contabilidad.cuentas_bancarias.id_moneda),'(',(select moned.codigo
                from contabilidad.monedas moned where moned.id_moneda = contabilidad.cuentas_bancarias.id_moneda),') ',numero_cuenta) as numero_cuenta")
        )->where('estado', 1)->orderby('id_banco')->orderby('id_moneda')->get();
        return response()->json([
            'status' => 'success',
            'result' => [
                'cuentas_bancarias' => $cuentas_bancarias,
                'total' => $solicitudes->total(),
                'rows' => $solicitudes->items()
            ],
            'messages' => null
        ]);
    }


    /**
     * obtener
     *
     * @access  public
     * @param
     * @return  JsonResponse(array)
     */

    public function obtenerSolicitud(Request $request)
    {
        $rules = [
            'id_solicitud_pago' => 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $solicitud = SolicitudesPago::
            where('id_solicitud_pago', $request->id_solicitud_pago)
                ->with('monedaSolicitud')
                ->with('monedaAprobadaSolicitud')
                ->with('solicitudCuentaBancaria')
                ->with(['usuarioRegistra' => function ($query) {
                    $query->with('trabajador');
                }])
                ->with(['usuarioRevisa' => function ($query) {
                    $query->with('trabajador');
                }])
                ->with(['usuarioAprueba' => function ($query) {
                    $query->with('trabajador');
                }])
                ->with(['usuarioEmite' => function ($query) {
                    $query->with('trabajador');
                }])
                ->with(['usuarioAnula' => function ($query) {
                    $query->with('trabajador');
                }])
                ->with(['documentoSolicitud' => function ($query) {
                    $query->with(['movimientosCuentas' => function ($query2) {
                        $query2->with('cuentaContable')->with('centroCosto');
                    }]);
                }])
                ->first();

            $nombre_empresa = AdmonAjustes::where('id_ajuste', 4)->select('valor')->first();
            $direccion_empresa = AdmonAjustes::where('id_ajuste', 5)->select('valor')->first();

            if (!empty($solicitud)) {
                return response()->json([
                    'status' => 'success',
                    'result' => [
                        'solicitud' => $solicitud,
                        'nombre_empresa' => $nombre_empresa->valor,
                        'direccion_empresa' => $direccion_empresa->valor,
                    ],
                    'messages' => null
                ]);

            } else {
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_solicitud_pago' => ["Datos no encontrados"]),
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

    public function nueva(Request $request)
    {
        $tasa = TasasCambios::select('tasa')->where('fecha', date("Y-m-d"))->first();
        $monedas = Monedas::select('id_moneda', 'descripcion', 'descripcion_singular', 'codigo')->where('estado', 1)->orderBy('id_moneda')->get();
        //$tipo_beneficiario = Monedas::select('id_moneda','descripcion','codigo')->where('activo',1)->orderBy('id_moneda')->get();
        $tipos_solicitud = TiposSolicitudesPago::select('id_tipo_solicitud', 'descripcion')->where('estado', 1)->where('registro_manual', true)->orderBy('id_tipo_solicitud')->get();
        $proveedores = Proveedores::where('estado',1)->get();
        return response()->json([
            'status' => 'success',
            'result' => [
                't_cambio' => $tasa,
                'monedas' => $monedas,
                'tipos_solicitud' => $tipos_solicitud,
                'proveedores' => $proveedores
            ],
            'messages' => null
        ]);
    }

    public function nuevaContabilizacion(Request $request)
    {
        $tasa = TasasCambios::select('tasa')->where('fecha', date("Y-m-d"))->first();
        $monedas = Monedas::select('id_moneda', 'descripcion', 'codigo')->where('activo', 1)->orderBy('id_moneda')->get();
        $tipos_documentos = ContabilidadTiposDocumentos::select(['id_tipo_doc', 'prefijo', 'descripcion'])->where('estado', true)->where('permite_registro_manual', true)->orderBy('id_tipo_doc')->get();
        $centro_costos_ingresos = ContabilidadCentroCostoIngreso::select('id_centro', 'descripcion')->where('estado', true)->get();
        $cuentas_bancarias = ContabilidadCuentasBancarias::select('id_cuenta_bancaria', 'id_banco', 'id_moneda', 'id_cuenta_contable',
            DB::raw("concat((select b.descripcion from cjabnco.bancos b where b.id_banco = contabilidad.cuentas_bancarias.id_banco),' ',(select moned.descripcion
                from cjabnco.monedas moned where moned.id_moneda = contabilidad.cuentas_bancarias.id_moneda),'(',(select moned.codigo
                from cjabnco.monedas moned where moned.id_moneda = contabilidad.cuentas_bancarias.id_moneda),') ',numero_cuenta) as numero_cuenta")
        )->with('monedaCuentaBancaria')->with('cuentaContableCuentaBancaria')->where('estado', 1)->orderby('id_banco')->orderby('id_moneda')->get();

        $solicitud = SolicitudesPago::where('id_solicitud_pago', $request->id_solicitud_pago)
            ->with('monedaSolicitud')
            ->with(['usuarioRegistra' => function ($query) {
                $query->with('trabajador');
            }])
            ->with(['usuarioRevisa' => function ($query) {
                $query->with('trabajador');
            }])
            ->with(['usuarioAprueba' => function ($query) {
                $query->with('trabajador');
            }])
            ->with(['usuarioEmite' => function ($query) {
                $query->with('trabajador');
            }])
            ->with(['usuarioAnula' => function ($query) {
                $query->with('trabajador');
            }])
            ->with(['documentoSolicitud' => function ($query) {
                $query->with('movimientosCuentas');
            }])->first();

        $cuentas_bancarias_seleccinada = ContabilidadCuentasBancarias::select('id_cuenta_bancaria', 'id_banco', 'id_moneda', 'id_cuenta_contable',
            DB::raw("concat((select b.descripcion from cjabnco.bancos b where b.id_banco = contabilidad.cuentas_bancarias.id_banco),' ',(select moned.descripcion
                from cjabnco.monedas moned where moned.id_moneda = contabilidad.cuentas_bancarias.id_moneda),'(',(select moned.codigo
                from cjabnco.monedas moned where moned.id_moneda = contabilidad.cuentas_bancarias.id_moneda),') ',numero_cuenta) as numero_cuenta")
        )->with('monedaCuentaBancaria')->with('cuentaContableCuentaBancaria')->find($solicitud->id_cuenta_bancaria);
        //$bancos = Bancos::select('id_banco','descripcion','activo')->where('activo', 1) ->with(['cuentasBancariasBanco' => function($query) {
        //  $query->with('monedaCuentaBancaria');}])->get();
        //this.obtenerSucursalesTodas();

        //$id_cuenta_contable_contraparte = 74; //defecto proveedores

        // $cuenta_contraparte = ContabilidadCuentasContablesVista::select('id_cuenta_contable','cta_contable','nombre_cuenta_completo')->find($id_cuenta_contable_contraparte);

//1 - Pago a proveedor o acreedor
//2 - Rembolso de Caja Chica
//3 - Pago a empleado

        return response()->json([
            'status' => 'success',
            'result' => [
                't_cambio' => $tasa,
                'monedas' => $monedas,
                'tipos_documentos' => $tipos_documentos,
                'cuentas_bancarias' => $cuentas_bancarias,
                'cuenta_bancaria_seleccionada' => $cuentas_bancarias_seleccinada,
                'centro_costos_ingresos' => $centro_costos_ingresos,
                'solicitud' => $solicitud
            ],
            'messages' => null
        ]);
    }

    public function registrar(Request $request)
    {
        $rules = [

            'tipo_beneficiario' => 'integer|required',
            // 'proveedor' => 'required_if:tipo_beneficiario,1|required|array|min:0|nullable',
            'proveedor.id_proveedor' => 'required_if:tipo_beneficiario,1|integer|exists:pgsql.inventario.proveedores,id_proveedor|nullable',
            'trabajador.id_trabajador' => 'required_if:tipo_beneficiario,2|integer|exists:pgsql.rrhh.trabajadores,id_trabajador|nullable',
            'concepto' => 'required|string|max:150',
            'beneficiario' => 'required|string|max:150',
            'moneda' => 'required|array|min:1',
            'tipo_solicitud' => 'required|array|min:1',
            'monto_solicitado' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/|min:1',
            //    'monto_dolares_solicitado' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/|min:1',
            'fecha_solicitud' => 'required|date',
            'monto_letras' => 'required|string|max:191',
            'no_tiene_cuentasxpagar' => 'boolean|required',

            'detalleCuentasxPagar' => 'required_if:no_tiene_cuentasxpagar,false|array|min:0',
            'detalleCuentasxPagar.*.cuentax.id_cuentaxpagar' => 'required_if:no_tiene_cuentasxpagar,false|integer|exists:pgsql.cuentasxpagar.cuentasxpagar,id_cuentaxpagar',
            'detalleCuentasxPagar.*.descripcion_pago' => 'required|string|max:300',
            'detalleCuentasxPagar.*.monto' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/',

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            try {
                /*DB::beginTransaction();*/

                $solicitud = new SolicitudesPago;
                $solicitud->beneficiario = $request->beneficiario;
                $solicitud->id_moneda = $request->moneda['id_moneda'];
                $solicitud->id_tipo_solicitud = $request->tipo_solicitud['id_tipo_solicitud'];


                if ($request->tipo_beneficiario == 1) {
                    $solicitud->numero_ruc = $request->proveedor['numero_ruc'];
                    $solicitud->numero_cedula = $request->proveedor['numero_cedula'];
                    $solicitud->id_proveedor = $request->proveedor['id_proveedor'];
                    $solicitud->id_tipo_beneficiario = $request->proveedor['clasificacion_contable'];
                } else {
                    $solicitud->numero_ruc = '';
                    $solicitud->numero_cedula = $request->trabajador['cedula'];
                    $solicitud->id_trabajador = $request->trabajador['id_trabajador'];
                    $solicitud->id_tipo_beneficiario = 3;
                }

                $solicitud->concepto = $request->concepto;
                $solicitud->fecha_solicitud = $request->fecha_solicitud;
                $solicitud->monto = $request->monto_solicitado;
                $solicitud->monto_letras = $request->monto_letras;
                //$solicitud->id_moneda = $request->moneda['id_moneda'];
                $solicitud->estado = 1;
                $solicitud->u_creacion = Auth::user()->name;
                $solicitud->save();

                if (!$request->no_tiene_cuentasxpagar && $request->tipo_beneficiario == 1) {
                    foreach ($request->detalleCuentasxPagar as $recibosDetalles) {
                        $detalle_solicitud = new SolicitudesPagoDetalles;
                        $detalle_solicitud->id_solicitud_pago = $solicitud->id_solicitud_pago;
                        $detalle_solicitud->concepto = $recibosDetalles['descripcion_pago'];
                        $detalle_solicitud->id_tipo_documento = $recibosDetalles['cuentax']['id_tipo_documento'];
                        $detalle_solicitud->monto = $recibosDetalles['monto'];
                        $detalle_solicitud->id_cuentaxpagar = $recibosDetalles['cuentax']['id_cuentaxpagar'];
                        $detalle_solicitud->id_sucursal = 1;
                        $detalle_solicitud->save();

                        /*$cuentas_x_cobrarUpdate = CuentasXPagarCuentasXPagar::findOrFail($detalle_solicitud->id_cuentaxpagar);

                        $saldoActual = round($cuentas_x_cobrarUpdate->saldo_actual_org - $detalle_solicitud->monto,2);

                        if( abs($saldoActual) == 0){
                            $cuentas_x_cobrarUpdate->saldo_actual = 0;
                            $cuentas_x_cobrarUpdate->estado = 2;
                        }else{
                            $cuentas_x_cobrarUpdate->saldo_actual = $saldoActual;
                        }
                        $cuentas_x_cobrarUpdate->save();

                        if($cuentas_x_cobrarUpdate->id_tipo_documento == 1){
                            $facturaActualizar = Facturas::where('id_factura',$cuentas_x_cobrarUpdate->identificador_origen)->first();
                            $facturaActualizar->saldo_factura = round($facturaActualizar->saldo_factura - $recibos_detalles->monto,4);
                            $facturaActualizar->save();
                        }
                        */
                    }

                }


                /*DB::commit();*/
                return response()->json([
                    'status' => 'success',
                    'result' => null,
                    'messages' => null
                ]);
            } catch (Exception $e) {
                /*DB::rollBack();*/
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


    public function revisar(Request $request)
    {

        $rules = [
            'id_solicitud_pago' => 'required|integer|min:1',
            //'num_documento' => 'required|string|max:15',
            'valor' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/|min:0',
            'monto_ir' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/|min:0',
            'monto_letras' => 'required|string|max:191',
            'moneda' => 'required|array|min:1',
            'concepto' => 'required|string|max:200',
            'tipoDocumento' => 'required|array|min:1',
            'cuenta_bancaria' => 'required|array|min:1',
            'detalleMovimientos' => 'required|array|min:2',
            'detalleMovimientos.*.cuentaContable.id_cuenta_contable' => 'required|integer|exists:pgsql.contabilidad.cuentas_contables,id_cuenta_contable',
            'detalleMovimientos.*.debe' => 'required|numeric|min:0',
            'detalleMovimientos.*.haber' => 'required|numeric|min:0',
            'detalleMovimientos.*.concepto' => 'required|string|max:100',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            try {

                DB::beginTransaction();

                $documento = new ContabilidadDocumentosContables;
                $tipo = ContabilidadTiposDocumentos::find($request->tipoDocumento['id_tipo_doc']);

                $documento->num_documento = $tipo->prefijo . '-' . $tipo->secuencia;

                date_default_timezone_set('America/Managua');

                $documento->fecha_emision = date('Y-m-d');

                $date = DateTime::createFromFormat("Y-m-d", $documento->fecha_emision);
                //echo date('Y-m-d h:i:s');
                $periodo = ContabilidadPeriodoFiscal::where('periodo', '=', $date->format("Y"))->first();

                if (empty($periodo)) {
                    return response()->json([
                        'status' => 'error',
                        'result' => array('fecha_emision' => ["El periodo " . $date->format("Y") . " no existe, por favor consulte al administrador"]),
                        'messages' => null
                    ]);
                    exit;
                }

                if ($periodo->estado) {
                    return response()->json([
                        'status' => 'error',
                        'result' => array('fecha_emision' => ["El periodo " . $date->format("Y") . " es inválido, ya que se encuentra en estado COMPLETADO"]),
                        'messages' => null
                    ]);
                    exit;
                }

                $documento->id_periodo_fiscal = $periodo->id_periodo_fiscal;

                $documento->id_tipo_doc = $request->tipoDocumento['id_tipo_doc'];
                $documento->valor = $request->valor;
                $documento->concepto = $request->concepto;
                $documento->id_moneda = $request->moneda['id_moneda'];
                $documento->u_creacion = Auth::user()->usuario;
                $documento->estado = 1;

                $documento->save();
                ContabilidadTiposDocumentos::find($documento->id_tipo_doc)->increment('secuencia');

                foreach ($request->detalleMovimientos as $movimientoCuenta) {
                    $documento_cuenta_contable = new ContabilidadDocumentosMovimientos;

                    $documento_cuenta_contable->id_documento = $documento->id_documento;
                    $documento_cuenta_contable->concepto = $movimientoCuenta['concepto'];
                    $documento_cuenta_contable->debe = $movimientoCuenta['debe'];
                    $documento_cuenta_contable->haber = $movimientoCuenta['haber'];
                    $documento_cuenta_contable->id_centro = $movimientoCuenta['centro_costo']['id_centro'];
                    $documento_cuenta_contable->id_cuenta_contable = $movimientoCuenta['cuentaContable']['id_cuenta_contable'];
                    $documento_cuenta_contable->cta_contable = $movimientoCuenta['cuentaContable']['cta_contable'];
                    $documento_cuenta_contable->cta_contable_padre = $movimientoCuenta['cuentaContable']['cta_contable'];
                    $documento_cuenta_contable->save();
                }

                $solicitud = SolicitudesPago::find($request->id_solicitud_pago);
                $solicitud->id_cuenta_bancaria = $request->cuenta_bancaria['id_cuenta_bancaria'];
                $solicitud->id_documento_contable = $documento->id_documento;
                $solicitud->monto_ir = $request->monto_ir;
                $solicitud->monto_letras = $request->monto_letras;

                $solicitud->estado = 2;
                $solicitud->u_revision = Auth::user()->usuario;
                $solicitud->f_revision = date('Y-m-d h:i:s');
                $solicitud->save();

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'result' => null,
                    'messages' => null
                ]);
            } catch (Exception $e) {
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


    public function contabilizarSolicitudPago(Request $request)
    {

        $messages = [
            'detalleMovimientos.min' => 'Se requiere agregar dos movimientos por lo menos.',
            'detalleMovimientos.*.cuentaContable.id_cuenta_contable.required' => 'Seleccione una cuenta contable válida',
            'detalleMovimientos.*.debe.min' => 'El precio debe ser mayor que cero',
            'detalleMovimientos.*.haber.min' => 'La cantidad debe ser mayor que cero',
            'detalleMovimientos.*.concepto.required' => 'Se debe definir un concepto',
        ];

        $rules = [
            'id_solicitud_pago' => 'required|integer|min:1',
            'fecha_emision' => 'required|date',
            //'num_documento' => 'required|string|max:15',
            'codigo_documento' => 'required|integer|min:1',
            'valor' => 'required|numeric|regex:/^\d*(\.\d{1,4})?$/|min:0',
            'concepto' => 'required|string|max:200',
            'tipoDocumento' => 'required|array|min:1',

            //  'moneda' => 'required|array|min:1',
            'monto_letras' => 'required|string|max:191',
            'moneda' => 'required|array|min:1',
            'cuenta_bancaria' => 'required|array|min:1',
            'detalleMovimientos' => 'required|array|min:2',
            'detalleMovimientos.*.cuentaContable.id_cuenta_contable' => 'required|integer|exists:pgsql.contabilidad.cuentas_contables,id_cuenta_contable',
            'detalleMovimientos.*.debe' => 'required|numeric|min:0',
            'detalleMovimientos.*.haber' => 'required|numeric|min:0',

            'detalleMovimientos.*.monedaMov' => 'required|array|min:1',
            'detalleMovimientos.*.monedaMov.id_moneda' => 'required|integer|min:1',

            'detalleMovimientos.*.debeORG' => 'required|numeric|min:0',
            'detalleMovimientos.*.haberORG' => 'required|numeric|min:0',
            'detalleMovimientos.*.concepto' => 'required|string|max:100',
        ];


        $validator = Validator::make($request->all(), $rules, $messages);
        if (!$validator->fails()) {
            try {

                DB::beginTransaction();
                $documento = new ContabilidadDocumentosContables;
                $tipo_doc = 1;
                if ($request->tipo_pago == 1) {
                    if ($request->cuenta_bancaria['id_moneda'] == 1) {
                        $tipo_doc = 3;
                    } else {
                        $tipo_doc = 4;
                    }
                } else if ($request->tipo_pago == 2) {
                    if ($request->cuenta_bancaria['id_moneda'] == 1) {
                        $tipo_doc = 14;
                    } else {
                        $tipo_doc = 15;
                    }
                }
                $tipo = ContabilidadTiposDocumentos::find($tipo_doc);
                $codigo = $documento->obtenerCodigoDocumento(array('id_tipo_doc' => $tipo->id_tipo_doc, 'fecha_doc' => $request->fecha_emision));

                $nuevo_codigo = json_decode($codigo[0]);

                $documento->num_documento = $tipo->prefijo . '-' . $nuevo_codigo->secuencia;
                $documento->fecha_emision = $request->fecha_emision;
                $documento->codigo_documento = $nuevo_codigo->secuencia;
                $documento->id_moneda = 1;

                $date = DateTime::createFromFormat("Y-m-d", $documento->fecha_emision);
                $meses = config('global.meses');
                $periodo = ContabilidadPeriodoFiscal::where('periodo', $date->format("Y"))->first();

                if (empty($periodo)) {
                    return response()->json([
                        'status' => 'error',
                        'result' => array('fecha_emision' => ["El periodo " . $date->format("Y") . " no se encuentra registrado, por favor consulte al administrador"]),
                        'messages' => null
                    ]);
                    exit;
                }

                if ($periodo->estado) {
                    return response()->json([
                        'status' => 'error',
                        'result' => array('fecha_emision' => ["El periodo " . $date->format("Y") . " es inválido, ya que se encuentra en estado COMPLETADO"]),
                        'messages' => null
                    ]);
                    exit;
                }

                $periodo_mes = ContabilidadPeriodoMeses::where('id_periodo_fiscal', $periodo->id_periodo_fiscal)->where('mes', $date->format("n"))->first();

                if (empty($periodo_mes)) {
                    return response()->json([
                        'status' => 'error',
                        'result' => array('fecha_emision' => ["El mes " . $date->format("F") . " no se encuentra registrado, por favor consulte al administrador"]),
                        'messages' => null
                    ]);
                    exit;
                }

                if ($periodo_mes->estado == 2) {
                    return response()->json([
                        'status' => 'error',
                        'result' => array('fecha_emision' => ["El mes " . $meses[$periodo_mes->mes - 1] . " es inválido, ya que se encuentra en estado COMPLETADO"]),
                        'messages' => null
                    ]);
                    exit;
                }

                $documento->id_periodo_fiscal = $periodo->id_periodo_fiscal;

                $documento->id_tipo_doc = $request->tipoDocumento['id_tipo_doc'];
                $documento->valor = $request->valor;
                $documento->concepto = $request->concepto;

                $documento->u_creacion = Auth::user()->usuario;
                $documento->estado = 99;

                /* date_default_timezone_set('America/Managua');
                 $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
                 $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                 $log['fecha_log'] = $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') .' a las '.date('h:i:s A');
                 $log['registro'] = 'Registro de salida en sistema por '.$documento->u_creacion;
                 $documento->log_salida = '['.json_encode($log).']';  */
                $documento->save();
                ContabilidadTiposDocumentos::find($documento->id_tipo_doc)->increment('secuencia');

                //print_r($request->detalleMovimientos);
                foreach ($request->detalleMovimientos as $movimientoCuenta) {

                    if ($movimientoCuenta['debeORG'] > 0 || $movimientoCuenta['haberORG'] > 0) {
                        $documento_cuenta_contable = new ContabilidadDocumentosMovimientos;

                        $documento_cuenta_contable->id_documento = $documento->id_documento;
                        $documento_cuenta_contable->concepto = $movimientoCuenta['concepto'];
                        $documento_cuenta_contable->debe = $movimientoCuenta['debe'];
                        $documento_cuenta_contable->haber = $movimientoCuenta['haber'];
                        $documento_cuenta_contable->debe_org = $movimientoCuenta['debeORG'];
                        $documento_cuenta_contable->haber_org = $movimientoCuenta['haberORG'];
                        $documento_cuenta_contable->id_moneda = $movimientoCuenta['monedaMov']['id_moneda'];

                        $documento_cuenta_contable->id_centro = $movimientoCuenta['centro_costo_ingreso'] ? $movimientoCuenta['centro_costo_ingreso']['id_centro'] : null;

                        $documento_cuenta_contable->id_cuenta_contable = $movimientoCuenta['cuentaContable']['id_cuenta_contable'];
                        $documento_cuenta_contable->cta_contable = $movimientoCuenta['cuentaContable']['cta_contable'];
                        $documento_cuenta_contable->cta_contable_padre = $movimientoCuenta['cuentaContable']['cta_contable'];
                        $documento_cuenta_contable->save();
                    }
                }

                $solicitud = SolicitudesPago::find($request->id_solicitud_pago);
                $solicitud->id_cuenta_bancaria = $request->cuenta_bancaria['id_cuenta_bancaria'];
                $solicitud->id_moneda_pago = $request->cuenta_bancaria['id_moneda'];
                $solicitud->id_documento_contable = $documento->id_documento;
                $solicitud->monto_neto = $request->monto_neto;
                $solicitud->tipo_pago = $request->tipo_pago;///1 cheque 2 transferencia
                $solicitud->monto_letras_neto = $request->monto_letras;
                $solicitud->estado = 3;///3 estado contabilizado
                $solicitud->u_revision = Auth::user()->usuario;
                $solicitud->f_revision = date('Y-m-d h:i:s');
                $solicitud->save();

//    numero_comprobante_pago smallint,

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'result' => null,
                    'messages' => null
                ]);
            } catch (Exception $e) {
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

    public function cambiarEstadoSolicitudPago(Request $request)
    {
        $rules = [
            'id_solicitud_pago' => 'required|integer|min:0',
            'id_cuenta_bancaria' => 'required|integer|min:0',
            'estado' => 'required|integer|min:0|max:3'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            try {

                DB::beginTransaction();
                $solicitud =
                    SolicitudesPago::find($request->id_solicitud_pago);
                if ($solicitud->estado == 1 && $request->estado == 2) {
                    if (!empty($request->id_cuenta_bancaria)) {
                        $solicitud->estado = $request->estado;
                        $solicitud->id_cuenta_bancaria = $request->id_cuenta_bancaria;
                        $solicitud->u_aprobacion = Auth::user()->usuario;
                        $solicitud->f_aprobacion = date('Y-m-d h:i:s');
                        $solicitud->save();
                    } else {
                        DB::rollBack();
                        return response()->json([
                            'status' => 'error',
                            'result' => 'La cuenta bancaria no es válida',
                            'messages' => null
                        ]);
                    }
                } elseif ($solicitud->estado == 1 && $request->estado == 0) {
                    $solicitud->estado = $request->estado;
                    $solicitud->u_anulacion = Auth::user()->usuario;
                    $solicitud->f_anulacion = date('Y-m-d h:i:s');
                    $solicitud->save();
                } elseif ($solicitud->estado == 2 && $request->estado == 0) {
                    $solicitud->estado = $request->estado;
                    $solicitud->u_anulacion = Auth::user()->usuario;
                    $solicitud->f_anulacion = date('Y-m-d h:i:s');
                    $solicitud->save();
                } else {
                    return response()->json([
                        'status' => 'error',
                        'result' => 'La solicitud de pago no puede ser autorizada',
                        'messages' => null
                    ]);
                }

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'result' => null,
                    'messages' => null
                ]);
            } catch (Exception $e) {
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


    public function aprobar(Request $request)
    {
        $rules = [
            'id_solicitud_pago' => 'required|integer|min:0'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $solicitud = SolicitudesPago::find($request->id_solicitud_pago);
            if ($solicitud->estado == 2) {
                $solicitud->estado = 3;
                $solicitud->u_aprobacion = Auth::user()->usuario;
                $solicitud->f_aprobacion = date('Y-m-d h:i:s');
                $solicitud->save();
            } else {
                return response()->json([
                    'status' => 'error',
                    'result' => 'El cheque no puede ser aprobado',
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


    public function entregarComprobante(Request $request)
    {
        $rules = [
            'id_solicitud_pago' => 'required|integer|min:0',
            'nombre_persona_recibe' => 'required|string|min:10|max:100',
            'identificacion_persona_recibe' => 'required|string|min:10|max:50'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            try {
                DB::beginTransaction();

                $solicitud = SolicitudesPago::find($request->id_solicitud_pago);
                if ($solicitud->estado == 4) {
                    $solicitud->estado = 5;
                    $solicitud->nombre_persona_recibe = $request->nombre_persona_recibe;
                    $solicitud->identificacion_persona_recibe = $request->identificacion_persona_recibe;
                    $solicitud->save();

                    //reembolsos de caja chica
                    if ($solicitud->id_tipo_solicitud == 2) {
                        //print_r('error');
                        $reembolso = CajaChicaReembolso::where('id_solicitud_pago', $solicitud->id_solicitud_pago)->with('reembolsoDetalle')->first();
                        //$reembolso->estado = 4;//estado reembolsado
                        //$reembolso->save();
                        //print_r($reembolso->reembolsoDetalle);
                        foreach ($reembolso->reembolsoDetalle as $detalle) {

                            $comprobante = CajaChicaComprobante::find($detalle['id_comprobante']);
                            $comprobante->estado = 4;//estado reembolsado
                            $comprobante->save();

                            $cajachica = CajaChicaCaja::find($comprobante->id_caja_chica);
                            $cajachica->saldo_actual = $cajachica->saldo_actual + $comprobante->monto_entregado;
                            $cajachica->save();
                        }
                    }

                    DB::commit();

                } else {
                    return response()->json([
                        'status' => 'error',
                        'result' => 'El cheque no puede ser entregado todavia',
                        'messages' => null
                    ]);
                }

                return response()->json([
                    'status' => 'success',
                    'result' => null,
                    'messages' => null
                ]);

            } catch (Exception $e) {
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


    public function emitir(Request $request)
    {
        $rules = [
            'id_solicitud_pago' => 'required|integer|min:0',
            'tipo_pago' => 'required|integer|min:1|max:2',
            'numero_comprobante_pago' => 'required|string|max:50',
            'correo_electronico_beneficiario' => 'string|max:50|nullable',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            try {

                DB::beginTransaction();

                $solicitud = SolicitudesPago::where('id_solicitud_pago', $request->id_solicitud_pago)->with('solicitudDetalles')->first();
                if ($solicitud->estado == 3) {
                    if ($solicitud->tipo_pago === 2) {
                        $solicitud->estado = 8;
                    } else {
                        $solicitud->estado = 4;
                    }

                    $solicitud->tipo_pago = $request->tipo_pago;
                    $solicitud->numero_comprobante_pago = $request->numero_comprobante_pago;
                    $solicitud->correo_electronico_beneficiario = $request->correo_electronico_beneficiario;
                    $solicitud->u_emision = Auth::user()->usuario;
                    $solicitud->f_emision = date('Y-m-d h:i:s');
                    $solicitud->save();

                    //Emitir pago
                    //0 aumentar numeracion chequera
                    if ($solicitud->tipo_pago === 1) {//Tipo cheque
                        $cuenta_bancaria = ContabilidadCuentasBancarias::find($solicitud->id_cuenta_bancaria);
                        if ($cuenta_bancaria->numeracion_chequera >= $solicitud->numero_comprobante_pago) {
                            $cuenta_bancaria->numeracion_chequera = $cuenta_bancaria->numeracion_chequera + 1;
                            $cuenta_bancaria->save();
                        } else {
                            DB::rollBack();
                            return response()->json([
                                'status' => 'error',
                                'result' => array('numero_comprobante_pago' => ['El cheque no puede ser emitido, ya fue emitido otro cheque con ese número']),
                                'messages' => null
                            ]);
                        }
                    }

                    $tasa = 1;
                    if ($solicitud->id_moneda != 1) {
                        $tasaObj = TasasCambios::select('tasa')->where('fecha', $solicitud->f_revision)->first();
                        $tasa = $tasaObj->tasa;
                    }
                    //2 actualizar cuentas por pagar para tipo pago acreedor proveedor y q tenga detalle de facturas
                    if ($solicitud->id_tipo_solicitud == 1 && $solicitud->solicitudDetalles) {
                        //print_r($solicitud->solicitudDetalles);
                        foreach ($solicitud->solicitudDetalles as $solicitud_detalles) {
                            $cuentas_x_pagar = CuentasXPagarCuentasXPagar::find($solicitud_detalles->id_cuentaxpagar);

                            $saldoActual = round($cuentas_x_pagar->saldo_actual - $solicitud_detalles->monto * $tasa, 2);
                            $saldoActualORG = round($cuentas_x_pagar->saldo_actual_org - ($solicitud_detalles->monto), 2);
                            /* 0.0018 */
                            /*revisar cuando saldo es negativo por diferencial cambiario */
                            if (abs($saldoActualORG) == 0) {
                                $cuentas_x_pagar->saldo_actual = 0;
                                $cuentas_x_pagar->saldo_actual_org = 0;
                                $cuentas_x_pagar->estado = 2;
                            } else {
                                $cuentas_x_pagar->saldo_actual = $saldoActual;
                                $cuentas_x_pagar->saldo_actual_org = $saldoActualORG;
                            }
                            $cuentas_x_pagar->save();
                        }

                        $cuentas_x_pagar = new CuentasXPagarCuentasXPagar();
                        $cuentas_x_pagar->id_proveedor = $solicitud->id_proveedor;
                        $cuentas_x_pagar->id_tipo_documento = 3;//pago efectuado
                        $cuentas_x_pagar->no_documento_origen = $solicitud->numero_comprobante_pago;
                        $cuentas_x_pagar->es_registro_importado = false;
                        $cuentas_x_pagar->id_moneda = $solicitud->id_moneda;
                        $cuentas_x_pagar->identificador_origen = $solicitud->id_solicitud_pago;
                        $cuentas_x_pagar->fecha_movimiento = $solicitud->f_emision;
                        $cuentas_x_pagar->monto_movimiento = $solicitud->monto * $tasa;
                        $cuentas_x_pagar->saldo_actual = 0;
                        $cuentas_x_pagar->monto_movimiento_org = $solicitud->monto;
                        $cuentas_x_pagar->saldo_actual_org = 0;
                        $cuentas_x_pagar->fecha_vencimiento = $solicitud->f_emision;
                        $cuentas_x_pagar->descripcion_movimiento = 'Emisión del pago al proveedor por factura(s) pendientes. No. documento ' . $solicitud->numero_comprobante_pago;
                        $cuentas_x_pagar->usuario_registra = $solicitud->u_emision;
                        $cuentas_x_pagar->estado = 1;
                        $cuentas_x_pagar->save();
                    }

                    //1 actualizar solicitudes de reembolso caja chica para tipo reembolso caja chica
                    //3 actualizar cuentas por pagar empleado? mismo que 1 solo que lleva detalle de caja chica

                    DB::commit();

                    return response()->json([
                        'status' => 'success',
                        'result' => null,
                        'messages' => null
                    ]);
                    /*return response()->json([
                        'status' => 'error',
                        'result' => 'El cheque no puede ser emitido, debe ser aprobado primeramente',
                        'messages' => null
                    ]);*/

                } else {
                    return response()->json([
                        'status' => 'error',
                        'result' => 'El cheque no puede ser emitido, debe ser aprobado primeramente',
                        'messages' => null
                    ]);
                }
            } catch (Exception $e) {
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

    public function cambiarEstado(Request $request)
    {
        $rules = [
            'id_solicitud_pago' => 'required|integer|min:1',
            'estado' => 'required|integer|min:0',
            'nombre_persona_recibe' => 'required_if:estado,8|string|min:10|max:100|nullable',
            'identificacion_persona_recibe' => 'required_if:estado,8|string|min:10|max:50|nullable'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            try {

                DB::beginTransaction();

                $solicitud = SolicitudesPago::where('id_solicitud_pago', $request->id_solicitud_pago)->first();

                if ($solicitud->estado == 4 && $request->estado == 5) {
                    ///pasar solicitud de pago de emitido a firmado
                    $documento_contable = ContabilidadDocumentosContables::find($solicitud->id_documento_contable);
                    if (!empty($documento_contable)) {
                        $documento_contable->estado = 1;
                        $documento_contable->save();
                    }
                    $solicitud->estado = $request->estado;
                    $solicitud->save();
                } elseif ($solicitud->estado == 5 && $request->estado == 6) {
                    ///pasar solicitud de pago de firmado a retenido
                    $solicitud->estado = $request->estado;
                    $solicitud->save();
                } elseif ($solicitud->estado == 5 && $request->estado == 7) {
                    ///pasar solicitud de pago de firmado a disponible
                    $solicitud->estado = $request->estado;
                    $solicitud->save();
                } elseif ($solicitud->estado == 6 && $request->estado == 7) {
                    ///pasar solicitud de pago de retenido a disponible
                    $solicitud->estado = $request->estado;
                    $solicitud->save();
                } elseif ($solicitud->estado == 6 && $request->estado == 8) {
                    ///pasar solicitud de pago de disponible a pagado
                    $solicitud->nombre_persona_recibe = $request->nombre_persona_recibe;
                    $solicitud->identificacion_persona_recibe = $request->identificacion_persona_recibe;
                    $solicitud->estado = $request->estado;
                    $solicitud->save();

                    if ($solicitud->id_tipo_solicitud == 2) {
                        //print_r('error');
                        $reembolso = CajaChicaReembolso::where('id_solicitud_pago', $solicitud->id_solicitud_pago)->with('reembolsoDetalle')->first();
                        //$reembolso->estado = 4;//estado reembolsado
                        //$reembolso->save();
                        //print_r($reembolso->reembolsoDetalle);
                        foreach ($reembolso->reembolsoDetalle as $detalle) {

                            $comprobante = CajaChicaComprobante::find($detalle['id_comprobante']);
                            $comprobante->estado = 4;//estado reembolsado
                            $comprobante->save();

                            $cajachica = CajaChicaCaja::find($comprobante->id_caja_chica);
                            $cajachica->saldo_actual = $cajachica->saldo_actual + $comprobante->monto_entregado;
                            $cajachica->save();
                        }
                    }
                } elseif ($solicitud->estado == 7 && $request->estado == 8) {
                    ///pasar solicitud de pago de disponible a pagado
                    $solicitud->nombre_persona_recibe = $request->nombre_persona_recibe;
                    $solicitud->identificacion_persona_recibe = $request->identificacion_persona_recibe;
                    $solicitud->estado = $request->estado;
                    $solicitud->save();

                    if ($solicitud->id_tipo_solicitud == 2) {
                        //print_r('error');
                        $reembolso = CajaChicaReembolso::where('id_solicitud_pago', $solicitud->id_solicitud_pago)->with('reembolsoDetalle')->first();
                        //$reembolso->estado = 4;//estado reembolsado
                        //$reembolso->save();
                        //print_r($reembolso->reembolsoDetalle);
                        foreach ($reembolso->reembolsoDetalle as $detalle) {

                            $comprobante = CajaChicaComprobante::find($detalle['id_comprobante']);
                            $comprobante->estado = 4;//estado reembolsado
                            $comprobante->save();

                            $cajachica = CajaChicaCaja::find($comprobante->id_caja_chica);
                            $cajachica->saldo_actual = $cajachica->saldo_actual + $comprobante->monto_entregado;
                            $cajachica->save();
                        }
                    }
                } elseif ($solicitud->estado == 8 && $request->estado == 9) {
                    ///pasar solicitud de pago de pagado a archivado
                    $solicitud->estado = $request->estado;
                    $solicitud->save();
                } else {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'result' => 'No se puede cambiar el estado',
                        'messages' => null
                    ]);
                }
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'result' => $validator->messages(),
                    'messages' => null
                ]);

            } catch (Exception $e) {
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

    public function anular(Request $request)
    {
        $rules = [
            'id_solicitud_pago' => 'required|integer|min:0'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            $solicitud = SolicitudesPago::find($request->id_solicitud_pago);
            try {
                if (in_array($solicitud->estado, array(1, 2))) {
                    DB::beginTransaction();
                    if ($solicitud->estado == 2) {
                        $documento = ContabilidadDocumentosContables::find($solicitud->id_documento_contable);
                        $documento->estado = 0;
                        $documento->save();
                    }
                    $solicitud->estado = 0;
                    $solicitud->save();
                    DB::commit();
                } else {
                    return response()->json([
                        'status' => 'error',
                        'result' => 'El cheque no puede ser anulado, luego de haber sido emitido',
                        'messages' => null
                    ]);
                }
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'result' => 'Error de base de datos',
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

    public function generarReporteCheque(Request $request)
    {
        // echo $ext;
        $rules = [
            'id_solicitud_pago' => 'required|integer|min:1',
            'extension' => 'required|string|max:4',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            $os = array("xls", "pdf", "html");
            //echo gethostname();
            if (in_array($request->extension, $os)) {

                $hora_actual = time();

                //$nombre_reporte='EmisionChequeBanproCordobas';

                $solicitud = SolicitudesPago::find($request->id_solicitud_pago);
                $cuenta_bancaria = ContabilidadCuentasBancarias::find($solicitud->id_cuenta_bancaria);

                //$nombre_reporte = $cuenta_bancaria->formato_cheque;

                if ($cuenta_bancaria->id_banco === 1 && $cuenta_bancaria->id_moneda === 1) { //validado
                    $nombre_reporte = 'EmisionChequeBanproCordobas';
                } else if ($cuenta_bancaria->id_banco === 1 && $cuenta_bancaria->id_moneda === 3) { //validado
                    $nombre_reporte = 'EmisionChequeBanproDolares';
                } else if ($cuenta_bancaria->id_banco === 2 && $cuenta_bancaria->id_moneda === 1) {
                    $nombre_reporte = 'EmisionChequeBacCordobas';
                } else if ($cuenta_bancaria->id_banco === 2 && $cuenta_bancaria->id_moneda === 3) { //validado
                    $nombre_reporte = 'EmisionChequeBacDolares';
                } else if ($cuenta_bancaria->id_banco === 3 && $cuenta_bancaria->id_moneda === 1) { //validado
                    $nombre_reporte = 'EmisionChequeLafiseCordobas';
                } else if ($cuenta_bancaria->id_banco === 3 && $cuenta_bancaria->id_moneda === 3) { // validado
                    $nombre_reporte = 'EmisionChequeLafiseDolares';
                } else if ($cuenta_bancaria->id_banco === 4 && $cuenta_bancaria->id_moneda === 1) {
                    $nombre_reporte = 'EmisionChequeAvanzCordobas';
                } else if ($cuenta_bancaria->id_banco === 4 && $cuenta_bancaria->id_moneda === 3) {
                    $nombre_reporte = 'EmisionChequeAvanzDolares';
                }

                echo 'Cheque a emitir ' . $nombre_reporte;


                $input = '/var/www/html/resources/reports/' . $nombre_reporte;
                $output = '/var/www/html/resources/reports/' . $hora_actual . $nombre_reporte;

//                $input = 'C:/xampp/htdocs/resources/reports/' . $nombre_reporte;
//                $output = 'C:/xampp/htdocs/resources/reports/' . $hora_actual . $nombre_reporte;


                $nombre_empresa = AdmonAjustes::where('id_ajuste', 4)->select('valor')->first();
                $logo_empresa = AdmonAjustes::where('id_ajuste', 3)->select(DB::raw("substr(valor, 2, length(valor) - 2)::json->>'file_thumbnail' as file_thumbnail"))->first();
                $url = '/var/www/html/resources/reports/';
                //$url = 'C:/xampp7/htdocs/resources/reports/';

                $options = [
                    'format' => [$request->extension],
                    'locale' => 'en',
                    'params' => [
                        'id_solicitud_pago' => $request->id_solicitud_pago,
                        //'empresa_nombre' => $nombre_empresa->valor,
                        //'empresa_logo' =>  env('APP_URL_REPORTS').$logo_empresa->file_thumbnail
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

                if ($request->extension == 'html') {
                    $headers = [
                        'Content-Type' => 'text/html',
                    ];
                }


                return response()->download($output . '.' . $request->extension, $hora_actual . 'ReporteCheque' . '.' . $request->extension, $headers);

//                exec($jasper->process($input, $output, $options)->output() . ' 2>&1', $output);
//                print_r($output);

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

    public function generarReporte(Request $request)
    {
        // echo $ext;
        $rules = [
            'id_solicitud_pago' => 'required|integer|min:1',
            'extension' => 'required|string|max:4',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            $os = array("xls", "pdf", "html");
            //echo gethostname();
            if (in_array($request->extension, $os)) {

                $hora_actual = time();

                $input = '/var/www/html/resources/reports/ReporteSolicitudPago';
                $output = '/var/www/html/resources/reports/' . $hora_actual . 'ReporteSolicitudPago';

                //$input = 'C:/xampp7/htdocs/resources/reports/ReporteSolicitudPago';
                //$output = 'C:/xampp7/htdocs/resources/reports/' .$hora_actual .'ReporteSolicitudPago';


                $nombre_empresa = AdmonAjustes::where('id_ajuste', 4)->select('valor')->first();
                $logo_empresa = AdmonAjustes::where('id_ajuste', 3)->select(DB::raw("substr(valor, 2, length(valor) - 2)::json->>'file_thumbnail' as file_thumbnail"))->first();
                $url = '/var/www/html/resources/reports/';
                //$url = 'C:/xampp7/htdocs/resources/reports/';

                $options = [
                    'format' => [$request->extension],
                    'locale' => 'en',
                    'params' => [
                        'id_solicitud_pago' => $request->id_solicitud_pago,
                        'empresa_nombre' => $nombre_empresa->valor,
                        'empresa_logo' => env('APP_URL_REPORTS') . $logo_empresa->file_thumbnail
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

                if ($request->extension == 'html') {
                    $headers = [
                        'Content-Type' => 'text/html',
                    ];
                }

                /*exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
                print_r($output);*/

                return response()->download($output . '.' . $request->extension, $hora_actual . 'ReporteSolicitudPago' . '.' . $request->extension, $headers);

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
