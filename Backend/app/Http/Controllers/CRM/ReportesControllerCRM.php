<?php



namespace App\Http\Controllers\CRM;


use App\Models\Admon\Ajustes;
use App\Models\Admon\UsuariosEmpresas;
use App\Models\CRM\ServiciosOferAcor;
use Illuminate\Http\JsonResponse;
use phpDocumentor\Reflection\Types\Object_;
use PHPJasper\Exception\ErrorCommandExecutable;
use PHPJasper\Exception\InvalidCommandExecutable;
use PHPJasper\Exception\InvalidFormat;
use PHPJasper\Exception\InvalidInputFile;
use PHPJasper\Exception\InvalidResourceDirectory;
use PHPJasper\PHPJasper;
use Validator,Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule,DB;
use App\Http\Controllers\Controller;
class ReportesControllerCRM    extends Controller
{
    public function generarReporteSeguimientosRangoFecha($ext, $f_inicial, $f_final)
    {
        // echo $ext;
        $os = array("xls", "pdf");
        //echo gethostname();
        if (in_array($ext, $os, true)) {

            $hora_actual = time() ;
            $input = env('APP_URL_REPORTES') . 'ReporteSeguimientosLlamadasLeads';
            $output = env('APP_URL_REPORTES') . $hora_actual . 'ReporteSeguimientosLlamadasLeads';
            // Rutas de descarga de Reportes en servidor
//            $input = env('APP_URL_REPORTES') . 'ReporteEntradasBodega';
//            $output = env('APP_URL_REPORTES') . $hora_actual .  'ReporteEntradasBodega';
            if($ext =='xls'){
                $input .= '.jasper';
            }
            //Ajustes generales del sistema
            $logo_empresa = Ajustes::where('id_ajuste', 3)->select('valor')->first();
            $nombre_empresa = Ajustes::where('id_ajuste', 4)->select('valor')->first();

            $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
            $options = [
                'format' => [$ext],
                'locale' => 'es',
                'params' => [
                    'logo_empresa' => env('APP_URL_IMAGES').$logo_empresa->valor,
                    'nombre_empresa' => $nombre_empresa->valor,
                    'fecha_inicial' => $f_inicial,
                    'fecha_final' => $f_final,
                ],
                'db_connection' => [
                    'driver' => 'postgres',
                    'username' => env('DB_USERNAME'),
                    'password' => env('DB_PASSWORD'),
                    'host' => env('DB_HOST'),
                    'database' => env('DB_DATABASE'),
                    'port' => env('DB_PORT')
                ]
            ];
            $jasper = new PHPJasper;


/*            exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
            print_r($output);*/
            try {
                $jasper->process($input, $output, $options)->execute();
            } catch (ErrorCommandExecutable $e) {
            } catch (InvalidCommandExecutable $e) {
            } catch (InvalidFormat $e) {
            } catch (InvalidInputFile $e) {
            } catch (InvalidResourceDirectory $e) {
            }

            $headers = [
                'Content-Type' => 'application/pdf',
            ];

            return response()->download($output . '.' . $ext ,$hora_actual. 'ReporteSeguimientosLlamadasLeads.' . $ext, $headers)->deleteFileAfterSend();

        }
        return '';
    }
    public function generarReporteLeadsDescartados($ext, $f_inicial, $f_final)
    {
        // echo $ext;
        $os = array("xls", "pdf");
        //echo gethostname();
        if (in_array($ext, $os, true)) {

            $hora_actual = time() ;
            $input = env('APP_URL_REPORTES') . 'leadsdescartado';
            $output = env('APP_URL_REPORTES') . $hora_actual . 'leadsdescartado';
            // Rutas de descarga de Reportes en servidor
//            $input = env('APP_URL_REPORTES') . 'ReporteEntradasBodega';
//            $output = env('APP_URL_REPORTES') . $hora_actual .  'ReporteEntradasBodega';
            if($ext =='xls'){
                $input .= '.jasper';
            }
            //Ajustes generales del sistema
            $logo_empresa = Ajustes::where('id_ajuste', 3)->select('valor')->first();
            $nombre_empresa = Ajustes::where('id_ajuste', 4)->select('valor')->first();

            $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
            $options = [
                'format' => [$ext],
                'locale' => 'es',
                'params' => [
                    'logo_empresa' => env('APP_URL_IMAGES').$logo_empresa->valor,
                    'nombre_empresa' => $nombre_empresa->valor,
                    'fechainicial' => $f_inicial,
                    'fechafinal' => $f_final,
                ],
                'db_connection' => [
                    'driver' => 'postgres',
                    'username' => env('DB_USERNAME'),
                    'password' => env('DB_PASSWORD'),
                    'host' => env('DB_HOST'),
                    'database' => env('DB_DATABASE'),
                    'port' => env('DB_PORT')
                ]
            ];
            $jasper = new PHPJasper;


/*            exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
            print_r($output);*/
            try {
                $jasper->process($input, $output, $options)->execute();
            } catch (ErrorCommandExecutable $e) {
            } catch (InvalidCommandExecutable $e) {
            } catch (InvalidFormat $e) {
            } catch (InvalidInputFile $e) {
            } catch (InvalidResourceDirectory $e) {
            }

            $headers = [
                'Content-Type' => 'application/pdf',
            ];

            return response()->download($output . '.' . $ext ,$hora_actual. 'ReporteSeguimientosLlamadasLeads.' . $ext, $headers)->deleteFileAfterSend();

        }
        return '';
    }

