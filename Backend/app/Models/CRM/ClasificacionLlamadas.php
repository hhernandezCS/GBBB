<?php

namespace App\Models\CRM;

use App\Models\Admon\UsuariosEmpresas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ClasificacionLlamadas extends Model
{
    protected $table = 'crm.clasificacion_llamadas';
    protected $primaryKey='id_clasificacion_llamada';
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

    public function obtenerClasificacionLlamas($request)
    {
        $clasifiacion_llamada = $this->select(['*']);
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $statusValue = $request->search['status'];
            $clasifiacion_llamada->where($searchField, 'ilike', '%' . $searchValue . '%');
            $clasifiacion_llamada->where('id_empresa',$usuario_empresa->id_empresa);
            if($statusValue == 0){
                $clasifiacion_llamada->where('estado',1);
                $clasifiacion_llamada->where('id_empresa',$usuario_empresa->id_empresa);
            }
            $clasifiacion_llamada->orderBy('id_clasificacion_llamada', 'asc');
        }
        return $clasifiacion_llamada->paginate($request->limit);
    }

}
