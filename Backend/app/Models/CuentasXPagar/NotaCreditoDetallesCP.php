<?php

namespace App\Models\CuentasXPagar;

use DB, Illuminate\Database\Eloquent\Model;
use App\Models\CuentasXPagar\NotaCreditoCP;
use App\Models\CuentasXPagar\TiposNotasCreditoCP;

class NotaCreditoDetallesCP extends Model {

	protected $table = 'cuentasxpagar.nota_credito_detalles';
	protected $primaryKey='id_nota_credito_detalle';
	const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
    public $timestamps = false;


	public function cuentaNotaCreditoDetalles()
    {
        return $this->belongsTo(NotaCreditoCP::class,'id_nota_credito');
    }

    public function notaCreditoTipo()
    {
        return $this->belongsTo(TiposNotasCreditoCP::class,'id_tipo_nota_credito');
    }
}
