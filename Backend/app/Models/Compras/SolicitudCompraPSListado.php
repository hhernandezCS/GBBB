<?php

namespace App\Models\Compras;

use Illuminate\Support\Facades\DB, Illuminate\Database\Eloquent\Model;

class SolicitudCompraPSListado extends Model {

	public $timestamps = false;
	protected $table = 'compra.solicitudes_servicios_detalles';
	protected $primaryKey='id_solicitud_servicio_detalle';
}
