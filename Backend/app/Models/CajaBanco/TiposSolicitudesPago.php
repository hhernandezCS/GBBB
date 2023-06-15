<?php

namespace App\Models\CajaBanco;

use App\Models\Admon\UsuariosEmpresas;
use DB, Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TiposSolicitudesPago extends Model
{
    protected $table = 'cjabnco.tipos_solicitudes_pago';
    protected $primaryKey='id_tipo_solicitud';
    protected $fillable = ['descripcion'];
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
     * Obtener Lista de Tipos solicitud pagos
     *
     * @access 	public
     * @param
     * @return 	json(array)
     */

    public function obtener($request)
    {
        $tipos = $this->select(['*']);
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $statusValue = $request->search['status'];
            $tipos->where($searchField, 'ilike', '%' . $searchValue . '%');
            if($statusValue == 0){
                $tipos->where('estado',1);
            }
        }
        $tipos->orderBy('id_tipo_solicitud');
        return $tipos->paginate($request->limit);
    }

}
