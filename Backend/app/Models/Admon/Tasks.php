<?php

namespace App\Models\Admon;

use DB, Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\This;

class Tasks extends Model
{
    protected $table = 'public.tasks';
    protected $primaryKey='id_tasks';
    protected $fillable = ['title','descripcion','id_responsable','due_date','id_tag'
        ,'is_priority','url_drive','estado','id_participante','id_observador','u_creacion','u_modificacion','estado'];
    public $timestamps = false;
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
     * Obtener Listado de Paises
     *
     * @access 	public
     * @param
     * @return 	json(array)
     */

    public function obtenerTasks($request)
    {
        $bancos = $this->select(['*']);
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $statusValue = $request->search['status'];
            $id_empresa = session()->get('id_empresa');
            $bancos->where($searchField, 'ilike', '%' . $searchValue . '%');
            if($statusValue == 0){
                $bancos->where('estado',true);
            }
            $bancos->orderBy('id_pais', 'asc');
        }
        return $bancos->paginate($request->limit);
    }

}
