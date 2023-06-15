<?php

namespace App\Models\CuentasXCobrar;

use Illuminate\Support\Facades\DB, Illuminate\Database\Eloquent\Model;
use App\Models\CuentasXCobrar\CatAuxiliar;
use App\Models\Contabilidad\CuentasContablesVista;
use App\Models\Contabilidad\CentrosCostosIngresos;

class TiposNotasDebito extends Model
{
    protected $table = 'cuentasxcobrar.tipos_nota_debito';
    protected $primaryKey='id_tipo_nota_debito';
    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';

    /**
     * Replace Field
     *
     * @access 	public
     * @param
     * @return 	string
     */

	public function replaceField($field, $fields = [])
    {
        if (in_array($field, $fields)) {
            return $fields[$field];
        }

        return $field;
    }

    /**
     * Obtener Lista de Tipos de ND
     *
     * @access 	public
     * @param
     * @return 	json(array)
     */

    public function obtener($request)
    {
        $tiposND = $this->select(['*']);
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $statusValue = $request->search['status'];
            $tiposND->where($searchField, 'ilike', '%' . $searchValue . '%');
            if($statusValue == 0){
                $tiposND->where('estado',true);
            }
        }
        $tiposND->with('tipoCuentaContable');
        $tiposND->orderby('id_tipo_nota_debito');
        return $tiposND->paginate($request->limit);
    }

    public function tipoCuentaContable()
    {
        return $this->belongsTo(CuentasContablesVista::class,'id_cuenta_contable')->select('id_cuenta_contable','cta_contable','nombre_cuenta_completo');
    }

    public function clasificacionCompraCuentaContable()
    {
        return $this->belongsTo(CuentasContablesVista::class,'id_cuenta_contable')->select('id_cuenta_contable','cta_contable','nombre_cuenta','nombre_cuenta_completo','requiere_aux');
    }
    public function clasificacionCentroCosto()
    {
        return $this->belongsTo(CentrosCostosIngresos::class,'id_centro_costo')->select('*');
    }
    public function clasificacionAuxiliar()
    {
        return $this->belongsTo(CatAuxiliar::class,'id_cat_auxiliar_cxc')->select('*');
    }


}
