<?php

namespace App\Models\CRM;

use App\Models\Admon\Departamentos;
use App\Models\Admon\Municipios;
use App\Models\Admon\Paises;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Leads extends Model
{
    protected $table = 'crm.leads';
    protected $primaryKey = 'id_lead';
    protected $fillable = ['nombre', 'apellido', 'id_cargo', 'email_contacto',
        'telefono_contacto', 'telefono_movil', 'nombre_compania', 'email_compania',
        'web_site', 'id_pais', 'id_departamento', 'id_municipio', 'codigo_postal',
        'facebook', 'instagram', 'google_my_business', 'yelp', 'id_giro', 'informacion_empresa',
        'detalle_servicio', 'zona_servicio', 'horario_servicio', 'id_origen_lead', 'id_servicio', 'comentario_lead',
        'codigo_prospecto', 'u_creacion', 'u_modificacion', 'id_empresa', 'estado', 'codigo', 'secuencia', 'id_estado', 'id_ciudad', 'f_descarta'];
    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
    protected $dateFormat = 'Y-m-d H:i:s.u';

    /**
     * Replace Field
     *
     * @access    public
     * @param
     * @return    string
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
     * @access    public
     * @param
     * @return    json(array)
     */

    public function obtener($request)
    {
        $lead = $this->select(['*']);
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $lead->where($searchField, 'ilike', '%' . $searchValue . '%');
            $lead->where('id_empresa', $usuario_empresa->id_empresa);
        }
        //Se convierte en un arreglo el string de la peticion
        $estados = explode(',', $request->map_estados_seleccionados);
        /**
         * Comprobamos si dentro del arreglo se encuentra el valor '0' que significa 'mostrar todos los registros'
         * Si el valor 0 esta presente, entonces se ignora el where que filtra los estado para mostrar todos los registros
         * de lo contrario, se realiza el filtro mediante los estados seleccionados desde el formulario
        */
        if(!in_array(0, $estados)){
            //Pasamos este arreglo a la consulta
            $lead->whereIn('estado', $estados);
        }
        /*
         *  Si el rol del usuario en sesion es distinto de 3 - "administrador" o 8 - "supervisor de venta
         * entonces se ejecuta el where para solo mostrar los registros asignados al usuario en sesion
         * de lo contrario, para los roles mencionados, se cargaran todos los registros en general
         * */
        if (Auth::user()->id_rol !== 3 && Auth::user()->id_rol !== 8) {
            $lead->where('u_responsable', Auth::user()->name);
        }

        /**
         * Utilizamos la funcion 'when' para agregar la clausula 'where' a la consulta solo si se cumple la condicion de que el campo
         * de fechas este tenga un valor, si esta vacio se omite la clausula 'where' y se obtienen todos los registros sin filtrar por fecha.
        */
        $f_desde =  $request->f_desde;
        $f_hasta = $request->f_hasta;
        $f_desde_modificacion =  $request->f_desde_modificacion;
        $f_hasta_modificacion = $request->f_hasta_modificacion;
        $lead->when(!empty($f_desde) && !empty($f_hasta), static function ($query) use ($f_desde, $f_hasta){
            return $query->whereRaw("date_trunc('day', f_creacion) >= ?", [$f_desde])->whereRaw("date_trunc('day', f_creacion) <= ?",[$f_hasta]);
        });

        $lead->when(!empty($f_desde_modificacion) && !empty($f_hasta_modificacion), static function ($query) use ($f_desde_modificacion, $f_hasta_modificacion){
            return $query->whereRaw("date_trunc('day', f_modificacion) >= ?", [$f_desde_modificacion])->whereRaw("date_trunc('day', f_modificacion) <= ?",[$f_hasta_modificacion]);
        });

        if($request->u_responsable){
            $u_responsable = $request->u_responsable['name'];
            $lead->when(!empty($u_responsable), static function ($query) use ($u_responsable){
                return $query->where('u_creacion',$u_responsable);
            });
        }
        // Ordenamos registros por fecha de modificacion de manera ascendente
        $lead->orderBy('f_modificacion', 'desc');
        return $lead->paginate($request->limit);
    }

    public function obtenerCodigoProspecto($extranjero)
    {
        $codigo = $this->select([DB::raw("COALESCE(max(secuencia),0)+1 as secuencia")]);
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        if ((!empty ($extranjero))) {
            $codigo->where('extrajero', $extranjero);
            $codigo->where('crm.prospecto.id_empresa', $usuario_empresa->id_empresa);
        }
        return $codigo->first();
    }

    public function paisProspecto()
    {
        return $this->belongsTo(Paises::class, 'id_pais');
    }

    public function departamentoProspecto()
    {
        return $this->belongsTo(Departamentos::class, 'id_departamento');
    }

    public function municipioProspecto()
    {
        return $this->belongsTo(Municipios::class, 'id_municipio');
    }

    public function ciudadProspecto()
    {
        return $this->belongsTo('App\Models\Admon\Ciudades', 'id_ciudad');
    }

    public function giroProspecto()
    {
        return $this->belongsTo('App\Models\CRM\GirosNegocios', 'id_giro_negocio');
    }

    public function origenProspecto()
    {
        return $this->belongsTo('App\Models\CRM\OrigenesLeads', 'id_origen_lead');
    }

    public function serviciosProspecto()
    {
        return $this->belongsTo('App\Models\CRM\ServiciosOferAcor', 'id_servicio_ofr_acor');
    }

    public function responsableProspecto()
    {
        return $this->belongsTo(User::class, 'u_responsable', 'name')->select('name', 'id');
    }

    public function cargos(){
        return $this->belongsTo(Cargos::class, 'id_cargo');
    }

}
