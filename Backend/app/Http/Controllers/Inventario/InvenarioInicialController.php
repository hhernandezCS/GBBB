<?php



namespace App\Http\Controllers\Inventario;

use App\Models\Admon\Ajustes;
use App\Models\Inventario\BodegasProductos;
use App\Models\Inventario\Bodegas;
use App\Models\Inventario\EntradaInicialProductos;
use App\Models\Inventario\EntradaProductos;
use App\Models\Inventario\EntradaProductosCons;
use App\Models\Inventario\Entradas;
use App\Models\Inventario\EntradasInicial;
use App\Models\Inventario\MovimientosProductos;
use App\Models\Inventario\Productos;
use App\Models\Inventario\UnidadMedida;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use PHPJasper\PHPJasper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
class InvenarioInicialController extends Controller
{
    public function obtener(Request $request, EntradasInicial $entradas)
    {
        $entradas = $entradas->obtener($request);

        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $entradas->total(),
                'rows' => $entradas->items()
            ],
            'messages' => null
        ]);
    }

    public function obtenerEntradaInvInicial(Request $request, EntradasInicial $entrada_inicial)
    {
        $rules = [
            'id_entrada_inicial' => 'required|integer|min:1',
            'cargar_dependencias' => 'required|boolean',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            $entrada_inicial = $entrada_inicial->obtenerEntradaInvInicial($request);
            $nombre_empresa = Ajustes::where('id_ajuste',4)->select('valor')->first();
            $direccion_empresa = Ajustes::where('id_ajuste',5)->select('valor')->first();

            $consolidado = EntradaInicialProductos::
            select('id_entrada_inicial','id_producto', \Illuminate\Support\Facades\DB::Raw('count(*) as cantidad_prod'))
                ->with('productoSimple')
                ->where('id_entrada_inicial',$request->id_entrada_inicial)->orderBy('id_producto', 'desc')
                ->groupBy(array('id_entrada_inicial','id_producto'))
                ->get();

            if(!empty($entrada_inicial)){

                if($request->cargar_dependencias){
                    if($entrada_inicial->tipo_productos==2){
                        $productos = Productos::select(['id_producto','codigo_barra','codigo_consecutivo','codigo_sistema','condicion','costo_estandar','descripcion',DB::raw("CONCAT(inventario.productos.nombre_comercial,' (',inventario.productos.codigo_sistema,')') AS text")])
                            ->where('activo', 1)->whereIn('tipo_producto',array(3))
                            ->with('unidadMedida')
                            ->whereHas('productoDetallesBaterias', function($q){
                                $q->whereIn('id_submarca', array(7));
                            })
                            /*->whereHas('productoDetallesBaterias', function($q){
                                $q->whereIn('id_submarca', array(7));
                            })*/
                            ->orderby('id_producto')->get();

                        $productos_usados = Productos::select(['id_producto','codigo_barra','codigo_consecutivo','codigo_sistema','condicion','costo_estandar','descripcion',DB::raw("CONCAT(inventario.productos.nombre_comercial,' (',inventario.productos.codigo_sistema,')') AS text")])
                            ->where('activo', 1)->whereIn('tipo_producto',array(3))
                            ->with('unidadMedida')
                            ->Where('condicion',2)
                            ->orderby('id_producto')->get();

                        $productos_herramientas = Productos::select('id_producto','id_unidad_medida','condicion','codigo_barra',DB::raw("CONCAT(inventario.productos.descripcion,' (',inventario.productos.codigo_barra,')') AS text"))
                            ->where('activo', 1)->whereIn('tipo_producto',array(1))
                            ->with('unidadMedida')
                            ->Where('condicion',1)
                            ->orderby('id_producto')->get();

                        $merged = $productos->merge($productos_herramientas);
                        $bodegas =Bodegas::where('activo', 1)->select('id_bodega','id_tipo_bodega','descripcion')->whereIn('id_tipo_bodega',array(1,2,3,5))->get();

                        return response()->json([
                            'status' => 'success',
                            'result' => [
                                'entrada_inicial' => $entrada_inicial,
                                'bodegas' => $bodegas,
                                'productos' => $merged,
                                'productos_usados' => $productos_usados,
                            ],
                            'messages' => null
                        ]);
                    }else{
                        $productos = Productos::select('id_producto','descripcion','codigo_barra',DB::raw("CONCAT(inventario.productos.descripcion,' (',inventario.productos.codigo_barra,')') AS text"))->where('activo', 1)->whereIn('tipo_producto',array(3))->with('unidadMedida')->with('productoDetallesBaterias')->orderby('id_producto')->get();
                        $bodegas =Bodegas::where('activo', 1)->select('id_bodega','descripcion')->get();

                        return response()->json([
                            'status' => 'success',
                            'result' => [
                                'entrada_inicial' => $entrada_inicial,
                                'bodegas' => $bodegas,
                                'productos' => $productos,
                            ],
                            'messages' => null
                        ]);
                    }



                }else{
                    return response()->json([
                        'status' => 'success',
                        'result' => [
                            'entrada_inicial' => $entrada_inicial,
                            'nombre_empresa'=>$nombre_empresa->valor,
                            'direccion_empresa'=>$direccion_empresa->valor,
                            'consolidado'=>$consolidado
                        ],
                        'messages' => null
                    ]);
                }



            }
            else{
                return response()->json([
                    'status' => 'error',
                    'result' => array('id_entrada_inicial'=>["Datos no encontrados"]),
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

    public function nuevo(Request $request){
        $productos = Productos::select('id_producto','codigo_barra',DB::raw("CONCAT(inventario.productos.descripcion,' (',inventario.productos.codigo_barra,')') AS text"))
            ->where('activo', 1)->whereIn('tipo_producto',array(3))
            ->with('unidadMedida')
            ->with('productoDetallesBaterias')
            ->whereHas('productoDetallesBaterias', function($q){
                $q->whereNotIn('id_submarca', array(7));
            })
            ->orderby('id_producto')->get();



        $bodegas =Bodegas::where('activo', 1)->select('id_bodega','descripcion')->get();

        $entrada_inicial = new EntradasInicial();
        $entrada_inicial->id_bodega = null;
        $entrada_inicial->fecha_entrada =date("Y-m-d H:i:s");
        $entrada_inicial->estado = 99;
        $entrada_inicial->id_trabajador = Auth::user()->id_empleado;

        $entrada_inicial->save();

        return response()->json([
            'status' => 'success',
            'result' => [
                'productos' => $productos,
                'bodegas' => $bodegas,
                'id_entrada_inicial' => $entrada_inicial->id_entrada_inicial,
            ],
            'messages' => null
        ]);
    }


    public function nuevoManual(Request $request){
        $productos = Productos::select(['id_producto','codigo_barra','codigo_consecutivo','codigo_sistema','condicion','costo_estandar','descripcion',DB::raw("CONCAT(inventario.productos.nombre_comercial,' (',inventario.productos.codigo_barra,')') AS text")])
            ->where('activo', 1)->whereIn('tipo_producto',array(3))
            ->with('unidadMedida')
            ->whereHas('productoDetallesBaterias', function($q){
                $q->whereIn('id_submarca', array(7));
            })
            /*->whereHas('productoDetallesBaterias', function($q){
                $q->whereIn('id_submarca', array(7));
            })*/
            ->orderby('id_producto')->get();

        $productos_usados = Productos::select(['id_producto','codigo_barra','codigo_consecutivo','codigo_sistema','condicion','costo_estandar','descripcion',DB::raw("CONCAT(inventario.productos.nombre_comercial,' (',inventario.productos.codigo_sistema,')') AS text")])
            ->where('activo', 1)->whereIn('tipo_producto',array(3))
            ->with('unidadMedida')
            ->Where('condicion',2)
            ->orderby('id_producto')->get();

        $productos_herramientas = Productos::select('id_producto','id_unidad_medida','condicion','codigo_barra',DB::raw("CONCAT(inventario.productos.descripcion,' (',inventario.productos.codigo_barra,')') AS text"))
            ->where('activo', 1)->whereIn('tipo_producto',array(1))
            ->with('unidadMedida')
            ->Where('condicion',1)
            ->orderby('id_producto')->get();

        $merged = $productos->merge($productos_herramientas);

        $bodegas =Bodegas::where('activo', 1)->select('id_bodega','id_tipo_bodega','descripcion')->whereIn('id_tipo_bodega',array(1,2,3,5))->get();

        /*$entrada_inicial = new EntradasInicial();
        $entrada_inicial->id_bodega = null;
        $entrada_inicial->fecha_entrada = date("Y-m-d H:i:s");
        $entrada_inicial->estado = 99;
        $entrada_inicial->id_trabajador = Auth::user()->id_empleado;

        $entrada_inicial->save();*/

        return response()->json([
            'status' => 'success',
            'result' => [
                'productos' => $merged,
                'productos_usados' => $productos_usados,
                'bodegas' => $bodegas,
                //'id_entrada_inicial' => $entrada_inicial->id_entrada_inicial,
            ],
            'messages' => null
        ]);
    }

    public function registrarBateria(Request $request)
    {
        $rules = [
            'id_entrada_inicial' => 'required|integer|min:1',
            'codigo_garantiax' => 'required|string|max:50',
            'codigo_barra' => 'nullable|string|max:50',
            'id_producto' => 'required|integer',
            'id_entrada_inicial_bateria' => 'nullable|integer',
            'estado' => 'required|integer',
            'fecha_activacion' => ['required','string','min:5','max:5'/*,'regex:/0\[1-9]|10|11|12)/[1-9][0-9]/'*/],

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            try{
                DB::beginTransaction();
                if($request->estado == 0){

                    if(!empty($request->id_entrada_inicial_bateria)){
                        EntradaInicialProductos::where('id_entrada_inicial_bateria', $request->id_entrada_inicial_bateria)->delete();
                    }

                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'result' => [
                            'id_entrada_inicial_bateria'=> null,
                        ],
                        'messages' => null
                    ]);

                }else{
                    if(!empty($request->id_entrada_inicial_bateria)){

                        $bateria_individual = EntradaInicialProductos::find($request->id_entrada_inicial_bateria);
                        $bateria_individual->fecha_activacion = $request->fecha_activacion;
                        $bateria_individual->save();

                        DB::commit();
                        return response()->json([
                            'status' => 'success',
                            'result' => [
                                'id_entrada_inicial_bateria'=> $bateria_individual->id_entrada_inicial_bateria,
                            ],
                            'messages' => null
                        ]);
                    }else{

                        //$bateriaRegistrada = EntradaInicialProductos::where('codigo_garantia',$request->codigo_garantiax)->where('id_entrada_inicial',$request->id_entrada_inicial)->first();
                        $bateriaRegistrada = EntradaInicialProductos::WhereRaw("upper(codigo_garantia) = upper(?)",[$request->codigo_garantiax])->where('id_entrada_inicial',$request->id_entrada_inicial)->first();


                        if(!empty($bateriaRegistrada)){
                            EntradaInicialProductos::where('id_entrada_inicial_bateria', $bateriaRegistrada->id_entrada_inicial_bateria)->delete();
                        }

                        $bateria_individual = new EntradaInicialProductos();
                        $bateria_individual->id_producto = $request->id_producto;
                        $bateria_individual->id_entrada_inicial = $request->id_entrada_inicial;
                        $bateria_individual->codigo_barras =  $request->codigo_barra;
                        $bateria_individual->codigo_garantia =  $request->codigo_garantiax;
                        $bateria_individual->fecha_activacion =  $request->fecha_activacion;
                        $bateria_individual->estado = 1;
                        $bateria_individual->save();
                        DB::commit();

                        return response()->json([
                            'status' => 'success',
                            'result' => [
                                'id_entrada_inicial_bateria'=> $bateria_individual->id_entrada_inicial_bateria,
                            ],
                            'messages' => null
                        ]);


                    }

                }



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


    public function recibir(Request $request)
    {
        $rules = [
            'id_entrada_inicial' => 'required|integer|exists:pgsql.inventario.entradas_inicial,id_entrada_inicial',
            'bodega' => 'required|array|min:1',
            'bodega.id_bodega' => 'required|integer|min:1',

            'detalle_baterias' => 'required|array|min:1',
            'detalle_baterias.*.codigo_garantiax' => 'required|string|max:50',
            'detalle_baterias.*.productox.codigo_barra' => 'nullable|string|max:50',
            'detalle_baterias.*.productox.id_producto' => 'required|integer',
            'detalle_baterias.*.id_entrada_inicial_bateria' => 'nullable|integer',
            'detalle_baterias.*.estado' => 'required|integer',
            'detalle_baterias.*.fecha_activacion' => 'required|string|min:5|max:5',

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            try{

                DB::beginTransaction();
                $entrada = EntradasInicial::find($request->id_entrada_inicial);
                $entrada->id_bodega = $request->bodega['id_bodega'];
                $entrada->fecha_entrada = $request->fecha_entrada;
                $entrada->estado = 1;
                $entrada->save();



                foreach ($request->detalle_baterias as $bateria) {

                    if($bateria['estado'] == 0){///Baterias eliminadas

                        if(!empty($bateria['id_entrada_inicial_bateria'])){
                            EntradaInicialProductos::where('id_entrada_inicial_bateria', $bateria['id_entrada_inicial_bateria'])->delete();
                        }

                    }else //baterias validas
                    {

                        if(empty($bateria['id_entrada_inicial_bateria'])){

                            $bateria_individual = new EntradaInicialProductos();
                            $bateria_individual->id_producto = $bateria['productox']['id_producto'];
                            $bateria_individual->id_entrada_inicial = $entrada->id_entrada_inicial;
                            $bateria_individual->codigo_barras =   $bateria['productox']['codigo_barra'];
                            $bateria_individual->codigo_garantia =   $bateria['codigo_garantiax'];
                            $bateria_individual->fecha_activacion =   $bateria['fecha_activacion'];
                            $bateria_individual->estado = 1;
                            $bateria_individual->save();

                        }else{///actualizar datos de baterias ya registradas
                            //echo $bateria['id_entrada_inicial_bateria'];
                            $bateria_individual = EntradaInicialProductos::find($bateria['id_entrada_inicial_bateria']);
                            $bateria_individual->fecha_activacion = $bateria['fecha_activacion'];
                            $bateria_individual->save();
                        }

                    }
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
        $rules = [
            'id_entrada_inicial' => 'required|integer|exists:pgsql.inventario.entradas_inicial,id_entrada_inicial',
            'entrada_bodega' => 'required|array|min:1',
            'entrada_bodega.id_bodega' => 'required|integer|min:1',

            'entrada_baterias' => 'required|array|min:1',
            'entrada_baterias.*.codigo_garantia' => 'required|string|max:50',
            'entrada_baterias.*.producto_simple.codigo_barra' => 'nullable|string|max:50',
            'entrada_baterias.*.producto_simple.id_producto' => 'required|integer',
            'entrada_baterias.*.id_entrada_inicial_bateria' => 'nullable|integer',
            'entrada_baterias.*.estado' => 'required|integer',
            'entrada_baterias.*.fecha_activacion' => 'required|string|min:5|max:5',

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            try{

                DB::beginTransaction();
                $entrada = EntradasInicial::find($request->id_entrada_inicial);
                $entrada->id_bodega = $request->entrada_bodega['id_bodega'];
                $entrada->fecha_entrada = $request->fecha_entrada;
                $entrada->estado = 1;
                $entrada->save();

                foreach ($request->entrada_baterias as $bateria) {

                    if($bateria['estado'] == 0){///Baterias eliminadas

                        //if ($bateria['registrada']){///Baterias registradas
                        if(!empty($bateria['id_entrada_inicial_bateria'])){
                            EntradaInicialProductos::where('id_entrada_inicial_bateria', $bateria['id_entrada_inicial_bateria'])->delete();
                        }
                        //}

                    }else //baterias validas
                    {
                        if (!$bateria['registrada']){///Baterias no registradas
                            if(empty($bateria['id_entrada_inicial_bateria'])){

                                $bateria_individual = new EntradaInicialProductos();
                                $bateria_individual->id_producto = $bateria['producto_simple']['id_producto'];
                                $bateria_individual->id_entrada_inicial = $entrada->id_entrada_inicial;
                                $bateria_individual->codigo_barras =   $bateria['producto_simple']['codigo_barra'];
                                $bateria_individual->codigo_garantia =   $bateria['codigo_garantia'];
                                $bateria_individual->fecha_activacion =   $bateria['fecha_activacion'];
                                $bateria_individual->estado = 1;
                                $bateria_individual->save();

                            }else{///actualizar datos de baterias ya registradas
                                $bateria_individual = EntradaInicialProductos::find($bateria['id_entrada_inicial_bateria']);
                                $bateria_individual->fecha_activacion = $bateria['fecha_activacion'];
                                $bateria_individual->save();
                            }
                        }else{///actualizar datos de baterias ya registradas
                            $bateria_individual = EntradaInicialProductos::find($bateria['id_entrada_inicial_bateria']);
                            $bateria_individual->fecha_activacion = $bateria['fecha_activacion'];
                            $bateria_individual->save();
                        }
                    }
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


    public function actualizarManual(Request $request)
    {
        $rules = [
            'id_entrada_inicial' => 'required|integer|exists:pgsql.inventario.entradas_inicial,id_entrada_inicial',
            'entrada_bodega' => 'required|array|min:1',
            'entrada_bodega.id_bodega' => 'required|integer|min:1',
            'es_borrador' => 'required|boolean',
            'entrada_productos' => 'required|array|min:1',
            'entrada_productos.*.id_producto' => 'required|integer',
            'entrada_productos.*.cantidad' => 'required|integer',

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            try{

                DB::beginTransaction();
                $entrada_inicial = EntradasInicial::find($request->id_entrada_inicial);
                $entrada_inicial->id_bodega = $request->entrada_bodega['id_bodega'];
                $entrada_inicial->fecha_entrada = $request->fecha_entrada;
                $entrada_inicial->estado = $request->es_borrador==false?2:1;
                $entrada_inicial->save();

                //Registrar Entrada
                if($entrada_inicial->estado==2){
                    $entrada = new Entradas;
                    $entrada->codigo_entrada = Entradas::max('id_entrada')+1;
                    $entrada->id_tipo_entrada = 10;
                    $entrada->fecha_entrada = $entrada_inicial->fecha_entrada;
                    $entrada->id_proveedor = null;
                    $entrada->fecha_recepcion= $request->fecha_recepcion; //date("Y-m-d H:i:s");
                    $entrada->id_bodega = $entrada_inicial->id_bodega;
                    $entrada->descripcion_entrada = 'Registramos entrada inicial de productos';
                    $entrada->u_creacion = Auth::user()->usuario;
                    $entrada->estado = 2;
                    $entrada->u_recepcion=Auth::user()->usuario;
                    $entrada->save();

                    $entrada_inicial->id_entrada = $entrada->id_entrada;
                    $entrada_inicial->save();
                }



                EntradaProductosCons::where('id_entrada_inicial',$entrada_inicial->id_entrada_inicial)->delete();

                foreach ($request->entrada_productos as $producto) {
                    $entrada_inicial_product = new EntradaProductosCons();
                    $entrada_inicial_product->id_producto = $producto['id_producto'];
                    $entrada_inicial_product->id_entrada_inicial = $entrada_inicial->id_entrada_inicial;
                    $entrada_inicial_product->cantidad = $producto['cantidad'];
                    $entrada_inicial_product->save();

                    if($entrada_inicial->estado==2){
                        $entrada_producto = new EntradaProductos;
                        $bodega_sub = BodegasProductos::where('id_bodega',$entrada->id_bodega)->where('id_producto',$entrada_inicial_product->id_producto)->first();
                        if(!empty($bodega_sub)){
                            $entrada_producto->id_bodega_producto = $bodega_sub['id_bodega_producto'];
                            if($entrada_inicial->estado==2) {
                                $bodega_sub->cantidad = $bodega_sub->cantidad + $producto['cantidad'];
                                $bodega_sub->save();
                            }
                        }else{
                            $nueva_bodega_sub = new BodegasProductos;
                            $nueva_bodega_sub->id_bodega=$entrada->id_bodega;
                            $nueva_bodega_sub->id_producto=$producto['id_producto'];
                            $nueva_bodega_sub->cantidad=$producto['cantidad'];
                            $nueva_bodega_sub->u_creacion =$entrada->u_creacion;
                            $nueva_bodega_sub->save();
                            $entrada_producto->id_bodega_producto = $nueva_bodega_sub->id_bodega_producto;
                        }

                        $productox = Productos::find($producto['id_producto']);

                        $unidad_medida = UnidadMedida::find($productox['id_unidad_medida']);
                        //$tasa = CajaBancoTasasCambios::select('tasa')->where('fecha',date("Y-m-d"))->first();

                        $entrada_producto->id_entrada = $entrada->id_entrada;
                        $entrada_producto->codigo_producto = $productox['codigo_sistema'];
                        $entrada_producto->descripcion_producto = $productox['descripcion'];
                        $entrada_producto->unidad_medida = $unidad_medida['descripcion'];
                        $entrada_producto->precio_unitario = $productox['costo_estandar'];
                        $entrada_producto->cantidad_solicitada = $producto['cantidad'];
                        $entrada_producto->cantidad_recibida = $producto['cantidad'];
                        $entrada_producto->cantidad_faltante = 0;
                        $entrada_producto->u_creacion =$entrada->u_creacion;
                        $entrada_producto->save();

                        $movimiento_producto = new MovimientosProductos();
                        $movimiento_producto->id_bodega_producto = $entrada_producto->id_bodega_producto ;
                        $movimiento_producto->fecha_movimiento = $entrada->fecha_recepcion;//date("Y-m-d H:i:s");
                        $movimiento_producto->descripcion_movimiento = 'Entrada inicial de productos, No. '.$entrada->codigo_entrada;
                        $movimiento_producto->identificador_origen_movimiento = $entrada->id_entrada;
                        $movimiento_producto->tipo_movimiento = 1;
                        $movimiento_producto->cantidad_movimiento = $entrada_producto->cantidad_recibida;
                        $movimiento_producto->costo = $entrada_producto->precio_unitario;
                        $movimiento_producto->usuario_registra = Auth::user()->usuario;
                        $movimiento_producto->save();
                    }

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

    public function registrar(Request $request)
    {
        $messages = [
            'entrada_baterias.*.codigo_garantia.unique'=> 'Ya se ha registrado una batería con ese código de garantía'
        ];

        $rules = [
            'id_entrada_inicial' => 'required|integer|exists:pgsql.inventario.entradas_inicial,id_entrada_inicial',
            'entrada_bodega' => 'required|array|min:1',
            'entrada_bodega.id_bodega' => 'required|integer|min:1',

            'entrada_baterias' => 'required|array|min:1',
            'entrada_baterias.*.codigo_garantia' => 'required|string|max:50'/*|unique:pgsql.inventario.baterias,codigo_garantia'*/,
            'entrada_baterias.*.producto_simple.codigo_barra' => 'nullable|string|max:50',
            'entrada_baterias.*.producto_simple.id_producto' => 'required|integer',
            'entrada_baterias.*.id_entrada_inicial_bateria' => 'nullable|integer',
            'entrada_baterias.*.estado' => 'required|integer',
            'entrada_baterias.*.fecha_activacion' => 'required|string|min:5|max:5',

            'consolidadoProductosId' => 'required|array|min:1',


        ];

        $validator = Validator::make($request->all(), $rules,$messages);
        if (!$validator->fails()) {

            try{

                DB::beginTransaction();
                $entradaIni = EntradasInicial::find($request->id_entrada_inicial);
                $entradaIni->id_bodega = $request->entrada_bodega['id_bodega'];
                $entradaIni->fecha_entrada = $request->fecha_entrada;
                $entradaIni->estado = 2;
                $entradaIni->save();


                //Registrar Entrada

                $entrada = new Entradas;
                $entrada->codigo_entrada = Entradas::max('id_entrada')+1;
                $entrada->id_tipo_entrada = 1;
                $entrada->fecha_entrada = $entradaIni->fecha_entrada;
                $entrada->id_proveedor = null;
                $entrada->fecha_recepcion = $entradaIni->fecha_entrada;//date("Y-m-d H:i:s");
                $entrada->id_bodega = $entradaIni->id_bodega;
                $entrada->descripcion_entrada = 'Registramos entrada inicial de productos';
                $entrada->u_creacion = Auth::user()->usuario;
                $entrada->u_recepcion=Auth::user()->usuario;
                $entrada->estado = 2;
                $entrada->save();

                $entradaIni->id_entrada = $entrada->id_entrada;
                $entradaIni->save();
                //$productosEntradaIni = EntradaInicialProductos::where('id_entrada_inicial', $entradaIni->id_entrada_inicial)->get();

                ///print_r($productosEntradaIni);
                //$tasa = CajaBancoTasasCambios::select('tasa')->where('fecha',date("Y-m-d"))->first();

                foreach ($request->entrada_baterias as $bateria) {

                    if($bateria['estado'] == 0){///Baterias eliminadas

                        //if ($bateria['registrada']){///Baterias registradas
                        if(!empty($bateria['id_entrada_inicial_bateria'])){
                            EntradaInicialProductos::where('id_entrada_inicial_bateria', $bateria['id_entrada_inicial_bateria'])->delete();
                        }
                        //}

                    }else //baterias validas
                    {

                        if (!$bateria['registrada']){///Baterias no registradas
                            if(empty($bateria['id_entrada_inicial_bateria'])){

                                $bateria_individual = new EntradaInicialProductos();
                                $bateria_individual->id_producto = $bateria['producto_simple']['id_producto'];
                                $bateria_individual->id_entrada_inicial = $entradaIni->id_entrada_inicial;
                                $bateria_individual->codigo_barras =   $bateria['producto_simple']['codigo_barra'];
                                $bateria_individual->codigo_garantia =   $bateria['codigo_garantia'];
                                $bateria_individual->fecha_activacion =   $bateria['fecha_activacion'];
                                $bateria_individual->estado = 1;
                                $bateria_individual->save();



                            }else{///actualizar datos de baterias ya registradas
                                $bateria_individual = EntradaInicialProductos::find($bateria['id_entrada_inicial_bateria']);
                                $bateria_individual->fecha_activacion = $bateria['fecha_activacion'];
                                $bateria_individual->save();
                            }
                        }else{///actualizar datos de baterias ya registradas
                            $bateria_individual = EntradaInicialProductos::find($bateria['id_entrada_inicial_bateria']);
                            $bateria_individual->fecha_activacion = $bateria['fecha_activacion'];
                            $bateria_individual->save();
                        }
                    }

                }


                ///paso 1
                $productosEntradaIni = EntradaInicialProductos::where('id_entrada_inicial', $entradaIni->id_entrada_inicial)->get();
                $validacion_unica=true;
                $errores = [];
                $last_contador = 0;
                foreach ($request->consolidadoProductosId as $producto) {

                    $entrada_producto = new EntradaProductos;
                    $bodega_sub = BodegasProductos::where('id_bodega',$entrada->id_bodega)->where('id_producto',$producto['id_producto'])->first();
                    if(!empty($bodega_sub)){
                        $entrada_producto->id_bodega_producto = $bodega_sub['id_bodega_producto'];
                        $bodega_sub->cantidad=$bodega_sub->cantidad+$producto['cantidad'];
                        $bodega_sub->save();
                    }else{
                        $nueva_bodega_sub = new BodegasProductos;
                        $nueva_bodega_sub->id_bodega=$entrada->id_bodega;
                        $nueva_bodega_sub->id_producto=$producto['id_producto'];
                        $nueva_bodega_sub->cantidad=$producto['cantidad'];
                        $nueva_bodega_sub->u_creacion =$entrada->u_creacion;
                        $nueva_bodega_sub->save();
                        $entrada_producto->id_bodega_producto = $nueva_bodega_sub->id_bodega_producto;
                    }

                    $productox = Productos::find($producto['id_producto']);

                    $unidad_medida = UnidadMedida::find($productox['id_unidad_medida']);

                    $entrada_producto->id_entrada = $entrada->id_entrada;
                    $entrada_producto->codigo_producto = $productox['codigo_sistema'];
                    $entrada_producto->descripcion_producto = $productox['descripcion'];
                    $entrada_producto->unidad_medida = $unidad_medida['descripcion'];
                    $entrada_producto->precio_unitario = $productox['costo_estandar'];
                    $entrada_producto->cantidad_solicitada = $producto['cantidad'];
                    $entrada_producto->cantidad_recibida = $producto['cantidad'];
                    $entrada_producto->cantidad_faltante = 0;
                    $entrada_producto->u_creacion =$entrada->u_creacion;
                    $entrada_producto->save();


                    $contador=$last_contador;
                    foreach ($productosEntradaIni as $bateria) {
                        //print_r($bateria);
                        if($bateria['id_producto'] == $producto['id_producto']){
                            //$bateria_existe = InventarioBaterias::where('codigo_garantia', $bateria['codigo_garantia'])->first();
                            $bateria_existe = InventarioBaterias::WhereRaw("upper(codigo_garantia) = upper(?)",[$bateria['codigo_garantia']])->first();


                            //print_r($bateria_existe);
                            if(empty($bateria_existe)){
                                $bateria_individual = new InventarioBaterias();
                                $bateria_individual->id_producto = $bateria['id_producto'];
                                $bateria_individual->id_bodega_inicial = $entrada->id_bodega;
                                $bateria_individual->id_bodega_actual = $entrada->id_bodega;
                                $bateria_individual->codigo_barras = $bateria['codigo_barras']!=''?$bateria['codigo_barras']:'';
                                $bateria_individual->codigo_garantia = $bateria['codigo_garantia'];
                                $bateria_individual->fecha_activacion = $bateria['fecha_activacion'];
                                $bateria_individual->fecha_ingreso = date("Y-m-d H:i:s");
                                $bateria_individual->estado = 1;
                                $bateria_individual->save();

                                $entrada_baterias = new InventarioEntradaProductoBaterias();
                                $entrada_baterias->id_bateria = $bateria_individual->id_bateria;
                                $entrada_baterias->id_entrada_producto = $entrada_producto->id_entrada_producto;
                                $entrada_baterias->save();
                            }else{

                                if($bateria_existe->estado==7){
                                    if( $bateria_existe->id_producto == $bateria['id_producto']){
                                        $bateria_existe->id_bodega_actual = $entrada->id_bodega;
                                        $bateria_existe->estado = 1;
                                        $bateria_existe->save();

                                        $entrada_baterias = new InventarioEntradaProductoBaterias();
                                        $entrada_baterias->id_bateria = $bateria_existe->id_bateria;
                                        $entrada_baterias->id_entrada_producto = $entrada_producto->id_entrada_producto;
                                        $entrada_baterias->save();
                                    }else{
                                        $validacion_unica = false;
                                        $errores = $errores +['entrada_baterias.'.$contador.'.codigo_garantia'=>['El modelo de la batería no coincide con registros previos del producto']];
                                    }
                                }else{
                                    $validacion_unica = false;
                                    $errores = $errores +['entrada_baterias.'.$contador.'.codigo_garantia'=>['Ya se ha registrado una batería con el código de garantía: '.$bateria_existe->codigo_garantia]];
                                }

                            }
                            $contador++;
                        }

                    }

                    $movimiento_producto = new MovimientosProductos();
                    $movimiento_producto->id_bodega_producto = $entrada_producto->id_bodega_producto ;
                    $movimiento_producto->fecha_movimiento = $request->fecha_recepcion;//date("Y-m-d H:i:s");
                    $movimiento_producto->descripcion_movimiento = 'Entrada inicial de productos, No. '.$entrada->codigo_entrada;
                    $movimiento_producto->identificador_origen_movimiento = $entrada->id_entrada;
                    $movimiento_producto->tipo_movimiento = 1;
                    $movimiento_producto->cantidad_movimiento = $entrada_producto->cantidad_recibida;
                    $movimiento_producto->costo = $entrada_producto->precio_unitario;
                    $movimiento_producto->usuario_registra = Auth::user()->usuario;
                    $movimiento_producto->save();

                    $last_contador = $contador;
                }

                if(!$validacion_unica){
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
                    'result' => '',
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

    public function registrarInvManual(Request $request)
    {

        $rules = [
            //'id_entrada_inicial' => 'required|integer|exists:pgsql.inventario.entradas_inicial,id_entrada_inicial',
            'bodega' => 'required|array|min:1',
            'bodega.id_bodega' => 'required|integer|min:1',
            'conteo_productos' => 'required|array|min:1',

        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            try{

                DB::beginTransaction();
                $entrada_inicial = new EntradasInicial();
                $entrada_inicial->id_bodega = $request->bodega['id_bodega'];
                $entrada_inicial->fecha_entrada = $request->fecha_entrada; //date("Y-m-d H:i:s");
                $entrada_inicial->estado = 1;
                $entrada_inicial->tipo_productos = 2;
                $entrada_inicial->id_trabajador = Auth::user()->id_empleado;
                $entrada_inicial->save();



                //Registrar Entrada

                /*$entrada = new Entradas;
                $entrada->codigo_entrada = Entradas::max('id_entrada')+1;
                $entrada->id_tipo_entrada = 1;
                $entrada->fecha_entrada = $entrada_inicial->fecha_entrada;
                $entrada->id_proveedor = null;
                $entrada->id_bodega = $entrada_inicial->id_bodega;
                $entrada->descripcion_entrada = 'Registramos entrada inicial de productos';
                $entrada->u_creacion = Auth::user()->usuario;
                $entrada->estado = 2;
                $entrada->save();*/

                //$productosEntradaIni = EntradaInicialProductos::where('id_entrada_inicial', $entradaIni->id_entrada_inicial)->get();

                ///print_r($productosEntradaIni);
                //$tasa = CajaBancoTasasCambios::select('tasa')->where('fecha',date("Y-m-d"))->first();


                ///paso 1
                //$productosEntradaIni = EntradaProductosCons::where('id_entrada_inicial', $entradaIni->id_entrada_inicial)->get();

                foreach ($request->conteo_productos as $producto) {

                    $entrada_inicial_product = new EntradaProductosCons();
                    $entrada_inicial_product->id_producto = $producto['productox']['id_producto'];
                    $entrada_inicial_product->id_entrada_inicial = $entrada_inicial->id_entrada_inicial;
                    $entrada_inicial_product->cantidad = $producto['cantidad'];
                    $entrada_inicial_product->save();

                    /* $entrada_producto = new EntradaProductos;
                     $bodega_sub = BodegasProductos::where('id_bodega',$entrada->id_bodega)->where('id_producto',$producto['id_producto'])->first();
                     if(!empty($bodega_sub)){
                         $entrada_producto->id_bodega_producto = $bodega_sub['id_bodega_producto'];
                     }else{
                         $nueva_bodega_sub = new BodegasProductos;
                         $nueva_bodega_sub->id_bodega=$entrada->id_bodega;
                         $nueva_bodega_sub->id_producto=$producto['id_producto'];
                         $nueva_bodega_sub->cantidad=$producto['cantidad'];
                         $nueva_bodega_sub->u_creacion =$entrada->u_creacion;
                         $nueva_bodega_sub->save();
                         $entrada_producto->id_bodega_producto = $nueva_bodega_sub->id_bodega_producto;
                     }

                     $productox = Productos::find($producto['id_producto']);

                     $unidad_medida = UnidadMedida::find($productox['id_unidad_medida']);

                     $entrada_producto->id_entrada = $entrada->id_entrada;
                     $entrada_producto->codigo_producto = $productox['codigo_sistema'];
                     $entrada_producto->descripcion_producto = $productox['descripcion'];
                     $entrada_producto->unidad_medida = $unidad_medida['descripcion'];
                     $entrada_producto->precio_unitario = $productox['costo_estandar']*$tasa->tasa;
                     $entrada_producto->cantidad_solicitada = $producto['cantidad'];
                     $entrada_producto->cantidad_recibida = $producto['cantidad'];
                     $entrada_producto->cantidad_faltante = 0;
                     $entrada_producto->u_creacion =$entrada->u_creacion;
                     $entrada_producto->save();



                     $movimiento_producto = new MovimientosProductos();
                     $movimiento_producto->id_bodega_producto = $entrada_producto->id_bodega_producto ;
                     $movimiento_producto->fecha_movimiento = date("Y-m-d H:i:s");
                     $movimiento_producto->descripcion_movimiento = 'Entrada inicial de productos, No. '.$entrada->codigo_entrada;
                     $movimiento_producto->identificador_origen_movimiento = $entrada->id_entrada;
                     $movimiento_producto->tipo_movimiento = 1;
                     $movimiento_producto->cantidad_movimiento = $entrada_producto->cantidad_recibida;
                     $movimiento_producto->costo = $entrada_producto->precio_unitario;
                     $movimiento_producto->usuario_registra = Auth::user()->usuario;
                     $movimiento_producto->save();
                         */
                }


                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'result' => '',
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

    public function generarReporteInventarioInicial(Request $request)
    {
        // echo $ext;
        $rules = [
            'id_entrada_inicial' => 'required|integer|min:1',
            'tipo_productos' => 'required|integer|min:1|max:2',
            'extension' => 'required|string|max:4'
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            $os = array("xls", "pdf","html");
            //echo gethostname();
            if (in_array($request->extension, $os)) {

                $hora_actual = time();
                //$input = '/Applications/XAMPP/xamppfiles/htdocs/resources/reports/EstadoCuentaClienteDetalle';
                //$output = '/Applications/XAMPP/xamppfiles/htdocs/resources/reports/'.$hora_actual.'EstadoCuentaClienteDetalle';
                $nombre_repo = $request->tipo_productos==1?'ReporteEntradaInicial':'ReporteEntradaInicialManual';

                $input = '/var/www/html/resources/reports/'.$nombre_repo;
                $output = '/var/www/html/resources/reports/' . $hora_actual . $nombre_repo;

                //$input = 'C:/xampp7/htdocs/resources/reports/'.$nombre_repo;
                //$output = 'C:/xampp7/htdocs/resources/reports/' .$hora_actual . $nombre_repo;

                $nombre_empresa = Ajustes::where('id_ajuste',4)->select('valor')->first();
                $logo_empresa = Ajustes::where('id_ajuste',3)->select(DB::raw("substr(valor, 2, length(valor) - 2)::json->>'file_thumbnail' as file_thumbnail"))->first();
                $url = '/var/www/html/resources/reports/';
                //$url = 'C:/xampp7/htdocs/resources/reports/';

                $options = [
                    'format' => [$request->extension],
                    'locale' => 'en',
                    'params' => [
                        'entrada' => $request->id_entrada_inicial,
                        'directorioReports'=>$url,
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

                return response()->download($output . '.' . $request->extension, $hora_actual . 'ReporteEntradaInicial.' . $request->extension, $headers);

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
