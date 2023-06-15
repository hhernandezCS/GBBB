<?php

namespace App\Models\CuentasXCobrar;

use Illuminate\Support\Facades\DB, Illuminate\Database\Eloquent\Model;
use App\Models\CuentasXCobrar\NotaCreditoDetalles;
use App\Models\Ventas\Clientes;

class NotaCredito extends Model
{
    protected $table = 'cuentasxcobrar.nota_credito';
    protected $primaryKey='id_nota_credito';
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
        $notas_credito = $this->select(['*']);
        if (!empty($request->search['field'])) {
           $searchField = $request->search['field'];
           $searchValue = $request->search['value'];
           $notas_credito->where($searchField, 'ilike', '%' . $searchValue . '%');
       }
       $notas_credito->with('notaCreditoCliente','notaCreditoDetalles');
       $notas_credito->orderBy('id_nota_credito','desc');

        return $notas_credito->paginate($request->limit);
    }

    public function notaCreditoDetalles()
    {
        return $this->hasMany(NotaCreditoDetalles::class,'id_nota_credito');
    }

    public function notaCreditoCliente()
    {
        return $this->belongsTo(Clientes::class,'id_cliente')->select('id_cliente','tipo_persona','nombre_comercial','numero_ruc','numero_cedula');
    }

    /*public function notaCreditoFactura()
    {
        return $this->belongsTo('App\Models\CajaBancoFacturas','id_factura')->select('id_factura','no_documento','mt_total');
    }*/
}
