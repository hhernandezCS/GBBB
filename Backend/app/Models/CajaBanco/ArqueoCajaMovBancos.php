<?php

namespace App\Models\CajaBanco;

use App\Models\Admon\UsuariosEmpresas;
use DB, Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ArqueoCajaMovBancos extends Model
{

    protected $table = 'cjabnco.arqueo_caja_mov_bancos';
    protected $primaryKey='id_arqueo_mov_banco';
    /*protected $fillable = ['descripcion','u_creacion','u_modificacion','estado','id_empresa'];*/
    public $timestamps=false;
}
