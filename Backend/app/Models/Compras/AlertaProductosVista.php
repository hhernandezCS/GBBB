<?php

namespace App\Models\Compras;

use DB, Illuminate\Database\Eloquent\Model;

class AlertaProductosVista extends Model
{
    protected $table = 'compra.listado_productos_minimo';
    protected $primaryKey='id_producto';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['descripcion','activo'];
    public $timestamps = false;


    public function obtenerProductosAlerta()
    {
        $productos = $this->select(['id_producto','costo_estandar']);
        //$productos->where('id_cuenta_contable', $request->id_cuenta_contable);
        return $productos->get();
    }


}
