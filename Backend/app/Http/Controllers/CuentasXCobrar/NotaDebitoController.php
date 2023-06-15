<?php

namespace App\Http\Controllers\CuentasXCobrar;


use App\Http\Controllers\Controller;
use App\Models\Admon\Ajustes;
use App\Models\CajaBanco\Facturas;
use App\Models\Contabilidad\TasasCambios;
use App\Models\Contabilidad\ConfiguracionComprobantes;
use App\Models\Contabilidad\CuentasContables;
use App\Models\Contabilidad\DocumentosContables;
use App\Models\Contabilidad\DocumentosCuentas;
use App\Models\Contabilidad\PeriodosFiscales;
use App\Models\Contabilidad\PeriodosMeses;
use App\Models\Contabilidad\TiposDocumentos;
use App\Models\CuentasXCobrar\CuentasXCobrar;
use App\Models\CuentasXCobrar\TiposNotasDebito;
use DateTime;
use Illuminate\Http\JsonResponse;
use PHPJasper\PHPJasper;
use Illuminate\Support\Facades\Validator, Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CuentasXCobrar\NotaDebito;
use App\Models\CuentasXCobrar\NotaDebitoDetalles;

use Illuminate\Http\Request;

class NotaDebitoController extends Controller
{
    /**
     * Obtener una lista de Documentos Contables
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function obtener(Request $request, NotaDebito $notas_debito)
    {
        $notas_debito = $notas_debito->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $notas_debito->total(),
                'rows' => $notas_debito->items()
            ],
            'messages' => null
        ]);
    }

    /**
     * obtener Estado Finaciero Especifico
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function obtenerNotaDebito(Request $request)
    {
        $rules = [
            'id_nota_debito' => 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $nota_debito = NotaDebito::where('id_nota_debito',$request->id_nota_debito)->with('notaDebitoDetalles','notaDebitoCliente', 'notaDebitoSucursal')->get();
            return response()->json([
                'status' => 'success',
                'result' => $nota_debito[0],
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

    public function nueva(Request $request)
    {
        $tasa = TasasCambios::select('tasa')->where('fecha',date("Y-m-d"))->first();
        $tiposND= TiposNotasDebito::select('id_tipo_nota_debito','descripcion','id_cuenta_contable')->with('tipoCuentaContable')->where('estado',1)->get();
        return response()->json([
            'status' => 'success',
            'result' => [
                't_cambio' => $tasa,
                'tipos_notas' => $tiposND,
            ],
            'messages' => null
        ]);
    }


    /**
     * Crear un nuevo recibo oficial de caja
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function registrar(Request $request)
    {
        $messages = [
            'detalleFacturas.min' => 'Se requiere agregar por lo menos una deuda.',
        ];

    	$rules = [
		    'fecha_emision' => 'required|date',

            'nota_debito_cliente' => 'required|array|min:1',
            'nota_debito_cliente.id_cliente' => 'required|integer|min:1',

            'concepto' => 'required|string|max:300',
            't_cambio' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/',

            'subtotal' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0',
            'monto_total_iva' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0',
            'monto_total' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0',


            'detalleFacturas' => 'required|array|min:1',
            'detalleFacturas.*.nota_debito_factura' => 'required|array|min:1',
            'detalleFacturas.*.nota_debito_factura.id_factura' => 'required|integer|min:1',
            'detalleFacturas.*.nota_debito_tipo' => 'required|array|min:1',
            'detalleFacturas.*.nota_debito_tipo.id_tipo_nota_debito' => 'required|integer|min:1',
            'detalleFacturas.*.descripcion' => 'required|string|max:300',
            'detalleFacturas.*.monto' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/',
            'detalleFacturas.*.subtotal' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/',
            'detalleFacturas.*.monto_iva' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/',

        ];

		$validator = Validator::make($request->all(), $rules, $messages);
		if (!$validator->fails()) {
			try{

			DB::beginTransaction();
                if($request->monto_total>0){
                $nota_debito = new NotaDebito;
                $nota_debito->fecha_emision = $request->fecha_emision;
                $nota_debito->fecha_vencimiento  =  date('Y-m-d', strtotime($nota_debito->fecha_emision. ' + 365 days'));
                $nota_debito->estado = 1;
                $nota_debito->id_cliente =  $request->nota_debito_cliente['id_cliente'];
                $nota_debito->id_cliente_sucursal =  $request->nota_debito_cliente['cliente_sucursal']['id_cliente_sucursal'];

                $nota_debito->concepto = $request->concepto;
                $nota_debito->monto_subtotal = $request->subtotal;
                $nota_debito->monto_iva = $request->monto_total_iva;
                $nota_debito->monto_total = $request->monto_total;

                $nota_debito->t_cambio = $request->t_cambio;
                $nota_debito->secuencia = NotaDebito::max('secuencia')+1;
                $nota_debito->no_documento = 'ND-'.$nota_debito->secuencia;
                $nota_debito->u_creacion = Auth::user()->usuario;
                $nota_debito->save();

                    foreach ($request->detalleFacturas as $NotaDebitoDetalles) {
                        $recibos_detalles = new NotaDebitoDetalles;
                        $recibos_detalles->id_factura = $NotaDebitoDetalles['nota_debito_factura']['id_factura'];
                        $recibos_detalles->id_tipo_nota_debito =  $NotaDebitoDetalles['nota_debito_tipo']['id_tipo_nota_debito'];
                        $recibos_detalles->id_nota_debito = $nota_debito->id_nota_debito;
                        $recibos_detalles->descripcion = $NotaDebitoDetalles['descripcion'];
                        $recibos_detalles->precio = $NotaDebitoDetalles['subtotal'];
                        $recibos_detalles->subtotal = $NotaDebitoDetalles['monto'];
                        $recibos_detalles->iva = $NotaDebitoDetalles['monto_iva'];
                        $recibos_detalles->cantidad = 1;
                        $recibos_detalles->save();
                    }


                    $cuentas_x_cobrar = new CuentasXCobrar;
                    $cuentas_x_cobrar->id_cliente =$nota_debito->id_cliente;
                    $cuentas_x_cobrar->id_cliente_sucursal =$nota_debito->id_cliente_sucursal;
                    $cuentas_x_cobrar->id_tipo_documento = 4; //Tipo 4: N/D
                    $cuentas_x_cobrar->no_documento_origen = $nota_debito->no_documento;
                    $cuentas_x_cobrar->es_registro_importado = false;
                    $cuentas_x_cobrar->identificador_origen = $nota_debito->id_nota_debito;
                    $cuentas_x_cobrar->fecha_movimiento = date("Y-m-d H:i:s");//$nota_debito->fecha_emision;
                    $cuentas_x_cobrar->monto_movimiento = $nota_debito->monto_total;
                    $cuentas_x_cobrar->saldo_actual = 0;
                    $cuentas_x_cobrar->fecha_vencimiento = $nota_debito->fecha_emision;
                    $cuentas_x_cobrar->descripcion_movimiento = 'Debitamos saldo por Nota de Débito No. '.$nota_debito->no_documento;
                    $cuentas_x_cobrar->usuario_registra =$nota_debito->u_creacion;
                    $cuentas_x_cobrar->estado = 1;
                    $cuentas_x_cobrar->save();


                    //Actualizar saldo actual del cliente

                    $factura_update = Facturas::findOrFail($recibos_detalles->id_factura);
                    $factura_update->saldo_factura = round($factura_update->saldo_factura + $nota_debito->monto_total,2);
                    if( abs($factura_update->saldo_factura) == 0){
                        $factura_update->saldo_factura = 0;
                        $factura_update->estado = 2;
                    }else {
                        $factura_update->saldo_factura = $factura_update->saldo_factura;
                    }

                    $factura_update->save();

                    $cuentasXCobrarUpdate = CuentasXCobrar::where('id_cliente',$nota_debito->id_cliente)->where('id_tipo_documento',1)->first();
                    $cuentasXCobrarUpdate->saldo_actual = round($cuentasXCobrarUpdate->saldo_actual + $nota_debito->monto_total,2);
                    if(abs($cuentasXCobrarUpdate->saldo_actual) == 0)
                    {
                        $cuentasXCobrarUpdate->saldo_actual = 0;
                        $cuentasXCobrarUpdate-> estado = 2;
                    }else{
                        $cuentasXCobrarUpdate->saldo_actual = $cuentasXCobrarUpdate->saldo_actual;
                    }

                    $cuentasXCobrarUpdate->save();


                    /*INICIA movimiento contable - recibo*/

