<?php

namespace App\Models\CuentasXCobrar;

use Illuminate\Support\Facades\DB, Illuminate\Database\Eloquent\Model;
use App\Models\CuentasXCobrar\NotaCredito;
use App\Models\CuentasXCobrar\TiposNotasCredito;

class NotaCreditoDetalles extends Model {

	protected $table = 'cuentasxcobrar.nota_credito_detalles';
	protected $primaryKey='id_nota_credito_detalle';
	const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
    public $timestamps = false;


	public function cuentaNotaCreditoDetalles()
    {
        return $this->belongsTo(NotaCredito::class,'id_nota_credito');
    }

    public function notaCreditoTipo()
    {
        return $this->belongsTo(TiposNotasCredito::class,'id_tipo_nota_credito');
    }
}
