<?php

namespace App\Models\Admon;

use DB, Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Estados extends Model
{
    protected $table = 'public.estados';
    protected $primaryKey='id_estado';
    protected $fillable = ['descripcion','id_empresa','estado','id_pais','u_creacion','u_modificacion'];
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
     * Obtener Listado de estados
     *
     * @access 	public
     * @param
     * @return 	json(array)
     * @author hgm7
     */

    public function buscar($request){
        $estado = $this->select(['descripcion','id_estado']);
        if ((!empty($request->q))){
            $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
            $searchValue = $request->q;
            $estado->where('estado', 1);
            $estado->where('id_empresa',$usuario_empresa->id_empresa);
            $estado->where(function($query) use($searchValue){
                $query->where('descripcion', 'ILIKE','%'. $searchValue . '%');
            });

            $this->with('ciudadesEstado');

            $estado->orderBy('id_estado', 'asc');
            return $estado->limit(6)->get();
        }else{
            $estado->where('estado', 1);
            $estado->where('descripcion', 'ILIKE', '%Controls%');
            return $estado->limit(0)->get();
        }
    }

    public function obtener($request)
    {
        $estado = $this->select(['*']);
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $estado->where($searchField, 'ilike', '%' . $searchValue . '%');
            $estado->orderBy('id_estado', 'asc')
            ->with('estadosPais');
        }

        return $estado->paginate($request->limit);
    }

    /**
     * Relación ciudades - estado
     * @return HasMany
     * @author hgm7
     */
    public function ciudadesEstado()
    {
        return $this->hasMany('App\Models\Admon\Ciudades','id_estado')->orderby('id_ciudad');
    }

    public function estadosPais()
    {
        return $this->belongsTo('App\Models\Admon\Paises','id_pais');
    }
}
