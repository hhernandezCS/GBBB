<?php

namespace App\Models\CuentasXCobrar;

use Illuminate\Support\Facades\DB, Illuminate\Database\Eloquent\Model;
use App\Models\CuentasXCobrar\TiposCargosAbonosTrabajador;

class CargosAbonosTrabajador extends Model
{
    protected $table = 'cuentasxcobrar.cargos_abonos_trabajador';
    protected $primaryKey='id_cargo_abono_trabajador';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['num_documento','beneficiario','valor','estado'];

    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';

    /**
     * Obtener Lista de entradas
     *
     * @access  public
     * @param
     * @return  json(array)
     */

    public function obtener($request)
    {
        $notas_debito = $this->select(['*']);
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $notas_debito->where($searchField, 'ilike', '%' . $searchValue . '%');
        }
        $notas_debito->with('cargoAbonoTrabajador','cargoAbonoTrabajadorTipo');
        $notas_debito->orderBy('id_cargo_abono_trabajador','desc');

        return $notas_debito->paginate($request->limit);
    }


    public function cargoAbonoTrabajador()
    {
        return $this->belongsTo('App\Models\RRHHTrabajadores','id_trabajador')->select('id_trabajador','primer_nombre','primer_apellido','segundo_nombre','segundo_apellido');
    }

    public function cargoAbonoTrabajadorTipo()
    {
        return $this->belongsTo(TiposCargosAbonosTrabajador::class,'id_tipo_cargo_abono_trabajador')->select('id_tipo_cargo_abono_trabajador','descripcion','tipo');
    }
}
