<?php

namespace App\Models\CuentasXCobrar;

use Illuminate\Support\Facades\DB, Illuminate\Database\Eloquent\Model;
use App\Models\Ventas\Clientes;
use App\Models\RRHHTrabajadores;

class CatAuxiliar extends Model
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

        $cat_auxiliar->where('clasificacion',1); //clasificacion 1 -> cuentas por cobrar
        $cat_auxiliar->orderBy('id_cat_auxiliar_cxc','desc');

        return $cat_auxiliar->paginate($request->limit);
    }

    public function trabajadorAuxiliar()
    {
        return $this->belongsTo(RRHHTrabajadores::class,'id_trabajador')->select('id_trabajador','primer_apellido','primer_nombre','segundo_apellido','segundo_nombre',
            'id_area','id_cargo','codigo',
            \Illuminate\Support\Facades\DB::raw("CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido) AS nombre_completo"),
            DB::raw("substr(codigo,2,8) as simplify_code"))->where('activo',true);
    }

    public function deudorAuxiliar()
    {
        return $this->belongsTo(Clientes::class,'id_cliente');
    }


}
