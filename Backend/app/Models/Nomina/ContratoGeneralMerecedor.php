<?php

namespace App\Models\Nomina;

use DB, Illuminate\Database\Eloquent\Model;

class ContratoGeneralMerecedor extends Model
{
    const CREATED_AT = 'f_grabacion';
    const UPDATED_AT = 'f_modificacion';
    protected $table = 'rrhh.contratos_dgenerales_merecedores';
    protected $primaryKey='id_contrato_dgeneral_merecedor';
    protected $fillable = ['nombre_representante','estado_civil','id_nivel_academico','id_nivel_estudio','caracter_cargo',
        'caracter_legal','no_escritura_publica','nombre_notario_publico','fecha_inscripcion_escritura','no_asiento_librodiario',
        'no_inscrito','no_tomo','no_unico','domicilio','departamento','denominacion','nombre_empresa','descripcion_contractual',
        'no_ruc'];
    protected $dateFormat = 'Y-m-d H:i:s.u';

    /**
     * Replace Field
     *
     * @access 	public
     * @param
     * @return 	string
     */


	public function replaceField($field, $fields = [])
    {
        if (in_array($field, $fields)) {
            return $fields[$field];
        }

        return $field;
    }

    /**
     * Obtener Listado de contrato
     *
     * @access 	public
     * @param
     * @return 	json(array)
     */

    public function obtenerContratosMerecedores($request)
    {
        $contrato = $this->select(['*']);
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $statusValue = $request->search['status'];
            $contrato->where($searchField, 'ilike', '%' . $searchValue . '%');
            /*if($statusValue == 0){
                $contrato->where('activo',true);
            }*/
            $contrato->orderBy('id_contrato_dgeneral_merecedor', 'asc');
        }
        $contrato->with('contratoMerecedorNivelEstudio');
        $contrato->with('contratoMerecedorNivelAcademico');
        $contrato->with('contratoMerecedorDepartamentoDomicilio');
        $contrato->with('contratoMerecedorDepartamento');
        $contrato->with('contratoMerecedorTipoActoJuridico');
        $contrato->with('contratoMerecedorDepartamentoLibrado');
        return $contrato->paginate($request->limit);
    }


    public function contratoMerecedorNivelEstudio()
    {
        return $this->belongsTo('App\Models\NivelesEstudios','id_nivel_estudio');
    }

    public function contratoMerecedorNivelAcademico()
    {
        return $this->belongsTo('App\Models\NivelesAcademicos','id_nivel_academico');
    }
    public function contratoMerecedorDepartamento()
    {
        return $this->belongsTo('App\Models\PublicDepartamentos','departemento');
    }
    public function contratoMerecedorDepartamentoDomicilio()
    {
        return $this->belongsTo('App\Models\PublicDepartamentos','domicilio');
    }
    public function contratoMerecedorDepartamentoLibrado()
    {
        return $this->belongsTo('App\Models\PublicDepartamentos','departamento_librado');
    }
    public function contratoMerecedorTipoActoJuridico()
    {
        return $this->belongsTo('App\Models\TiposActosJuridicos','id_tipo_acto_juridico');
    }
}
