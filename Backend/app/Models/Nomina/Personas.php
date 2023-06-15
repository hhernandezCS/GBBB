<?php

namespace App\Models\Nomina;

use DB, Illuminate\Database\Eloquent\Model;

class Personas extends Model {

	protected $table = 'rrhh.personas';
	protected $primaryKey='id_persona';
	protected $fillable = ['nombre','primer_apellido','segundo_apellido','cedula','email','direccion','telefono','estado'];
    const CREATED_AT = 'f_creacion';
	const UPDATED_AT = 'f_modificacion';

	protected $hidden = [
        'id_persona', 'f_creacion','f_modificacion','estado',
    ];

}
