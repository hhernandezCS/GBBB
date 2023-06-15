<?php

namespace App\Models\CuentasXPagar;

use Illuminate\Support\Facades\DB, Illuminate\Database\Eloquent\Model;
use App\Models\CuentasXPagar\NotaCreditoDetallesCP;
use App\Models\Inventario\Proveedores;

class NotaCreditoCP extends Model
{
    protected $table = 'cuentasxpagar.nota_credito';
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
       $notas_credito->with('notaCreditoProveedor','notaCreditoDetalles');
       $notas_credito->orderBy('id_nota_credito','desc');

        return $notas_credito->paginate($request->limit);
    }

    public function notaCreditoDetalles()
    {
        return $this->hasMany(NotaCreditoDetallesCP::class,'id_nota_credito');
    }

    public function notaCreditoProveedor()
    {
        return $this->belongsTo(Proveedores::class,'id_proveedor')
            ->select('id_proveedor','nombre_comercial','numero_ruc','numero_cedula','id_tipo_proveedor');
    }

}
