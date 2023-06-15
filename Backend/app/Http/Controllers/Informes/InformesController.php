<?php

namespace App\Http\Controllers\Informes;


use App\Http\Controllers\Controller;

use App\Models\CajaBanco\FacturaDetalle;
use App\Models\CajaBanco\Facturas;
use Illuminate\Http\JsonResponse;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InformesController extends Controller {
    /**
     * Informe de ventas brutas
     *
     * @access  public
     * @param Request $request
     * @return JsonResponse
     */

    public function obtenerVentasBrutas(Request $request){

        $series = Facturas::groupBy('f_factura')->selectRaw('f_factura, SUM(mt_total) as mt_total')->whereBetween('f_factura', [$request->fecha_inicial, $request->fecha_final])->whereNotIn('estado', [0])->orderBy('f_factura', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'result' => $series,
            'messages' => null
        ]);
    }

    /**
     * Informe de ventas netas
     *
     * @access  public
     * @param Request $request
     * @return JsonResponse
     */

    public function obtenerVentasNetas(Request $request){

        $series = Facturas::groupBy('f_factura')->selectRaw('f_factura, SUM(mt_total) - SUM(mt_impuesto) as mt_neto')->whereBetween('f_factura', [$request->fecha_inicial, $request->fecha_final])->whereNotIn('estado', [0])->orderBy('f_factura', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'result' => $series,
            'messages' => null
        ]);
    }

    /**
     * Informe de beneficio bruto
     *
     * @access  public
     * @param Request $request
     * @return JsonResponse
     */

    public function obtenerBeneficioBruto(Request $request){

        $series = Facturas::select('cjabnco.facturas.f_factura', DB::raw('SUM(cjabnco.facturas_detalles.precio - cjabnco.facturas_detalles.precio_costo) as mt_total'))->join('cjabnco.facturas_detalles', 'cjabnco.facturas.id_factura', '=', 'cjabnco.facturas_detalles.id_factura')
            ->whereBetween('f_factura', [$request->fecha_inicial, $request->fecha_final])->groupBy('cjabnco.facturas.f_factura')->whereNotIn('cjabnco.facturas.estado', [0])->orderBy('cjabnco.facturas.f_factura', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'result' => $series,
            'messages' => null
        ]);
    }

    /**
     * Informe de ventas por mes
     *
     * @access  public
     * @param Request $request
     * @return JsonResponse
     */

    public function obtenerVentasPorMes(Request $request){

        $series = Facturas::select(DB::raw('EXTRACT(MONTH FROM f_factura) AS mes, SUM(mt_total) AS total_ventas'))->whereBetween('f_factura', [$request->fecha_inicial, $request->fecha_final])->whereNotIn('estado', [0])
            ->groupBy('mes')->orderBy('mes', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'result' => $series,
            'messages' => null
        ]);
    }

    public function obtenerVentasPorDia() {

        $startDate = Carbon::now()->startOfWeek()->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $series = DB::table(DB::raw("
            (SELECT generate_series(
                '" . $startDate->toDateTimeString() . "'::date,
                '" . $endDate->toDateTimeString() . "'::date,
                '1 day'::interval) AS dia_semana)
                AS generated
            "))
            ->leftJoin('cjabnco.facturas', DB::raw("DATE_TRUNC('day', facturas.f_factura)"), '=', 'dia_semana')
            ->whereRaw("EXTRACT(ISODOW FROM dia_semana) <= EXTRACT(ISODOW FROM CURRENT_DATE)")
            ->whereNotIn('cjabnco.facturas.estado', [0])
            ->selectRaw("TO_CHAR(dia_semana, 'Day') AS dia_semana, EXTRACT(DOW FROM generated.dia_semana) AS numero_dia, COALESCE(SUM(facturas.mt_total), 0) AS total_por_dia")
            ->groupBy('dia_semana', 'facturas.f_factura')
            ->orderBy('facturas.f_factura')
            ->get();

        return response()->json([
            'status' => 'success',
            'result' => $series,
            'messages' => null
        ]);
    }

    public function obtenerProductosPopulares() {

    }
}
