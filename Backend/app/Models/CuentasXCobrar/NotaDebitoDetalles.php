<?php

namespace App\Models\CuentasXCobrar;

use Illuminate\Support\Facades\DB, Illuminate\Database\Eloquent\Model;
use App\Models\CuentasXCobrar\NotaDebito;
use App\Models\CuentasXCobrar\TiposNotasDebito;

class NotaDebitoDetalles extends Model {

	protected $table = 'cuentasxcobrar.nota_debito_detalles';
	protected $primaryKey='id_nota_debito_detalle';
	const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
    public $timestamps = false;


	public function cuentaNotaDebitoDetalles()
    {
        return $this->belongsTo(NotaDebito::class,'id_nota_debito');
    }

    public function notaDebitoTipo()
    {
        return $this->belongsTo(TiposNotasDebito::class,'id_tipo_nota_debito');
    }
}