                    $documento = new DocumentosContables;
                    $tipo = TiposDocumentos::find(1);
                    $fecha= date("Y-m-d H:i:s");
                    $codigo = $documento->obtenerCodigoDocumento(array('id_tipo_doc'=>1,'fecha_doc'=>$fecha));

                    $nuevo_codigo = json_decode($codigo[0]);

                    date_default_timezone_set('America/Managua');

                    $documento->num_documento = $tipo->prefijo.'-'.$nuevo_codigo->secuencia;
                    $documento->fecha_emision =  date('Y-m-d');
                    $documento->codigo_documento = $nuevo_codigo->secuencia;


                    $date = DateTime::createFromFormat("Y-m-d", $documento->fecha_emision);

                    $periodo = PeriodosFiscales::where('periodo',$date->format("Y"))->get();

                    if(empty($periodo[0])){
                        return response()->json([
                            'status' => 'error',
                            'result' =>   array('fecha_emision'=>["El periodo ".$date->format("Y")." no se encuentra registrado, por favor consulte al administrador"]),
                            'messages' => null
                        ]);
                        exit;
                    }

                    if($periodo[0]->estado){
                        return response()->json([
                            'status' => 'error',
                            'result' =>   array('fecha_emision'=>["El periodo ".$date->format("Y")." es inválido, ya que se encuentra en estado COMPLETADO"]),
                            'messages' => null
                        ]);
                        exit;
                    }

