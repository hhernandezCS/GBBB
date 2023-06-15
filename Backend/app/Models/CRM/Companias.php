<?php

namespace App\Models\CRM;

use App\Models\Admon\Departamentos;
use App\Models\Admon\Estados;
use App\Models\Admon\Municipios;
use App\Models\Admon\Paises;
use App\Models\Admon\UsuariosEmpresas;
use Illuminate\Support\Facades\DB,
    Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Companias extends Model
{
    protected $table = 'crm.companias';
    protected $primaryKey = 'id_compania';
    protected $fillable = ['nombre_compania', 'telefono_compania', 'email', 'web_site', 'direccion_compania',
        'id_departamento', 'id_municipio', 'id_estado', 'id_ciudad', 'codigo_postal', 'facebook', 'twitter', 'instagram', 'google_mb',
        'yelp', 'id_giro_negocio', 'informacion_empresa', 'detalle_servicio', 'zona_servicio', 'horario_servicio', 'id_origen_lead',
        'url_origen', 'id_servicios_necesitados', 'id_servicios_ofertados', 'u_creacion', 'f_creacion', 'u_modificacion',
        'f_modificacion', 'u_responsable', 'id_empresa', 'estado', 'id_contacto', 'id_contactos', 'ubicacion_compania', 'servicio_destacado', 'id_lead'];
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

    public function buscar($request)
    {
        $companias = $this->select(['id_compania', 'nombre_compania', 'telefono_compania','email', 'web_site', 'direccion_compania',
            'id_departamento', 'id_municipio', 'id_estado', 'id_ciudad', 'codigo_postal', 'facebook', 'twitter', 'instagram', 'google_mb'
            , 'yelp', 'id_giro_negocio', 'informacion_empresa', 'detalle_servicio', 'zona_servicio', 'horario_servicio', 'id_origen_lead',
            'url_origen', 'id_servicios_necesitados', 'id_servicios_ofertados', 'u_creacion', 'u_responsable', 'estado', 'id_contacto', 'id_contactos', 'ubicacion_compania', 'servicio_destacado', 'id_lead']);
        if ((!empty($request->q))) {
            $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
            $searchValue = $request->q;
            $companias->where('estado', 1);
            $companias->where('id_empresa', $usuario_empresa->id_empresa);
            $companias->where(function ($query) use ($searchValue) {
                $query->where('nombre_compania', 'ILIKE', '%' . $searchValue . '%')
                    ->orWhere('telefono_compania', 'ILIKE', '%' . $searchValue . '%');
            });
            $companias->orderBy('id_compania', 'asc');
            return $companias->limit(6)->get();
        } else {
            $companias->where('estado', 1);
            $companias->where('nombre_compania', 'ILIKE', '%Controls%');
            return $companias->limit(0)->get();
        }
    }

    public function obtener($request)
    {
        $companias = $this->select(['*']);
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        $companias->where('id_empresa', $usuario_empresa->id_empresa);
        $companias->where('id_compania', '>', 1);
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $statusValue = $request->search['status'];
            $companias->where($searchField, 'ilike', '%' . $searchValue . '%');

            if ($statusValue == 0) {
                $companias->where('estado', 1);
                $companias->where('id_empresa', $usuario_empresa->id_empresa);
            }
        }

        if (Auth::user()->id_rol !== 3 && Auth::user()->id_rol !== 8) {
            $companias->where('u_responsable', Auth::user()->name);
        }

        $companias->with('companiaContactos');
        $companias->with('paisCompania');
        $companias->with('giroNegocio');
        $companias->orderBy('id_compania', 'desc');
        return $companias->paginate($request->limit);
    }

    public function companiaContactos()
    {
        return $this->belongsTo(Contactos::class, 'id_contacto')->select(['crm.contactos.id_contacto', DB::raw("CONCAT(crm.contactos.nombre, ' ' ,crm.contactos.apellido) as nombreCompleto")]);
    }

    public function giroNegocio()
    {
        return $this->belongsTo('App\Models\CRM\GirosNegocios', 'id_giro_negocio');
    }

    public function responsableProspecto()
    {
        return $this->belongsTo('App\Models\User', 'u_responsable', 'name')->select('name', 'id');
    }

    public function paisCompania()
    {
        return $this->belongsTo(Paises::class, 'id_pais');
    }

    public function departamentoCompania()
    {
        return $this->belongsTo(Departamentos::class, 'id_departamento');
    }

    public function municipioCompania()
    {
        return $this->belongsTo(Municipios::class, 'id_municipio');
    }

    public function estadoCompania()
    {
        return $this->belongsTo(Estados::class, 'id_estado');
    }

    public function ciudadCompania()
    {
        return $this->belongsTo('App\Models\Admon\Ciudades', 'id_ciudad');
    }

}
