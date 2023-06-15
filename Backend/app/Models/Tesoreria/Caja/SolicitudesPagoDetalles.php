<?php

namespace App\Models\Tesoreria\Caja;

use DB, Illuminate\Database\Eloquent\Model;

class SolicitudesPagoDetalles extends Model {

	protected $table = 'cjabnco.solicitudes_pago_detalles';
	protected $primaryKey='id_solicitud_pago_detalle';
    public $timestamps = false;


	public function cuentaSolicitudDetalle()
    {
        return $this->belongsTo('App\Models\Tesoreria\Caja\SolicitudesPago','id_solicitud_pago');
    }
}
