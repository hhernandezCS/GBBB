<?php

namespace App\Models\Admon;

use DB, Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Paises extends Model
{
    protected $table = 'public.paises';
    protected $primaryKey='id_pais';
    protected $fillable = ['codigo','descripcion','u_creacion','estado'];
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

    public function buscar($request){
        $pais = $this->select(['descripcion','id_pais']);
        if ((!empty($request->q))){
            $searchValue = $request->q;
            $pais->where('estado', 1);
            $pais->where(function($query) use($searchValue){
                $query->where('descripcion', 'ILIKE','%'. $searchValue . '%');
            });

            $pais->with('departamentos');

            $pais->orderBy('id_pais', 'asc');
            return $pais->limit(6)->get();
        }else{
            $pais->where('estado', 1);
            $pais->where('descripcion', 'ILIKE', '%Controls%');
            return $pais->limit(0)->get();
        }
    }

    /**
     * Obtener Listado de Paises
     *
     * @access 	public
     * @param
     * @return 	json(array)
     */

    public function obtenerPaises($request)
    {
        $bancos = $this->select(['*']);
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $statusValue = $request->search['status'];
            $id_empresa = session()->get('id_empresa');
            $bancos->where($searchField, 'ilike', '%' . $searchValue . '%');
            if($statusValue == 0){
                $bancos->where('estado',true);
            }
            $bancos->orderBy('id_pais', 'asc');
        }
        return $bancos->paginate($request->limit);
    }

    public function departamentos()
    {
        return $this->hasMany(Departamentos::class,'id_pais');
    }
    public function estados()
    {
        return $this->hasMany(Estados::class,'id_pais');
    }
}

