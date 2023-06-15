<?php

namespace App\Models\CRM;

use App\Models\Admon\Departamentos;
use App\Models\Admon\Estados;
use App\Models\Admon\Municipios;
use App\Models\Admon\Paises;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\User;
use Barryvdh\Debugbar\Controllers\AssetController;
use Illuminate\Support\Facades\DB,
    Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Contactos extends Model
{
    /* The above code is creating a table called contactos and a primary key called id_contacto. */
    protected $table = 'crm.contactos';
    protected $primaryKey='id_contacto';
    /* A list of fields that are allowed to be mass assigned. */
    protected $fillable = ['nombre','apellido','email','telefono_trabajo','telefono_movil',
        'u_creacion','u_modificacion','estado','u_responsable','id_empresa', 'id_medio_contacto',
        'direccion','codigo_postal','id_tipo_contacto','id_clasificacion_contacto','id_cargo', 'id_lead'];
    /* This is telling Laravel to use the f_creacion and f_modificacion fields for the created_at and updated_at fields. */
    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
    protected $dateFormat = 'Y-m-d H:i:s.u';


    public function buscar($request)
    {
        $contactos = $this->select(['crm.contactos.id_contacto', DB::raw("CONCAT(crm.contactos.nombre, ' ' ,crm.contactos.apellido) as nombreCompleto")]);
        if ((!empty($request->q))) {
            $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
            $searchValue = $request->q;
            $contactos->where('estado', 1);
            $contactos->where('id_empresa', $usuario_empresa->id_empresa);
            $contactos->where(function ($query) use ($searchValue) {
                $query->where('nombre', 'ILIKE', '%' . $searchValue . '%');
            });
            $contactos->orderBy('id_contacto', 'asc');
            return $contactos->limit(6)->get();
        } else {
            $contactos->where('estado', 1);
            $contactos->where('nombre', 'ILIKE', '%Controls%');
            return $contactos->limit(0)->get();
        }
    }


    /**
     * It's a function that returns a paginated list of contacts, filtered by a search field and value, and a status value
     * @author hgm7
     * @copyright hgm7
     * @param request The request object.
     *
     * @return The query is being returned.
     */
    public function obtener($request)
    {
        $contactos = $this->select(['*']);
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        $contactos->where('id_empresa',$usuario_empresa->id_empresa);
        $contactos->where('id_contacto','>',1);
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $statusValue = $request->search['status'];
            $contactos->where($searchField, 'ilike', '%' . $searchValue . '%');

            if($statusValue == 0){
                $contactos->where('estado',1);
                $contactos->where('id_empresa',$usuario_empresa->id_empresa);
            }
        }

        if (Auth::user()->id_rol !== 3 && Auth::user()->id_rol !== 8) {
            $contactos->where('u_responsable', Auth::user()->name);
        }

        $contactos->with('companiaContactos');
        $contactos->with('paisContacto');
        $contactos->with('cargos');
        $contactos->orderBy('id_contacto', 'desc');
        return $contactos->paginate($request->limit);
    }

    public function nombreCompleto(){
        $nombre= $this->select(['crm.contactos.id_contacto', DB::raw("CONCAT(crm.contactos.nombre, ' ' ,crm.contactos.apellido) as nombreCompleto")]);
        return $nombre ->get();
    }

    /**
     * The function `companiaContactos()` returns a relationship between the `Contacto` model and the `Compania` model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function companiaContactos()
    {
        return $this->belongsTo(Companias::class,'id_compania');
    }

    public function medioContacto()
    {
        return $this->belongsTo(MediosContacto::class,'id_medio_contacto');
    }

    public function tipoContacto()
    {
        return $this->belongsTo(TipoContactos::class,'id_tipo_contacto');
    }

    public function clasificacionContacto()
    {
        return $this->belongsTo(ClasificacionContactos::class,'id_clasificacion_contacto');
    }

    public function paisContacto()
    {
        return $this->belongsTo(Paises::class,'id_pais')->select('descripcion', 'id_pais');
    }
    public function departamentoContacto()
    {
        return $this->belongsTo(Departamentos::class,'id_departamento');
    }
    public function municipioContacto()
    {
        return $this->belongsTo(Municipios::class,'id_municipio');
    }

    public function estadoContacto()
    {
        return $this->belongsTo(Estados::class,'id_estado');
    }

    public function ciudadContacto()
    {
        return $this->belongsTo('App\Models\Admon\Ciudades','id_ciudad');
    }

    public function responsableProspecto(){
        return $this->belongsTo('App\Models\User','u_responsable', 'name')->select('name', 'id');
    }

    public function cargos(){
        return $this->belongsTo(Cargos::class, 'id_cargo');
    }

}
