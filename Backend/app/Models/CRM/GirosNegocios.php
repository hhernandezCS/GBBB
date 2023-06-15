<?php

namespace App\Models\CRM;

use App\Models\Admon\UsuariosEmpresas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class GirosNegocios extends Model
{
    protected $table = 'crm.giros_negocios';
    protected $primaryKey='id_giro_negocio';
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

    public function obtenerGirosNegocios($request)
    {
        $giro_negocio = $this->select(['*']);
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $statusValue = $request->search['status'];
            $giro_negocio->where($searchField, 'ilike', '%' . $searchValue . '%');
            $giro_negocio->where('id_empresa',$usuario_empresa->id_empresa);
            if($statusValue == 0){
                $giro_negocio->where('estado',1);
                $giro_negocio->where('id_empresa',$usuario_empresa->id_empresa);
            }
            $giro_negocio->orderBy('id_giro_negocio', 'asc');
        }
        return $giro_negocio->paginate($request->limit);
    }

}
