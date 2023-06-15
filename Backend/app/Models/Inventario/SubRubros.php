<?php

namespace App\Models\Inventario;

use App\Models\Admon\UsuariosEmpresas;
use Illuminate\Support\Facades\DB, Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SubRubros extends Model
{
    protected $table = 'inventario.subrubros';
    protected $primaryKey='id_subrubro';
    protected $fillable = ['id_rubro','codigo','descripcion','estado','u_creacion','u_modificacion'];
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
     * Obtener Listado de subRubros
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
            $bancos->with('rubro');
            $bancos->orderBy('id_subrubro', 'asc');
        }
        return $bancos->paginate($request->limit);
    }



    public function obtenerSubRubro($request)
    {
        $bancos = $this->select(['*']);
       // $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        $bancos->where('id_subrubro', $request->id_subrubro);
/*        $bancos->where('id_empresa',$usuario_empresa->id_empresa);*/
        /*$productos->with(['proveedoresLista' => function($query) {
            $query->with('proveedores');
        }]);*/
        $bancos->with('rubro');
        return $bancos->get();
    }

    public function obtenerRubrosSubrubro($request)
    {
        $rubro = $this->select([
            '*',
        ]);
        if ( (!empty($request->id_rubro)) ) {
            $rubro->Where('id_rubro', $request->id_rubro);
            $rubro->Where('estado', '=',1);
            return $rubro->get();
        }

        return $rubro->limit(0)->get();
    }
    /*Relación producto - rubro*/
    public function rubro()
    {
        return $this->belongsTo(Rubros::class,'id_rubro')->select('*');
    }

}
