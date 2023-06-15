<?php

namespace App\Models\Compras;

use DB, Illuminate\Database\Eloquent\Model;

class OrdenCompraPSListado extends Model {

	public $timestamps = false;
	protected $table = 'compra.ordenes_servicios_listado';
	protected $primaryKey='id_orden_servicio_detalle';

    public function servicioClasificacion()
    {
        return $this->belongsTo('App\Models\ClasificacionCompras','id_clasificacion_servicio')->select('id_clasificacion_servicio','descripcion','id_cuenta_contable','id_centro_costo','id_cat_auxiliar_cxc','concepto_comprobante')->with('clasificacionCompraCuentaContable','clasificacionCentroCosto','clasificacionAuxiliar');
    }
}
