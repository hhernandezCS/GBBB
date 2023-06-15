<?php

namespace App\Models\CajaBanco;

use DB, Illuminate\Database\Eloquent\Model;
use App\Models\Ventas\Vendedores;
use App\Models\CajaBanco\ArqueoCajaDocumentos;
use App\Models\CajaBanco\ArqueoCajaMovBancos;

class ArqueoCaja extends Model
{
    public $timestamps = true;
    protected $table = 'cjabnco.arqueo_caja';
    protected $primaryKey='id_arqueo';
    /*protected $fillable = ['descripcion','valor'];*/
    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';

    /**
     * Replace Field
     *
     * @access 	public
     * @param
     * @return 	string
     */

    public function obtener($request)
    {
        $solicitud = $this->select(['id_arqueo','fecha_arqueo','id_vendedor','estado','monto_ingresos']);
        if (!empty($request->search['field']) && !empty($request->search['value'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $solicitud->where($searchField, $searchValue);
        }
        $statusValue = $request->search['status'];
        if($statusValue === 0){
            $solicitud->whereIn('estado',array(1));
        }
        $solicitud->with('vendedorArqueo');

        $solicitud->orderBy('fecha_arqueo', 'desc');
        return $solicitud->paginate($request->limit);
    }


    public function vendedorArqueo()
    {
        return $this->belongsTo(Vendedores::class,'id_vendedor')->select('id_vendedor','nombre_completo');
    }
    public function documentosArqueo()
    {
        return $this->belongsTo(ArqueoCajaDocumentos::class,'id_arqueo')->select('*');
    }
    public function bancosArqueo()
    {
        return $this->belongsTo(ArqueoCajaMovBancos::class,'id_arqueo')->select('*');
    }



}
