<?php

namespace App\Models\CajaBanco;

use App\Models\Admon\UsuariosEmpresas;
use DB, Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Normativas extends Model
{

    protected $table = 'cjabnco.Normativas';
    protected $primaryKey='id_normativa';
    protected $fillable = ['concepto','monto_minimo','monto_maximo','tiempo','u_creacion','u_modificacion','estado','id_empresa'];
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
     * Obtener Listado de Normativas
     *
     * @access 	public
     * @param
     * @return 	json(array)
     */

    public function obtenerNormativas($request)
    {
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        $normativas = $this->select(['*']);
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
    public function normativaViaticos(){
        $this->hasMany('App\Models\CajaBanco\Viaticos','id_normativa');
    }
}
