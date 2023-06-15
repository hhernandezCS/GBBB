<?php

namespace App\Models\CuentasXCobrar;

use Illuminate\Support\Facades\DB, Illuminate\Database\Eloquent\Model;
use App\Models\CuentasXCobrar\Recibos;

class RecibosDetalles extends Model {

    protected $table = 'cuentasxcobrar.recibos_ingresos_detalles';
    protected $primaryKey='id_recibo_detalle';
    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
    public $timestamps = false;


    public function cuentaReciboDetalle()
    {
        return $this->belongsTo(Recibos::class,'id_recibo');
    }
}
