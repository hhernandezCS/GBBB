<?php

namespace App\Models\Contabilidad;

use App\Models\Admon\UsuariosEmpresas;
use App\Models\CuentasXCobrar\CatAuxiliar;
use Illuminate\Support\Facades\DB, Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Contabilidad\CuentasContablesVista;
use App\Models\Contabilidad\CentrosCostosIngresos;

class ConfiguracionComprobantes extends Model {

    public $timestamps = false;
    protected $table = 'contabilidad.configuracion_comprobante';
    protected $primaryKey='id_configuracion_contabilidad';


    public function obtener($request)
    {
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        $importaciones = $this->select(['*']);
        $importaciones->with('configuracionCuentaContable','configuracionCentroCosto');//,'configuracionAuxiliares');
        $importaciones->orderBy('debe_haber', 'asc');
        $importaciones->orderBy('id_configuracion_contabilidad', 'asc');
        $importaciones->where('id_tipo_configuracion', $request->id_tipo_configuracion);
        $importaciones->where('id_empresa',$usuario_empresa->id_empresa);
        return $importaciones->paginate($request->limit);
    }

    public function configuracionCuentaContable()
    {
        return $this->belongsTo(CuentasContablesVista::class,'id_cuenta_contable')->select('id_cuenta_contable','cta_contable','nombre_cuenta','nombre_cuenta_completo','requiere_aux','id_centro_costo','codigo_centro_costo','codigo_auxiliar','id_cat_auxiliar_cxc');
    }

    public function configuracionCentroCosto()
    {
        return $this->belongsTo(CentrosCostosIngresos::class,'id_centro_costo','id_centro')->select('*');
    }

    public function configuracionAuxiliares()
    {
        return $this->belongsTo(CatAuxiliar::class,'id_cat_auxiliar_cxc')->select('*');
    }
}
