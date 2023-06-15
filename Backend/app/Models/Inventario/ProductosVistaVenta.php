<?php

namespace App\Models\Inventario;

use App\Models\Admon\UsuariosEmpresas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductosVistaVenta extends Model
{
    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
    protected $table = 'inventario.v_productos_venta';
    protected $primaryKey = 'id_producto';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['codigo', 'descripcion', 'descripcion', 'precio_sugerido', 'estado'];

    public function serviciosVenta($request)
    {
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        $productos = $this->select([
            'inventario.v_productos_venta.id_producto',
            'inventario.v_productos_venta.codigo_sistema',
            'inventario.v_productos_venta.unidad_medida',
            'inventario.v_productos_venta.tasa_impuesto',
            'inventario.v_productos_venta.codigo_barra',
            'inventario.v_productos_venta.descripcion',
            DB::raw('0 AS id_bodega_producto'),
            DB::raw("CONCAT(inventario.v_productos_venta.descripcion,' (',inventario.v_productos_venta.codigo_barra,')') AS text"),
            DB::raw('500 AS cantidad_disponible'), 'inventario.v_productos_venta.precio_sugerido', 'inventario.v_productos_venta.precio_distribuidor',
            'inventario.v_productos_venta.costo_estandar AS costo_promedio', 'inventario.v_productos_venta.costo_estandar_me AS costo_promedio_me',
            'inventario.v_productos_venta.id_tipo_producto',
            'inventario.v_productos_venta.tipo_servicio', 'inventario.v_productos_venta.id_empresa'
        ]);
        $productos->where('id_tipo_producto', 2);
        $productos->orderBy('inventario.v_productos_venta.descripcion', 'asc');
        return $productos->get();
    }

    public function productosBodegaVenta($request)
    {

        if (!empty($request->is_carne) && $request->is_carne === 'A') {
            $codigo = str_split($request->q);
            $arrEnt = array_map('intval', $codigo);
            $idProducto = array_splice($arrEnt, 2, 5);
            $ent = intval(implode("", $idProducto));

            $productos = $this->select(['inventario.v_productos_venta.id_producto', 'inventario.v_productos_venta.codigo_sistema', 'inventario.v_productos_venta.unidad_medida', 'inventario.v_productos_venta.tasa_impuesto', 'inventario.v_productos_venta.codigo_barra', 'inventario.v_productos_venta.descripcion', 'inventario.v_productos_venta.nombre_comercial',
                'inventario.v_productos_venta.id_bodega_producto'
                , DB::raw("CONCAT(inventario.v_productos_venta.id_producto,'-',inventario.v_productos_venta.descripcion,' (',inventario.v_productos_venta.codigo_barra,')') AS text"),
                'inventario.v_productos_venta.cantidad as cantidad_disponible', 'inventario.v_productos_venta.no_documento',
                /* DB::raw("(case when inventario.v_productos_venta.tipo_producto = 3 then
                inventario.obtener_unidades_disponibles(inventario.bodegas_productos.id_bodega,inventario.bodegas_productos.id_producto,1)
             else inventario.bodegas_productos.cantidad end)::INTEGER as cantidad_disponible"),*/
                /*    DB::raw("inventario.obtener_unidades_disponibles(inventario.bodegas_productos.id_bodega,inventario.bodegas_productos.id_producto,8) as recuperadas"),
               DB::raw("inventario.obtener_unidades_disponibles(inventario.bodegas_productos.id_bodega,inventario.bodegas_productos.id_producto,6) as obsoletas"),
            */
                /*'inventario.bodegas_productos.cantidad as cantidad_disponible',*/

                'inventario.v_productos_venta.precio_sugerido', 'inventario.v_productos_venta.precio_distribuidor', 'inventario.v_productos_venta.costo_estandar as costo_promedio', 'inventario.v_productos_venta.costo_promedio_me', 'inventario.v_productos_venta.id_tipo_producto', 'inventario.v_productos_venta.id_tipo_producto as existencia_minima']);
            if ((!empty($request->id_bodega)) && (!empty($request->q))) {

                $searchValue = strtolower(str_replace(' ', '%', $request->q));
                $productos->where('inventario.v_productos_venta.id_bodega', $request->id_bodega);
//            $productos->Where('inventario.v_productos_venta.cantidad', '>', 0);
                $productos->where(function ($query) use ($searchValue, $ent) {
                    $query->where(DB::raw("LOWER(REPLACE(descripcion, ' ', ''))"), 'LIKE', '%' . $searchValue . '%');
                    $query->Orwhere(DB::raw("LOWER(REPLACE(nombre_comercial, ' ', ''))"), 'LIKE', '%' . $searchValue . '%');
                    $query->Orwhere('codigo_barra', 'ILIKE', '%' . $searchValue . '%');
                    $query->Orwhere('id_producto', '=', $ent);
                });
                $productos->orderBy('inventario.v_productos_venta.nombre_comercial', 'asc');
                return $productos->limit(6)->get();
            }
            $productos->where('nombre_comercial', 'ILIKE', '%%');
            return $productos->limit(0)->get();
        } else {
            $productos = $this->select(['inventario.v_productos_venta.id_producto', 'inventario.v_productos_venta.codigo_sistema', 'inventario.v_productos_venta.unidad_medida', 'inventario.v_productos_venta.tasa_impuesto', 'inventario.v_productos_venta.codigo_barra', 'inventario.v_productos_venta.descripcion', 'inventario.v_productos_venta.nombre_comercial',
                'inventario.v_productos_venta.id_bodega_producto'
                , DB::raw("CONCAT(inventario.v_productos_venta.id_producto,'-',inventario.v_productos_venta.descripcion,' (',inventario.v_productos_venta.codigo_barra,')') AS text"),
                'inventario.v_productos_venta.cantidad as cantidad_disponible', 'inventario.v_productos_venta.no_documento',
                /* DB::raw("(case when inventario.v_productos_venta.tipo_producto = 3 then
                inventario.obtener_unidades_disponibles(inventario.bodegas_productos.id_bodega,inventario.bodegas_productos.id_producto,1)
             else inventario.bodegas_productos.cantidad end)::INTEGER as cantidad_disponible"),*/
                /*    DB::raw("inventario.obtener_unidades_disponibles(inventario.bodegas_productos.id_bodega,inventario.bodegas_productos.id_producto,8) as recuperadas"),
               DB::raw("inventario.obtener_unidades_disponibles(inventario.bodegas_productos.id_bodega,inventario.bodegas_productos.id_producto,6) as obsoletas"),
            */
                /*'inventario.bodegas_productos.cantidad as cantidad_disponible',*/

                'inventario.v_productos_venta.precio_sugerido', 'inventario.v_productos_venta.precio_distribuidor', 'inventario.v_productos_venta.costo_estandar as costo_promedio', 'inventario.v_productos_venta.costo_promedio_me', 'inventario.v_productos_venta.id_tipo_producto', 'inventario.v_productos_venta.id_tipo_producto as existencia_minima']);
            if ((!empty($request->id_bodega)) && (!empty($request->q))) {

                $searchValue = strtolower(str_replace(' ', '%', $request->q));
                $productos->where('inventario.v_productos_venta.id_bodega', $request->id_bodega);
//            $productos->Where('inventario.v_productos_venta.cantidad', '>', 0);
                $productos->where(function ($query) use ($searchValue) {
                    $query->where(DB::raw("LOWER(REPLACE(descripcion, ' ', ''))"), 'LIKE', '%' . $searchValue . '%');
                    $query->Orwhere(DB::raw("LOWER(REPLACE(nombre_comercial, ' ', ''))"), 'LIKE', '%' . $searchValue . '%');
                    $query->Orwhere('codigo_barra', 'ILIKE', '%' . $searchValue . '%');
                });
                $productos->orderBy('inventario.v_productos_venta.nombre_comercial', 'asc');
                return $productos->limit(6)->get();
            }
            $productos->where('nombre_comercial', 'ILIKE', '%%');
            return $productos->limit(0)->get();
        }


    }

}
