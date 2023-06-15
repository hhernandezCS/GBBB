<?php

namespace App\Models\ActivoFijo;

use DB, Illuminate\Database\Eloquent\Model;

class Vehiculos extends Model
{
    public $timestamps=false;
    protected $table = 'activofijo.vehiculos';
    protected $primaryKey='id_vehiculo';
}
