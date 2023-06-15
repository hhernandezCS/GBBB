<?php

namespace App\Models\Compras;

use DB, Illuminate\Database\Eloquent\Model;
use App\Models\Inventario\Productos;

class OrdenCompraProductos extends Model {

	public $timestamps = false;
	protected $table = 'compra.ordenes_compras_productos';
	protected $primaryKey='id_orden_compra_producto';

    public function ordenCompraProducto()
    {
        return $this->belongsTo(Productos::class,'id_producto')->select('id_producto','descripcion','codigo_sistema','id_unidad_medida','codigo_barra')->with('unidadMedida');
    }



}
