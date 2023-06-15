<?php

namespace App\Models\CuentasXPagar;

use Illuminate\Support\Facades\DB, Illuminate\Database\Eloquent\Model;
use App\Models\Inventario\Proveedores;
use App\Models\Contabilidad\CuentasContables;
use App\Models\CajaBanco\Bancos;

class CatAuxiliarCP extends Model
{
    protected $table = 'cuentasxcobrar.cat_auxiliar_cxc';
    protected $primaryKey='id_cat_auxiliar_cxc';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    const CREATED_AT = 'f_registra';
    const UPDATED_AT = 'f_modifica';

    /**
     * Obtener Lista de entradas
     *
     * @access  public
     * @param
     * @return  json(array)
     */

    public function obtener($request)
    {
        $cat_auxiliar = $this->select(['*']);
        if (!empty($request->search['field'])) {
           $searchField = $request->search['field'];
           $searchValue = $request->search['value'];
            $cat_auxiliar->where($searchField, 'ilike', '%' . $searchValue . '%');
       }

        $cat_auxiliar->whereIn('clasificacion',array(2)); //clasificacion 2 -> cuentas por pagar
        $cat_auxiliar->orderBy('id_cat_auxiliar_cxc','desc');

        return $cat_auxiliar->paginate($request->limit);
    }

    public function proveedor_auxiliar()
    {
        return $this->belongsTo(Proveedores::class,'id_cat_auxiliar_cxc');
    }

    public function pasivo_auxiliar()
    {
        return $this->belongsTo(CuentasContables::class,'id_cuenta_contable');
    }

    public function prestamos_auxiliar()
    {
        return $this->belongsTo(Bancos::class,'id_banco');
    }

}
