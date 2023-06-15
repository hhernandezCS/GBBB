<?php

namespace App\Models\CuentasXPagar;

use Illuminate\Support\Facades\DB, Illuminate\Database\Eloquent\Model;
use App\Models\Inventario\Proveedores;

class CuentasXPagar extends Model {

	const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
	protected $table = 'cuentasxpagar.cuentasxpagar';
	protected $primaryKey='id_cuentaxpagar';

    public function obtener($request)
    {
        $cuentas = $this->select(['*']);

        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $cuentas->where($searchField, 'ilike', '%' . $searchValue . '%');
        }

        $cuentas->with('cuentaProveedor');

        $cuentas->orderBy('id_cuentaxpagar','desc');

        return $cuentas->paginate($request->limit);
    }

    public function obtenerCuentasProveedor($request)
    {
        $productos = $this->select([
            'id_cuentaxpagar',
            'id_proveedor',
            'id_tipo_documento',
            'id_moneda',
            'no_documento_origen',
            'identificador_origen',
            'monto_movimiento',
            'saldo_actual',
            'saldo_actual_org',
            'fecha_movimiento',
            'fecha_vencimiento',
            'descripcion_movimiento',
            'usuario_registra',
            'es_registro_importado',
            'estado',
            DB::raw("CONCAT(no_documento_origen,' ( Saldo: ',(select m.codigo from cjabnco.monedas m where m.id_moneda=cuentasxpagar.cuentasxpagar.id_moneda),round(saldo_actual_org,2),' VENC:',fecha_vencimiento::date,' )') AS text")
        ]);
        if ((!empty($request->id_proveedor))) {
            $productos->Where('id_proveedor', $request->id_proveedor);
            $productos->Where('saldo_actual', '>',0);
            $productos->with('cuentaOrdenCompra');
            $productos->with('cuentaOrdenCompraPS');
            $productos->orderBy('fecha_vencimiento', 'asc');
            return $productos->get();
        }else{
            return $productos->limit(0)->get();
        }
    }


    public function cuentaProveedor()
    {
        return $this->belongsTo(Proveedores::class,'id_proveedor')
            ->select('id_proveedor','nombre_comercial','numero_ruc','numero_cedula','id_tipo_proveedor');
    }

    public function cuentaOrdenCompra()
    {
        return $this->belongsTo('App\Models\Compras\OrdenesCompras','identificador_origen','id_orden_compra');
    }

    public function cuentaOrdenCompraPS()
    {
        return $this->belongsTo('App\Models\Compras\OrdenesComprasPS','identificador_origen','id_orden_servicio');
    }

}
