<?php



namespace App\Http\Controllers\CRM;


use App\Models\Admon\Ajustes;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\CRM\CompaniaContactoDetalle;
use Illuminate\Http\JsonResponse;
use PHPJasper\PHPJasper;
use Validator,Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule,DB;
use App\Http\Controllers\Controller;
class CompaniasContactoDetalleController extends Controller
{
    /**
     * Obtener una lista de los giros de negocios
     * @access  public
     * @param Request $request
     * @param CompaniaContactoDetalle $ccd
     * @return JsonResponse
     * @author hgm7
     */

    public function obtener(Request $request, CompaniaContactoDetalle $ccd)
    {
        $ccd = $ccd->obtenerCompaniaContactoDetalle($request);
        return response()->json([
            'status' => 'success',
            'result' => [
                'total' => $ccd->total(),
                'rows' => $ccd->items()
            ],
            'messages' => null
        ]);
    }

}
