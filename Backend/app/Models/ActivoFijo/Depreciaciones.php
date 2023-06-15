<?php

namespace App\Models\ActivoFijo;

use DB, Illuminate\Database\Eloquent\Model;

class Depreciaciones extends Model
{

    public $timestamps= false;
    protected $table = 'activofijo.detalles_depreciaciones';
    protected $primaryKey='id_detalle_depreciacion';



    public function detalleActivo()
    {
        return $this->belongsTo('App\Models\ActivoFijoActivos','id_activo')->select(['id_activo','id_sucursal','codigo','descripcion','id_area','id_trabajador','marca',
            'serie','fecha_adquisicion','valor_adquisicion','depreciacion_mensual','depreciacion_acumulada','valor_libro','id_fondo','estado',
            'id_grupo_activo','id_tipo_activo','meses_depreciarse','mes_depreciado','anio_depreciado','id_color','activo']);
    }


}