    public function generarReporteLeadsSeguimiento($ext, $f_inicial, $f_final)
    {
        // echo $ext;
        $os = array("xls", "pdf");
        //echo gethostname();
        if (in_array($ext, $os, true)) {

            $hora_actual = time() ;
            $input = env('APP_URL_REPORTES') . 'SegLeads';
            $output = env('APP_URL_REPORTES') . $hora_actual . 'SegLeads';
            // Rutas de descarga de Reportes en servidor
//            $input = env('APP_URL_REPORTES') . 'ReporteEntradasBodega';
//            $output = env('APP_URL_REPORTES') . $hora_actual .  'ReporteEntradasBodega';
            if($ext =='xls'){
                $input .= '.jasper';
            }
            //Ajustes generales del sistema
            $logo_empresa = Ajustes::where('id_ajuste', 3)->select('valor')->first();
            $nombre_empresa = Ajustes::where('id_ajuste', 4)->select('valor')->first();

            $usuario_empresa = UsuariosEmpresas::where('id_usuario', '=', \Illuminate\Support\Facades\Auth::user()->id)->first();
            $options = [
                'format' => [$ext],
                'locale' => 'es',
                'params' => [
                    'logo_empresa' => env('APP_URL_IMAGES').$logo_empresa->valor,
                    'nombre_empresa' => $nombre_empresa->valor,
                    'fecha_inicial' => $f_inicial,
                    'fecha_final' => $f_final,
                ],
                'db_connection' => [
                    'driver' => 'postgres',
                    'username' => env('DB_USERNAME'),
                    'password' => env('DB_PASSWORD'),
                    'host' => env('DB_HOST'),
                    'database' => env('DB_DATABASE'),
                    'port' => env('DB_PORT')
                ]
            ];
            $jasper = new PHPJasper;


/*            exec($jasper->process($input,$output,$options)->output().' 2>&1', $output);
            print_r($output);*/
            try {
                $jasper->process($input, $output, $options)->execute();
            } catch (ErrorCommandExecutable $e) {
            } catch (InvalidCommandExecutable $e) {
            } catch (InvalidFormat $e) {
            } catch (InvalidInputFile $e) {
            } catch (InvalidResourceDirectory $e) {
            }

            $headers = [
                'Content-Type' => 'application/pdf',
            ];

            return response()->download($output . '.' . $ext ,$hora_actual. 'ReporteSeguimientosLeads.' . $ext, $headers)->deleteFileAfterSend();

        }
        return '';
    }
}
