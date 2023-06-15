<?php

namespace App\Models\CRM;

use App\Models\Admon\UsuariosEmpresas;
use Illuminate\Support\Facades\DB,
    Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrigenesLeads extends Model
{
    protected $table = 'crm.origenes_leads';
    protected $primaryKey='id_origen_lead';
    protected $fillable = ['descripcion','u_creacion','u_modificacion','id_empresa','estado'];
    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
    protected $dateFormat = 'Y-m-d H:i:s.u';
    /**
     * Replace Field
     *
     * @access 	public
     * @param
     * @return 	string
     */

	public function replaceField($field, $fields = [])
    {
        if (in_array($field, $fields, true)) {
            return $fields[$field];
        }

        return $field;
    }

    /**
     * Obtener Listado de Tipos de salida
     *
     * @access 	public
     * @param
     * @return 	json(array)
     */

    public function obtener($request)
    {
        $origen_lead = $this->select(['*']);
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $statusValue = $request->search['status'];
            $origen_lead->where($searchField, 'ilike', '%' . $searchValue . '%');
            $origen_lead->where('id_empresa',$usuario_empresa->id_empresa);
            if($statusValue == 0){
                $origen_lead->where('estado',1);
                $origen_lead->where('id_empresa',$usuario_empresa->id_empresa);
            }
            $origen_lead->orderBy('id_origen_lead', 'asc');
        }
        return $origen_lead->paginate($request->limit);
    }

}
