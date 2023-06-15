<?php

namespace App\Models\CRM;

use App\Models\Admon\UsuariosEmpresas;
use App\Models\User;
use Barryvdh\Debugbar\Controllers\AssetController;
use Illuminate\Support\Facades\DB,
    Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TipoContactos extends Model
{
    /* The above code is creating a table called tipo_contactos and a primary key called id_tipo_contacto. */
    protected $table = 'crm.tipo_contactos';
    protected $primaryKey='id_tipo_contacto';
    /* A list of fields that are allowed to be mass assigned. */
    protected $fillable = ['descripcion','u_creacion','u_modificacion','estado','id_empresa'];
    /* This is telling Laravel to use the f_creacion and f_modificacion fields for the created_at and updated_at fields. */
    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
    protected $dateFormat = 'Y-m-d H:i:s.u';




    /**
     * It's a function that returns a paginated list of contacts, filtered by a search field and value, and a status value
     * @author rsequeira
     * @copyright rsequeira
     * @param request The request object.
     *
     * @return The query is being returned.
     */
    public function obtener($request)
    {
        $contactos = $this->select(['*']);
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $statusValue = $request->search['status'];
            $contactos->where($searchField, 'ilike', '%' . $searchValue . '%');
            $contactos->where('id_empresa',$usuario_empresa->id_empresa);
            if($statusValue == 0){
                $contactos->where('estado',1);
                $contactos->where('id_empresa',$usuario_empresa->id_empresa);
            }
        }
        $contactos->where('id_empresa',$usuario_empresa->id_empresa);
        $contactos->orderBy('id_tipo_contacto', 'desc');
        return $contactos->paginate($request->limit);
    }

}
