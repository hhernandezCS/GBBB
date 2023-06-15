<?php

namespace App\Http\Controllers;


use App\Models\CajaBanco\TasasCambios;
use App\Models\Contabilidad\DocumentosContables;
use App\Models\Contabilidad\DocumentosCuentas;
use App\Models\Contabilidad\PeriodosFiscales;
use App\Models\Contabilidad\PeriodosMeses;
use App\Models\Contabilidad\TiposDocumentos;
use App\Models\CuentasXCobrar\CuentasXCobrar;
use App\Models\CuentasXCobrar\TiposCargosAbonosTrabajador;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator, Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CuentasXCobrar\CargosAbonosTrabajador;


use Illuminate\Http\Request;

class CargoAbonoTrabajadorController extends Controller
{
    /**
     * Obtener una lista de Documentos Contables
     *
     * @access  public
     * @param
     * @return  json(array)
     */

    public function obtener(Request $request, CargosAbonosTrabajador $cargos_abonos_trabajador)
    {
        $cargos_abonos_trabajador = $cargos_abonos_trabajador->obtener($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $cargos_abonos_trabajador->total(),
                'rows' => $cargos_abonos_trabajador->items()
            ],
            'messages' => null
        ]);
    }

    /**
     * obtener Estado Finaciero Especifico
     *
     * @access  public
     * @param
     * @return  json(array)
     */

    public function obtenerCargoAbonoTrabajador(Request $request)
    {
        $rules = [
            'id_cargo_abono_trabajador' => 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $cargo_abono_trabajador = CargosAbonosTrabajador::where('id_cargo_abono_trabajador',$request->id_cargo_abono_trabajador)->with('cargoAbonoTrabajador','cargoAbonoTrabajadorTipo')->get();
            return response()->json([
                'status' => 'success',
                'result' => $cargo_abono_trabajador[0],
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
        $tiposAC= TiposCargosAbonosTrabajador::select('id_tipo_cargo_abono_trabajador','descripcion','id_cuenta_contable','tipo',
        DB::raw("case when tipo=2 then concat(descripcion,' (ABONO)') else concat(descripcion,' (CARGO)') end as descripcionx ") )->with('tipoCuentaContable')->where('estado',1)->get();
        return response()->json([
            'status' => 'success',
            'result' => [
                't_cambio' => $tasa,
                'tipos_abonos_cargos' => $tiposAC,
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

            'cargo_abono_trabajador' => 'required|array|min:1',
            'cargo_abono_trabajador.id_trabajador' => 'required|integer|min:1',

            'concepto' => 'required|string|max:300',
            't_cambio' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/',

            'monto_total' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0',

            'cargo_abono_trabajador_tipo' => 'required|array|min:1',
            'cargo_abono_trabajador_tipo.id_tipo_cargo_abono_trabajador' => 'required|integer|min:1',
            'cargo_abono_trabajador_tipo.tipo' => 'required|integer|min:1|max:2',

            'cargo_abono_trabajador_cuenta' => 'nullable|required_if:tipo,2|array|min:1',
            'cargo_abono_trabajador_cuenta.id_cuentaxcobrar' => 'nullable|required_if:tipo,2|integer|exists:pgsql.cuentasxcobrar.cuentasxcobrar,id_cuentaxcobrar',

        ];

		$validator = Validator::make($request->all(), $rules, $messages);
		if (!$validator->fails()) {
			try{

			DB::beginTransaction();
                if($request->monto_total>0){
                $cargo_abono_trabajador = new CargosAbonosTrabajador;
                $cargo_abono_trabajador->fecha_emision = $request->fecha_emision;
                $cargo_abono_trabajador->fecha_vencimiento  =  $request->fecha_emision;
                $cargo_abono_trabajador->estado = 1;
                $cargo_abono_trabajador->id_trabajador =  $request->cargo_abono_trabajador['id_trabajador'];

                $cargo_abono_trabajador->concepto = $request->concepto;
                $cargo_abono_trabajador->monto_total = $request->monto_total;

                $cargo_abono_trabajador->t_cambio = $request->t_cambio;
                $cargo_abono_trabajador->secuencia = CargosAbonosTrabajador::max('secuencia')+1;
                $cargo_abono_trabajador->no_documento = 'TR-EMP-'.$cargo_abono_trabajador->secuencia;
                $cargo_abono_trabajador->u_creacion = Auth::user()->usuario;
                $cargo_abono_trabajador->id_tipo_cargo_abono_trabajador =  $request->cargo_abono_trabajador_tipo['id_tipo_cargo_abono_trabajador'];

                $cargo_abono_trabajador->save();


                    $cuentas_x_cobrar = new CuentasXCobrar;
                    $cuentas_x_cobrar->id_trabajador =$cargo_abono_trabajador->id_trabajador;

                    if($request->cargo_abono_trabajador_tipo['tipo']==1){
                        $cuentas_x_cobrar->id_tipo_documento = 7; //Tipo 7: cargo empleado
                        $cuentas_x_cobrar->monto_movimiento = $cargo_abono_trabajador->monto_total;
                        $cuentas_x_cobrar->descripcion_movimiento = 'Registro de cargo a cta de empleado, por doc. No. '.$cargo_abono_trabajador->no_documento.', en concepto de '.$request->cargo_abono_trabajador_tipo['descripcion'];
                        $cuentas_x_cobrar->saldo_actual = $cargo_abono_trabajador->monto_total;
                    }

                    if($request->cargo_abono_trabajador_tipo['tipo']==2){
                        $cuentas_x_cobrar->id_tipo_documento = 8; //Tipo 8:  abono empleado
                        $cuentas_x_cobrar->monto_movimiento = $cargo_abono_trabajador->monto_total*-1;
                        $cuentas_x_cobrar->descripcion_movimiento = 'Registro de abono a cta de empleado, por doc. No. '.$cargo_abono_trabajador->no_documento.', en concepto de '.$request->cargo_abono_trabajador_tipo['descripcion'];
                        $cuentas_x_cobrar->saldo_actual = 0;


                        $cuentaActualizar = CuentasXCobrar::find($request->cargo_abono_trabajador_cuenta['id_cuentaxcobrar']);

                        $saldoActual = round($cuentaActualizar->saldo_actual - $cargo_abono_trabajador->monto_total,2);

                        if( abs($saldoActual) == 0){
                            $cuentaActualizar->saldo_actual = 0;
                            $cuentaActualizar->estado = 2;
                        }else{
                            $cuentaActualizar->saldo_actual = $saldoActual;
                        }
                        $cuentaActualizar->save();
                    }

                    $cuentas_x_cobrar->no_documento_origen = $cargo_abono_trabajador->no_documento;
                    $cuentas_x_cobrar->es_registro_importado = false;
                    $cuentas_x_cobrar->identificador_origen = $cargo_abono_trabajador->id_cargo_abono_trabajador;
                    $cuentas_x_cobrar->fecha_movimiento = date("Y-m-d H:i:s");//$cargo_abono_trabajador->fecha_emision;

                    $cuentas_x_cobrar->fecha_vencimiento = $cargo_abono_trabajador->fecha_emision;
                    $cuentas_x_cobrar->usuario_registra =$cargo_abono_trabajador->u_creacion;
                    $cuentas_x_cobrar->estado = 1;
                    $cuentas_x_cobrar->save();

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
                    $documento->valor = $cargo_abono_trabajador->monto_total;


                    if($request->cargo_abono_trabajador_tipo['tipo']==1){
                        $documento->concepto = 'Realizamos cargo a empleado, por documento No. '.$cargo_abono_trabajador->no_documento.', en concepto de '.$request->cargo_abono_trabajador_tipo['descripcion'];
                    }elseif($request->cargo_abono_trabajador_tipo['tipo']==2){
                        $documento->concepto = 'Realizamos abono de empleado, por documento No. '.$cargo_abono_trabajador->no_documento.', en concepto de '.$request->cargo_abono_trabajador_tipo['descripcion'];
                    }

                    $documento->id_moneda = 1;
                    $documento->u_creacion = Auth::user()->usuario;
                    $documento->estado = 1;

                    $documento->save();
                    TiposDocumentos::find($documento->id_tipo_doc)->increment('secuencia');

                    $documento_cuenta_contableCliente = new DocumentosCuentas;
                    $documento_cuenta_contableCliente->id_documento = $documento->id_documento;
                    $documento_cuenta_contableCliente->id_moneda = 1;
                    $documento_cuenta_contableCliente->concepto = $documento->concepto;

                    if($request->cargo_abono_trabajador_tipo['tipo']==1){
                        $documento_cuenta_contableCliente->debe = 0;
                        $documento_cuenta_contableCliente->haber =  $cargo_abono_trabajador->monto_total;
                        $documento_cuenta_contableCliente->debe_org = 0;
                        $documento_cuenta_contableCliente->haber_org =  $cargo_abono_trabajador->monto_total;
                        $documento_cuenta_contableCliente->id_centro =  null;
                    }elseif($request->cargo_abono_trabajador_tipo['tipo']==2){
                        $documento_cuenta_contableCliente->haber= 0;
                        $documento_cuenta_contableCliente->debe =  $cargo_abono_trabajador->monto_total;
                        $documento_cuenta_contableCliente->haber_org = 0;
                        $documento_cuenta_contableCliente->debe_org =  $cargo_abono_trabajador->monto_total;
                        $documento_cuenta_contableCliente->id_centro =  null;
                    }

                    $documento_cuenta_contableCliente->id_cuenta_contable = $request->cargo_abono_trabajador_tipo['id_cuenta_contable'];
                    $documento_cuenta_contableCliente->cta_contable = $request->cargo_abono_trabajador_tipo['tipo_cuenta_contable']['cta_contable'];
                    $documento_cuenta_contableCliente->cta_contable_padre = $request->cargo_abono_trabajador_tipo['tipo_cuenta_contable']['cta_contable'];
                    $documento_cuenta_contableCliente->save();


                    $documento_cuenta_contableCaja = new DocumentosCuentas;
                    $documento_cuenta_contableCaja->id_documento = $documento->id_documento;
                    $documento_cuenta_contableCaja->concepto = $documento->concepto;
                    $documento_cuenta_contableCaja->id_moneda = 1;


                    if($request->cargo_abono_trabajador_tipo['tipo']==2){
                        $documento_cuenta_contableCaja->debe = 0;
                        $documento_cuenta_contableCaja->haber =  $cargo_abono_trabajador->monto_total;
                        $documento_cuenta_contableCaja->debe_org = 0;
                        $documento_cuenta_contableCaja->haber_org =  $cargo_abono_trabajador->monto_total;
                        $documento_cuenta_contableCaja->id_centro =  null;
                    }elseif($request->cargo_abono_trabajador_tipo['tipo']==1){
                        $documento_cuenta_contableCaja->haber= 0;
                        $documento_cuenta_contableCaja->debe =  $cargo_abono_trabajador->monto_total;
                        $documento_cuenta_contableCaja->haber_org = 0;
                        $documento_cuenta_contableCaja->debe_org =  $cargo_abono_trabajador->monto_total;
                        $documento_cuenta_contableCaja->id_centro =  null;
                    }


                    $documento_cuenta_contableCaja->id_cuenta_contable = 62;
                    $documento_cuenta_contableCaja->cta_contable = '1123-00-000';
                    $documento_cuenta_contableCaja->cta_contable_padre = '1120-00-000';
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
