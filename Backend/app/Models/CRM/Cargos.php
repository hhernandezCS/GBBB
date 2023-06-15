<?php

namespace App\Models\CRM;

use App\Models\Admon\UsuariosEmpresas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cargos extends Model
{
    protected $table = 'crm.cargos';
    protected $primaryKey='id_cargo';
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

    public function obtenerCargos($request)
    {
        $cargo = $this->select(['*']);
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $statusValue = $request->search['status'];
            $cargo->where($searchField, 'ilike', '%' . $searchValue . '%');
            $cargo->where('id_empresa',$usuario_empresa->id_empresa);
            if($statusValue == 0){
                $cargo->where('estado',1);
                $cargo->where('id_empresa',$usuario_empresa->id_empresa);
            }
            $cargo->orderBy('id_cargo', 'asc');
        }
        return $cargo->paginate($request->limit);
    }

}