                    $periodo_mes = ContabilidadPeriodoMeses::where('id_periodo_fiscal',$periodo[0]->id_periodo_fiscal)->where('mes',$date->format("n"))->get();

                    if(empty($periodo_mes[0])){
                        return response()->json([
                            'status' => 'error',
                            'result' =>   array('fecha_emision'=>["El mes ".$date->format("F")." no se encuentra registrado, por favor consulte al administrador"]),
                            'messages' => null
                        ]);
                        exit;
                    }

                    if($periodo_mes[0]->estado == 2 ){
                        return response()->json([
                            'status' => 'error',
                            'result' =>   array('fecha_emision'=>["El mes ".config('global.meses')[$periodo_mes[0]->mes-1]." es inválido, ya que se encuentra en estado COMPLETADO"]),
                            'messages' => null
                        ]);
                        exit;
                    }

                    $documento->id_periodo_fiscal = $periodo[0]->id_periodo_fiscal;

                    $documento->id_tipo_doc = 1;
                    $documento->valor = $nota_debito->monto_total;
                    $documento->concepto = 'Registramos nota de débito No. '.$nota_debito->no_documento ;
                    $documento->id_moneda = 1;
                    $documento->u_creacion = Auth::user()->usuario;
                    $documento->estado = 1;

                    $documento->save();
                    TiposDocumentos::find($documento->id_tipo_doc)->increment('secuencia');


