<?php

namespace App\Models\CRM;

use App\Models\Admon\UsuariosEmpresas;
use App\Models\User;
use Barryvdh\Debugbar\Controllers\AssetController;
use Illuminate\Support\Facades\DB,
    Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SeguimientosLeads extends Model
{
    protected $table = 'crm.seguimientos_leads';
    protected $primaryKey='id_seguimiento_lead';
    protected $fillable = ['estado_seguimiento', 'tipo_contacto', 'valoracion_contacto', 'idioma_llamada',
        'estado_comunicacion', 'id_clasificacion_llamada','servicios_ofr_acor', 'u_responsable',
        'comentario_seguimiento', 'f_actividad', 'asunto_actividad', 'descripcion_actividad', 'u_creacion',
        'u_modificacion','id_empresa', 'id_lead', 'id_vendedor'];
    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
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
        if (in_array($field, $fields, true)) {
            return $fields[$field];
        }

        return $field;
    }

    /**
     * Obtener Listado de Tipos de salida
     *
     * @access 	public
     * @param
     * @return 	json(array)
     */

    public function obtener($request)
    {
        $lead = $this->select(['*']);
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $statusValue = $request->search['status'];
            $lead->where($searchField, 'ilike', '%' . $searchValue . '%');
            $lead->where('id_empresa',$usuario_empresa->id_empresa);
            $lead->where('u_responsable', Auth::user()->name);
            if($statusValue == 0){
                $lead->where('estado',1);
                $lead->where('id_empresa',$usuario_empresa->id_empresa);
                $lead->where('u_responsable',Auth::user()->name);
            }
            $lead->orderBy('id_seguimiento_lead', 'asc');
        }
        return $lead->paginate($request->limit);
    }

    public function seguimientoClasificacionLlamada()
    {
        return $this->belongsTo('App\Models\CRM\ClasificacionLlamadas','id_clasificacion_llamada');
    }

    public function seguimientoServicios()
    {
        return $this->belongsTo('App\Models\CRM\ServiciosOferAcor','id_servicio_ofr_acor');
    }

    public function responsableProspecto(){
        return $this->belongsTo('App\Models\User','u_responsable', 'name')->select('name', 'id');
    }
}
