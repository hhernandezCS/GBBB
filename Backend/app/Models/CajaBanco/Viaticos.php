<?php

namespace App\Models\CajaBanco;

use App\Models\Admon\UsuariosEmpresas;
use DB, Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Viaticos extends Model
{

    protected $table = 'cjabnco.viaticos';
    protected $primaryKey='id_viatico';
    protected $fillable = ['descripcion','monto','id_normativa','u_creacion','u_modificacion','estado','id_empresa'];
    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
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
     * Obtener Listado de viaticos
     *
     * @access 	public
     * @param
     * @return 	json(array)
     */

    public function obtenerViaticos($request)
    {
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        $viaticos = $this->select(['*']);
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $statusValue = $request->search['status'];
            $viaticos->where($searchField, 'ilike', '%' . $searchValue . '%');
            if($statusValue == 0){
                $viaticos->where('estado',true);
            }
            $viaticos->orderBy('id_viatico', 'asc');
        }
        $viaticos->with('normativaViatico');
        return $viaticos->paginate($request->limit);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function normativaViatico()
    {
        return $this->belongsTo('App\Models\CajaBanco\Normativas','id_normativa');
    }
}
