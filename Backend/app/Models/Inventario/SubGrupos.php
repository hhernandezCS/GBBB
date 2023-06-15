<?php

namespace App\Models\Inventario;

use App\Models\Admon\UsuariosEmpresas;
use Illuminate\Support\Facades\DB, Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;

class SubGrupos extends Model implements ToModel
{
    protected $table = 'inventario.subgrupos';
    protected $primaryKey='id_subgrupo';
    protected $fillable = ['id_grupo','codigo','descripcion','estado','u_creacion','u_modificacion'];
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
            $bancos->with('grupo');
            $bancos->orderBy('id_subgrupo', 'asc');
        }
        return $bancos->paginate($request->limit);
    }



    public function obtenerSubgrupo($request)
    {
        $bancos = $this->select(['*']);
        // $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        $bancos->where('id_subgrupo', $request->id_subgrupo);
        /*        $bancos->where('id_empresa',$usuario_empresa->id_empresa);*/
        /*$productos->with(['proveedoresLista' => function($query) {
            $query->with('proveedores');
        }]);*/
        $bancos->with('grupo');
        return $bancos->get();
    }

    public function obtenerGruposSubgrupos($request)
    {
        $rubro = $this->select([
            '*',
        ]);
        if ( (!empty($request->id_grupo)) ) {
            $rubro->Where('id_grupo', $request->id_grupo);
            $rubro->Where('estado', '=',1);
            return $rubro->get();
        }

        return $rubro->limit(0)->get();
    }
    /*Relación producto - rubro*/
    public function grupo()
    {
        return $this->belongsTo(Grupos::class,'id_grupo')->select('*');
    }

    public function model(array $row)
    {
        // TODO: Implement model() method.
    }
}
