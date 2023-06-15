<?php

namespace App\Models\CuentasXCobrar;

use Illuminate\Support\Facades\DB, Illuminate\Database\Eloquent\Model;
use App\Models\Contabilidad\CuentasContablesVista;

class TiposCargosAbonosTrabajador extends Model
{
    protected $table = 'cuentasxcobrar.tipos_cargo_abono_trabajador';
    protected $primaryKey='id_tipo_cargo_abono_trabajador';
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
     * Obtener Lista de Tipos de AC Trabajador
     *
     * @access 	public
     * @param
     * @return 	json(array)
     */

    public function obtener($request)
    {
        $tiposACTrabajador = $this->select(['*']);
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $statusValue = $request->search['status'];
            $tiposACTrabajador->where($searchField, 'ilike', '%' . $searchValue . '%');
            if($statusValue == 0){
                $tiposACTrabajador->where('estado',true);
            }
        }
        $tiposACTrabajador->with('tipoCuentaContable');
        $tiposACTrabajador->orderby('id_tipo_cargo_abono_trabajador');
        return $tiposACTrabajador->paginate($request->limit);
    }

    public function tipoCuentaContable()
    {
        return $this->belongsTo(CuentasContablesVista::class,'id_cuenta_contable')->select('id_cuenta_contable','cta_contable','nombre_cuenta_completo');
    }

}
