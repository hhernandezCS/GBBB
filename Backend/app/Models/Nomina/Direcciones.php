<?php

namespace App\Models\Nomina;

use Illuminate\Support\Facades\DB, Illuminate\Database\Eloquent\Model;

class Direcciones extends Model
{
    protected $table = 'public.direcciones';
    protected $dateFormat = 'Y-m-d H:i:s.u';
    protected $primaryKey='id_direccion';
    protected $fillable = ['descripcion','u_creacion','estado'];
    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
    /**
     * Replace Field
     *
     * @access 	public
     * @param
     * @return 	string
     */


    public function replaceField($field, $fields = []): string
    {
        if (in_array($field, $fields)) {
            return $fields[$field];
        }

        return $field;
    }

    /**
     * Obtener Listado de Direcciones
     *
     * @access 	public
     * @param
     * @return 	json(array)
     */

    public function obtenerDirecciones($request)
    {
        $bancos = $this->select(['*']);
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $statusValue = $request->search['status'];
            $bancos->where($searchField, 'ilike', '%' . $searchValue . '%');
            if($statusValue == 0){
                $bancos->where('estado',true);
            }
            $bancos->with('direccionSucursal');
            $bancos->orderBy('id_direccion', 'asc');
        }
        return $bancos->paginate($request->limit);
    }

    public function direccionSucursal()
    {
        return $this->belongsTo('App\Models\PublicSucursales', 'id_sucursal') ->select('id_sucursal','descripcion','serie');
    }

}
