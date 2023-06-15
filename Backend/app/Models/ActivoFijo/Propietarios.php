<?php

namespace App\Models\ActivoFijo;

use DB, Illuminate\Database\Eloquent\Model;

class Propietarios extends Model
{
    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
    protected $table = 'activofijo.propietarios';
    protected $primaryKey='id_propietario';
    protected $fillable = ['descripcion','u_creacion','activo'];
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
            $bancos->where($searchField, 'ilike', '%' . $searchValue . '%');
            if($statusValue == 0){
                $bancos->where('estado',1);
            }
            $bancos->orderBy('id_propietario', 'asc');
        }
        return $bancos->paginate($request->limit);
    }

}
