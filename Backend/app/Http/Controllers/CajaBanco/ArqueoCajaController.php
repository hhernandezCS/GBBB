<?php


namespace App\Http\Controllers\CajaBanco;

use App\Mail\ArqueoCajaMail;
use App\Models\Admon\Ajustes;
use App\Models\Admon\Departamentos;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\CajaBanco\ArqueoCaja;
use App\Models\CajaBanco\ArqueoCajaDepositos;
use App\Models\CajaBanco\ArqueoCajaDocumentos;
use App\Models\CajaBanco\ArqueoCajaMovBancos;
use App\Models\CajaBanco\Bancos;
use App\Models\CajaBanco\Facturas;
use App\Models\CajaBanco\FacturaViaPagos;
use App\Models\Contabilidad\TasasCambios;
use App\Models\Ventas\Vendedores;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPJasper\Exception\ErrorCommandExecutable;
use PHPJasper\Exception\InvalidCommandExecutable;
use PHPJasper\Exception\InvalidFormat;
use PHPJasper\Exception\InvalidInputFile;
use PHPJasper\Exception\InvalidResourceDirectory;
use PHPJasper\PHPJasper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArqueoCajaController extends Controller
{
    /**
     * Obtener una lista de bancos
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function obtenerArqueos(Request $request, ArqueoCaja $arqueocaja)
    {
        $arqueocaja = $arqueocaja->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $arqueocaja->total(),
                'rows' => $arqueocaja->items()
            ],
            'messages' => null
        ]);
    }

    /**
     * Obtener una lista de bancos sin ningun filtro
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function obtenerDatosArqueo(Request $request)
    {
        $rules = [
            'fecha_arqueo' => 'required|date',
            'id_vendedor' => 'required|integer|min:1',
        ];
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $arqueo = DB::select('SELECT * from cjabnco.cargararqueodiario(?,?)', array($request->id_vendedor, $request->fecha_arqueo));
            $bancos_cheques = Bancos::select('id_banco', 'descripcion'
                , DB::raw("cjabnco.cargarMontoChequesArqueoDiario(" . $request->id_vendedor . ",'" . $request->fecha_arqueo . "',5,id_banco) AS monto_cheque_banco")
            )->orderby('descripcion')->get();

            $bancos_min_transf = Bancos::select('id_banco', 'descripcion'
                , DB::raw("cjabnco.cargarMontoTranfMinutasArqueoDiario(" . $request->id_vendedor . ",'" . $request->fecha_arqueo . "',1,id_banco) AS monto_tran_cordobas")
                , DB::raw("cjabnco.cargarMontoTranfMinutasArqueoDiario(" . $request->id_vendedor . ",'" . $request->fecha_arqueo . "',2,id_banco) AS monto_tran_dolares")
            )->orderby('descripcion')->get();

            $arqueox = ArqueoCaja::where('fecha_arqueo', $request->fecha_arqueo)->where('id_vendedor', $request->id_vendedor)->where('estado', 1)->first();

            //$existe_arqueo=false;
            (!empty($arqueox)) ? $existe_arqueo = true : $existe_arqueo = false;

            return response()->json([
                'status' => 'success',
                'result' => ['arqueo' => $arqueo, 'bancos' => $bancos_cheques, 'bancos_min_transf' => $bancos_min_transf, 'existe_arqueo' => $existe_arqueo],
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
     * obtener tipo de arqueo Especifico
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function obtenerArqueoEspecifico(Request $request)
    {
        $rules = [
            'id_arqueo' => 'required|integer|min:1'
        ];


        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            $arqueo_loop = DB::select('select * from cjabnco.cargararqueoespecifico(?)', array($request->id_arqueo));
            $arqueo_deposito = ArqueoCaja::where('id_arqueo', '=', $request->id_arqueo)->first();
            $arqueo_cheques = ArqueoCajaMovBancos::where('id_arqueo', '=', $request->id_arqueo)->where('tipo', '=', 1)->get();
            $arqueo_minutas = ArqueoCajaMovBancos::where('id_arqueo', '=', $request->id_arqueo)->where('tipo', '=', 2)->get();

            if (!empty($arqueo_loop) && !empty($arqueo_deposito) && !empty($arqueo_cheques) && !empty($arqueo_minutas)) {

                return response()->json([
                    'status' => 'success',
                    'result' => [
                        'arqueo_loop' => $arqueo_loop,
                        'arqueo_deposito' => $arqueo_deposito,
                        'arqueo_cheques' => $arqueo_cheques,
                        'arqueo_minutas' => $arqueo_minutas,
                    ],
                    'messages' => null
                ]);
            }

            return response()->json([
                'status' => 'error',
                'result' => array('id_arqueo' => ["Datos no encontrados"]),
                'messages' => null
            ]);


        }

        return response()->json([
            'status' => 'error',
            'result' => $validator->messages(),
            'messages' => null
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function anular(Request $request)
    {

        $messages = [
        ];


        $rules = [
            'id_arqueo' => 'required|integer',
            'causa_anulacion' => 'required|string|max:100',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if (!$validator->fails()) {

            try {

                DB::beginTransaction();
                $compra_usado = ArqueoCaja::find($request->id_arqueo);
                $compra_usado->estado = 0;
                $compra_usado->causa_anulacion = $request->causa_anulacion;
                $compra_usado->save();

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

    public function nuevo(Request $request)
    {
        $vendedores = Vendedores::select('id_vendedor', 'nombre_completo')->get();
        $tasaObj = TasasCambios::select('tasa', 'tasa_paralela')->where('fecha', date("Y-m-d"))->first();
        $bancos = Bancos::select('*')->where('estado', '=', 1)->get();

        $valor = 0;
        $fecha_actual = Carbon::now()->format('Y-m-d');

        foreach ($bancos as $banco) {
            $banco['fecha_cord'] = $fecha_actual;
            $banco['fecha_dol'] = $fecha_actual;
            $banco['referencia_cord'] = $valor;
            $banco['referencia_dol'] = $valor;
            $banco['valor_cord'] = $valor;
            $banco['valor_dol'] = $valor;
        }
        unset($banco);

        $tasa_cambio = $tasaObj->tasa;
        $tasa_paralela = $tasaObj->tasa_paralela;
        return response()->json([
            'status' => 'success',
            'result' => [
                'vendedores' => $vendedores,
                'tasa_cambio' => $tasa_cambio,
                'tasa_paralela' => $tasa_paralela,
                'bancos' => $bancos
            ],
            'messages' => null
        ]);

    }

    /**
     * Crear un nuevo tipo de Salida
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function registrar(Request $request)
    {
        $rules = [
            'fecha_arqueo' => 'required|date',
            'vendedor' => 'required|array|min:1',
            'vendedor.id_vendedor' => 'required|integer|min:1',
            'observaciones' => 'nullable|string|max:300',

            'cantidad_mil' => 'required|integer|min:0',
            'cantidad_quinientos' => 'required|integer|min:0',
            'cantidad_doscientos' => 'required|integer|min:0',
            'cantidad_cien' => 'required|integer|min:0',
            'cantidad_cincuenta' => 'required|integer|min:0',
            'cantidad_veinte' => 'required|integer|min:0',
            'cantidad_diez' => 'required|integer|min:0',
            'cantidad_cinco' => 'required|integer|min:0',
            'cantidad_uno' => 'required|integer|min:0',
            'cantidad_cincuenta_centavos' => 'required|integer|min:0',
            'cantidad_cien_dol' => 'required|integer|min:0',
            'cantidad_cincuenta_dol' => 'required|integer|min:0',
            'cantidad_veinte_dol' => 'required|integer|min:0',
            'cantidad_diez_dol' => 'required|integer|min:0',
            'cantidad_cinco_dol' => 'required|integer|min:0',
            'cantidad_uno_dol' => 'required|integer|min:0',

            'tasa_cambio' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/',

            'total_retencion' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/',
            'monto_pagado_minuta' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/',
            'monto_pagado_efectivo' => 'required|numeric',/*|regex:/^\d*(\.\d{1,2})?$/*/
            'monto_pagado_tarjeta' => 'required|numeric', /*|regex:/^\d*(\.\d{1,2})?$/*/
            'monto_pagado_cheque' => 'required|numeric', /*|regex:/^\d*(\.\d{1,2})?$/*/
            'monto_pagado_transferencia' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/',
            'monto_pagado' => 'required|numeric', /*|regex:/^\d*(\.\d{1,2})?$/*/
            'monto_credito' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/',
            'monto_ingreso' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/',

            'total_a_depositar' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/',
            'sobrante_faltante' => 'required|numeric',
            'banco_depositado' =>'numeric',

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            try {

                DB::beginTransaction();

                $arqueo = new ArqueoCaja();
                $arqueo->observaciones = $request->observaciones;
                $arqueo->fecha_arqueo = $request->fecha_arqueo;
                $arqueo->u_creacion = Auth::user()->usuario;

                $arqueo->id_vendedor = $request->vendedor['id_vendedor'];

                $arqueo->cantidad_mil = $request->cantidad_mil;
                $arqueo->cantidad_quinientos = $request->cantidad_quinientos;
                $arqueo->cantidad_doscientos = $request->cantidad_doscientos;
                $arqueo->cantidad_cien = $request->cantidad_cien;
                $arqueo->cantidad_cincuenta = $request->cantidad_cincuenta;
                $arqueo->cantidad_veinte = $request->cantidad_veinte;
                $arqueo->cantidad_diez = $request->cantidad_diez;
                $arqueo->cantidad_cinco = $request->cantidad_cinco;
                $arqueo->cantidad_uno = $request->cantidad_uno;
                $arqueo->cantidad_cincuenta_centavos = $request->cantidad_cincuenta_centavos;
                $arqueo->cantidad_veinticinco_centavos = $request->cantidad_veinticinco_centavos;
                $arqueo->cantidad_diez_centavos = $request->cantidad_diez_centavos;

                $arqueo->cantidad_cien_dol = $request->cantidad_cien_dol;
                $arqueo->cantidad_cincuenta_dol = $request->cantidad_cincuenta_dol;
                $arqueo->cantidad_veinte_dol = $request->cantidad_veinte_dol;
                $arqueo->cantidad_diez_dol = $request->cantidad_diez_dol;
                $arqueo->cantidad_cinco_dol = $request->cantidad_cinco_dol;
                $arqueo->cantidad_uno_dol = $request->cantidad_uno_dol;

                $arqueo->tasa_cambio = $request->tasa_cambio;
                $arqueo->total_retencion = $request->total_retencion;
                $arqueo->monto_pagado_minuta = $request->monto_pagado_minuta;
                $arqueo->monto_pagado_efectivo = $request->monto_pagado_efectivo;
                $arqueo->monto_pagado_tarjeta = $request->monto_pagado_tarjeta;
                $arqueo->monto_pagado_cheque = $request->monto_pagado_cheque;
                $arqueo->monto_pagado_transferencia = $request->monto_pagado_transferencia;
                $arqueo->monto_pagado = $request->monto_pagado;
                $arqueo->monto_credito = $request->monto_credito;
                $arqueo->monto_ingresos = $request->monto_ingreso;

                $arqueo->efectivo_inicial = $request->efectivo_inicial;
                $arqueo->banco_depositado = $request->banco_depositado;
                $arqueo->total_a_depositar = $request->total_a_depositar;
                $arqueo->sobrante_faltante = $request->sobrante_faltante;
                $arqueo->costos_gastos = $request->costos_gastos;
                $arqueo->entregado_por = '';
                $arqueo->recibido_por = '';
                $arqueo->revisado_por = '';
                $arqueo->observaciones = $request->observaciones;
                $arqueo->estado = 1;
                $arqueo->save();

                foreach ($request->detalleFacturas as $documento) {

                    $arqueo_doc = new ArqueoCajaDocumentos();
                    $arqueo_doc->id_arqueo = $arqueo->id_arqueo;
                    $arqueo_doc->f_factura = $documento['f_factura'];
                    $arqueo_doc->no_documento = $documento['no_documento'] == null ? '' : $documento['no_documento'];
                    $arqueo_doc->no_recibo = $documento['no_recibo'] == null ? '' : $documento['no_recibo'];
                    $arqueo_doc->doc_exonera = $documento['doc_exonera'];
                    $arqueo_doc->mt_retencion = $documento['mt_retencion'];
                    $arqueo_doc->monto_pagado_minuta = $documento['monto_pagado_minuta'];
                    $arqueo_doc->monto_pagado_efectivo = $documento['monto_pagado_efectivo'];
                    $arqueo_doc->monto_pagado_tarjeta = $documento['monto_pagado_tarjeta'];

                    $arqueo_doc->monto_pagado_cheque = $documento['monto_pagado_cheque'];
                    $arqueo_doc->monto_pagado_transferencia = $documento['monto_pagado_transferencia'];
                    $arqueo_doc->monto_pagado = $documento['monto_pagado'];
                    $arqueo_doc->mt_total = $documento['mt_total'];
                    $arqueo_doc->mt_deuda = $documento['mt_deuda'];
                    $arqueo_doc->estado = $documento['estado'];
                    $arqueo_doc->id_factura = $documento['id_factura'];
                    $arqueo_doc->id_recibo = $documento['id_recibo'];
                    $arqueo_doc->tipo_doc = $documento['tipo_doc'];
                    $arqueo_doc->estadox = $documento['estadox'];
                    $arqueo_doc->save();

                }


                foreach ($request->bancos_montos as $documento) {

                    $arqueo_doc = new ArqueoCajaMovBancos();
                    $arqueo_doc->id_arqueo = $arqueo->id_arqueo;
                    $arqueo_doc->id_banco = $documento['id_banco'];
                    $arqueo_doc->descripcion = $documento['descripcion'];
                    $arqueo_doc->monto_tran_cordobas = $documento['monto_cheque_banco'];
                    $arqueo_doc->monto_tran_dolares = 0;
                    $arqueo_doc->tipo = 1;
                    $arqueo_doc->save();
                }

                foreach ($request->bancos_montos_trans as $documento) {

                    $arqueo_doc = new ArqueoCajaMovBancos();
                    $arqueo_doc->id_arqueo = $arqueo->id_arqueo;
                    $arqueo_doc->id_banco = $documento['id_banco'];
                    $arqueo_doc->descripcion = $documento['descripcion'];
                    $arqueo_doc->monto_tran_cordobas = $documento['monto_tran_cordobas'];
                    $arqueo_doc->monto_tran_dolares = $documento['monto_tran_dolares'];
                    $arqueo_doc->tipo = 2;
                    $arqueo_doc->save();
                }

                DB::commit();

                $this->enviar($arqueo->id_arqueo);
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
                    'messages' => $e->getMessage()
                ]);
            }


        } else {
            return response()->json([
                'status' => 'error',
                'result' => $validator->messages(),
                'messages' => $validator->messages()
            ]);
        }
    }

    /**
     * Envio de resumen de arqueo de caja via mail
     * @Author octaviom
     */
    public function enviar($id_arqueo)
    {
        #region arqueo
        $current_date = "2023-05-11";
//        $correos = 'aruiz@sanmartin.com.ni,nandaime@sanmartin.com.ni,ecaldera@sanmartin.com.ni,paguaga@sanmartin.com.ni,satha@sanmartin.com.ni,s.martingalerias@gmail.com,saabelybaquedano@gmail.com,octaviomr9810@gmail.com,ccantarero@capitalsoftware.com.ni,hhernandez@capital.software,jchavez@capital.software';
        $correos = 'hhernandez@capital.software';
        $destinatarios = explode(',', $correos);
        $arqueo = ArqueoCaja::where('id_arqueo', $id_arqueo)->first();
        $vendedor_arqueo = Vendedores::where('id_vendedor', $arqueo->id_vendedor)->first();
        $bancos = ArqueoCajaMovBancos::where(['id_arqueo' => $arqueo->id_arqueo, 'tipo' => 2])->get();
        #endregion

        #region Top3ProductosVendidos
        $facturas = new Facturas;
        $producto_top_cant = $facturas->obtenerTopProductosVendidos($arqueo->fecha_arqueo);
        #endregion

        #region Top3ProductosVendidosIngresos
        $producto_top_cant_ingresos = $facturas->obtenerTopProductosVendidosIngresos($arqueo->fecha_arqueo);
        #endregion
        if ($arqueo) {
            Mail::bcc($destinatarios)->send(new ArqueoCajaMail($arqueo, $vendedor_arqueo, $bancos, $producto_top_cant, $producto_top_cant_ingresos));

            return response()->json([
                'msg' => 'Arqueo enviado correctamente.',
                'status' => 'success',
                'arqueo' => $arqueo,
            ], 200);
        }
        return response()->json([
            'msg' => 'No se encontro arqueo en la fecha solicitada',
            'fecha' => $current_date,
            'status' => 'Failed',
            'factura' => $facturas
        ], 500);
    }

    /**
     * Actualizar Rol existente
     *
     * @access  public
     * @param Request $request
     * @return JsonResponse
     */

    public function actualizar(Request $request)
    {
        $rules = [
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            try {

                DB::beginTransaction();

                $arqueo = ArqueoCaja::findOrfail($request->id_arqueo);
                $arqueo->estado = 2;
                $arqueo->save();

                foreach ($request->bancos_deposito as $bancos) {

                    $arqueo_doc = new ArqueoCajaDepositos();
                    $arqueo_doc->id_arqueo = $request->id_arqueo;
                    $arqueo_doc->id_banco = $bancos['id_banco'];
                    $arqueo_doc->valor_cord = $bancos['valor_cord'];
                    $arqueo_doc->referencia_cord = $bancos['referencia_cord'];
                    $arqueo_doc->fecha_cord = $bancos['fecha_cord'];
                    $arqueo_doc->valor_dol = $bancos['valor_dol'];
                    $arqueo_doc->referencia_dol = $bancos['referencia_dol'];
                    $arqueo_doc->fecha_dol = $bancos['fecha_dol'];
                    $arqueo->u_creacion = Auth::user()->name;
                    $arqueo_doc->save();
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

    /**
     * Desactiva rol
     *
     * @access  public
     * @param Request $request
     * @return JsonResponse
     */

    public function desactivar(Request $request)
    {
        $rules = [
            'id_banco' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $banco = Bancos::find($request->id_banco);
            $banco->estado = 0;
            $banco->u_modificacion = Auth::user()->name;
            $banco->save();

            return response()->json([
                'status' => 'success',
                'result' => null,
                'messages' => null
            ]);
        }

        return response()->json([
            'status' => 'error',
            'result' => $validator->messages(),
            'messages' => null
        ]);
    }


    public function activar(Request $request)
    {
        $rules = [
            'id_banco' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $banco = Bancos::find($request->id_banco);
            $banco->estado = 1;
            $banco->u_modificacion = Auth::user()->name;
            $banco->save();

            return response()->json([
                'status' => 'success',
                'result' => null,
                'messages' => null
            ]);
        }

        return response()->json([
            'status' => 'error',
            'result' => $validator->messages(),
            'messages' => null
        ]);
    }

    public function generarReporteArqueo($ext, $id_arqueo)
    {
        // echo $ext;
        $rules = [
            'id_arqueo' => 'required|integer|min:1|exists:pgsql.cjabnco.arqueo_caja,id_arqueo',
            'extension' => 'required|string|max:4'
        ];

//        if (!$validator->fails()) {

        $os = array("xls", "pdf");
        //echo gethostname();
        if (in_array($ext, $os, true)) {

            $hora_actual = time();

            $input = env('APP_URL_REPORTES') . 'ArqueoCaja';
            $output = env('APP_URL_REPORTES') . $hora_actual . 'ArqueoCaja';

            //$input = 'C:/xampp7/htdocs/resources/reports/ArqueoCaja';
            //$output = 'C:/xampp7/htdocs/resources/reports/' .$hora_actual . 'ArqueoCaja';

            //Ajustes generales del sistema
            $logo_empresa = Ajustes::where('id_ajuste', 3)->select('valor')->first();
            $nombre_empresa = Ajustes::where('id_ajuste', 4)->select('valor')->first();
            $url = env('APP_URL_REPORTES');
            //$url = 'C:/xampp7/htdocs/resources/reports/';

            $options = [
                'format' => [$ext],
                'locale' => 'en',
                'params' => [
                    'id_arqueo' => $id_arqueo,
                    'directorioReports' => $url,
                    'empresa_logo' => env('APP_URL_IMAGES') . $logo_empresa->valor,
                    'empresa_nombre' => $nombre_empresa->valor,
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

            try {
                $jasper->process($input, $output, $options)->execute();
            } catch (ErrorCommandExecutable $e) {
            } catch (InvalidCommandExecutable $e) {
            } catch (InvalidFormat $e) {
            } catch (InvalidInputFile $e) {
            } catch (InvalidResourceDirectory $e) {
            }

            $headers = [
                'Content-Type' => 'application/pdf',
            ];


            /*                exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
                            print_r($output);*/

            return response()->download($output . '.' . $ext, $hora_actual . 'ArqueoCaja.' . $ext, $headers)->deleteFileAfterSend();

            /*                exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
                            print_r($output);*/
//            }

        }

        return '';
    }

    /**
     * Obtener facturas pagadas con tarjeta y transferencia
     *
     * @access public
     * @param Request $request
     * @return JsonResponse
     * @author rbetancourt
     */

    public function obtenerFacturasTT (Request $request) {

        $facturas = FacturaViaPagos::join('cjabnco.facturas', 'cjabnco.factura_via_pagos.id_factura', '=', 'cjabnco.facturas.id_factura')
            ->join('venta.clientes', 'cjabnco.facturas.id_cliente', '=', 'venta.clientes.id_cliente')
            ->join('cjabnco.bancos', 'cjabnco.bancos.id_banco', '=', 'cjabnco.factura_via_pagos.id_banco')
            ->select('cjabnco.facturas.no_documento', 'venta.clientes.nombre_comercial', 'cjabnco.factura_via_pagos.id_factura_via', 'cjabnco.factura_via_pagos.id_via_pago', 'cjabnco.bancos.descripcion as banco', 'cjabnco.factura_via_pagos.id_moneda', 'cjabnco.factura_via_pagos.numero_minuta', 'cjabnco.factura_via_pagos.numero_transferencia', 'cjabnco.factura_via_pagos.monto', 'cjabnco.factura_via_pagos.monto_me')
            ->whereIn('cjabnco.factura_via_pagos.id_via_pago', array(3,6))
            ->whereNotIn('cjabnco.facturas.estado', [0])
            ->where('cjabnco.facturas.f_factura', $request->fecha_arqueo)
            ->where('cjabnco.facturas.id_vendedor', $request->vendedor['id_vendedor'])
            ->orderBy('cjabnco.facturas.no_documento')
            ->get();

        return response()->json([
            'status' => 'success',
            'result' => ['facturas' => $facturas],
            'messages' => null
        ]);
    }

    /**
     * Actualizar las vías de pago de facturas pagadas con tarjeta y transferencia
     *
     * @access public
     * @param Request $request
     * @return JsonResponse
     * @author rbetancourt
     */

    public function actualizarMetodosPago(Request $request) {
        $i = 0;
        foreach ($request->vias_pagos as $v) {
            $vias_pago = FacturaViaPagos::where('id_factura_via', $v['id_factura_via'])->first();

            if ($v['id_via_pago'] === 3 && $v['numero_minuta'] !== null) {
                $vias_pago->numero_minuta = $v['numero_minuta'];
                $vias_pago->save();
                $i++;
            } elseif ($v['id_via_pago'] === 6 && $v['numero_transferencia'] !== null) {
                $vias_pago->numero_transferencia = $v['numero_transferencia'];
                $vias_pago->save();
                $i++;
            }
        }

        return response()->json([
            'status' => 'success',
            'result' => $i,
            'messages' => null
        ]);
    }
}