                    foreach ($request->detalleFacturas as $NotaDebitoDetalles) {

                        if($NotaDebitoDetalles['nota_debito_tipo']['id_tipo_nota_debito'] === 1)
                        {
                            $id_tipo_configuracion = 2; //Nota debito por cheque rebotado
                            $nombre_seccion_cxc = 'CuentaXCobrar';
                            //obtener datos de BD con estos paremetros
                            $cuentaSeccionCXC = ConfiguracionComprobantes::where('nombre_seccion', $nombre_seccion_cxc)->where('id_tipo_configuracion', $id_tipo_configuracion)->with('configuracionCuentaContable')->first();
                            $cuenta_contable_cxc = CuentasContables::find($cuentaSeccionCXC->id_cuenta_contable);
                            $cuenta_contable_cxc_padre = CuentasContables::find($cuenta_contable_cxc->id_cuenta_padre);

                            $documento_cuenta_CXC = new DocumentosCuentas();
                            $documento_cuenta_CXC->id_documento = $documento->id_documento;
                            $documento_cuenta_CXC->concepto = $cuentaSeccionCXC->descripcion_movimiento . ' ' .$nota_debito->no_documento;

                            if ($cuentaSeccionCXC->debe_haber === 1) {
                                $documento_cuenta_CXC->debe = $NotaDebitoDetalles['subtotal'] + $NotaDebitoDetalles['nota_debito_tipo']['comision'];
                                $documento_cuenta_CXC->haber = 0;
                                $documento_cuenta_CXC->debe_org = $NotaDebitoDetalles['subtotal'] + $NotaDebitoDetalles['nota_debito_tipo']['comision'];
                                $documento_cuenta_CXC->haber_org = 0;
                            } else {

                                $cuentaSeccionCXC->debe = 0;
                                $documento_cuenta_CXC->haber = $NotaDebitoDetalles['subtotal'] + $NotaDebitoDetalles['nota_debito_tipo']['comision'];
                                $documento_cuenta_CXC->debe_org = 0;
                                $documento_cuenta_CXC->haber_org = $NotaDebitoDetalles['subtotal'] + $NotaDebitoDetalles['nota_debito_tipo']['comision'];
                            }

                            //Verificación de centros de costo

                            if ($cuenta_contable_cxc->requiere_aux === 0) {
                                $documento_cuenta_CXC->id_centro = null;
                                $documento_cuenta_CXC->id_cat_auxiliar_cxc = null;

                            } else if ($cuenta_contable_cxc->requiere_aux === 2 || $cuenta_contable_cxc->requiere_aux === 3) {
                                $documento_cuenta_CXC->id_centro = $cuentaSeccionCXC->id_centro_costo;
                                $documento_cuenta_CXC->id_cat_auxiliar_cxc = null;

                            } else if ($cuenta_contable_cxc->requiere_aux === 1) {
                                $documento_cuenta_CXC->id_centro = null;
                                $documento_cuenta_CXC->id_cat_auxiliar_cxc = $cuentaSeccionCXC->id_cat_auxiliar_cxc;
                            }

                            $documento_cuenta_CXC->id_moneda = 1;
                            $documento_cuenta_CXC->id_cuenta_contable = $cuenta_contable_cxc->id_cuenta_contable;
                            $documento_cuenta_CXC->cta_contable = $cuenta_contable_cxc->cta_contable;
                            $documento_cuenta_CXC->cta_contable_padre = $cuenta_contable_cxc_padre->id_cuenta_contable;
                            $documento_cuenta_CXC->save();


                            if($nota_debito->monto_iva>0){

                                $id_tipo_configuracion = 2; //Nota debito por cheque rebotado
                                $nombre_seccion_iva = 'Impuesto';
                                //obtener datos de BD con estos paremetros
                                $cuentaSeccionIVA = ConfiguracionComprobantes::where('nombre_seccion', $nombre_seccion_iva)->where('id_tipo_configuracion', $id_tipo_configuracion)->with('configuracionCuentaContable')->first();
                                $cuenta_contable_iva = CuentasContables::find($cuentaSeccionIVA->id_cuenta_contable);
                                $cuenta_contable_iva_padre = CuentasContables::find($cuenta_contable_iva->id_cuenta_padre);

                                $documento_cuenta_IVA = new DocumentosCuentas();
                                $documento_cuenta_IVA->id_documento = $documento->id_documento;
                                $documento_cuenta_IVA->concepto = $cuentaSeccionCXC->descripcion_movimiento . ' ' .$nota_debito->no_documento;

                                if ($cuentaSeccionIVA->debe_haber === 1) {
                                    $documento_cuenta_IVA->debe = $nota_debito->monto_iva;
                                    $documento_cuenta_IVA->haber = 0;
                                    $documento_cuenta_IVA->debe_org = $nota_debito->monto_iva;
                                    $documento_cuenta_IVA->haber_org = 0;
                                } else {

                                    $documento_cuenta_IVA->debe = 0;
                                    $documento_cuenta_IVA->haber = $nota_debito->monto_iva;
                                    $documento_cuenta_IVA->debe_org = 0;
                                    $documento_cuenta_IVA->haber_org = $nota_debito->monto_iva;
                                }

                                //Verificación de centros de costo

                                if ($cuenta_contable_iva->requiere_aux === 0) {
                                    $documento_cuenta_IVA->id_centro = null;
                                    $documento_cuenta_IVA->id_cat_auxiliar_cxc = null;

                                } else if ($cuenta_contable_iva->requiere_aux === 2 || $cuenta_contable_iva->requiere_aux === 3) {
                                    $documento_cuenta_IVA->id_centro = $cuentaSeccionIVA->id_centro_costo;
                                    $documento_cuenta_IVA->id_cat_auxiliar_cxc = null;

                                } else if ($cuenta_contable_iva->requiere_aux === 1) {
                                    $documento_cuenta_IVA->id_centro = null;
                                    $documento_cuenta_IVA->id_cat_auxiliar_cxc = $cuentaSeccionIVA->id_cat_auxiliar_cxc;
                                }

                                $documento_cuenta_IVA->id_moneda = 1;
                                $documento_cuenta_IVA->id_cuenta_contable = $cuenta_contable_iva->id_cuenta_contable;
                                $documento_cuenta_IVA->cta_contable = $cuenta_contable_iva->cta_contable;
                                $documento_cuenta_IVA->cta_contable_padre = $cuenta_contable_iva_padre->id_cuenta_contable;
                                $documento_cuenta_IVA->save();

                            }

                            $documento_cuenta_contableCliente = new DocumentosMovimientos;
                            $documento_cuenta_contableCliente->id_documento = $documento->id_documento;
                            $documento_cuenta_contableCliente->id_moneda = 1;
                            $documento_cuenta_contableCliente->concepto = $documento->concepto.' . Por factura No. '.$NotaDebitoDetalles['nota_debito_factura']['no_documento'];
                            $documento_cuenta_contableCliente->debe = 0;
                            $documento_cuenta_contableCliente->haber =  $nota_debito->monto_total + $NotaDebitoDetalles['nota_debito_tipo']['comision'];
                            $documento_cuenta_contableCliente->debe_org = 0;
                            $documento_cuenta_contableCliente->haber_org =  $nota_debito->monto_total + $NotaDebitoDetalles['nota_debito_tipo']['comision'];
                            $documento_cuenta_contableCliente->id_centro =  null;
                            $documento_cuenta_contableCliente->id_cuenta_contable = $NotaDebitoDetalles['nota_debito_tipo']['id_cuenta_contable'];
                            $documento_cuenta_contableCliente->cta_contable = $NotaDebitoDetalles['nota_debito_tipo']['tipo_cuenta_contable']['cta_contable'];
                            $documento_cuenta_contableCliente->cta_contable_padre = $NotaDebitoDetalles['nota_debito_tipo']['tipo_cuenta_contable']['cta_contable'];
                            $documento_cuenta_contableCliente->save();
                        // fin contabilización nota debito cheque rebotado
                        } else
                            {
                                $documento_cuenta_contableCliente2 = new DocumentosCuentas;
                                $documento_cuenta_contableCliente2->id_documento = $documento->id_documento;
                                $documento_cuenta_contableCliente2->id_moneda = 1;
                                $documento_cuenta_contableCliente2->concepto = $NotaDebitoDetalles['nota_debito_tipo']['concepto_comprobante'].' '.$NotaDebitoDetalles['nota_debito_factura']['no_documento'];
                                $documento_cuenta_contableCliente2->debe = 0;
                                $documento_cuenta_contableCliente2->haber =  $NotaDebitoDetalles['subtotal'];
                                $documento_cuenta_contableCliente2->debe_org = 0;
                                $documento_cuenta_contableCliente2->haber_org =  $NotaDebitoDetalles['subtotal'];
                                $documento_cuenta_contableCliente2->id_centro =  null;
                                $documento_cuenta_contableCliente2->id_cuenta_contable = $NotaDebitoDetalles['nota_debito_tipo']['id_cuenta_contable'];
                                $documento_cuenta_contableCliente2->cta_contable = $NotaDebitoDetalles['nota_debito_tipo']['tipo_cuenta_contable']['cta_contable'];
                                $documento_cuenta_contableCliente2->cta_contable_padre = $NotaDebitoDetalles['nota_debito_tipo']['tipo_cuenta_contable']['cta_contable'];
                                $documento_cuenta_contableCliente2->save();

                                if($nota_debito->monto_iva>0){
                                    $documento_cuenta_contableS5 = new DocumentosCuentas;
                                    $documento_cuenta_contableS5->id_documento = $documento->id_documento;
                                    $documento_cuenta_contableS5->concepto = 'Registramos Impuesto al valor agregado por nota de debito No. '.$nota_debito->no_documento;
                                    $documento_cuenta_contableS5->id_moneda = 1;
                                    $documento_cuenta_contableS5->debe =0;
                                    $documento_cuenta_contableS5->haber = $nota_debito->monto_iva;
                                    $documento_cuenta_contableS5->debe_org = 0;
                                    $documento_cuenta_contableS5->haber_org =  $nota_debito->monto_iva;
                                    $documento_cuenta_contableS5->id_moneda =  1;
                                    $documento_cuenta_contableS5->id_centro =  null;
                                    $documento_cuenta_contableS5->id_cuenta_contable = 78;
                                    $documento_cuenta_contableS5->cta_contable = '2131-00-000';
                                    $documento_cuenta_contableS5->cta_contable_padre = '2130-00-000';
                                    $documento_cuenta_contableS5->save();
                                }

                                $documento_cuenta_contableCaja = new DocumentosCuentas;
                                $documento_cuenta_contableCaja->id_documento = $documento->id_documento;
                                $documento_cuenta_contableCaja->concepto = $documento->concepto;
                                $documento_cuenta_contableCaja->id_moneda = 1;
                                $documento_cuenta_contableCaja->debe = $nota_debito->monto_total;
                                $documento_cuenta_contableCaja->haber = 0;
                                $documento_cuenta_contableCaja->debe_org = $nota_debito->monto_total;
                                $documento_cuenta_contableCaja->haber_org = 0;
                                $documento_cuenta_contableCaja->id_centro=  null;
                                $documento_cuenta_contableCaja->id_cuenta_contable = 60;
                                $documento_cuenta_contableCaja->cta_contable = '1121-00-000';
                                $documento_cuenta_contableCaja->cta_contable_padre = '1120-00-000';
                                $documento_cuenta_contableCaja->save();
                            }
                    }
                    /* TERMINA MOVIMIENTO CONTABLE*/
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



