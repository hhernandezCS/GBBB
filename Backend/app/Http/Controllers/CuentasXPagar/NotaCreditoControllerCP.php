<?php

namespace App\Http\Controllers\CuentasXPagar;


use App\Http\Controllers\Controller;
use App\Models\Contabilidad\Monedas;
use App\Models\Contabilidad\TasasCambios;
use App\Models\Contabilidad\DocumentosContables;
use App\Models\Contabilidad\DocumentosCuentas;
use App\Models\Contabilidad\PeriodosFiscales;
use App\Models\Contabilidad\PeriodosMeses;
use App\Models\Contabilidad\TiposDocumentos;
use App\Models\CuentasXPagar\CuentasXPagar;
use App\Models\CuentasXPagar\TiposNotasCreditoCP;
use App\Models\Inventario\Proveedores;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator, Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CuentasXPagar\NotaCreditoCP;
use App\Models\CuentasXPagar\NotaCreditoDetallesCP;

use Illuminate\Http\Request;

class NotaCreditoControllerCP extends Controller
{
    /**
     * Obtener una lista de Documentos Contables
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function obtener(Request $request, NotaCreditoCP $notas_credito)
    {
        $notas_credito = $notas_credito->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $notas_credito->total(),
                'rows' => $notas_credito->items()
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

    public function obtenerNotaCredito(Request $request)
    {
        $rules = [
            'id_nota_credito' => 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $nota_credito = NotaCreditoCP::where('id_nota_credito',$request->id_nota_credito)->with('notaCreditoDetalles','notaCreditoCliente')->get();
            return response()->json([
                'status' => 'success',
                'result' => $nota_credito[0],
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
        $tiposNC= TiposNotasCreditoCP::select('id_tipo_nota_credito','descripcion','id_cuenta_contable')->with('tipoCuentaContable')->where('estado',1)->get();
        $monedas = Monedas::select('id_moneda','descripcion','codigo')->get();


        return response()->json([
            'status' => 'success',
            'result' => [
                't_cambio' => $tasa,
                'tipos_notas' => $tiposNC,
                'monedas'=>$monedas,
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
            'detalleFacturas.min' => 'Se requiere agregar por lo menos una factura.',
        ];

    	$rules = [
		    'fecha_emision' => 'required|date',

            'nota_credito_proveedor' => 'required|array|min:1',
            'nota_credito_proveedor.id_proveedor' => 'required|integer|min:1',

            'nota_credito_moneda' => 'required|array|min:1',
            'nota_credito_moneda.id_moneda' => 'required|integer|min:1',

            'concepto' => 'required|string|max:300',
            't_cambio' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/',

            'subtotal' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0',
            'monto_total_iva' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0',
            'monto_total' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0',


            'detalleFacturas' => 'required|array|min:1',
            'detalleFacturas.*.nota_credito_factura' => 'required|array|min:1',
            'detalleFacturas.*.nota_credito_factura.identificador' => 'required|integer|min:1',
            'detalleFacturas.*.nota_credito_factura.tipo' => 'required|integer|min:1',
            'detalleFacturas.*.nota_credito_tipo' => 'required|array|min:1',
            'detalleFacturas.*.nota_credito_tipo.id_tipo_nota_credito' => 'required|integer|min:1',
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
                $nota_credito = new NotaCreditoCP;
                $nota_credito->fecha_emision = $request->fecha_emision;
                $nota_credito->fecha_vencimiento  =  date('Y-m-d', strtotime($nota_credito->fecha_emision. ' + 365 days'));
                $nota_credito->estado = 1;
                $nota_credito->id_proveedor =  $request->nota_credito_proveedor['id_proveedor'];
                $nota_credito->id_moneda =  $request->nota_credito_moneda['id_moneda'];


                $nota_credito->concepto = $request->concepto;
                $nota_credito->subtotal = $request->subtotal;
                $nota_credito->monto_total_iva = $request->monto_total_iva;
                $nota_credito->monto_total = $request->monto_total;

                $nota_credito->t_cambio = $request->t_cambio;
                $nota_credito->secuencia = NotaCreditoCP::max('secuencia')+1;
                $nota_credito->no_documento = 'NC-'.$nota_credito->secuencia;
                $nota_credito->u_creacion = Auth::user()->usuario;
                $nota_credito->save();

                    foreach ($request->detalleFacturas as $notaCreditoDetalles) {
                        $recibos_detalles = new NotaCreditoDetallesCP;
                        $recibos_detalles->tipo_orden_compra = $notaCreditoDetalles['nota_credito_factura']['tipo'];

                        if($recibos_detalles->tipo_orden_compra == 1){
                            $recibos_detalles->id_orden_compra = $notaCreditoDetalles['nota_credito_factura']['identificador'];
                            $recibos_detalles->id_orden_compra_servicios = null;
                        }else{
                            $recibos_detalles->id_orden_compra = null;
                            $recibos_detalles->id_orden_compra_servicios = $notaCreditoDetalles['nota_credito_factura']['identificador'];
                        }

                        $recibos_detalles->id_tipo_nota_credito =  $notaCreditoDetalles['nota_credito_tipo']['id_tipo_nota_credito'];

                        $recibos_detalles->id_nota_credito = $nota_credito->id_nota_credito;
                        $recibos_detalles->descripcion = $notaCreditoDetalles['descripcion'];
                        $recibos_detalles->precio = $notaCreditoDetalles['subtotal'];
                        $recibos_detalles->subtotal = $notaCreditoDetalles['monto'];
                        $recibos_detalles->iva = $notaCreditoDetalles['monto_iva'];
                        $recibos_detalles->cantidad = 1;
                        $recibos_detalles->save();
                    }

                    $tasa =1;
                    if($nota_credito->id_moneda != 1){
                        $tasaObj = TasasCambios::select('tasa')->where('fecha',$nota_credito->fecha_emision)->first();
                        $tasa = $tasaObj->tasa;
                    }

                    $cuentas_x_pagar = new CuentasXPagar;
                    $cuentas_x_pagar->id_proveedor =$nota_credito->id_proveedor;
                    $cuentas_x_pagar->id_moneda =  $nota_credito->id_moneda;
                    $cuentas_x_pagar->id_tipo_documento = 5; //Tipo 5: N/C
                    $cuentas_x_pagar->no_documento_origen = $nota_credito->no_documento;
                    $cuentas_x_pagar->es_registro_importado = false;
                    $cuentas_x_pagar->identificador_origen = $nota_credito->id_nota_credito;
                    $cuentas_x_pagar->fecha_movimiento = date("Y-m-d H:i:s");//$nota_credito->fecha_emision;

                    $cuentas_x_pagar->monto_movimiento = ($nota_credito->monto_total)*$tasa;
                    $cuentas_x_pagar->saldo_actual = 0;
                    $cuentas_x_pagar->monto_movimiento_org = ($nota_credito->monto_total);
                    $cuentas_x_pagar->saldo_actual_org = 0;

                    $cuentas_x_pagar->fecha_vencimiento = $nota_credito->fecha_emision;
                    $cuentas_x_pagar->descripcion_movimiento = 'Acreditamos por Nota de Crédito No. '.$nota_credito->no_documento;
                    $cuentas_x_pagar->usuario_registra =$nota_credito->u_creacion;
                    $cuentas_x_pagar->estado = 1;
                    $cuentas_x_pagar->save();

                    /*INICIA movimiento contable - recibo*/

