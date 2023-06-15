<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function Symfony\Component\ErrorHandler\ErrorRenderer\addElementToGhost;

class ImportacionConfiguracion extends Model {

    use HasFactory;

    public $timestamps = false;
    protected $table = 'inventario.configuracion_comprobante_importaciones';
    protected $primaryKey='id_configuracion_importacion';

    public function obtener($request)
    {
        $importaciones = $this->select(['*']);
        $importaciones->with('configuracionImportacioncuentaContable');
        $importaciones->orderBy('id_configuracion_importacion', 'asc');
        return $importaciones->paginate($request->limit);
    }

    public function configuracionImportacioncuentaContable()
    {
        return $this->belongsTo('App\Models\ContabilidadCuentasContablesVista','id_cuenta_contaable')->select('id_cuenta_contable','cta_contable','nombre_cuenta','nombre_cuenta_completo');
    }
}
