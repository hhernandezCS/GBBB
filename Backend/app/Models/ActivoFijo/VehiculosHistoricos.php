<?php

namespace App\Models\ActivoFijo;

use DB, Illuminate\Database\Eloquent\Model;

class VehiculosHistoricos extends Model
{
    public $timestamps=false;
    protected $table = 'activofijo.vehiculos_historicos';
    protected $primaryKey='id_vehiculo';
}