                    $documento = new DocumentosContables();
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

                    $periodo_mes = PeriodosMeses::where('id_periodo_fiscal',$periodo[0]->id_periodo_fiscal)->where('mes',$date->format("n"))->get();

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
                    $documento->valor = $nota_credito->monto_total;
                    $documento->concepto = 'Registramos nota de crédito No. '.$nota_credito->no_documento ;
                    $documento->id_moneda = 1;
                    $documento->u_creacion = Auth::user()->usuario;
                    $documento->estado = 1;

                    $documento->save();
                    TiposDocumentos::find($documento->id_tipo_doc)->increment('secuencia');


                    foreach ($request->detalleFacturas as $notaCreditoDetalles) {
                        $documento_cuenta_contableCliente = new DocumentosCuentas;
                        $documento_cuenta_contableCliente->id_documento = $documento->id_documento;
                        $documento_cuenta_contableCliente->id_moneda = 1;
                        $documento_cuenta_contableCliente->concepto = $documento->concepto.' . Por factura No. '.$notaCreditoDetalles['nota_credito_factura']['numero_factura'];
                        $documento_cuenta_contableCliente->debe = $notaCreditoDetalles['subtotal'];
                        $documento_cuenta_contableCliente->haber =  0;
                        $documento_cuenta_contableCliente->debe_org = $notaCreditoDetalles['subtotal'];
                        $documento_cuenta_contableCliente->haber_org =  0;
                        $documento_cuenta_contableCliente->id_centro =  null;
                        $documento_cuenta_contableCliente->id_cuenta_contable = $notaCreditoDetalles['nota_credito_tipo']['id_cuenta_contable'];
                        $documento_cuenta_contableCliente->cta_contable = $notaCreditoDetalles['nota_credito_tipo']['tipo_cuenta_contable']['cta_contable'];
                        $documento_cuenta_contableCliente->cta_contable_padre = $notaCreditoDetalles['nota_credito_tipo']['tipo_cuenta_contable']['cta_contable'];
                        $documento_cuenta_contableCliente->save();
                    }

                    if($nota_credito->monto_total_iva>0){
                        $documento_cuenta_contableS5 = new DocumentosCuentas;
                        $documento_cuenta_contableS5->id_documento = $documento->id_documento;
                        $documento_cuenta_contableS5->concepto = 'Registramos Impuesto al valor agregado por nota de crédito No. '.$nota_credito->no_documento;
                        $documento_cuenta_contableS5->id_moneda = 1;
                        $documento_cuenta_contableS5->debe =$nota_credito->monto_total_iva;
                        $documento_cuenta_contableS5->haber = 0;
                        $documento_cuenta_contableS5->debe_org = $nota_credito->monto_total_iva;
                        $documento_cuenta_contableS5->haber_org =  0;
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
                    $documento_cuenta_contableCaja->debe = 0;
                    $documento_cuenta_contableCaja->haber = $nota_credito->monto_total;
                    $documento_cuenta_contableCaja->debe_org = 0;
                    $documento_cuenta_contableCaja->haber_org = $nota_credito->monto_total;
                    $documento_cuenta_contableCaja->id_centro=  null;

                    $tipo_proveedor = Proveedores::select('clasificacion_contable')->where('id_proveedor',$nota_credito->id_proveedor)->first();

                    if($tipo_proveedor['clasificacion_contable']===1){ ///PROVEEDORES
                        $documento_cuenta_contableCaja->id_cuenta_contable =74;
                        $documento_cuenta_contableCaja->cta_contable = '2111-00-000';
                        $documento_cuenta_contableCaja->cta_contable_padre = '2110-00-000';
                    }elseif($tipo_proveedor['clasificacion_contable']===2){////ACREEDORES
                        $documento_cuenta_contableCaja->id_cuenta_contable =75;
                        $documento_cuenta_contableCaja->cta_contable = '2112-00-000';
                        $documento_cuenta_contableCaja->cta_contable_padre = '2110-00-000';
                    }
                    $documento_cuenta_contableCaja->save();
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
}
