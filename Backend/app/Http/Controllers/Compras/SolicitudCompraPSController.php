<?php

namespace App\Http\Controllers;

use App\Models\Compras\SolicitudCompraPS;

use App\Models\Inventario\Proveedores;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash, Illuminate\Support\Facades\Validator, Illuminate\Support\Facades\Auth;
use App\Models\Compras\SolicitudCompraPSListado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPJasper\Exception\ErrorCommandExecutable;
use PHPJasper\Exception\InvalidCommandExecutable;
use PHPJasper\Exception\InvalidFormat;
use PHPJasper\Exception\InvalidInputFile;
use PHPJasper\Exception\InvalidResourceDirectory;
use PHPJasper\PHPJasper;

class SolicitudCompraPSController extends Controller
{

	/**
     * Get List of Entradas
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function obtener(Request $request, SolicitudCompraPS $ordenes)
    {
        $ordenes = $ordenes->obtenerSolicitudesComprasPS($request);

        foreach($ordenes as $orden ){
            //   print_r($orden_compra);
            $items = collect($orden->solicitudCompraListado);

            $orden->tot_unidades = $items->sum(function($item) {
                return $item['cantidad'];
            });
            $orden->subtotal = $items->sum(function($item) {
                return $item['precio']*$item['cantidad'];
            });

            $orden->total_descuento = $items->sum(function($item) {
                return $item['precio']*$item['cantidad']*($item['descuento']/100);
            });

            $orden->total = $orden->subtotal - $orden->total_descuento;
        }

        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $ordenes->total(),
                'rows' => $ordenes->items()
            ],
            'messages' => null
        ]);
	}


    public function nueva(Request $request)
    {
        $proveedores = Proveedores::where('activo', 1)->orderby('id_proveedor')->select('id_proveedor','nombre_comercial','numero_ruc','numero_cedula')->get();

            return response()->json([
                'status' => 'success',
                'result' => [
                    'proveedores' => $proveedores,
                ],
                'messages' => null
            ]);

    }



	 /**
     * Get List of Ordenes
     *
     * @access  public
     * @param
     * @return JsonResponse
     */


