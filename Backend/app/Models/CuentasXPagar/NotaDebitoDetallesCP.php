<?php

namespace App\Models\CuentasXPagar;

use Illuminate\Support\Facades\DB, Illuminate\Database\Eloquent\Model;
use App\Models\CuentasXPagar\NotaDebitoCP;
use App\Models\CuentasXPagar\TiposNotasDebitoCP;

class NotaDebitoDetallesCP extends Model {

	protected $table = 'cuentasxpagar.nota_debito_detalles';
	protected $primaryKey='id_nota_debito_detalle';
	const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
    public $timestamps = false;


	public function cuentaNotaDebitoDetalles()
    {
        return $this->belongsTo(NotaDebitoCP::class,'id_nota_debito');
    }

    public function notaDebitoTipo()
    {
        return $this->belongsTo(TiposNotasDebitoCP::class,'id_tipo_nota_debito');
    }
}
