<?php

namespace App\Models\ActivoFijo;

use DB, Illuminate\Database\Eloquent\Model;

class TiposActivos extends Model
{
    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
    protected $table = 'activofijo.tipos_activos';
    //protected $dateFormat = 'Y-m-d H:i:s.u';
    protected $primaryKey='id_tipo_activo';
    protected $fillable = ['descripcion','u_creacion','activo'];

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
     * Obtener Listado de Grupo de activo
     *
     * @access 	public
     * @param
     * @return 	json(array)
     */

    public function obtener($request)
    {
        $bancos = $this->select(['*']);
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $statusValue = $request->search['status'];
            if($statusValue == 0){
                $bancos->where('estado',1);
            }
            $bancos->where($searchField, 'ilike', '%' . $searchValue . '%');
            $bancos->orderBy('id_tipo_activo', 'asc');
        }
        return $bancos->paginate($request->limit);
    }

    public function gruposActivos()
    {
        return $this->hasMany('App\Models\ActivoFijo\Grupos','id_tipo_activo')->where('nivel',2);
    }
}
