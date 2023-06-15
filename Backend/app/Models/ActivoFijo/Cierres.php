<?php

namespace App\Models\ActivoFijo;

use DB, Illuminate\Database\Eloquent\Model;

class Cierres extends Model
{

    public $timestamps=false;
    protected $table = 'activofijo.cierres_activos';
    protected $primaryKey='id_cierre_activo';

    public function obtenerCierres($request)
    {
        $activos = $this->select(['id_cierre_activo', 'id_sucursal', 'mes', 'anio', 'u_grabacion'
            ,'f_grabacion']);
        $activos->with('cierreSucursal');
        $activos->with(['detallesDepreciacion' => function($query) {
            $query->with('detalleActivo');}]);
        $activos->orderBy('id_cierre_activo', 'asc');

        return $activos->paginate($request->limit);
    }

    public function detallesDepreciacion()
    {
        return $this->hasMany('App\Models\ActivoFijo\Depreciaciones','id_cierre_activo');
    }

    public function cierreSucursal()
    {
        return $this->belongsTo('App\Models\Admon\Sucursales', 'id_sucursal') ->select('id_sucursal','descripcion','serie');
    }
}
