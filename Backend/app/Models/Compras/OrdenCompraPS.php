<?php

namespace App\Models\Compras;

use DB, Illuminate\Database\Eloquent\Model;

class OrdenCompraPS extends Model
{
    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
    protected $table = 'compra.ordenes_servicios';
    protected $primaryKey='id_orden_servicio';

    /**
     * Obtener Lista de Ordenes compras
     *
     * @access  public
     * @param
     * @return  json(array)
     */

    public function obtenerOrdenesCompras($request)
    {
        $ordenes = $this->select(['*']);
        if (!empty($request->search['value'])) {
           $searchField = $request->search['field'];
           $searchValue = $request->search['value'];
            $ordenes->where($searchField, 'ilike', '%' . $searchValue . '%');
       }
        $ordenes->with('ordenCompraListado');
        $ordenes->with('ordenCompraProveedor');
        $ordenes->orderBy('f_modificacion', 'desc');

        return $ordenes->paginate($request->limit);
    }

    public function obtenerOrdenCompra($request)
    {
        $ordenes = $this->select(['*']);
        $ordenes->where('id_orden_servicio', $request->id_orden_servicio);

        $ordenes->with(['ordenCompraListado' => function($query)
        { $query->with('servicioClasificacion');}]);

        $ordenes->with('ordenCompraProveedor','ordenCompraMoneda');
        return $ordenes->get();
    }

    public function ordenCompraListado()
    {
        return $this->hasMany('App\Models\OrdenCompraPSListado','id_orden_servicio');
    }

    public function ordenCompraProveedor()
    {
        return $this->belongsTo('App\Models\InventarioProveedores','id_proveedor')->select('id_proveedor','nombre_comercial','numero_ruc','numero_cedula','id_tipo_proveedor');
    }

    public function ordenCompraMoneda()
    {
        return $this->belongsTo('App\Models\CajaBancoMonedas','id_moneda')->select('id_moneda','descripcion','codigo');
    }

}

