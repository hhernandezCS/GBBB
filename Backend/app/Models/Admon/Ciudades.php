<?php

namespace App\Models\Admon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Ciudades extends Model
{
    protected $table = 'public.ciudades';
    protected $primaryKey='id_ciudad';
    protected $fillable = ['descripcion','id_pais','id_estado','estado','id_empresa','u_creacion','u_modificacion'];
    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
    /**
     * Replace Field
     *
     * @access 	public
     * @param
     * @return 	string
     */

    public function buscar($request){
        $ciudad = $this->select(['descripcion','id_ciudad']);
        if ((!empty($request->q))){
            $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
            $searchValue = $request->q;
            $estado = $request->r;
            $ciudad->where('id_empresa',$usuario_empresa->id_empresa);
            $ciudad->where('id_estado', $estado);
            $ciudad->where(function($query) use($searchValue){
                $query->where('descripcion', 'ILIKE','%'. $searchValue . '%');
            });
            $ciudad->orderBy('id_ciudad', 'asc');
            return $ciudad->limit(6)->get();
        }else{
            $ciudad->where('descripcion', 'ILIKE', '%Controls%');
            return $ciudad->limit(0)->get();
        }
    }

    public function replaceField($field, $fields = [])
    {
        if (in_array($field, $fields)) {
            return $fields[$field];
        }

        return $field;
    }

    /**
     * Obtener Listado de municipios
     *
     * @access 	public
     * @param
     * @return 	json(array)
     * @author octaviom
     */

    public function obtener($request)
    {

        $ciudades = $this->select(['*']);
        if (!empty($request->search['field'])) {
            if($request->search['field'] === 'estado'){
                $searchField = 'public.estados.descripcion';
            }else {
                $searchField = 'public.ciudades.descripcion';
            }
            $searchValue = $request->search['value'];
            $ciudades->where($searchField, 'ilike', '%' . $searchValue . '%');
        }
        $ciudades->with('estadoCiudades');
        $ciudades->orderby('id_ciudad');
        return $ciudades->paginate($request->limit);
    }

    /**
     * Relación municipio - departamento
     * @return BelongsTo
     * @author rsequeira
     */
    public function estadoCiudades()
    {
        return $this->belongsTo('App\Models\Admon\Estados','id_estado')->select('id_estado','descripcion');
    }

}
