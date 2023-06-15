<?php

namespace App\Models\Compras;

use DB, Illuminate\Database\Eloquent\Model;
use App\Models\Inventario\Productos;
use App\Models\Inventario\Proveedores;

class SolicitudCompraProductos extends Model {

	public $timestamps = false;
	protected $table = 'compra.solicitudes_compras_productos';
	protected $primaryKey='id_solicitud_compra_producto';

    public function solicitudProducto()
    {
        return $this->belongsTo(Productos::class,'id_producto')->select('id_producto','descripcion','codigo_sistema','id_unidad_medida')->with('unidadMedida');
    }

    public function solicitudProveedor()
    {
        return $this->belongsTo(Proveedores::class,'id_proveedor')->select('id_proveedor','nombre_comercial');
    }
}
