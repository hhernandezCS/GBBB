<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\Controller;
use App\Models\Admon\Ajustes;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\Compras\OrdenCompra;
use App\Models\Compras\OrdenCompraProductos;
use App\Models\Compras\OrdenCompraPS;
use App\Models\Compras\SolicitudCompra;
use App\Models\Contabilidad\Monedas;
use App\Models\Contabilidad\TasasCambios;
use App\Models\CuentasXPagar\CuentasXPagar;
use App\Models\Inventario\BodegaProductos;
use App\Models\Inventario\Bodegas;
use App\Models\Inventario\EntradaProductos;
use App\Models\Inventario\EntradaProductosCons;
use App\Models\Inventario\Entradas;
use App\Models\Inventario\EntradasInicial;
use App\Models\Inventario\ImportacionConfiguracion;
use App\Models\Inventario\MovimientosProductos;
use App\Models\Inventario\Productos;
use App\Models\Inventario\Proveedores;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PHPJasper\PHPJasper;

class OrdenCompraController extends Controller
{

    public function obtenerFacturasCompraProveedor(Request $request)
    {
        $ordenes_compra_baterias = OrdenCompra::select(DB::RAW('1 as tipo'), 'id_orden_compra as identificador',
            'id_proveedor', 'numero_factura', 'id_moneda', DB::raw('round(total+iva,2) as total'), DB::raw("CONCAT(numero_factura,' ( Monto: ',(select m.codigo from cjabnco.monedas m where m.id_moneda=compra.ordenes_compras.id_moneda),round(total+iva,2),')') AS text")
        )
            ->where('id_proveedor', $request->id_proveedor)->where('estado', 4)->get();

        $ordenes_compra_servicios = OrdenCompraPS::select(DB::RAW('2 as tipo'), 'id_orden_servicio as identificador',
            'id_proveedor', 'numero_factura', 'id_moneda', DB::raw('round(total+iva,2) as total'), DB::raw("CONCAT(numero_factura,' ( Monto: ',(select m.codigo from cjabnco.monedas m where m.id_moneda=compra.ordenes_servicios.id_moneda),round(total+iva,2),')') AS text")
        )
            ->where('id_proveedor', $request->id_proveedor)->where('estado', 4)->get();

        return response()->json([
            'status' => 'success',
            'result' => [
                'ordenes_compra_baterias' => $ordenes_compra_baterias,
                'ordenes_compra_servicios' => $ordenes_compra_servicios,

            ],
            'messages' => null
        ]);
    }

    /**
     * Get List of Entradas
     *
     * @access  public
     * @param Request $request
     * @param OrdenCompra $ordenes
     * @return json|JsonResponse
     */

