<?php

namespace App\Models\CRM;

use App\Models\Admon\UsuariosEmpresas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ServiciosOferAcor extends Model
{
    protected $table = 'crm.servicios_ofr_acor';
    protected $primaryKey='id_servicio_ofr_acor';
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
        if (in_array($field, $fields)) {
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

    public function obtenerServiciosOfrAcor($request)
    {
        $servicios = $this->select(['*']);
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $statusValue = $request->search['status'];
            $servicios->where($searchField, 'ilike', '%' . $searchValue . '%');
            $servicios->where('id_empresa',$usuario_empresa->id_empresa);
            if($statusValue == 0){
                $servicios->where('estado',1);
                $servicios->where('id_empresa',$usuario_empresa->id_empresa);
            }
            $servicios->orderBy('id_servicio_ofr_acor', 'asc');
        }
        return $servicios->paginate($request->limit);
    }

}
