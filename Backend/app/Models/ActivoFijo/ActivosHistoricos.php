<?php

namespace App\Models\ActivoFijo;

use DB, Illuminate\Database\Eloquent\Model;

class ActivosHistoricos extends Model
{

    const CREATED_AT = 'f_grabacion';
    const UPDATED_AT = 'f_modificacion';
    protected $table = 'activofijo.activos_historico';
    protected $primaryKey='id_activo_historico';

    public function obtenerActivos($request)
    {
        $activos = $this->select(['id_activo','id_sucursal','codigo','descripcion','id_area','id_trabajador','marca',
            'serie','fecha_adquisicion','valor_adquisicion','depreciacion_mensual','depreciacion_acumulada','valor_libro','id_fondo','estado',
            'id_grupo_activo','id_tipo_activo','meses_depreciarse','mes_depreciado','anio_depreciado','id_color','activo']);
        if (!empty($request->search['field'])) {

            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $activos->where($searchField, 'ilike', '%' . $searchValue . '%');
        }
        $activos->with('activoSucursal');
        $activos->with('activoArea');
        $activos->with('activoTrabajador');
        $activos->with('activoGrupo');
        $activos->with('activoTipoActivo');
        $activos->with('activoColor');
        $activos->with('activoPropietario');
        $activos->orderBy('codigo', 'asc');

        return $activos->paginate($request->limit);
    }

    public function obtenerActivosTrabajador($request)
    {
        $activos = $this->select(['id_activo','id_sucursal','codigo','descripcion','id_area','id_trabajador','marca',
            'serie','fecha_adquisicion','valor_adquisicion','depreciacion_mensual','depreciacion_acumulada','valor_libro','id_fondo','estado',
            'id_grupo_activo','id_tipo_activo','meses_depreciarse','mes_depreciado','anio_depreciado','id_color','activo']);
        if (!empty($request->id_trabajador)) {
            $activos->where('id_trabajador', $request->id_trabajador);
            $activos->with('activoSucursal');
            $activos->with('activoArea');
            $activos->with('activoTrabajador');
            $activos->with('activoGrupo');
            $activos->with('activoTipoActivo');
            $activos->with('activoColor');
            $activos->with('activoPropietario');
            $activos->orderBy('codigo', 'asc');

            return $activos->get();
        }else{
            return [];
        }
    }

    public function obtenerActivosSucursal($request)
    {

        if (!empty($request->id_sucursal) && !empty($request->fecha_final)) {

            $activos = $this->select(['id_activo','id_sucursal','codigo','descripcion','id_area','id_trabajador','marca',
                'serie','fecha_adquisicion','valor_adquisicion','depreciacion_mensual','depreciacion_acumulada','valor_libro','id_fondo','estado',
                'id_grupo_activo','id_tipo_activo','meses_depreciarse','mes_depreciado','anio_depreciado','id_color','activo',
                DB::raw("((DATE_PART('year', '".$request->fecha_final."'::date) - DATE_PART('year', fecha_adquisicion::date)) * 12 +(DATE_PART('month', '".$request->fecha_final."'::date) - DATE_PART('month', fecha_adquisicion::date))) as mes_depreciado_actual")
            ]);

            $activos->where('id_sucursal', $request->id_sucursal)->where('estado',1);
            $activos->with('activoSucursal');
            $activos->with('activoArea');
            $activos->with('activoTrabajador');
            $activos->with('activoGrupo');
            $activos->with('activoTipoActivo');
            $activos->with('activoColor');
            $activos->with('activoPropietario');
            $activos->orderBy('codigo', 'asc');
            return $activos->get();
        }else{
            return [];
        }
    }

    public function obtenerActivo($request)
    {
        $activo = $this->select(['*']);
        $activo->where('id_activo', $request->id_activo);

        /*$activo->with(['facturaProductos' => function($query) {
            $query->with(['bodegaProducto' => function($query2) {
                $query2->with('productoSimple');
            }]);
        }]);*/

        $activo->with('activoSucursal');
        $activo->with('activoArea');
        $activo->with('activoTrabajador');
        $activo->with('activoGrupo');
        $activo->with('activoTipoActivo');
        $activo->with('activoColor');
        $activo->with('activoPropietario');
        return $activo->first();
    }


    public function obtenerCodigoActivo($id_sucursal,$id_propietario,$id_grupo_activo)
    {
        $codigo = $this->select([DB::raw("COALESCE(max(secuencia),0)+1 as secuencia")]);
        if((!empty($id_sucursal))&&(!empty($id_propietario))&&(!empty($id_grupo_activo))){
            $codigo->where('id_sucursal',$id_sucursal);
            $codigo->where('id_propietario',$id_propietario);
            $codigo->where('id_grupo_activo',$id_grupo_activo);
        }
        return $codigo->first();
    }

    public function activoPropietario()
    {
        return $this->belongsTo('App\Models\ActivoFijo\Propietarios','id_propietario')->select(['id_propietario','descripcion']);
    }

    public function activoSucursal()
    {
        return $this->belongsTo('App\Models\Admon\Sucursales','id_sucursal')->select(['id_sucursal','descripcion']);
    }

    public function activoArea()
    {
        return $this->belongsTo('App\Models\Admon\Areas','id_area')->select(['id_area','descripcion']);
    }

    public function activoTrabajador()
    {
        return $this->belongsTo('App\Models\RRHH\RRHHTrabajadores','id_trabajador')->select('id_trabajador','primer_apellido','primer_nombre','segundo_apellido','segundo_nombre','codigo',DB::raw("CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido) AS nombre_completo"));
    }

    public function activoGrupo()
    {
        return $this->belongsTo('App\Models\ActivoFijo\Grupos','id_grupo_activo')->select(['id_grupo_activo','descripcion']);
    }

    public function activoTipoActivo()
    {
        return $this->belongsTo('App\Models\ActivoFijo\TiposActivos','id_tipo_activo')->select(['id_tipo_activo','descripcion']);
    }

    public function activoColor()
    {
        return $this->belongsTo('App\Models\ActivoFijo\Colores','id_color')->select(['id_color','descripcion']);
    }

    public function activoDetalleVehiculo()
    {
        return $this->hasOne('App\Models\ActivoFijo\Vehiculos','id_activo')->select(['id_vehiculo', 'placa', 'color', 'chasis', 'numero_motor', 'id_tipo_combustible', 'cilindro', 'anio', 'capacidad_carga', 'pasajeros', 'id_activo']);
    }

}