    public function generarReporte(Request $request)
    {
        // echo $ext;
        $rules = [
            'id_nota_debito' => 'required|integer|min:0',
            'extension' => 'required|string|max:4',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            $os = array("xls", "pdf","html");
            //echo gethostname();
            if (in_array($request->extension, $os)) {

                $hora_actual = time();

                $input =  env('APP_URL_REPORTS').'ReporteNotaDebito';
                $output = env('APP_URL_REPORTS') .$hora_actual .'ReporteNotaDebito';

                //$input = 'C:/xampp7/htdocs/resources/reports/ReporteNotaDebito';
                //$output = 'C:/xampp7/htdocs/resources/reports/' .$hora_actual .'ReporteNotaDebito';


                $nombre_empresa = Ajustes::where('id_ajuste',4)->select('valor')->first();
                $logo_empresa = Ajustes::where('id_ajuste',3)->select(DB::raw("substr(valor, 2, length(valor) - 2)::json->>'file_thumbnail' as file_thumbnail"))->first();
                $url = ENV('APP_URL_REPORTS');
                //$url = 'C:/xampp7/htdocs/resources/reports/';

                $options = [
                    'format' => [$request->extension],
                    'locale' => 'en',
                    'params' => [
                        'id_nota_debitox' => $request->id_nota_debito,
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

                if($request->extension == 'html'){
                    $headers = [
                        'Content-Type' => 'text/html',
                    ];
                }

                /*exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
                print_r($output);*/

                return response()->download($output . '.' . $request->extension, $hora_actual . 'ReporteNotaDebito'.'.' . $request->extension, $headers)->deleteFileAfterSend();

            }
            else {
                return response()->json([
                    'status' => 'error',
                    'result' => $validator->messages(),
                    'messages' => null
                ]);
            }

        }else{
            return '';
        }
    }
}
