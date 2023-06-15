<?php
namespace App\Http\Controllers\Admon;

use App\Http\Controllers\Controller;
use App\Imports\DataImport;
use App\Imports\GruposImport;
use App\Imports\MarcasImport;
use App\Imports\ProductosImport;
use App\Imports\SubGruposImport;
use App\Imports\ListasPreciosImport;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\CajaBanco\Facturas;
use App\Models\Contabilidad\Monedas;
use App\Models\CuentasXCobrar\Recibos;
use App\Models\Inventario\Marcas;
use App\Models\Inventario\Productos;
use App\Models\Ventas\Clientes;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Admon\Ajustes;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class AjustesController extends Controller
{
    /**
     * Obtener Ajustes Generales
     *
     * @access  public
     * @param
     * @return JsonResponse
     */

    public function obtenerAjustes(Request $request)
    {
        if (!$request->ajax()) {

        }

        //   $settings = AdmonAjustes::/*whereIn('id_ajuste',array(1, 2, 3, 4, 5, 6, 7, 8, 9, 21, 22, 26))->*/orderBy('id_ajuste')->get();
        $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=',Auth::user()->id)->first();
        $conf = $usuario_empresa->id_empresa;
        $settings = Ajustes::where('id_empresa',$conf)->orderBy('id_ajuste')->get();
        $monedas = Monedas::select('id_moneda', 'descripcion', 'codigo')->get();
        return response()->json([
            'status' => 'success',
            'result' => [
                'ajustes' => $settings,
                'monedas' => $monedas
            ],
            'messages' => null
        ]);
    }

    /**
     * Obtener Ajustes Basicos (no requiere usuario autenticado)
     *
     * @access  public
     * @param
     * @return JsonResponse
     */
    public function obtenerAjustesBasicos(Request $request)
    {
        // $settings = AdmonAjustes::whereIn('id_ajuste',array(2,3,21,22))->orderBy('id_ajuste')->select('id_ajuste','valor')->get();
        $conf = session()->get('id_empresa');
        $host=request()->getSchemeAndHttpHost();
        $company_name = Ajustes::where('id_empresa',$conf)->where('id_ajuste',2)->select('id_ajuste','valor')->first();
        $logo_left = Ajustes::where('id_empresa',$conf)->where('id_ajuste',3)->select('id_ajuste','valor')->first();
        $logo_icon = Ajustes::where('id_empresa',$conf)->where('id_ajuste',9)->select('id_ajuste','valor')->first();
        $logo_login = Ajustes::where('id_empresa',$conf)->where('id_ajuste',10)->select('id_ajuste','valor')->first();
        //$settings = AdmonAjustes::where('id_empresa',$conf)->whereIn('id_ajuste',array(2,3,21,22))->orderBy('id_ajuste')->select('id_ajuste','valor','id_empresa')->get();
        return response()->json([
            'status' => 'success',
            'result' => [
                'host' => $host,
                'company_name' => $company_name,
                'logo_left' => $logo_left,
                'logo_icon' => $logo_icon,
                'logo_login' => $logo_login,
            ],
            'messages' => null
        ]);
    }

    /**
     * Obtener Ajustes Contabilidad
     *
     * @access  public
     * @param
     * @return JsonResponse
     */
    public function obtenerAjustesContabilidad(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $conf = session()->get('id_empresa');
        $settings = Ajustes::where('id_empresa',$conf)->whereIn('identificador',array('cuenta_perdida_ganancia'))->orderBy('id_ajuste')->get();
        return response()->json([
            'status' => 'success',
            'result' => $settings,
            'messages' => null
        ]);
    }


    /**
     * Obtener Imagenes (logo)
     *
     * @access  public
     * @param
     * @return JsonResponse
     */
    public function obtenerImagenes(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $conf = session()->get('id_empresa');
        $settings = Ajustes::where('id_empresa',$conf)->whereIn('id_ajuste',array(21,3))->orderBy('id_ajuste')->select('id_ajuste','valor','id_empresa')->get();
        return response()->json([
            'status' => 'success',
            'result' => $settings,
            'messages' => null
        ]);
    }


    /**
     * Guardar Ajustes del sistema
     *
     * @access 	public
     * @param
     * @return JsonResponse
     */

    public function registrar(Request $request)
    {

        $rules = [
            'app_title' => 'required|string|max:191',
            'company_address' => 'required|string|max:191',
            'company_email' => 'required|email|string|max:191',
            'company_name' => 'required|string|max:50',
            'company_telephone' => 'required|string|max:15',
            'company_website' => 'required|string|max:191',
//            'uploaded_logo' => 'required|string',
//            'uploaded_icon' => 'required|string',
//            'login_img' => 'required|string',
            'ruc_company' => 'required|string|max:16',

            //'footer' => 'required',
            //'footer_line_1' => 'required',
            //'footer_line_2' => 'required',
            //'footer_line_3' => 'required',
            //'footer-html' => 'required',
            //'global_bcc_email' => 'required',
            //'header' => 'required',
            //'header-html' => 'required',
            //'sent_from_email' => 'required',
            //'sent_from_name' => 'required',
            //'uploaded_logo' => 'required',

        ];


        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $conf = session()->get('id_empresa');
            $setting = Ajustes::where('id_empresa',$conf)->where('identificador', 'app_title')->first();
            $setting->valor = $request->app_title;
            $setting->save();

            $setting = Ajustes::where('id_empresa',$conf)->where('identificador', 'company_address')->first();
            $setting->valor = $request->company_address;
            $setting->save();

            $setting = Ajustes::where('id_empresa',$conf)->where('identificador', 'company_email')->first();
            $setting->valor = $request->company_email;
            $setting->save();

            $setting = Ajustes::where('id_empresa',$conf)->where('identificador', 'company_name')->first();
            $setting->valor = $request->company_name;
            $setting->save();

            $setting = Ajustes::where('id_empresa',$conf)->where('identificador', 'company_telephone')->first();
            $setting->valor = $request->company_telephone;
            $setting->save();

            $setting = Ajustes::where('id_empresa',$conf)->where('identificador', 'company_website')->first();
            $setting->valor = $request->company_website;
            $setting->save();

            $setting = Ajustes::where('id_empresa',$conf)->where('identificador', 'currency_id')->first();
            $setting->valor = $request->moneda;
            $setting->save();


            $setting = Ajustes::where('id_empresa',$conf)->where('identificador', 'ruc_company')->first();
            $setting->valor = $request->ruc_company;
            $setting->save();

            $setting = Ajustes::where('id_empresa',$conf)->where('identificador', 'discount_max')->first();
            $setting->valor = $request->discount_max;
            $setting->save();

            // upload images

            if ($request->hasFile('uploaded_logo')){
                $setting = Ajustes::where('id_empresa',$conf)->where('identificador', 'uploaded_logo')->first();
                $setting->valor = $request->file('uploaded_logo_name')->getClientOriginalName();
                $request->file('uploaded_logo')->storeAs('uploads',$setting->valor);
                $setting->save();
            }


            if($request->hasFile('uploaded_icon')){
                $setting = Ajustes::where('id_empresa',$conf)->where('identificador', 'uploaded_icon')->first();
                $setting->valor = $request->file('uploaded_icon_name')->getClientOriginalName();
                $request->file('uploaded_icon')->storeAs('uploads',$setting->valor);
                //$setting->valor = $request->uploaded_icon;
                $setting->save();
            }

            if($request->hasFile('login_img')){
                $setting = Ajustes::where('id_empresa',$conf)->where('identificador', 'login_img')->first();
                $setting->valor = $request->file('login_img_name')->getClientOriginalName();
                $request->file('login_img')->storeAs('uploads',$setting->valor);
                //$setting->valor = $request->uploaded_icon;
                $setting->save();
            }


            return response()->json([
                'status' => 'success',
                'result' => null,
                'messages' => null
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'result' => $validator->messages(),
                'messages' => null
            ]);
        }
    }

    /*
     * Obtener datos estadisticos para cargar en iconos del dashboard
     * @access public
     * @param
     * @return JsonResponse
     * */
    /*
       * Obtener datos estadisticos para cargar en iconos del dashboard
       * @access public
       * copyright: Octaviom
       * @param
       * @return JsonResponse
       * */
    public function obtenerEstadisticasDashboard(): JsonResponse
    {

        DB::statement("SET datestyle = 'iso, dmy';");
        // Obtener cantidad de registros de cada tabla, omitiendo los anulados
        $facturas = Facturas::select(
            DB::raw("to_char(f_factura, 'TMMonth') as mes_nombre"),
            DB::raw('SUM(mt_total) as cantidad'),
            DB::raw('EXTRACT(MONTH FROM f_factura) as mes_number'))
            ->whereNotIn('estado', [0])
            ->groupBy('mes_nombre','mes_number')
            ->orderBy('mes_number', 'asc')
            ->get();



        // Convertir los resultados en un array
        $resultados = [];
        foreach ($facturas as $factura) {
            $resultados[] = [
                'fecha_factura' => $factura->mes_nombre,
                'anio' => $factura->anio,
                'cantidad' => $factura->cantidad,
            ];
        }
        // Retornar la respuesta en formato JSON
        return response()->json([
            'status' => 'success',
            'result' => [
                'facturas' => $resultados,
            ],
            'messages' => null
        ]);


    }



    public function cantidadPorProducto() {
        // Obtener el número total de ventas
        $totalVentas = DB::table('cjabnco.facturas')->count();

        // Obtener la cantidad de ventas por producto
        $cantidadPorProducto = Productos::select('productos.descripcion', DB::raw('COUNT(cjabnco.facturas.id_factura) as cantidad'), DB::raw('COUNT(cjabnco.facturas.id_factura) * 100 / '.$totalVentas.' as porcentaje'))
            ->join('cjabnco.facturas_detalles as fd', 'fd.id_producto', '=', 'productos.id_producto')
            ->join('cjabnco.facturas', 'fd.id_factura', '=', 'facturas.id_factura')
            ->groupBy('productos.id_producto', 'productos.descripcion')
            ->orderByDesc('cantidad')
            ->limit(5)
            ->get();

        // Convertir los resultados en un array
        $resultados = [];
        foreach ($cantidadPorProducto as $item) {
            $resultados[] = [
                'icon'=> 'DollarSignIcon',
                'color' => 'light-success',
                'cantidad' => $item->cantidad,
                'descripcion' => strtoupper($item->descripcion),
                'porcentaje' => round($item->porcentaje, 0),
            ];
        }
        // Retornar la respuesta en formato JSON
        return response()->json([
            'status' => 'success',
            'result' => [
                'productos' => $resultados,
            ],
            'messages' => null
        ]);
    }


    /*
       * Obtener datos estadisticos para cargar en iconos del dashboard
       * @access public
       * @param
       * @return JsonResponse
       * */
    public function obtenerEstadisticas(){
        // Obtener cantidad de registros de cada tabla, omitiendo los anulados
        $facturas = Facturas::whereNotIn('estado', array(0))->count();
        $clientes = Clientes::whereNotIn('estado',array(0))->count();
        $productos = Productos::whereNotIn('estado',array(0))->count();
        //$recibos = Recibos::whereNotIn('estado', array(0))->count();

        // Retornamos en respuesta json, los objetos de cada contador, incluyendo el icono, color, titulo y clases para ser renderizada en frontend
        return response()->json([
            'status' => 'success',
            'result' => [
                'facturas' => [
                    'icon'=> 'TrendingUpIcon',
                    'color' => 'light-primary',
                    'title' => $facturas,
                    'subtitle' => 'Facturas',
                    'customClass' => 'mb-2 mb-xl-0'
                ],
                'clientes' => [
                    'icon'=> 'UserIcon',
                    'color' => 'light-info',
                    'title' => $clientes,
                    'subtitle' => 'Clientes',
                    'customClass' => 'mb-2 mb-xl-0'
                ],
                'productos' => [
                    'icon'=> 'BoxIcon',
                    'color' => 'light-danger',
                    'title' => $productos,
                    'subtitle' => 'Productos',
                    'customClass' => 'mb-2 mb-xl-0'
                ],
/*                'recibos' => [
                    'icon'=> 'DollarSignIcon',
                    'color' => 'light-success',
                    'title' => $recibos,
                    'subtitle' => 'Recibos',
                    'customClass' => 'mb-2 mb-xl-0'
                ],*/
            ],
            'messages' => null
        ]);
    }

    /**
     * Funcion para importar archivos excel
     * Se procesa el excel recibido y se retornan los datos en forma de arreglo para su debida revision
     * previo al registro en base de datos
     * @param Request $request
     * @return JsonResponse
     * @access public
     * @author octaviom - 30/01/2023
     */
    public function procesarExcel(Request $request){
        // mensajes personalizados para las reglas de validacion de datos
        $messages = [
            'file.required' => 'Debe seleccionar un archivo valido',
            'file.mimes' => 'El archivo debe tener formato xls o xlsx.'
        ];
        // validacion de datos recibidos
        $rules = [
            'file' => 'required|mimes:xls,xlsx'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if (!$validator->fails()){
//            $path = $request->file('file')->getRealPath();
            $headings = (new HeadingRowImport())->toArray($request->file);
            $array = Excel::toArray(new Marcas(), $request->file);

//            Excel::import(new MarcasImport(),$path);

            return response()->json([
                'status' => 'success',
                'result' => [
                    'datos' => $array,
                    'datos_' => $array[0],
                    'cabeceras' => $headings,
                    'tabla' => ''
                ]
            ]);
        }

        return response()->json([
            'status' => 'error',
            'result' => $validator->messages(),
            'messages' => 'Favor verifique que ha seleccionado un archivo y que este tiene el formato correcto.'
        ]);
    }

    /**
     * Funcion para importar archivos excel
     * Se procesa el excel recibido y se manda a grabar en su tabla respectiva
     * @param Request $request
     * @return JsonResponse
     * @access public
     * @author octaviom - 14/02/2023
     */
    public function importarDatosExcel(Request $request){

        // mensajes personalizados para las reglas de validacion de datos
        $messages = [
            'file.required' => 'Debe seleccionar un archivo valido',
            'file.mimes' => 'El archivo debe tener formato xls o xlsx.'
        ];
        // validacion de datos recibidos
        $rules = [
            'file' => 'required|mimes:xls,xlsx',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if (!$validator->fails()){
            // Se recibe el archivo importado
            $path = $request->file;
            // Se extraer el nombre del archivo para identificacion de modelo a afectar
            $file_name = $request->file->getClientOriginalName();
            $import = '';
            // Se obtiene el nombre del archivo para comprara y cargar el modelo a importar
            if($file_name === 'FormatoImportacionGrupos.xlsx'){
                //Instanciamos al archivo import
                $import = new GruposImport();
                //Se importan los datos del archivo cargado
                Excel::import($import,$path);
            }else if($file_name === 'FormatoImportacionMarcas.xlsx') {
                $import = new MarcasImport();
                Excel::import($import, $path);
            }else if($file_name === 'FormatoImportacionSubGrupos.xlsx'){
                    $import = new SubGruposImport();
                    Excel::import($import,$path);
            }else if($file_name === 'FormatoImportacionProductos.xlsx'){
                $import = new ProductosImport();
                Excel::import($import,$path);
            }else if($file_name === 'FormatoImportacionListasPrecios.xlsx'){
                $import = new ListasPreciosImport();
                Excel::import($import,$path);
            }

            return response()->json([
                'status' => 'success',
                'result' => [
                    'total_rows' => $import->getRowCount() // imprimiento total de filas importas
                ]
            ]);
        }
        return response()->json([
            'status' => 'error',
            'result' => $validator->messages(),
            'messages' => 'Favor verifique que ha seleccionado un archivo y que este tiene el formato correcto.'
        ]);
    }

    /**
     * Metodo para obtener imagenes desde base de dato
     * copyright: Dev_Lopez02
     */
    public function obtenerRecursos(Request $request)
    {

        // $settings = AdmonAjustes::whereIn('id_ajuste',array(2,3,21,22))->orderBy('id_ajuste')->select('id_ajuste','valor')->get();
        $host= request()->getSchemeAndHttpHost();
        $company_name = Ajustes::where('id_ajuste',2)->select('id_ajuste','valor')->first();
        $logo_left = Ajustes::where('id_ajuste',3)->select('id_ajuste','valor')->first();
        $logo_right = Ajustes::where('id_ajuste',21)->select('id_ajuste','valor')->first();
        $logo_login = Ajustes::where('id_ajuste',10)->select('id_ajuste','valor')->first();
        $logo_factura = Ajustes::where('id_ajuste',15)->select('id_ajuste','valor')->first();
        $logo_home = Ajustes::where('id_ajuste',16)->select('id_ajuste','valor')->first();
        //$settings = AdmonAjustes::where('id_empresa',$conf)->whereIn('id_ajuste',array(2,3,21,22))->orderBy('id_ajuste')->select('id_ajuste','valor','id_empresa')->get();
        return response()->json([
            'status' => 'success',
            'result' => [
                'host'=> $host,
                'company_name' => $company_name,
                'logo_left' => $logo_left,
                'logo_right' => $logo_right,
                'logo_login' => $logo_login,
                'logo_factura' => $logo_factura,
                'logo_home' => $logo_home
            ],
            'messages' => null
        ]);
    }

}
