<?php

namespace App\Models\ActivoFijo;

use DB, Illuminate\Database\Eloquent\Model;

class Cuentas extends Model
{

    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
    protected $table = 'activofijo.cuentas_activos';
    protected $primaryKey='id_cuenta_activo';
    //protected $fillable = ['id_cuenta_activo','descripcion','id_cuenta_depreciacion','id_cuenta_depreciado','activo'];

    public function obtenerCuentasActivos($request)
    {
        $cuentas = $this->select(['id_cuenta_activo', 'id_sucursal', 'descripcion', 'id_cuenta_depreciacion', 'descripcion_cuenta_depreciacion',
            'id_cuenta_depreciado', 'descripcion_cuenta_depreciado', 'anios_vida',  'activo', 'id_tipo_activo']);
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $cuentas->where($searchField, 'ilike', '%' . $searchValue . '%');
        }
        $cuentas->with('cuentaContable');
        $cuentas->with('cuentaContableDepreciado');
        $cuentas->orderBy('id_tipo_activo', 'asc');

        return $cuentas->paginate($request->limit);
    }

    public function cuentaContable()
    {
        return $this->belongsTo('App\Models\Contabilidad\CuentasContablesVista','id_cuenta_depreciacion')->select('id_cuenta_contable','cta_contable','nombre_cuenta','nombre_cuenta_completo');
    }
    public function cuentaContableDepreciado()
    {
        return $this->belongsTo('App\Models\Contabilidad\CuentasContablesVista','id_cuenta_depreciado')->select('id_cuenta_contable','cta_contable','nombre_cuenta','nombre_cuenta_completo');
    }

}
