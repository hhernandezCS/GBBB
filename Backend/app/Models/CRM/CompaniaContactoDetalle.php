<?php

namespace App\Models\CRM;

use App\Models\Admon\UsuariosEmpresas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CompaniaContactoDetalle extends Model
{
    protected $table = 'crm.compania_contacto_detalle';
    protected $primaryKey='id_compania_contacto_detalle';
    protected $fillable = ['id_lead','id_contacto','id_compania','id_origen_lead','url_origen', 'servicio_necesitado',
        'detalle_servicio', 'comentario_lead', 'id_empresa'];
    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
    protected $dateFormat = 'Y-m-d H:i:s.u';


    /**
     * Obtener Listado de Tipos de salida
     *
     * @access 	public
     * @param
     * @return 	json(array)
     */

    public function obtenerCompaniaContactoDetalle($request)
    {
        $cargo = $this->select(['*']);
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $statusValue = $request->search['status'];
            $cargo->where($searchField, 'ilike', '%' . $searchValue . '%');
            $cargo->where('id_empresa',$usuario_empresa->id_empresa);
            if($statusValue == 0){
                $cargo->where('estado',1);
                $cargo->where('id_empresa',$usuario_empresa->id_empresa);
            }
            $cargo->orderBy('id_compania_contacto_detalle', 'desc');
        }
        return $cargo->paginate($request->limit);
    }

}
