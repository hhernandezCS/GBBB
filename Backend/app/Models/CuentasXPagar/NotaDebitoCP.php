<?php

namespace App\Models\CuentasXPagar;

use DB, Illuminate\Database\Eloquent\Model;
use App\Models\CuentasXPagar\NotaDebitoDetallesCP;
use App\Models\Inventario\Proveedores;

class NotaDebitoCP extends Model
{
    protected $table = 'cuentasxpagar.nota_debito';
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
        $notas_debito->with('notaDebitoProveedor','notaDebitoDetalles');
        $notas_debito->orderBy('id_nota_debito','desc');

        return $notas_debito->paginate($request->limit);
    }

    public function notaDebitoDetalles()
    {
        return $this->hasMany(NotaDebitoDetallesCP::class,'id_nota_debito');
    }

    public function notaDebitoProveedor()
    {
        return $this->belongsTo(Proveedores::class,'id_proveedor')
            ->select('id_proveedor','nombre_comercial','numero_ruc','numero_cedula','id_tipo_proveedor');
    }




}