    public function obtenerOrdenCompra(Request $request, SolicitudCompraPS $orden)
    {
		$rules = [
            'id_solicitud_servicio' => 'required|integer|min:1',
            'cargar_dependencias' => 'required|boolean',
		];


        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $orden = $orden->obtenerSolicitudCompra($request);

            if(!empty($orden[0])){

                if($request->cargar_dependencias){
                    $proveedores = Proveedores::where('activo', 1)->orderby('id_proveedor')->get();


                    return response()->json([
                        'status' => 'success',
                        'result' => [
                            'orden' => $orden[0],
                            'proveedores' => $proveedores,
                        ],
                        'messages' => null
                    ]);
                }

                return response()->json([
                'status' => 'success',
                'result' => [
                    'orden' => $orden[0]
                ],
                'messages' => null
            ]);
            }

            return response()->json([
                'status' => 'error',
                'result' => array('id_solicitud_servicio'=>["Datos no encontrados"]),
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
     * Create a New User
     *
     * @access 	public
     * @param
     * @return JsonResponse
     */

	public function registrar(Request $request)
	{

        $messages = [
            'detalleProductos.min' => 'Se requiere agregar un producto por lo menos.',
            'detalleProductos.*.descripcion.required' => 'Seleccione un producto válido',
            'detalleProductos.*.precio.min' => 'El precio debe ser mayor que cero',
            'detalleProductos.*.cantidad.min' => 'La cantidad debe ser mayor que cero',
        ];


    	$rules = [
            'atencion' => 'string|max:100|nullable',
            'email_proveedor' => 'string|max:100|nullable',
            'telefono_proveedor' => 'string|max:50|nullable',
            'referencia_solicitud' => 'string|max:255|nullable',
            'area_proveedor' => 'string|max:100|nullable',
            'es_borrador' => 'required|boolean',
            'f_necesidad' => 'required|date',

            'direccion' => 'string|max:100|nullable',

            'nota' => 'string|max:500|nullable',
            'tiempo_entrega' => 'required|integer|min:1',
            'id_condicion_pago'=> 'required|integer|min:1|max:3',
            'id_medio_pago'=> 'required|integer|min:1|max:6',

            'proveedor' => 'required|array|min:1',
            'proveedor.id_proveedor' =>'required|integer|exists:pgsql.inventario.proveedores,id_proveedor',

            'detalleProductos' => 'required|array|min:1',
            'detalleProductos.*.descripcion' => 'required|string|max:100',
            'detalleProductos.*.precio' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0.01',
            'detalleProductos.*.descuento' => 'required|integer|min:0|max:100',
            'detalleProductos.*.cantidad' => 'required|integer|min:1',

            'tipo_compra'=> 'required|integer|min:1|max:2',

            'total' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0.01',
            'iva' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0',
            'porcentaje_iva' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0',

		];

		$validator = Validator::make($request->all(), $rules,$messages);
		if (!$validator->fails()) {

			try{

			DB::beginTransaction();
			$orden = new SolicitudCompraPS;

            $orden->id_proveedor = $request->proveedor['id_proveedor'];
            $orden->atencion = $request->atencion;
            $orden->email_proveedor = $request->email_proveedor;
            $orden->telefono_proveedor = $request->telefono_proveedor;
            $orden->referencia_solicitud = $request->referencia_solicitud;
            $orden->area_proveedor = $request->area_proveedor;
            $orden->f_necesidad = $request->f_necesidad;
            $orden->direccion = $request->direccion;
            $orden->nota = $request->nota;
            $orden->tiempo_entrega = $request->tiempo_entrega;
            $orden->id_condicion_pago = $request->id_condicion_pago;
            $orden->id_medio_pago = $request->id_medio_pago;
            $orden->tipo_compra = $request->tipo_compra;
            $orden->total = $request->total;
            $orden->porcentaje_iva = $request->porcentaje_iva;
            $orden->iva = $request->iva;
			$orden->u_creacion = Auth::user()->usuario;
            $request->es_borrador === 'true' ? $orden->estado = 99:  $orden->estado = 1;
            $orden->save();

            $i = 1;
			foreach ($request->detalleProductos as $producto) {
                $orden_producto = new SolicitudCompraPSListado;
                $orden_producto->numero_item = $i;
				$orden_producto->id_solicitud_servicio = $orden->id_solicitud_servicio;
                $orden_producto->descripcion = $producto['descripcion'];
                $orden_producto->precio = $producto['precio'];
                $orden_producto->cantidad = $producto['cantidad'];
                $orden_producto->descuento = $producto['descuento'];
                $orden_producto->subtotal = $producto['total'];
				$orden_producto->save();
				$i++;
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


    public function actualizar(Request $request)
    {
        $messages = [
            'orden_compra_productos.min' => 'Se requiere agregar un producto por lo menos.',
            'orden_compra_productos.*.descripcion.required' => 'Seleccione un producto válido',
            'orden_compra_productos.*.precio.min' => 'El precio debe ser mayor que cero',
            'orden_compra_productos.*.cantidad.min' => 'La cantidad debe ser mayor que cero',
        ];

        $rules = [
            'id_solicitud_servicio' => 'required|integer|exists:pgsql.compra.solicitudes_servicios,id_solicitud_servicio',
            'atencion' => 'string|max:100|nullable',
            'email_proveedor' => 'string|max:100|nullable',
            'telefono_proveedor' => 'string|max:50|nullable',
            'referencia_solicitud' => 'string|max:255|nullable',
            'area_proveedor' => 'string|max:100|nullable',
            'es_borrador' => 'required|boolean',
            'f_necesidad' => 'required|date',

            'direccion' => 'string|max:100|nullable',

            'nota' => 'string|max:500|nullable',
            'tiempo_entrega' => 'required|integer|min:1',

            'id_condicion_pago'=> 'required|integer|min:1|max:2',
            'id_medio_pago'=> 'required|integer|min:1|max:6',

            'tipo_compra'=> 'required|integer|min:1|max:2',

            'total' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0.01',
            'iva' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0',
            'porcentaje_iva' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0',

            'orden_compra_proveedor' => 'required|array|min:1',
            'orden_compra_proveedor.id_proveedor' =>'required|integer|exists:pgsql.inventario.proveedores,id_proveedor',

            'orden_compra_productos' => 'required|array|min:1',
            'orden_compra_productos.*.descripcion' => 'required|string|max:100',
            'orden_compra_productos.*.precio' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0.01',
            'orden_compra_productos.*.descuento' => 'required|integer|min:0|max:100',
            'orden_compra_productos.*.cantidad' => 'required|numeric|regex:/^\d*(\.\d{1,2})?$/|min:1',

        ];

        $validator = Validator::make($request->all(), $rules,$messages);
        if (!$validator->fails()) {

            try{


                DB::beginTransaction();
                $orden = SolicitudCompraPS::find($request->id_solicitud_servicio);

                if($orden->estado === 99){

                    $orden->id_proveedor = $request->orden_compra_proveedor['id_proveedor'];
                    $orden->atencion = $request->atencion;
                    $orden->email_proveedor = $request->email_proveedor;
                    $orden->telefono_proveedor = $request->telefono_proveedor;
                    $orden->referencia_solicitud = $request->referencia_solicitud;
                    $orden->area_proveedor = $request->area_proveedor;

                    $orden->f_necesidad = $request->f_necesidad;


                    $orden->direccion = $request->direccion;
                    $orden->nota = $request->nota;
                    $orden->tiempo_entrega = $request->tiempo_entrega;

                    $orden->id_condicion_pago = $request->id_condicion_pago;
                    $orden->id_medio_pago = $request->id_medio_pago;

                    $orden->tipo_compra = $request->tipo_compra;

                    $orden->total = $request->total;
                    $orden->porcentaje_iva = $request->porcentaje_iva;
                    $orden->iva = $request->iva;

                    $orden->u_creacion = Auth::user()->usuario;
                    $request->es_borrador === 'true' ? $orden->estado = 99:  $orden->estado = 1;
                    $orden->save();

                    SolicitudCompraPSListado::where('id_solicitud_servicio', $request->id_solicitud_servicio)->delete();

                    $i = 1;
                    foreach ($request->orden_compra_productos as $producto) {
                        $orden_producto = new SolicitudCompraPSListado;
                        $orden_producto->numero_item = $i;
                        $orden_producto->id_solicitud_servicio = $orden->id_solicitud_servicio;
                        $orden_producto->id_producto = $producto['id_producto'];
                        $orden_producto->precio = $producto['precio'];
                        $orden_producto->cantidad = $producto['cantidad'];
                        $orden_producto->descuento = $producto['descuento'];
                        $orden_producto->subtotal = $producto['total'];
                        $orden_producto->save();
                        $i++;
                    }

                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'result' => null,
                        'messages' => null
                    ]);

                }

                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'result' => 'La orden de compra ha sido modificada previamente, no se pueden grabar los cambios',
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

    /**
     * Cambiar Estado Entrada
     *
     * @access 	public
     * @param
     * @return JsonResponse
     */

    public function cambiarEstado(Request $request)
    {

        $rules = [
            'id_solicitud_servicio' => 'required|integer|exists:pgsql.compra.ordenes_compras,id_solicitud_servicio',
            'estado' => 'required|integer|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $orden = SolicitudCompraPS::find($request->id_solicitud_servicio);
            $respuesta = response()->json([
                'status' => 'success',
                'result' => null,
                'messages' => null
            ]);
            if($request->estado >= 0 && $request->estado <= 4 && $orden->estado != $request->estado){


                $estado_org = $orden->estado;

                    if( ($estado_org === 1 && ($request->estado === 2|| $request->estado=== 0))
                        || (($estado_org === 2 && ($request->estado === 3 || $request->estado === 0))
                            || ($estado_org === 3 && ($request->estado === 4|| $request->estado === 0))
                                || ($estado_org === 4 && ($request->estado === 5|| $request->estado === 0)))) {
                        $orden->estado = $request->estado;
                        $orden->save();

                        DB::commit();
                        return $respuesta;
                    }

                DB::rollBack();
                $respuesta = response()->json([
                    'status' => 'error',
                    'result' => 'Error al cambiar el estado de la orden de compras ('. $estado_org.' a '.$request->estado.' )',
                    'messages' => null
                ]);
            }else{
                $respuesta=  response()->json([
                    'status' => 'error',
                    'result' => 'Error al cambiar el estado de la orden de compras',
                    'messages' => null
                ]);
            }

            return $respuesta;

        }

        return response()->json([
            'status' => 'error',
            'result' => $validator->messages(),
            'messages' => null
        ]);
    }


    public function reporte($id_solicitud_servicio)
    {
        // echo $ext;
        $ext = 'pdf';
        $os = array("pdf");
        if (in_array($ext, $os)) {
            $hora_actual = time() ;
            $input = 'C:/xampp7/htdocs/resources/reports/OrdenDeCompra';
            $output = 'C:/xampp7/htdocs/resources/reports/' .$hora_actual . 'OrdenDeCompra';
            //$input = '/var/www/html/resources/reports/OrdenDeCompra';
            //$output = '/var/www/html/resources/reports/'.$hora_actual.'OrdenDeCompra';
             $url = '/var/www/html/resources/reports/';
            //$url = 'C:/xampp7/htdocs/resources/reports/';

            $options = [
                'format' => [$ext],
                'locale' => 'en',
                'params' => [
                    'id_solicitud_servicio' => $id_solicitud_servicio,
                    //  'directorioReports'=>$url
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

            /*exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
          print_r($output);*/

            $headers = [
                'Content-Type' => 'application/pdf',
            ];

            return response()->download($output . '.' . $ext ,$hora_actual. 'OrdenDeCompra.' . $ext, $headers);
        }

        return '';
    }

}

