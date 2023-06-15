<?php

namespace App\Models\Tesoreria\CajaChica;

use DB, Illuminate\Database\Eloquent\Model;

class Normativa extends Model
{
    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
    protected $table = 'cjabnco.normativas';
    protected $primaryKey='id_normativa';
    protected $fillable = ['concepto','monto_minimo','monto_maximo','tiempo','estado'];
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
/*
    public function obtenerCodigoZona()
    {
        $codigo = $this->select([DB::raw("COALESCE(max(codigo::integer),0)+1 as secuencia")]);
        return $codigo->first();
    }
*/
    /**
     * Obtener Listado de Normativas
     *
     * @access 	public
     * @param
     * @return 	json(array)
     */

    public function obtenerNormativa($request)
    {
        $normativas = $this->select(['id_normativa','concepto','monto_minimo','monto_maximo','tiempo','estado']);
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $statusValue = $request->search['status'];
            $normativas->where($searchField, 'ilike', '%' . $searchValue . '%');
            if($statusValue == 0){
                $normativas->where('estado',true);
            }

            $normativas->orderBy('id_normativa', 'asc');
        }
        return $normativas->paginate($request->limit);
    }

}
