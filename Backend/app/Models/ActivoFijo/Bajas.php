<?php

namespace App\Models\ActivoFijo;

use DB, Illuminate\Database\Eloquent\Model;

class Bajas extends Model
{

    //const CREATED_AT = 'f_creacion';
    //const UPDATED_AT = 'f_modificacion';
    public $timestamps= false;
    protected $table = 'activofijo.detalles_bajas';
    protected $primaryKey='id_detalle_baja';

    public function obtenerTraslados($request)
    {
        $activos = $this->select(['id_traslado_activo', 'id_activo', 'id_area_origen', 'id_area_destino',
            'id_trabajador_origen', 'id_trabajador_destino', 'fecha_traslado']);

        $activos->with('activoTraslado');
        $activos->with('activoAreaOrigen');
        $activos->with('activoAreaDestino');
        $activos->with('activoTrabajadorOrigen');
        $activos->with('activoTrabajadorDestino');
        $activos->orderBy('fecha_traslado', 'asc');

        return $activos->paginate($request->limit);
    }

    public function activoTraslado()
    {
        return $this->belongsTo('App\Models\ActivoFijoActivos','id_activo')->select(['id_activo','id_sucursal','codigo','descripcion','id_area','id_trabajador','marca',
            'serie','fecha_adquisicion','valor_adquisicion','depreciacion_mensual','depreciacion_acumulada','valor_libro','id_fondo','estado',
            'id_grupo_activo','id_tipo_activo','meses_depreciarse','mes_depreciado','anio_depreciado','id_color','activo']);
    }

    public function activoAreaOrigen()
    {
        return $this->belongsTo('App\Models\Admon\Areas','id_area_origen')->select(['id_area','descripcion']);
    }

    public function activoAreaDestino()
    {
        return $this->belongsTo('App\Models\Admon\Areas','id_area_destino')->select(['id_area','descripcion']);
    }

    public function activoTrabajadorOrigen()
    {
        return $this->belongsTo('App\Models\RRHH\RRHHTrabajadores','id_trabajador_origen')->select('id_trabajador','primer_apellido','primer_nombre','segundo_apellido','segundo_nombre','codigo',DB::raw("CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido) AS nombre_completo"));
    }

    public function activoTrabajadorDestino()
    {
        return $this->belongsTo('App\Models\RRHH\RRHHTrabajadores','id_trabajador_destino')->select('id_trabajador','primer_apellido','primer_nombre','segundo_apellido','segundo_nombre','codigo',DB::raw("CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido) AS nombre_completo"));
    }

}
