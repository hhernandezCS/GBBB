<?php

namespace App\Models\Inventario;

use App\Models\Admon\Impuestos;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\Inventario\UnidadMedida;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Inventario\Grupos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function Symfony\Component\ErrorHandler\ErrorRenderer\addElementToGhost;

class Productos extends Model {

    use HasFactory;

    protected $table = 'inventario.productos';
    protected $primaryKey='id_producto';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['codigo_consecutivo','codigo_sistema','descripcion','nombre_comercial','id_unidad_medida',
        'codigo_barra','costo_estandar','precio_sugerido', 'precio_distribuidor','existencia_min','id_tipo_producto','id_impuesto','u_creacion', 'u_modificacion',
        'estado','id_empresa','condicion','existencia_max','precio_compra','id_marca','tipo_servicio','costo_estandar_me','id_grupo','id_subgrupo','cantidad_inicial','porcentaje_ganancia'];
    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';


    /**
     * Buscar productos
     *
     * @access  public
     * @param
     * @return  json(array)
     */

    /**
     * @param $request
     * @return mixed
     */
    public function buscar($request){
        $producto = $this->select(['id_producto','descripcion','nombre_comercial','codigo_barra','precio_compra',
            'precio_sugerido','porcentaje_ganancia','id_unidad_medida','codigo_sistema',
            DB::raw("CONCAT(inventario.productos.codigo_sistema,' - ',inventario.productos.nombre_comercial,' (',inventario.productos.codigo_barra,')') AS text")]);
        if ((!empty($request->q))){
            $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
            $searchValue = strtolower(str_replace(' ', '%', $request->q));
            $producto->where('estado', 1);
            $producto->where('id_empresa',$usuario_empresa->id_empresa);
            $producto->whereIn('id_tipo_producto', array( 1,3));
            $producto->where(function($query) use($searchValue){
                $query->where(DB::raw("LOWER(REPLACE(descripcion, ' ', ''))") , 'LIKE', '%' . $searchValue . '%');
                $query->Orwhere(DB::raw("LOWER(REPLACE(nombre_comercial, ' ', ''))") , 'LIKE', '%' . $searchValue . '%');
                $query->Orwhere('codigo_barra', 'ILIKE','%'. $searchValue . '%');
            });
            $producto->with('unidadMedida');
            $producto->orderBy('descripcion', 'asc');
            return $producto->limit(6)->get();
        }

        $producto->where('estado', 1);
        $producto->where('nombre_comercial', 'ILIKE', '%%');
        return $producto->limit(0)->get();
    }

    public function buscarProductosVenta($request){
        $producto = $this->select();
    }


    public function obtenerProductosEntrada($request)
    {
        $productos = $this->select(['*','descripcion AS text']);
        $searchValue = $request->q;
        $productos->where('activo', 1);
        $productos->where('descripcion', 'ILIKE', '%' . $searchValue . '%');
        $productos->orderBy('descripcion', 'asc');
        return $productos->limit(10)->get();
    }




    public function generarCodigoBateria($id_proveedor,$id_marca,$id_submarca,$id_aplicacion)
    {
        $codigo = $this->select([DB::raw("COALESCE(max(codigo_consecutivo),0)+1 as secuencia")])
            ->join('inventario.baterias_detalles', 'inventario.productos.id_producto', '=', 'inventario.baterias_detalles.id_producto')
            ->join('inventario.baterias_submarcas', 'inventario.baterias_detalles.id_submarca', '=', 'inventario.baterias_submarcas.id_submarca')
            ->join('inventario.baterias_subaplicaciones', 'inventario.baterias_detalles.id_subaplicacion', '=', 'inventario.baterias_subaplicaciones.id_subaplicacion');
        if((!empty($id_proveedor))&&(!empty($id_marca))&&(!empty($id_submarca))&&(!empty($id_aplicacion))){
            $codigo->where('condicion',1);
            $codigo->where('id_proveedor',$id_proveedor);
            $codigo->where('inventario.baterias_submarcas.id_marca',$id_marca);
            $codigo->where('inventario.baterias_submarcas.id_submarca',$id_submarca);
            $codigo->where('inventario.baterias_subaplicaciones.id_aplicacion',$id_aplicacion);
        }
        return $codigo->first();
    }

