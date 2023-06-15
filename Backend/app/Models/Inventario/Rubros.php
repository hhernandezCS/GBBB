<?php

namespace App\Models\Inventario;

use App\Models\Admon\UsuariosEmpresas;
use Illuminate\Support\Facades\DB, Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Rubros extends Model
{
    protected $table = 'inventario.rubros';
    protected $primaryKey='id_rubro';
    protected $fillable = ['codigo','descripcion','u_creacion','u_modificacion','estado'];
    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
    protected $dateFormat = 'Y-m-d H:i:s.u';

    /**
     * Replace Field
     *
     * @access    public
     * @param
     * @param array $fields
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
     * Obtener Listado de Paises
     *
     * @access 	public
     * @param
     * @return 	json(array)
     */

    public function obtener($request)
    {
        $bancos = $this->select(['*']);
/*        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();*/
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $statusValue = $request->search['status'];
/*            $bancos->where('id_empresa',$usuario_empresa->id_empresa);*/
            $bancos->where($searchField, 'ilike', '%' . $searchValue . '%');
            if($statusValue === 0){
                $bancos->where('estado',1);
            }
            $bancos->orderBy('id_rubro', 'asc');
        }
        return $bancos->paginate($request->limit);
    }


    public function rubroSubRubros()
    {
        return $this->hasMany('App\Models\Inventario\SubRubros','id_rubro')->select('id_subrubro','id_rubro','descripcion');
    }

}