    public function obtener(Request $request, OrdenCompra $ordenes)
    {
        $ordenes = $ordenes->obtenerOrdenesCompras($request);

        foreach ($ordenes as $orden) {
            //   print_r($orden_compra);
            $items = collect($orden->ordenCompraProductos);

            $orden->tot_unidades = $items->sum(function ($item) {
                return $item['cantidad'];
            });
            $orden->subtotal = $items->sum(function ($item) {
                return $item['precio_cord'] * $item['cantidad'];
            });

            $orden->total_descuento = $items->sum(function ($item) {
                return $item['precio_cord'] * $item['cantidad'] * ($item['descuento'] / 100);
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

    public function buscar(Request $request, OrdenCompra $ordenes_compra)
    {
        $ordenes_compra = $ordenes_compra->buscar($request);
        return response()->json([
            'results' => $ordenes_compra
        ]);
    }

    public function nueva(Request $request)
    {
        $proveedores = Proveedores::where('estado', 1)->orderby('id_proveedor')->select('id_proveedor', 'nombre_comercial', 'numero_ruc', 'numero_cedula')->get();
        $bodegas = Bodegas::where('estado', 1)->get();
        $monedas = Monedas::where('estado', 1)->orderBy('id_moneda')->get();
        $productos = Productos::select(['id_producto', 'codigo_barra', 'codigo_consecutivo', 'codigo_sistema', 'condicion', 'precio_compra', 'descripcion', DB::raw("CONCAT(inventario.productos.nombre_comercial,' (',inventario.productos.codigo_barra,')') AS text")])
            ->where('estado', 1)->whereIn('id_tipo_producto', array(1, 3))->where('condicion', 1)->orderBy('descripcion', 'asc')
            ->get();
        $tasa = TasasCambios::select('tasa')->where('fecha', date("Y-m-d"))->first();


        if (!empty($request->id_solicitud_compra)) {
            $solicitud = SolicitudCompra::where('id_solicitud_compra', $request->id_solicitud_compra)->with(['solicitudProductos' => function ($query) {
                $query->with('solicitudProducto');
            }])->with('solicitudMoneda')->with('solicitudBodega')->first();

            if (!empty($solicitud)) {
                return response()->json([
                    'status' => 'success',
                    'result' => [
                        'proveedores' => $proveedores,
                        'bodegas' => $bodegas,
                        'monedas' => $monedas,
                        'solicitud' => $solicitud,
                        'tasa' => $tasa
//                        'logo_empresa' => $logo_empresa->file_thumbnail
                    ],
                    'messages' => null
                ]);
            }

            return response()->json([
                'status' => 'error',
                'result' => array('id_solicitud_compra' => ["Datos no encontrados"]),
                'messages' => null
            ]);

        }

        return response()->json([
            'status' => 'success',
            'result' => [
                'proveedores' => $proveedores,
                'productos' => $productos,
                'bodegas' => $bodegas,
                'monedas' => $monedas,
                'tasa' => $tasa
//                'logo_empresa' => $logo_empresa->file_thumbnail
            ],
            'messages' => null
        ]);
    }


    /**
     * Get List of Ordenes
     *
     * @access  public
     * @param Request $request
     * @param OrdenCompra $orden
     * @return JsonResponse
     */


    public function obtenerOrdenCompra(Request $request, OrdenCompra $orden)
    {
        $rules = [
            'id_orden_compra' => 'required|integer|min:1',
            'cargar_dependencias' => 'required|boolean',
        ];


        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $orden = $orden->obtenerOrdenCompra($request);

            if (!empty($orden)) {

                if ($request->cargar_dependencias) {
                    $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
                    $proveedores = Proveedores::where('estado', 1)->orderby('id_proveedor')->get();
                    $bodegas = Bodegas::where('estado', 1)->get();
                    $monedas = Monedas::where('estado', 1)->orderBy('id_moneda')->get();
                    $tasa = TasasCambios::select('tasa')->where('fecha', date("Y-m-d"))->first();
                    $nombre_empresa = Ajustes::where('id_ajuste', 4)->select('valor')->first();
                    $direccion_empresa = Ajustes::where('id_ajuste', 5)->where('id_empresa', $usuario_empresa->id_empresa)->select('valor')->first();
                    $telefono_empresa = Ajustes::where('id_ajuste', 6)->where('id_empresa', $usuario_empresa->id_empresa)->select('valor')->first();

                    return response()->json([
                        'status' => 'success',
                        'result' => [
                            'orden' => $orden,
                            'proveedores' => $proveedores,
                            'bodegas' => $bodegas,
                            'monedas' => $monedas,
                            'tasa' => $tasa,
                            'nombre_empresa' => $nombre_empresa->valor,
                            'direccion_empresa' => $direccion_empresa->valor,
                            'telefono_empresa' => $telefono_empresa->valor,
                        ],
                        'messages' => null
                    ]);
                }

                return response()->json([
                    'status' => 'success',
                    'result' => [
                        'orden' => $orden
                    ],
                    'messages' => null
                ]);
            }

            return response()->json([
                'status' => 'error',
                'result' => array('id_orden_compra' => ["Datos no encontrados"]),
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
     * @access    public
     * @param
     * @return JsonResponse
     */

    public function registrar(Request $request)
    {

        $messages = [
            'detalleProductos.min' => 'Se requiere agregar un producto por lo menos.',
            'detalleProductos.*.productox.id_producto.required' => 'Seleccione un producto válido',
    /*        'detalleProductos.*.precio_dol.min' => 'El precio debe ser mayor que cero',*/
            'detalleProductos.*.cantidad.min' => 'La cantidad debe ser mayor que cero',
        ];


        $rules = [
            'atencion' => 'string|max:100|nullable',
            'email_proveedor' => 'string|max:100|nullable',
            'telefono_proveedor' => 'string|max:50|nullable',
            'referencia_solicitud' => 'string|max:255|nullable',
            'area_proveedor' => 'string|max:100|nullable',
            'es_borrador' => 'required|boolean',
            'f_orden_compra' => 'required|date',

            'total' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0.01',
            /*            'iva' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0',
                        'porcentaje_iva' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0',
                        'plazo_credito' => 'required|integer|min:0|max:60',*/

            'direccion' => 'string|max:100|nullable',
            'condicion_envio' => 'string|max:50|nullable',
            'nota' => 'string|max:500|nullable',
            'tiempo_entrega' => 'required|integer|min:1',
            'id_condicion_pago' => 'required|integer|min:1|max:3',
            'id_medio_pago' => 'required|integer|min:1|max:6',

            'proveedor' => 'required|array|min:1',
            'proveedor.id_proveedor' => 'required|integer|exists:pgsql.inventario.proveedores,id_proveedor',

            'bodega' => 'nullable|array|min:1',
            'bodega.id_bodega' => 'required|integer|min:1',

            'moneda' => 'required|array|min:1',
            'moneda.id_moneda' => 'required|integer|min:1',

            'detalleProductos' => 'required|array|min:1',
            'detalleProductos.*.productox.id_producto' => 'required|integer|exists:pgsql.inventario.productos,id_producto',
/*            'detalleProductos.*.precio_dol' => 'nullable|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0.01',*/
            'detalleProductos.*.precio_cord' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0.01',
            'detalleProductos.*.descuento' => 'nullable|integer|min:0|max:100',
            'detalleProductos.*.cantidad' => 'required|gt:0',

            'tipo_compra' => 'required|integer|min:1|max:2',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if (!$validator->fails()) {

            try {

                DB::beginTransaction();
                $orden = new OrdenCompra;
                $consecutivo = OrdenCompra::max('id_orden_compra') + 1;
                $orden->no_orden = 'OC-' . $consecutivo;
                $orden->id_proveedor = $request->proveedor['id_proveedor'];
                $orden->id_bodega = $request->bodega['id_bodega'];
                $orden->id_moneda = $request->moneda['id_moneda'];
                $orden->atencion = $request->atencion;
                $orden->email_proveedor = $request->email_proveedor;
                $orden->telefono_proveedor = $request->telefono_proveedor;
                $orden->referencia_solicitud = $request->referencia_solicitud;
                $orden->area_proveedor = $request->area_proveedor;
                $orden->id_solicitud_compra = $request->id_solicitud_compra;
                $orden->f_orden_compra = $request->f_orden_compra;
                $orden->condicion_envio = $request->condicion_envio;
                $orden->direccion = $request->direccion;
                $orden->numero_factura = $request->numero_factura;

                $orden->iva = $request->iva;
                $orden->total = $request->total;
                $orden->porcentaje_iva = $request->porcentaje_iva;
                $orden->porcentaje_descuento = $request->porcentaje_descuento;
                $orden->total_descuento = $request->descuento;
                $orden->total_descuento_me = $request->descuento_me;
                $orden->porcentaje_iva = $request->porcentaje_iva;
                $orden->plazo_credito = $request->plazo_credito;

                $orden->area_proveedor = $request->area_proveedor;

                $orden->nota = $request->nota;
                $orden->tiempo_entrega = $request->tiempo_entrega;

                $orden->id_condicion_pago = $request->id_condicion_pago;
                $orden->id_medio_pago = $request->id_medio_pago;

                $orden->tipo_compra = $request->tipo_compra;

                $orden->u_creacion = Auth::user()->name;
                $request->es_borrador === true ? $orden->estado = 99 : $orden->estado = 1;
                $orden->save();

                /* Actividad SGCL-42
                 * Cambio de estado en compras locales
                 * La solicitud de compra no cambia de estado al registrar una orden
                 * */

                $solicitudorden = SolicitudCompra::find($request->id_solicitud_compra);
                if ($solicitudorden) {

                    // Se cambia el estado de la solicitud de la orden a "Orden Compra"

                    $solicitudorden->estado = 4;

                    $solicitudorden->save();

                }


                #region [Detalle producto]
                $i = 1;
                foreach ($request->detalleProductos as $producto) {
                    $orden_producto = new OrdenCompraProductos;
                    $orden_producto->numero_item = $i;
                    $orden_producto->id_orden_compra = $orden->id_orden_compra;
                    $orden_producto->id_producto = $producto['productox']['id_producto'];
                    $orden_producto->precio_dol = $producto['precio_dol'];
                    $orden_producto->precio_cord = $producto['precio_cord'];
                    $orden_producto->precio_sugerido = $producto['precio_sugerido'];
                    $orden_producto->porcentaje_ganancia = $producto['porcentaje_ganancia'];
                    $orden_producto->impuesto_cord = $producto['impuesto_cord'];
                    $orden_producto->impuesto_dol = $producto['impuesto_dol'];
                    $orden_producto->cantidad = $producto['cantidad'];
                    $orden_producto->descuento = $producto['descuento'];
                    $orden_producto->subtotal = $producto['total'];
                    $orden_producto->save();
                    $i++;
                }
                #endregion
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
                    'messages' => $e
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
            'orden_compra_productos.*.id_producto.required' => 'Seleccione un producto válido',
            'orden_compra_productos.*.precio.min' => 'El precio debe ser mayor que cero',
            'orden_compra_productos.*.cantidad.min' => 'La cantidad debe ser mayor que cero',
        ];

        $rules = [
            'id_orden_compra' => 'required|integer|exists:pgsql.compra.ordenes_compras,id_orden_compra',
            'atencion' => 'string|max:100|nullable',
            'email_proveedor' => 'string|max:100|nullable',
            'telefono_proveedor' => 'string|max:50|nullable',
            'referencia_solicitud' => 'string|max:255|nullable',
            'area_proveedor' => 'string|max:100|nullable',
            'es_borrador' => 'required|boolean',
            'f_orden_compra' => 'required|date',

            'direccion' => 'string|max:100|nullable',
            'condicion_envio' => 'string|max:50|nullable',
            'nota' => 'string|max:500|nullable',
            'tiempo_entrega' => 'required|integer|min:1',

            'id_condicion_pago' => 'required|integer|min:1|max:2',
            'id_medio_pago' => 'required|integer|min:1|max:6',

            'orden_compra_bodega' => 'nullable|array|min:1',
            'orden_compra_bodega.id_bodega' => 'required|integer|min:1',

            'total' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0.01',
            /*           'iva' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0',
                       'porcentaje_iva' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0',
                       'plazo_credito' => 'required|integer|min:0|max:60',*/

            'orden_compra_moneda' => 'required|array|min:1',
            'orden_compra_moneda.id_moneda' => 'required|integer|min:1',

            'tipo_compra' => 'required|integer|min:1|max:2',

            'orden_compra_proveedor' => 'required|array|min:1',
            'orden_compra_proveedor.id_proveedor' => 'required|integer|exists:pgsql.inventario.proveedores,id_proveedor',

            'orden_compra_productos' => 'required|array|min:1',
            'orden_compra_productos.*.id_producto' => 'required|integer|exists:pgsql.inventario.productos,id_producto',
            'orden_compra_productos.*.precio_cord' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0.01',
//            'orden_compra_productos.*.precio_dol' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0.01',
//            'orden_compra_productos.*.descuento' => 'required|integer|min:0|max:100',
            'orden_compra_productos.*.cantidad' => 'required|gt:0',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if (!$validator->fails()) {

            try {


                DB::beginTransaction();
                $orden = OrdenCompra::find($request->id_orden_compra);

                if ($orden->estado === 99) {

                    $orden->id_proveedor = $request->orden_compra_proveedor['id_proveedor'];
                    $orden->id_bodega = $request->orden_compra_bodega['id_bodega'];
                    $orden->id_moneda = $request->orden_compra_moneda['id_moneda'];

                    $orden->atencion = $request->atencion;
                    $orden->email_proveedor = $request->email_proveedor;
                    $orden->telefono_proveedor = $request->telefono_proveedor;
                    $orden->referencia_solicitud = $request->referencia_solicitud;
                    $orden->area_proveedor = $request->area_proveedor;
                    $orden->id_solicitud_compra = $request->id_solicitud_compra;
                    $orden->f_orden_compra = $request->f_orden_compra;

                    $orden->condicion_envio = $request->condicion_envio;
                    $orden->direccion = $request->direccion;
                    $orden->nota = $request->nota;
                    $orden->tiempo_entrega = $request->tiempo_entrega;


                    $orden->iva = $request->iva;
                    $orden->total = $request->total;
                    $orden->porcentaje_iva = $request->porcentaje_iva;
                    $orden->plazo_credito = $request->plazo_credito;
                    $orden->porcentaje_descuento = $request->porcentaje_descuento;
                    $orden->total_descuento = $request->descuento;
                    $orden->total_descuento_me = $request->descuento_me;
                    $orden->id_condicion_pago = $request->id_condicion_pago;
                    $orden->id_medio_pago = $request->id_medio_pago;

                    $orden->tipo_compra = $request->tipo_compra;

                    $orden->u_creacion = Auth::user()->name;
                    $request->es_borrador === true ? $orden->estado = 99 : $orden->estado = 1;
                    $orden->save();

                    OrdenCompraProductos::where('id_orden_compra', $request->id_orden_compra)->delete();

                    $i = 1;
                    foreach ($request->orden_compra_productos as $producto) {
                        $orden_producto = new OrdenCompraProductos;
                        $orden_producto->numero_item = $i;
                        $orden_producto->id_orden_compra = $orden->id_orden_compra;
                        $orden_producto->id_producto = $producto['id_producto'];
                        $orden_producto->precio_cord = $producto['precio_cord'];
                        $orden_producto->precio_dol = $producto['precio_dol'];
                        $orden_producto->precio_sugerido = $producto['precio_sugerido'];
                        $orden_producto->porcentaje_ganancia = $producto['porcentaje_ganancia'];
                        $orden_producto->impuesto_cord = $producto['impuesto_cord'];
                        $orden_producto->impuesto_dol = $producto['impuesto_dol'];
                        $orden_producto->cantidad = $producto['cantidad'];
                        $orden_producto->descuento = $producto['monto_descuento'];
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

            } catch (Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'result' => 'Error de base de datos',
                    'messages' => $e
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
     * @access    public
     * @param Request $request
     * @return    JsonResponse(string)
     * @author omorales
     * @copyright omorales
     * @modified 12/10/21
     */

    public function cambiarEstado(Request $request)
    {

        $messages = [
            'numero_factura.required_if' => 'El campo Número Factura es requerido cuando se confirma facturación',
        ];

        $rules = [
            'id_orden_compra' => 'required|integer|exists:pgsql.compra.ordenes_compras,id_orden_compra',
            'estado' => 'required|integer|min:0',

            'total' => 'required_if:estado,3|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0.01',
            'iva' => 'required_if:estado,3|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0.00',
            'id_condicion_pago' => 'required_if:estado,3|integer|min:1|max:2',
            'plazo_credito' => 'required_if:estado,3|integer|min:0|max:60',
            'porcentaje_iva' => 'required_if:estado,3|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0',

            'numero_factura' => 'required_if:estado,3|string|max:50|nullable',
            'productos' => 'required|array|min:1',
            'productos.*.id_producto' => 'required|integer|exists:pgsql.inventario.productos,id_producto',
            'productos.*.id_orden_compra_producto' => 'required|integer',
            'productos.*.precio_cord' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0.01',
//            'productos.*.precio_dol' => 'required|numeric|regex:/^\d*(\.\d{1,6})?$/|min:0.01',
            'productos.*.descuento' => 'nullable|integer|min:0|max:100',
            'productos.*.cantidad' => 'required|gt:0',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if (!$validator->fails()) {
            $orden = OrdenCompra::find($request->id_orden_compra);
            $respuesta = response()->json([
                'status' => 'success',
                'result' => null,
                'messages' => null
            ]);
            if ($request->estado >= 0 && $request->estado <= 4 && $orden->estado !== $request->estado) {

                DB::beginTransaction();
                $estado_org = $orden->estado;

                if (($estado_org === 1 && ($request->estado === 2 || $request->estado === 0)) || $estado_org === 99
                    || (($estado_org === 2 && ($request->estado === 3 || $request->estado === 0))
                        || ($estado_org === 3 && ($request->estado === 4 || $request->estado === 0))
                        || ($estado_org === 4 && ($request->estado === 5 || $request->estado === 0)))) {


                    try {

                        $orden->estado = $request->estado;
                        $orden->numero_factura = $request->numero_factura;
                        $orden->total = $request->total;
                     /*   $orden->iva = $request->iva;*/
                      /*  $orden->porcentaje_iva = $request->porcentaje_iva;*/
                        $orden->id_condicion_pago = $request->id_condicion_pago;
                        $orden->plazo_credito = $request->plazo_credito;
                        $orden->save();

                        if ($estado_org === 99 && $request->estado === 4) {

                            $entrada = new Entradas;
                            $entrada->codigo_entrada = Entradas::max('id_entrada') + 1;
                            $entrada->id_tipo_entrada = 1;
                            $entrada->fecha_entrada = date('Y-m-d');
                            $entrada->fecha_recepcion = date('Y-m-d');
                            $entrada->id_proveedor = null;
                            $entrada->id_bodega = $orden->id_bodega;
                            $entrada->descripcion_entrada = 'Registramos entrada de productos por Orden de compra No. ' . $orden->no_orden;
                            $entrada->u_creacion = Auth::user()->name;
                            $entrada->u_recepcion = Auth::user()->name;
                            $entrada->no_documento = 'GENERAL'; // lote
                            $entrada->numero_documento = $orden->numero_factura;
                            $entrada->estado = 2; // Estado recibido
                            $entrada->save();

                            $entrada_inicial = new EntradasInicial();
                            $entrada_inicial->id_bodega = $orden->id_bodega;
                            $entrada_inicial->fecha_entrada = date("Y-m-d H:i:s");
                            $entrada_inicial->estado = 2;
                            $entrada_inicial->tipo_productos = 1;
                            $entrada_inicial->usuario_registra = Auth::user()->name;
                            $entrada_inicial->save();

                            $orden->id_entrada = $entrada->id_entrada;
                            $orden->save();

                            foreach ($request->productos as $producto) {
                                $orden_producto = OrdenCompraProductos::find($producto['id_orden_compra_producto']);

                                $entrada_producto = new EntradaProductos();
                                $bodega_sub = BodegaProductos::where('id_bodega', $entrada->id_bodega)->where('id_producto', $producto['id_producto'])->where('no_documento', $entrada->no_documento)->first();
                                if (!empty($bodega_sub)) {
                                    $entrada_producto->id_bodega_producto = $bodega_sub['id_bodega_producto'];
                                    $bodega_sub->cantidad += $producto['cantidad'];
                                    $bodega_sub->save();
                                } else {
                                    $nueva_bodega_sub = new BodegaProductos;
                                    $nueva_bodega_sub->id_bodega = $entrada->id_bodega;
                                    $nueva_bodega_sub->id_producto = $producto['id_producto'];
                                    $nueva_bodega_sub->cantidad = $producto['cantidad'];
                                    $nueva_bodega_sub->u_creacion = $entrada->u_creacion;
                                    $nueva_bodega_sub->no_documento = $entrada->no_documento;
                                    $nueva_bodega_sub->save();
                                    $entrada_producto->id_bodega_producto = $nueva_bodega_sub->id_bodega_producto;
                                }
                                $entrada_producto->id_entrada = $entrada->id_entrada;
                                $entrada_producto->codigo_producto = $producto['orden_compra_producto']['codigo_sistema'];
                                $entrada_producto->descripcion_producto = $producto['orden_compra_producto']['descripcion'];
                                $entrada_producto->unidad_medida = 'UNDS';
                                $entrada_producto->precio_unitario = $producto['precio_cord'];
                                $entrada_producto->precio_unitario_me = $producto['precio_dol'];
                                $entrada_producto->cantidad_solicitada = (int)$producto['cantidad'];
                                $entrada_producto->cantidad_recibida = (int)$producto['cantidad'];
                                $entrada_producto->cantidad_faltante = 0;
                                $entrada_producto->no_documento = $entrada->no_documento;
                                $entrada_producto->u_creacion = $entrada->u_creacion;
                                $entrada_producto->estado = 1;
                                $entrada_producto->id_empresa = 1;
                                $entrada_producto->save();

                                $entrada_inicial_product = new EntradaProductosCons();
                                $entrada_inicial_product->id_producto = $producto['id_producto'];
                                $entrada_inicial_product->id_entrada_inicial = $entrada_inicial->id_entrada_inicial;
                                $entrada_inicial_product->cantidad = (int)$producto['cantidad'];
                                $entrada_inicial_product->save();

                                $orden_producto = OrdenCompraProductos::find($producto['id_orden_compra_producto']);
                                $orden_producto->id_entrada_producto = $entrada_producto->id_entrada_producto;
                                $orden_producto->save();

                                $movimiento_producto = new MovimientosProductos();
                                $movimiento_producto->id_bodega_producto = $entrada_producto->id_bodega_producto;
                                $movimiento_producto->fecha_movimiento = $entrada->fecha_recepcion;//date("Y-m-d H:i:s");
                                $movimiento_producto->descripcion_movimiento = $entrada->descripcion_entrada;
                                $movimiento_producto->identificador_origen_movimiento = $entrada->id_entrada;
                                $movimiento_producto->tipo_movimiento = 1;
                                $movimiento_producto->cantidad_movimiento = $entrada_producto->cantidad_recibida;
                                $movimiento_producto->costo = $entrada_producto->precio_unitario;
                                $movimiento_producto->costo_me = $entrada_producto->precio_unitario_me;
                                $movimiento_producto->usuario_registra = Auth::user()->name;
                                $movimiento_producto->id_empresa = $entrada->id_empresa;
                                $movimiento_producto->save();


                                $orden_producto->id_producto = $producto['id_producto'];
                                $productoinv = Productos::findOrFail($orden_producto->id_producto);

                                // Guardamos el precio anterior en la columna precio_compra_ant
                                $productoinv->precio_compra_ant = $productoinv->precio_compra;
                                 // Actualizamos el precio de compra
                                $productoinv->precio_compra = $producto['precio_cord'];
                                $productoinv->porcentaje_ganancia = $producto['porcentaje_ganancia'];
                                $productoinv->precio_sugerido = $producto['precio_sugerido'];
                                $productoinv->save();

                                /*// Actualizamos el precio sugerido
                                $porcentaje_ganancia = $productoinv->porcentaje_ganancia;
                                $precio_compra = $productoinv->precio_compra;
                                $precio_sugerido = round((($precio_compra * ($porcentaje_ganancia / 100)) + $precio_compra), 2);
                                $productoinv->precio_sugerido = $precio_sugerido;
                                $productoinv->save();*/

                            }
                            DB::commit();
                            return $respuesta;


                        }

                        if ($estado_org === 2 && $request->estado === 3) {

                            foreach ($request->productos as $producto) {
                                $orden_producto = OrdenCompraProductos::find($producto['id_orden_compra_producto']);
                                $orden_producto->precio_facturado = $producto['precio'];
                                $orden_producto->cantidad_facturado = $producto['cantidad'];
                                $orden_producto->descuento = $producto['descuento'];
                                $orden_producto->save();
                            }
                            $tasa = 1;
                            if ($orden->id_moneda !== 1) {
                                $tasaObj = TasasCambios::select('tasa')->where('fecha', date("Y-m-d"))->first();
                                $tasa = $tasaObj->tasa;
                            }

                            //print_r($orden);

                            $cuentas_x_pagar = new CuentasXPagar();
                            $cuentas_x_pagar->id_proveedor = $orden->id_proveedor;
                            $cuentas_x_pagar->id_tipo_documento = 1;//Orden de compra
                            $cuentas_x_pagar->no_documento_origen = $orden->numero_factura;
                            $cuentas_x_pagar->es_registro_importado = false;
                            $cuentas_x_pagar->id_moneda = $orden->id_moneda;
                            $cuentas_x_pagar->identificador_origen = $orden->id_orden_compra;
                            $cuentas_x_pagar->fecha_movimiento = $orden->f_orden_compra;

                            $cuentas_x_pagar->monto_movimiento = ($orden->total + $orden->iva) * $tasa * -1;
                            $cuentas_x_pagar->saldo_actual = ($orden->total + $orden->iva) * $tasa;
                            $cuentas_x_pagar->monto_movimiento_org = ($orden->total + $orden->iva) * -1;
                            $cuentas_x_pagar->saldo_actual_org = $orden->total + $orden->iva;

                            $cuentas_x_pagar->fecha_vencimiento = date('Y-m-d', strtotime($orden->f_orden_compra . '  + ' . ($orden->plazo_credito) . ' days'));
                            $cuentas_x_pagar->descripcion_movimiento = 'Registro del Monto de la Factura de compra No. ' . $orden->numero_factura;
                            $cuentas_x_pagar->usuario_registra = $orden->u_creacion;
                            $cuentas_x_pagar->estado = 1;
                            $cuentas_x_pagar->save();


                            DB::commit();
                            return $respuesta;

                        }

                        if ($orden->estado === 2 && !empty($orden->id_solicitud_compra)) {
                            $solicitud = SolicitudCompra::find($orden->id_solicitud_compra);
                            if ($solicitud->estado == 3) {
                                $solicitud->estado = 4;
                                $solicitud->save();
                            }
                            DB::commit();
                            return $respuesta;
                        }

                        DB::commit();
                        return $respuesta;


                    } catch (Exception $e) {
                        DB::rollBack();
                        return response()->json([
                            'status' => 'error',
                            'result' => 'Error de base de datos',
                            'messages' => $e
                        ]);
                    }


                } else {
                    DB::rollBack();
                    $respuesta = response()->json([
                        'status' => 'error',
                        'result' => 'Error al cambiar el estado de la orden de compras (' . $estado_org . ' a ' . $request->estado . ' )',
                        'messages' => null
                    ]);
                }
            } else {
                $respuesta = response()->json([
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


    public function reporte($id_orden_compra)
    {
        // echo $ext;
        $ext = 'pdf';
        $os = array("pdf");
        if (in_array($ext, $os)) {
            $hora_actual = time();
            //$input = 'C:/xampp7/htdocs/resources/reports/OrdenDeCompra';
            //$output = 'C:/xampp7/htdocs/resources/reports/' .$hora_actual . 'OrdenDeCompra';
            $input = '/var/www/html/resources/reports/OrdenDeCompra';
            $output = '/var/www/html/resources/reports/' . $hora_actual . 'OrdenDeCompra';
            $url = '/var/www/html/resources/reports/';
            //$url = 'C:/xampp7/htdocs/resources/reports/';

            $options = [
                'format' => [$ext],
                'locale' => 'en',
                'params' => [
                    'id_orden_compra' => $id_orden_compra,
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

            $jasper->process($input, $output, $options)->execute();

            /*exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
          print_r($output);*/

            $headers = [
                'Content-Type' => 'application/pdf',
            ];

            return response()->download($output . '.' . $ext, $hora_actual . 'OrdenDeCompra.' . $ext, $headers);
        } else {
            return '';
        }
    }

}

