<?php

namespace App\Models\Nomina;

use Illuminate\Support\Facades\DB, Illuminate\Database\Eloquent\Model;

class Configuracion extends Model {

    public $timestamps = false;
    protected $table = 'rrhh.configuracion_comprobante_rrhh';
    protected $primaryKey='id_configuracion_rrhh';

    public function obtener($request)
    {
        $importaciones = $this->select(['*']);
        $importaciones->with('configuracionCuentaContable','configuracionCentroCosto','configuracionAuxiliares');
        $importaciones->orderBy('debe_haber', 'asc');
        $importaciones->orderBy('id_configuracion_rrhh', 'asc');
        $importaciones->where('id_tipo_configuracion', $request->id_tipo_configuracion);
        return $importaciones->paginate($request->limit);
    }

    public function configuracionCuentaContable()
    {
        return $this->belongsTo('App\Models\ContabilidadCuentasContablesVista','id_cuenta_contable')->select('id_cuenta_contable','cta_contable','nombre_cuenta','nombre_cuenta_completo','requiere_aux','id_centro_costo','codigo_centro_costo','codigo_auxiliar','id_cat_auxiliar_cxc');
    }

    public function configuracionCentroCosto()
    {
        return $this->belongsTo('App\Models\ContabilidadCentroCostoIngreso','id_centro_costo','id_centro')->select('*');
    }

    public function configuracionAuxiliares()
    {
        return $this->belongsTo('App\Models\CuentasXCobrarCatAuxiliar','id_cat_auxiliar_cxc')->select('*');
    }
}
