<?php

namespace App\Models\Inventario;

use App\Models\Inventario\BodegaProductos;
use DB, Illuminate\Database\Eloquent\Model;

class SalidaProductos extends Model {

    protected $table = 'inventario.salida_productos';
    protected $primaryKey='id_salida_producto';
    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';

    public function bodegaProducto()
    {
        return $this->belongsTo(BodegaProductos::class,'id_bodega_producto');
    }
    public function producto(){}

}
