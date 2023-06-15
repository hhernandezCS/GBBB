<?php

//namespace App\Models\CajaBanco;

use App\Models\Admon\UsuariosEmpresas;
use DB, Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SolicitudesPago extends Model
{

    protected $table = 'cjabnco.solicitudes_pago';
    protected $primaryKey='id_solicitud_pago';
    protected $fillable = ['beneficiario','concepto','id_moneda','monto','u_creacion','u_modificacion','estado','id_empresa'];
    const CREATED_AT = 'f_creacion';
    const UPDATED_AT = 'f_modificacion';
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
     * Obtener Listado de Bancos
     *
     * @access 	public
     * @param
     * @return 	json(array)
     */

    public function obtener($request)
    {
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        $solicitudes = $this->select(['*']);
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $solicitudes->where($searchField, 'ilike', '%' . $searchValue . '%');
        }
        $solicitudes->with('monedaSolicitud');
        $solicitudes->with('tipoSolicitud');
        $solicitudes->orderBy('id_solicitud_pago','desc');

        return $solicitudes->paginate($request->limit);
    }

    public function obtenerSolicitudesAprobacion($request)
    {
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', Auth::user()->id)->first();
        $solicitudes = $this->select(['*']);
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $solicitudes->where($searchField, 'ilike', '%' . $searchValue . '%');
        }
        $solicitudes->whereIn('estado',array(1,2));
        $solicitudes->with('monedaSolicitud');
        $solicitudes->orderBy('id_solicitud_pago');

        return $solicitudes->paginate($request->limit);
    }
    public function solicitudCuentaBancaria()
    {
        return $this->belongsTo('App\Models\Contabilidad\CuentasBancarias','id_cuenta_bancaria')->select('id_cuenta_bancaria','id_banco','id_moneda','id_cuenta_contable','numeracion_chequera',
            DB::raw("concat((select b.descripcion from cjabnco.bancos b where b.id_banco = contabilidad.cuentas_bancarias.id_banco),' ',(select moned.descripcion
                from cjabnco.monedas moned where moned.id_moneda = contabilidad.cuentas_bancarias.id_moneda),'(',(select moned.codigo
                from cjabnco.monedas moned where moned.id_moneda = contabilidad.cuentas_bancarias.id_moneda),') ',numero_cuenta) as numero_cuenta")
        );
    }
    public function solicitudDetalles()
    {
        return $this->hasMany('App\Models\CajaBanco\SolicitudesPagoDetalles','id_solicitud_pago');
    }

    public function tipoSolicitud()
    {
        return $this->belongsTo('App\Models\CajaBanco\TiposSolicitudesPago','id_tipo_solicitud');
    }

    public function monedaSolicitud()
    {
        return $this->belongsTo('App\Models\Contabilidad\Monedas','id_moneda');
    }

    public function monedaAprobadaSolicitud()
    {
        return $this->belongsTo('App\Models\Contabilidad\Monedas','id_moneda_pago');
    }

    public function documentoSolicitud()
    {
        return $this->belongsTo('App\Models\Contabilidad\DocumentosContables','id_documento_contable');
    }

    public function usuarioRegistra()
    {
        return $this->belongsTo('App\Models\User','u_creacion','name');
    }

    public function usuarioRevisa()
    {
        return $this->belongsTo('App\Models\User','u_revision','usuario');
    }

    public function usuarioAprueba()
    {
        return $this->belongsTo('App\Models\User','u_aprobacion','usuario');
    }

    public function usuarioEmite()
    {
        return $this->belongsTo('App\Models\User','u_emision','usuario');
    }
    public function usuarioAnula()
    {
        return $this->belongsTo('App\Models\User','u_anulacion','usuario');
    }

}
