<?php



namespace App\Http\Controllers\ActivoFijo;

use App\Models\Admon\Departamentos;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\ActivoFijo\Traslados;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPJasper\PHPJasper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class TrasladosController extends Controller
{
    public function obtenerTraslados(Request $request, Traslados $traslados)
    {
        $traslados = $traslados->obtenerTraslados($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $traslados->total(),
                'rows' => $traslados->items()
            ],
            'messages' => null
        ]);
    }
}
