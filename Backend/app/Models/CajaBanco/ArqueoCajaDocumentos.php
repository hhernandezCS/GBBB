<?php

namespace App\Models\CajaBanco;

use App\Models\Admon\UsuariosEmpresas;
use DB, Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ArqueoCajaDocumentos extends Model
{

    protected $table = 'cjabnco.arqueo_caja_documentos';
    protected $primaryKey='id_arqueo_documento';
    /*protected $fillable = ['descripcion','u_creacion','u_modificacion','estado','id_empresa'];*/
    public $timestamps=false;
}
