<?php

namespace App\Models\Admon;

use DB, Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Departamentos extends Model
{
    protected $table = 'public.departamentos';
    protected $primaryKey='id_departamento';
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
     * Obtener Listado de departamentos
     *
     * @access 	public
     * @param
     * @return 	json(array)
     * @author octaviom
     */

    public function buscar($request){
        $depa = $this->select(['descripcion','id_departamento']);
        if ((!empty($request->q))){
            $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
            $searchValue = $request->q;
//            $user->where('estado', 1);
            $depa->where('id_empresa',$usuario_empresa->id_empresa);
            $depa->where(function($query) use($searchValue){
                $query->where('descripcion', 'ILIKE','%'. $searchValue . '%');
            });
            $this->with('municipiosDepartamento');
            $depa->orderBy('id_departamento', 'asc');
            return $depa->limit(6)->get();
        }else{
//            $user->where('estado', 1);
            $depa->where('descripcion', 'ILIKE', '%Controls%');
            return $depa->limit(0)->get();
        }
    }

    public function obtenerDepartamentos($request)
    {
        $bancos = $this->select(['*']);
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $bancos->where($searchField, 'ilike', '%' . $searchValue . '%');
            $bancos->orderBy('id_departamento', 'asc');
        }
        return $bancos->paginate($request->limit);
    }

    /**
     * Relación municipio - departamento
     * @return HasMany
     * @author octaviom
     */
    public function municipiosDepartamento()
    {
        return $this->hasMany('App\Models\Admon\Municipios','id_departamento')->orderby('id_municipio');
    }
    public function sectoresDepartamento()
    {
        return $this->hasMany('App\Models\Admon\Sectores','id_departamento')->orderby('id_sector');
    }

}