    /**
     * Obtener Lista de productos
     *
     * @access  public
     * @param
     * @return  json(array)
     */

    public function obtenerProductos($request)
    {
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        $productos = $this->select(['*']);
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
//            $searchValue = $request->search['value'];
            $statusValue = $request->search['status'];
            $searchValue = str_replace(' ', '%', $request->search['value']) ;
            $productos->where($searchField, 'ilike', '%' . $searchValue . '%');
            $productos->where('id_empresa',$usuario_empresa->id_empresa);

            //Se convierte en un arreglo el string de la peticion
            $estados = explode(',', $request->map_estados_seleccionados);
            /**
             * Comprobamos si dentro del arreglo se encuentra el valor '0' que significa 'mostrar todos los registros'
             * Si el valor 0 esta presente, entonces se ignora el where que filtra los estado para mostrar todos los registros
             * de lo contrario, se realiza el filtro mediante los estados seleccionados desde el formulario
             */
            if(!in_array(99, $estados)){
                //Pasamos este arreglo a la consulta
                $productos->whereIn('estado', $estados);
            }
            /**
             * Utilizamos la funcion 'when' para agregar la clausula 'where' a la consulta solo si se cumple la condicion de que el campo
             * de fechas este tenga un valor, si esta vacio se omite la clausula 'where' y se obtienen todos los registros sin filtrar por fecha.
             */
            $f_desde_creacion =  $request->f_desde_creacion;
            $f_hasta_creacion = $request->f_hasta_creacion;
            $f_desde_modificacion =  $request->f_desde_modificacion;
            $f_hasta_modificacion = $request->f_hasta_modificacion;
            $productos->when(!empty($f_desde_creacion) && !empty($f_hasta_creacion), static function ($query) use ($f_desde_creacion, $f_hasta_creacion){
                return $query->whereRaw("date_trunc('day', f_creacion) >= ?", [$f_desde_creacion])->whereRaw("date_trunc('day', f_creacion) <= ?",[$f_hasta_creacion]);
            });

            $productos->when(!empty($f_desde_modificacion) && !empty($f_hasta_modificacion), static function ($query) use ($f_desde_modificacion, $f_hasta_modificacion){
                return $query->whereRaw("date_trunc('day', f_modificacion) >= ?", [$f_desde_modificacion])->whereRaw("date_trunc('day', f_modificacion) <= ?",[$f_hasta_modificacion]);
            });

            $productos->whereIn('id_tipo_producto',array(1,2,3));
            $productos->with('unidadMedida');
            $productos->with('marca');
            $productos -> with('productosEnBodega');
            $productos->with('grupo');

            $productos->orderBy('id_producto', 'asc');
        }
        return $productos->paginate($request->limit);

    }


    /**
     * Obtener codigo estructurado
     *
     * @access 	public
     * @param
     * @return 	array
     */

    public function obtenerCodigoProducto($request)
    {
        $codigo = $this->select([DB::raw("COALESCE(max(codigo_consecutivo),0)+1 as codigo_siguiente")]);
        return $codigo->get();
    }

    public function obtenerProducto($request)
    {
        $productos = $this->select(['*']);
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        $productos->where('id_producto', $request->id_producto);
        $productos->where('id_empresa',$usuario_empresa->id_empresa);
        /*$productos->with(['proveedoresLista' => function($query) {
            $query->with('proveedores');
        }]);*/
        $productos->with('unidadMedida');
        $productos->with('marca');
        $productos->with(['grupo' => function($query) {
            $query->with('grupoSubgrupos'); }]);
        $productos->with('subgrupo');
        $productos->with('impuestoProducto');
        return $productos->get();
    }

    public function obtenerBateria($request)
    {
        $productos = $this->select(['*']);
        $productos->where('id_producto', $request->id_producto);

        $productos->with('unidadMedida');
        $productos->with('grupoProducto');
        $productos->with('impuestoProducto');

        $productos->with(['productoDetallesBaterias' => function($query) {
            $query->with('bateriaMaterial');
            $query->with('bateriaLineaProducto');
            $query->with('bateriaSubAplicacion');
            $query->with('bateriaSubMarca');
        }]);

        return $productos->get();
    }

    public function obtenerProductosBodega($request)
    {
        $productos = $this->select(['inventario.productos.codigo_sistema','inventario.productos.id_unidad_medida','inventario.productos.nombre_comercial as nom_prod','inventario.productos.id_producto',
            'inventario.bodega_productos.id_bodega_producto as id_bodega_producto','inventario.productos.costo_estandar as precio_sugerido',
            DB::raw("concat(inventario.productos.codigo_barra,' - ',inventario.productos.nombre_comercial) as nombre_producto"),
            DB::raw("(SELECT COALESCE(sum(iep.cantidad_recibida),0) as entradas FROM inventario.entrada_productos iep  left join inventario.entradas ie on ie.id_entrada=iep.id_entrada
        where ie.estado in (2) and iep.id_bodega_producto = inventario.bodega_productos.id_bodega_producto) -(SELECT COALESCE(sum(isp.cantidad_despachada),0) as salidas FROM inventario.salida_productos isp
        left join inventario.salidas ie on ie.id_salida=isp.id_salida where ie.estado in (2) and isp.id_bodega_producto = inventario.bodega_productos.id_bodega_producto) as inventario")
        ]);
        $productos->leftJoin('inventario.bodega_productos', 'inventario.productos.id_producto', '=', 'inventario.bodega_productos.id_producto');
        $productos->where('inventario.bodega_productos.id_bodega', '=', 1/*$request->id_bodega*/);
        $productos->where('inventario.productos.estado', '=', 1);
        $productos->groupBy('inventario.productos.codigo_sistema','inventario.productos.id_unidad_medida', 'inventario.productos.nombre_comercial','inventario.productos.id_producto','inventario.bodega_productos.id_bodega_producto');
        //$productos->having('inventario', '>', 0);
        $productos->havingRaw('(SELECT COALESCE(sum(iep.cantidad_recibida),0) as entradas FROM inventario.entrada_productos iep  left join inventario.entradas ie on ie.id_entrada=iep.id_entrada
        where ie.estado in (2) and iep.id_bodega_producto = inventario.bodega_productos.id_bodega_producto) -(SELECT COALESCE(sum(isp.cantidad_despachada),0) as salidas FROM inventario.salida_productos isp
        left join inventario.salidas ie on ie.id_salida=isp.id_salida where ie.estado in (2) and isp.id_bodega_producto = inventario.bodega_productos.id_bodega_producto) > 0');

        $productos->with('unidadMedida');
        $productos->with('tipoProductos');
        return $productos->get();
    }

    public function obtenerProductosValidos($request)
    {
        $productos = $this->select(['*']);
        $productos->where('estado', 1)->whereIn('id_tipo_producto',array(1,3))->with('unidadMedida')->orderby('id_producto');
        return $productos->get();
    }

    /*public function proveedoresLista()
    {
        return $this->hasMany('App\Models\InventarioProductoProveedor','id_producto');
    }*/

    public function entradasProductos()
    {
        return $this->hasMany('App\Models\Inventario\EntradaProductos','id_producto');
    }


    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class,'id_unidad_medida')->select('id_unidad_medida','siglas','descripcion');
    }
    /*Relación producto - grupo*/
    public function grupo()
    {
        return $this->belongsTo(Grupos::class,'id_grupo')->select('*');
    }    /*Relación producto - subgrupo*/
    public function subgrupo()
    {
        return $this->belongsTo(SubGrupos::class,'id_subgrupo')->select('*');
    }
    public function grupoSubgrupos()
    {
        return $this->belongsTo(SubGrupos::class,'id_grupo')->select('id_subgrupo','id_grupo','descripcion');
    }
    /*Relación producto - marca*/

    public function marca()
    {
        return $this->belongsTo(Marcas::class,'id_marca')->select('id_marca','descripcion','estado');
    }


    public function productosEnBodega()
    {
        return $this->belongsTo(ProductosVistaVenta::class, 'id_producto') ->select('id_producto', 'cantidad') ;

    }


    public function  tipoProductos(){
        return $this->belongsTo('App\Models\Inventario\TipoProductos', 'id_tipo_producto');
    }

    public function impuestoProducto()
    {
        return $this->belongsTo(Impuestos::class,'id_impuesto')->select('id_impuesto','descripcion');
    }

}
