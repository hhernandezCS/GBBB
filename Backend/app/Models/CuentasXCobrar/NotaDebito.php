<?php

namespace App\Models\CuentasXCobrar;

use Illuminate\Support\Facades\DB, Illuminate\Database\Eloquent\Model;
use App\Models\CuentasXCobrar\NotaDebitoDetalles;
use App\Models\Ventas\Clientes;

class NotaDebito extends Model
{
    protected $table = 'cuentasxcobrar.nota_debito';
    protected $primaryKey='id_nota_debito';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['num_documento','beneficiario','valor','estado'];

    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';

    /**
     * Obtener Lista de entradas
     *
     * @access  public
     * @param
     * @return  json(array)
     */

    public function obtener($request)
    {
        $notas_debito = $this->select(['*']);
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $notas_debito->where($searchField, 'ilike', '%' . $searchValue . '%');
        }
        $notas_debito->with('notaDebitoCliente','notaDebitoDetalles','notaDebitoSucursal');
        $notas_debito->orderBy('id_nota_debito','desc');

        return $notas_debito->paginate($request->limit);
    }

    public function notaDebitoDetalles()
    {
        return $this->hasMany(NotaDebitoDetalles::class,'id_nota_debito');
    }

    public function notaDebitoCliente()
    {
        return $this->belongsTo(Clientes::class,'id_cliente')->select('id_cliente','tipo_persona','nombre_comercial','numero_ruc','numero_cedula');
    }

    /*public function notaDebitoFactura()
    {
        return $this->belongsTo('App\Models\CajaBancoFacturas','id_factura')->select('id_factura','no_documento','mt_total');
    }*/
}
