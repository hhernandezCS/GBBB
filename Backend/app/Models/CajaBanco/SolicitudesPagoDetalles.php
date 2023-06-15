<?php

namespace App\Models\CajaBanco;

use App\Models\Admon\UsuariosEmpresas;
use DB, Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SolicitudesPagoDetalles extends Model {

	protected $table = 'cjabnco.solicitudes_pago_detalles';
	protected $primaryKey='id_solicitud_pago_detalle';
    public $timestamps = false;


	public function cuentaSolicitudDetalle()
    {
        return $this->belongsTo('App\Models\CajaBanco\SolicitudesPago','id_solicitud_pago');
    }
}
