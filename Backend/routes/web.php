<?php

use App\Http\Controllers\ActivoFijo\ActivosController;
use App\Http\Controllers\ActivoFijo\ColoresController;
use App\Http\Controllers\ActivoFijo\CuentasController;
use App\Http\Controllers\ActivoFijo\PropietariosController;
use App\Http\Controllers\ActivoFijo\TiposActivosController;
use App\Http\Controllers\ActivoFijo\TrasladosController;
use App\Http\Controllers\Admon\AjustesController;
use App\Http\Controllers\Admon\CiudadesController;
use App\Http\Controllers\Admon\DepartamentosController;
use App\Http\Controllers\Admon\EstadosController;
use App\Http\Controllers\Admon\ImpuestosController;
use App\Http\Controllers\Admon\InviteController;
use App\Http\Controllers\Admon\ModulosController;
use App\Http\Controllers\Admon\MunicipiosController;
use App\Http\Controllers\Admon\PaisesController;
use App\Http\Controllers\Admon\PermisosController;
use App\Http\Controllers\Admon\RolesController;
use App\Http\Controllers\Admon\SectoresController;
use App\Http\Controllers\Admon\SucursalesController;
use App\Http\Controllers\Admon\TasksController;
use App\Http\Controllers\Admon\UsuariosController;
use App\Http\Controllers\Admon\ZonasController;
use App\Http\Controllers\AreasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Bitacora\AccesosController;
use App\Http\Controllers\CajaBanco\AfectacionesController;
use App\Http\Controllers\CajaBanco\ArqueoCajaController;
use App\Http\Controllers\CajaBanco\BancosController;
use App\Http\Controllers\CajaBanco\FacturasConfiguracionController;
use App\Http\Controllers\CajaBanco\FacturasController;
use App\Http\Controllers\CajaBanco\ProformasController;
use App\Http\Controllers\CajaBanco\ProformaSeguimientoController;
use App\Http\Controllers\CajaBanco\ProyectosController;
use App\Http\Controllers\CajaBanco\ReportesCjaBncoController;
use App\Http\Controllers\CajaBanco\ViapagosController;
use App\Http\Controllers\CajaBanco\ViaticosController;
use App\Http\Controllers\Compras\ClasificacionComprasController;
use App\Http\Controllers\Compras\OrdenCompraController;
use App\Http\Controllers\Compras\OrdenCompraPSController;
use App\Http\Controllers\Compras\SolicitudCompraController;
use App\Http\Controllers\Contabilidad\AnexosController;
use App\Http\Controllers\Contabilidad\CentrosCostosIngresosController;
use App\Http\Controllers\Contabilidad\CierresMensualesController;
use App\Http\Controllers\Contabilidad\ConfiguracionComprobantesController;
use App\Http\Controllers\Contabilidad\CuentasBancariasController;
use App\Http\Controllers\Contabilidad\DocumentosContablesController;
use App\Http\Controllers\Contabilidad\EstadosFinancierosController;
use App\Http\Controllers\Contabilidad\NivelesCuentasController;
use App\Http\Controllers\Contabilidad\PeriodosFiscalesController;
use App\Http\Controllers\Contabilidad\ReportesContabilidadController;
use App\Http\Controllers\Contabilidad\ReportesFinancierosController;
use App\Http\Controllers\Contabilidad\TasasCambioController;
use App\Http\Controllers\Contabilidad\TiposCuentasController;
use App\Http\Controllers\Contabilidad\TiposDocumentosController;
use App\Http\Controllers\CRM\ClasificacionContactosController;
use App\Http\Controllers\CRM\ClasificacionLlamadasController;
use App\Http\Controllers\CRM\CompaniasController;
use App\Http\Controllers\CRM\ContactosController;
use App\Http\Controllers\CRM\GirosNegociosController;
use App\Http\Controllers\CRM\LeadsController;
use App\Http\Controllers\CRM\MediosContactoController;
use App\Http\Controllers\CRM\OrigenesLeadsController;
use App\Http\Controllers\CRM\ReportesControllerCRM;
use App\Http\Controllers\CRM\SeguimientoLeadsController;
use App\Http\Controllers\CRM\ServiciosOferAcorController;
use App\Http\Controllers\CRM\TipoContactosControllers;
use App\Http\Controllers\CRM\CargosController;
use App\Http\Controllers\CuentasXCobrar\CatOtrasCuentasController;
use App\Http\Controllers\CuentasXCobrar\CuentasXCobrarController;
use App\Http\Controllers\CuentasXCobrar\NotaCreditoController;
use App\Http\Controllers\CuentasXCobrar\NotaDebitoController;
use App\Http\Controllers\CuentasXCobrar\RecibosController;
use App\Http\Controllers\CuentasXCobrar\TiposNotaCreditoController;
use App\Http\Controllers\CuentasXCobrar\TiposNotaDebitoController;
use App\Http\Controllers\CuentasXCobrar\TiposNotaDebitoControllerCP;
use App\Http\Controllers\CuentasXPagar\CatOtrasCuentasControllerCP;
use App\Http\Controllers\CuentasXPagar\NotaCreditoControllerCP;
use App\Http\Controllers\CuentasXPagar\NotaDebitoControllerCP;
use App\Http\Controllers\CuentasXPagar\TiposNotaCreditoControllerCP;
use App\Http\Controllers\CuentasXPagarController;
use App\Http\Controllers\Inventario\BodegasController;
use App\Http\Controllers\Inventario\CategoriasController;
use App\Http\Controllers\Inventario\ConfiguracionInventarioController;
use App\Http\Controllers\Inventario\EntradasController;
use App\Http\Controllers\Inventario\GruposController;
use App\Http\Controllers\Inventario\InventarioFisicoController;
use App\Http\Controllers\Inventario\InventarioInicialController;
use App\Http\Controllers\Inventario\MovimientosProductosController;
use App\Http\Controllers\Inventario\ProductosController;
use App\Http\Controllers\Inventario\ProveedoresControllers;
use App\Http\Controllers\Inventario\ReportesController;
use App\Http\Controllers\Inventario\SalidasController;
use App\Http\Controllers\Inventario\SubGruposController;
use App\Http\Controllers\Inventario\TipoBodegaController;
use App\Http\Controllers\Inventario\TipoEntradaController;
use App\Http\Controllers\Inventario\TipoProveedorController;
use App\Http\Controllers\Inventario\TipoSalidaController;
use App\Http\Controllers\Inventario\TiposProductosController;
use App\Http\Controllers\Inventario\UnidadMedidaController;
use App\Http\Controllers\Nomina\ConfiguracionIRController;
use App\Http\Controllers\Nomina\ContratoGeneralInternoController;
use App\Http\Controllers\Nomina\ContratoGeneralMerecedorController;
use App\Http\Controllers\Nomina\ContratoSolicitudController;
use App\Http\Controllers\Nomina\DatosMedicosController;
use App\Http\Controllers\Nomina\DireccionesController;
use App\Http\Controllers\Nomina\FeriadosController;
use App\Http\Controllers\Nomina\GenerarPlanillasController;
use App\Http\Controllers\Nomina\IngresosDeduccionesController;
use App\Http\Controllers\Nomina\IngresosDeduccionesTrabajadoresController;
use App\Http\Controllers\Nomina\MarcadasController;
use App\Http\Controllers\Nomina\NivelesAcademicosController;
use App\Http\Controllers\Nomina\NivelesEstudiosController;
use App\Http\Controllers\Nomina\ParentescoController;
use App\Http\Controllers\Nomina\PlanillaControlController;
use App\Http\Controllers\Nomina\PlanillasControlesController;
use App\Http\Controllers\Nomina\SaldosVacacionesController;
use App\Http\Controllers\Nomina\SolicitudVacacionesController;
use App\Http\Controllers\Nomina\TipoActoJuridicoController;
use App\Http\Controllers\Nomina\TiposJustificacionesMarcadasController;
use App\Http\Controllers\Nomina\TrabajadoresController;
use App\Http\Controllers\Tesoreria\Caja\SolicitudesPagoController;
use App\Http\Controllers\Tesoreria\CajaChica\NormativaController;
use App\Http\Controllers\Ventas\ClientesController;
use App\Http\Controllers\Ventas\TipoClienteController;
use App\Http\Controllers\Ventas\VendedoresController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which


/*
|--------------------------------------------------------------------------
| Web contabilidad
|--------------------------------------------------------------------------
|
| Here is where you can register web contabilidad for your application. These
| contabilidad are loaded by the contabilidaderviceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Funciones de autenticación
Route::get('obtener-recursos', [AjustesController::class, 'obtenerRecursos']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

// Rutas sanctum

Route::group(['middleware' => ["auth:sanctum"]], function () {

    // Obtener datos del usuario en sesion
    Route::get('me', [AuthController::class, 'me']);
    Route::get('user-activity', [AuthController::class, 'userActivity']);

    // Funciones ajustes generales

    Route::get('ajustes/cargar-basico', [AjustesController::class, 'obtenerAjustesBasicos']);
    Route::get('ajustes/cargar-imagenes', [AjustesController::class, 'obtenerImagenes']);
    Route::get('ajustes/cargar', [AjustesController::class, 'obtenerAjustes']);
    Route::get('ajustes/cargar-contabilidad', [AjustesController::class, 'obtenerAjustesContabilidad']);
    Route::post('ajustes/guardar', [AjustesController::class, 'registrar']);
    Route::post('ajustes/procesar-excel', [AjustesController::class, 'procesarExcel']);
    Route::post('ajustes/importar-datos-excel', [AjustesController::class, 'importarDatosExcel']);
    Route::get('ajustes/dashboard', [AjustesController::class, 'obtenerEstadisticasDashboard']);
    Route::get('ajustes/estadistica', [AjustesController::class, 'obtenerEstadisticas']);
    Route::get('ajustes/productos', [AjustesController::class, 'cantidadPorProducto']);

    // Funciones catalogo de roles

    Route::post('admon/roles/obtener-roles', [RolesController::class, 'obtenerRoles']);
    Route::get('admon/roles/obtener-roles-todos', [RolesController::class, 'obtenerTodosRoles']);
    Route::post('admon/roles/obtener-rol', [RolesController::class, 'obtenerRolEspecifico']);
    Route::post('admon/roles/registrar', [RolesController::class, 'crearRol']);
    Route::put('admon/roles/actualizar', [RolesController::class, 'actualizarRol']);
    Route::put('admon/roles/desactivar', [RolesController::class, 'desactivaRol']);
    Route::put('admon/roles/activar', [RolesController::class, 'activaRol']);

    // funciones catalogo usuarios

    Route::post('admon/usuarios/obtener', [UsuariosController::class, 'obtener']);
    Route::get('admon/usuarios/buscar', [UsuariosController::class, 'buscarUsuarios']);
    Route::get('admon/usuarios/todos', [UsuariosController::class, 'obtenerTodos']);
    Route::post('admon/usuarios/obtener-usuario', [UsuariosController::class, 'obtenerUsuario']);
    Route::post('admon/usuarios/obtener-user-login', [UsuariosController::class, 'obtenerUserLogin']);
    Route::get('admon/usuarios/obtener-activos', [UsuariosController::class, 'obtenerUsuario']);
    Route::post('admon/usuarios/registrar', [UsuariosController::class, 'registrar']);
    Route::put('admon/usuarios/cambiar-contrasena', [UsuariosController::class, 'cambiarContrasena']);
    Route::put('admon/usuarios/desactivar', [UsuariosController::class, 'desactivar']);
    Route::put('admon/usuarios/activar', [UsuariosController::class, 'activar']);

    // funciones modulos

    Route::get('admon/modulos/obtener', [ModulosController::class, 'obtenerModulos']);
    Route::post('admon/menus/verificar', [ModulosController::class, 'verificarPermisoVista']);
    Route::get('admon/menu/obtener-menus-todos', [ModulosController::class, 'obtenerMenusTodos']);

    // funciones Roles
    Route::post('bitacora/accesos/obtener-accesos', [AccesosController::class, 'obtenerAccesos']);
    Route::post('bitacora/accesos/obtener-accesos-reporte', [AccesosController::class, 'obtenerAccesosReporte']);

    // Rutas Permisos
    Route::post('admon/permisos/obtener-permisos', [PermisosController::class, 'obtenerPermisos']);
    Route::post('admon/permisos/guardar', [PermisosController::class, 'guardarPermiso']);

    // Rutas Paises
    Route::get('admon/paises/buscar', [PaisesController::class, 'buscarPais']);
    Route::post('admon/paises/obtener', [PaisesController::class, 'obtener']);
    Route::get('admon/paises/obtener-todos', [PaisesController::class, 'obtenerTodosPaises']);
    Route::post('admon/paises/obtener-pais', [PaisesController::class, 'obtenerPais']);
    Route::post('admon/paises/registrar', [PaisesController::class, 'registrar']);
    Route::put('admon/paises/actualizar', [PaisesController::class, 'actualizar']);
    Route::put('admon/paises/desactivar', [PaisesController::class, 'desactivar']);
    Route::put('admon/paises/activar', [PaisesController::class, 'activar']);

    // Rutas Departamentos
    Route::get('admon/departamentos/buscar', [DepartamentosController::class, 'buscarDepartamentos']);
    Route::post('admon/departamentos/obtener', [DepartamentosController::class, 'obtener']);
    Route::get('admon/departamentos/obtener-todos', [DepartamentosController::class, 'obtenerTodosDepartamentos']);
    Route::post('admon/departamentos/obtener-departamento', [DepartamentosController::class, 'obtenerDepartamento']);
    Route::post('admon/departamentos/registrar', [DepartamentosController::class, 'registrar']);
    Route::put('admon/departamentos/actualizar', [DepartamentosController::class, 'actualizar']);

    // Rutas Municipios
    Route::get('admon/municipios/buscar', [MunicipiosController::class, 'buscarMunicipios']);
    Route::post('admon/municipios/obtener', [MunicipiosController::class, 'obtener']);
    Route::get('admon/municipios/obtener-todos', [MunicipiosController::class, 'obtenerTodosMunicipios']);
    Route::post('admon/municipios/obtener-municipio', [MunicipiosController::class, 'obtenerMunicipio']);
    Route::post('admon/municipios/registrar', [MunicipiosController::class, 'registrar']);
    Route::put('admon/municipios/actualizar', [MunicipiosController::class, 'actualizar']);

    // Rutas Estados
    Route::get('admon/estados/buscar', [EstadosController::class, 'buscarEstados']);
    Route::post('admon/estados/obtener', [EstadosController::class, 'obtener']);
    Route::post('admon/estados/obtener-estado', [EstadosController::class, 'obtenerEstado']);
    Route::post('admon/estados/nuevo', [EstadosController::class, 'nuevo']);
    Route::post('admon/estados/registrar', [EstadosController::class, 'registrar']);
    Route::put('admon/estados/actualizar', [EstadosController::class, 'actualizar']);
    Route::put('admon/estados/activar', [EstadosController::class, 'activar']);
    Route::put('admon/estados/desactivar', [EstadosController::class, 'desactivar']);
    Route::get('admon/estados/reporte', [EstadosController::class, 'generarReporte']);

    // Rutas ciudades
    Route::get('admon/ciudades/buscar', [CiudadesController::class, 'buscarCiudades']);
    Route::post('admon/ciudades/obtener', [CiudadesController::class, 'obtener']);
    Route::post('admon/ciudades/obtener-ciudad', [CiudadesController::class, 'obtenerCiudades']);
    Route::post('admon/ciudades/nuevo', [CiudadesController::class, 'nuevo']);
    Route::post('admon/ciudades/registrar', [CiudadesController::class, 'registrar']);
    Route::put('admon/ciudades/actualizar', [CiudadesController::class, 'actualizar']);
    Route::put('admon/ciudades/activar', [CiudadesController::class, 'activar']);
    Route::put('admon/ciudades/desactivar', [CiudadesController::class, 'desactivar']);
    Route::get('admon/ciudades/reporte', [CiudadesController::class, 'generarReporte']);


    // Rutas Sucursales
    Route::post('admon/sucursales/obtener', [SucursalesController::class, 'obtener']);
    Route::get('admon/sucursales/obtener-todas', [SucursalesController::class, 'obtenerTodas']);
    Route::post('admon/sucursales/obtener-sucursal', [SucursalesController::class, 'obtenerSucursal']);
    Route::post('admon/sucursales/registrar', [SucursalesController::class, 'registrar']);
    Route::put('admon/sucursales/actualizar', [SucursalesController::class, 'actualizar']);
    Route::put('admon/sucursales/activar', [SucursalesController::class, 'activar']);
    Route::put('admon/sucursales/desactivar', [SucursalesController::class, 'desactivar']);
    Route::get('admon/sucursales/buscar', [SucursalesController::class, 'buscar']);
    Route::get('admon/sucursales/reporte/{ext}', [SucursalesController::class, 'generarReporte']);

    //Rutas Impuestos
    Route::post('admon/impuestos/obtener', [ImpuestosController::class, 'obtener']);
    Route::get('admon/impuestos/obtener-impuestos-todos', [ImpuestosController::class, 'obtenerTodos']);
    Route::post('admon/impuestos/obtener-impuesto', [ImpuestosController::class, 'obtenerImpuesto']);
    Route::post('admon/impuestos/registrar', [ImpuestosController::class, 'registrar']);
    Route::put('admon/impuestos/actualizar', [ImpuestosController::class, 'actualizar']);
    Route::put('admon/impuestos/activar', [ImpuestosController::class, 'activar']);
    Route::put('admon/impuestos/desactivar', [ImpuestosController::class, 'desactivar']);
    Route::put('admon/impuestos/reporte/{ext}', [ImpuestosController::class, 'generarReporte']);
    //Rutas Impuestos Fin

    //Rutas Zonas
    Route::post('admon/zonas/obtener', [ZonasController::class, 'obtener']);
    Route::get('admon/zonas/nuevo', [ZonasController::class, 'nuevo']);
    Route::post('admon/zonas/obtener-zona', [ZonasController::class, 'obtenerZona']);
    Route::post('admon/zonas/registrar', [ZonasController::class, 'registrar']);
    Route::put('admon/zonas/actualizar', [ZonasController::class, 'actualizar']);
    Route::put('admon/zonas/activar', [ZonasController::class, 'activar']);
    Route::put('admon/zonas/desactivar', [ZonasController::class, 'desactivar']);
    Route::put('admon/zonas/reporte/{ext}', [ZonasController::class, 'generarReporte']);
    //Rutas Zonas Fin

    //Rutas Sectores
    Route::post('admon/sectores/obtener', [SectoresController::class, 'obtener']);
    Route::get('admon/sectores/nuevo', [SectoresController::class, 'nuevo']);
    Route::post('admon/sectores/obtener-sector', [SectoresController::class, 'obtenerSector']);
    Route::post('admon/sectores/regitrar', [ZonasController::class, 'registrar']);
    Route::put('admon/sectores/actualizar', [ZonasController::class, 'actualizar']);
    Route::put('admon/sectores/activar', [ZonasController::class, 'activar']);
    Route::put('admon/sectores/desactivar', [ZonasController::class, 'desactivar']);
    Route::put('admon/sectores/reporte/{ext}', [ZonasController::class, 'generarReporte']);
    //Rutas Sectores Fin

    //Rutas Tasks
    Route::get('admon/tasks/obtener', [TasksController::class, 'obtener']);
    //Rutas Tasks Fin

//contabilidad contabilidad -> Anexos
    Route::post('contabilidad/anexos/obtener-anexos', [AnexosController::class, 'obtenerAnexos']);
    Route::get('contabilidad/anexos/obtener-anexos-todos', [AnexosController::class, 'obtenerTodosAnexos']);
    Route::post('contabilidad/anexos/obtener-anexos-estado-financiero', [AnexosController::class, 'obtenerAnexosPorEstadoFinanciero']);
    Route::post('contabilidad/anexos/obtener-anexo', [AnexosController::class, 'obtenerAnexo']);
    Route::post('contabilidad/anexos/registrar', [AnexosController::class, 'registrar']);
    Route::put('contabilidad/anexos/actualizar', [AnexosController::class, 'actualizar']);
    Route::put('contabilidad/anexos/desactivar', [AnexosController::class, 'desactivar']);
    Route::put('contabilidad/anexos/activar', [AnexosController::class, 'activar']);
    Route::put('contabilidad/anexos/actualizar-orden', [AnexosController::class, 'actualizarOrdenAnexos']);
//Fin contabilidad contabilidad -> Anexos

//contabilidad contabilidad -> Centros Costos Ingresos
    Route::post('contabilidad/centro-costo/obtener', [CentrosCostosIngresosController::class, 'obtener']);
    Route::get('contabilidad/centro-costo/obtener-todos', [CentrosCostosIngresosController::class, 'obtenerTodos']);
    Route::post('contabilidad/centro-costo/obtener-centro', [CentrosCostosIngresosController::class, 'obtenerCentro']);
    Route::post('contabilidad/centro-costo/registrar', [CentrosCostosIngresosController::class, 'registrar']);
    Route::put('contabilidad/centro-costo/actualizar', [CentrosCostosIngresosController::class, 'actualizar']);
    Route::put('contabilidad/centro-costo/desactivar', [CentrosCostosIngresosController::class, 'desactivar']);
    Route::put('contabilidad/centro-costo/activar', [CentrosCostosIngresosController::class, 'activar']);
    Route::get('contabilidad/centro-costo/reporte/{ext}', [CentrosCostosIngresosController::class, 'generarReporte']);
//Fin contabilidad contabilidad -> Centros Costos Ingresos

//Route contabilidad -> Cierres Mensuales
    Route::post('contabilidad/cuentas-contables/obtener-cuentas-contables', [CierresMensualesController::class, 'obtenerCuentasContables']);
    Route::get('contabilidad/cuentas-contables/obtener-cuentas-contables-todas', [CierresMensualesController::class, 'obtenerTodasCuentasContables']);
    Route::post('contabilidad/cuentas-contables/obtener-cuenta-contable', [CierresMensualesController::class, 'obtenerCuentaContable']);
    Route::post('contabilidad/cuentas-contables/obtener-cuenta-contable-v', [CierresMensualesController::class, 'obtenerCuentaContableV']);
    Route::post('contabilidad/cuentas-contables/registrar', [CierresMensualesController::class, 'guardarCuentaContable']);
    Route::put('contabilidad/cuentas-contables/actualizar', [CierresMensualesController::class, 'actualizarCuentaContable']);
    Route::put('contabilidad/cuentas-contables/desactivar', [CierresMensualesController::class, 'desactivarCuentaContable']);
    Route::put('contabilidad/cuentas-contables/activar', [CierresMensualesController::class, 'activarCuentaContable']);
    Route::post('contabilidad/cuentas-contables/obtener-cuentas-nivel', [CierresMensualesController::class, 'obtenerCuentasContablesNivel']);
    Route::post('contabilidad/cuentas-contables/buscar', [CierresMensualesController::class, 'buscarCuentasContables']);
    Route::post('contabilidad/cuentas-contables/buscarf', [CierresMensualesController::class, 'buscarCuentasContablesF']);
    Route::get('contabilidad/cuentas-contables/reporte/{ext}', [ReportesContabilidadController::class, 'generarReporteCatalogoCuentasContables']);
    Route::get('contabilidad/documentos-contables-detallados/reporte/{ext}/{f_inicial}/{f_final}', [ReportesContabilidadController::class, 'generarReporteDocumentosContablesDetallado']);
    Route::get('contabilidad/documentos-contables-detallados/reporte/{ext}/{f_inicial}/{f_final}', [ReportesContabilidadController::class, 'generarReporteDocumentosContablesDetallado']);
    //Reporte de Listado de Facturas

    Route::get('contabilidad/obtener-varios', [ReportesContabilidadController::class, 'obtenerCatalagoG']);
    Route::get('contabilidad/listado-de-facturas/{extension}/{id_vendedor}/{fecha_inicial}/{fecha_final}', [ReportesContabilidadController::class, 'generarReporteListadoFactura']);

    //Reporte de Listado de Recibo

/*    Route::get('contabilidad/obtener-varios', [ReportesContabilidadController::class, 'obtenerCatalagoRecibo']);*/
    Route::get('contabilidad/listado-de-recibo/{extension}/{id_cliente}/{fecha_inicial}/{fecha_final}', [ReportesContabilidadController::class, 'generarReporteListadoRecibo']);


//Fin contabilidad contabilidad -> Cierres Mensuales

//Route contabilidad -> Cuentas Bancarias
    Route::post('contabilidad/cuentas-bancarias/obtener', [CuentasBancariasController::class, 'obtenerCuentasBancarias']);
    Route::get('contabilidad/cuentas-bancarias/obtener-cuentas-bancarias-todas', [CuentasBancariasController::class, 'obtenerTodasCuentasBancarias']);
    Route::post('contabilidad/cuentas-bancarias/obtener-cuentas-bancarias', [CuentasBancariasController::class, 'obtenerCuentasBancarias']);
    Route::post('contabilidad/cuentas-bancarias/obtener-cuenta-bancaria', [CuentasBancariasController::class, 'obtenerCuentaBancaria']);
    Route::post('contabilidad/cuentas-bancarias/nueva', [CuentasBancariasController::class, 'nueva']);
    Route::post('contabilidad/cuentas-bancarias/registrar', [CuentasBancariasController::class, 'registrar']);
    Route::put('contabilidad/cuentas-bancarias/actualizar', [CuentasBancariasController::class, 'actualizar']);
    Route::put('contabilidad/cuentas-bancarias/desactivar', [CuentasBancariasController::class, 'desactivar']);
    Route::put('contabilidad/cuentas-bancarias/activar', [CuentasBancariasController::class, 'activar']);
    Route::get('contabilidad/cuentas-bancarias/reporte/{ext}', [ReportesCjaBncoController::class, 'generarReporteCuentasBancarias']);
//Fin Route contabilidad -> Cuentas Bancarias

//Route contabilidad -> Documentos Contables
    Route::post('contabilidad/documentos-contables/obtener', [DocumentosContablesController::class, 'obtener']);
    Route::get('contabilidad/documentos-contables/obtener-todos', [DocumentosContablesController::class, 'obtenerTodos']);
    Route::post('contabilidad/documentos-contables/obtener-documento', [DocumentosContablesController::class, 'obtenerDocumentoContable']);
    Route::post('contabilidad/documentos-contables/registrar', [DocumentosContablesController::class, 'registrar']);
    Route::put('contabilidad/documentos-contables/actualizar', [DocumentosContablesController::class, 'actualizar']);
    Route::post('contabilidad/documentos-contables/obtener-codigo-documento', [DocumentosContablesController::class, 'obtenerCodigoDocumento']);
    Route::post('contabilidad/documentos-contables/nuevo', [DocumentosContablesController::class, 'nuevo']);
    Route::post('contabilidad/documentos-contables/anular', [DocumentosContablesController::class, 'anular']);
    Route::get('contabilidad/documentos-contables/reporte/{ext}/{type}/{fecha_inicial}/{fecha_final}', [ReportesContabilidadController::class, 'generarReporteDocumentosContables']);
    Route::get('contabilidad/documentos-contables/reporte-especifico/{ext}/{id_documento}', [ReportesContabilidadController::class, 'generarReporteDocumentoContableEspecifico']);
//Fin Route contabilidad -> Documentos Contables

//Route contabilidad -> Estados Financieros
    Route::post('contabilidad/Estados-financieros/obtener-estados-financieros', [EstadosFinancierosController::class, 'obtenerEstadosFinacieros']);
    Route::get('contabilidad/Estados-financieros/obtener-estados-financieros-todas', [EstadosFinancierosController::class, 'obtenerTodosEstadosFinacieros']);
    Route::get('contabilidad/Estados-financieros/obtener-estado-financiero-contable', [EstadosFinancierosController::class, 'obtenerEstadoFinaciero']);
    Route::get('contabilidad/Estados-financieros/obtener-estados-financieros-lista', [EstadosFinancierosController::class, 'obtenerListaEstadosFinacieros']);
//Fin Route contabilidad -> Estados Financieros

//Route contabilidad -> Niveles Cuentas
    Route::post('contabilidad/niveles-cuentas/obtener-niveles-cuenta', [NivelesCuentasController::class, 'obtenerNivelesCuenta']);
    Route::get('contabilidad/niveles-cuentas/obtener-niveles-cuenta-todas', [NivelesCuentasController::class, 'obtenerTodosNivelesCuenta']);
    Route::post('contabilidad/niveles-cuentas/obtener-nivel-cuenta', [NivelesCuentasController::class, 'obtenerNivelCuenta']);
    Route::put('contabilidad/niveles-cuentas/actualizar', [NivelesCuentasController::class, 'actualizar']);
    Route::get('contabilidad/niveles-cuentas/reporte/{ext}', [ReportesContabilidadController::class, 'generarReporteNivelesCuentas']);
//Fin Route contabilidad -> Niveles Cuentas

//Route contabilidad -> Periodos Fiscales
    Route::post('contabilidad/periodos/obtener', [PeriodosFiscalesController::class, 'obtener']);
    Route::get('contabilidad/periodos/obtener-todos', [PeriodosFiscalesController::class, 'obtenerTodos']);
    Route::post('contabilidad/periodos/obtener-periodo', [PeriodosFiscalesController::class, 'obtenerPeriodo']);
    Route::post('contabilidad/periodos/registrar', [PeriodosFiscalesController::class, 'registrar']);
    Route::put('contabilidad/periodos/actualizar', [PeriodosFiscalesController::class, 'actualizar']);
    Route::put('contabilidad/periodos/cerrar-mes', [PeriodosFiscalesController::class, 'cerrarMes']);
//Fin Route contabilidad -> Periodos Fiscales

//Route contabilidad -> Reportes Financieros
    Route::post('contabilidad/estados-financieros/balanza', [ReportesFinancierosController::class, 'obtenerBalanzaComprobacion']);
    Route::post('contabilidad/estados-financieros/balanza/dependicias', [ReportesFinancierosController::class, 'obtenerDependenciasBalanzaComprobacion']);
    Route::post('contabilidad/estados-financieros/balanza/dependicias', [ReportesFinancierosController::class, 'obtenerDependenciasBalanzaComprobacion']);
    Route::post('contabilidad/estados-financieros/balanza-nueva', [ReportesFinancierosController::class, 'obtenerBalanzaComprobacionRta91']);
    Route::get('contabilidad/estados-financieros/balanza-comprobacion/reporte/{id_nivel_cuenta}/{fecha_inicial}/{fecha_final}/{currency}/{extension}', [ReportesFinancierosController::class, 'reporteBalanzaComprobacion']);
    Route::get('contabilidad/reporte/movimiento-por-cuenta/{extension}/{id_cuenta_contable}/{fecha_inicial}/{fecha_final}', [ReportesContabilidadController::class, 'generarReporteMovimientosPorCuenta']);
    Route::get('contabilidad/reporte/ingresocostorubro/{extension}/{fecha_inicial}/{fecha_final}', [ReportesContabilidadController::class, 'generarReporteIngresoCostoRubro']);
    Route::post('contabilidad/estados-financieros/balanza-comprobacion/reporte-anual', [ReportesFinancierosController::class, 'reporteBalanzaComprobacionAnual']);
    Route::post('contabilidad/estados-financieros/balance-general', [ReportesFinancierosController::class, 'obtenerBalanceGeneral']);
    Route::post('contabilidad/estados-financieros/estado-resultado', [ReportesFinancierosController::class, 'obtenerEstadoResultados']);
    Route::post('contabilidad/estados-financieros/balance-general/reporte', [ReportesFinancierosController::class, 'obtenerBalanceGeneralReporte']);

    Route::get('contabilidad/estados-financieros/estado-resultado/reporte/{id_periodo_fiscal}/{mes}/{currency}/{extension}/{periodo}', [ReportesContabilidadController::class, 'generarReporteEstadoResultado']);

    Route::get('contabilidad/estados-financieros/libro-diario/reporte/{ext}/{periodo}/{id_periodo}/{mes}', [ReportesContabilidadController::class, 'generarReporteLibroDiario']);
    Route::get('contabilidad/estados-financieros/libro-mayor/reporte/{ext}/{periodo}/{id_periodo}/{mes}', [ReportesContabilidadController::class, 'generarReporteLibroMayor']);
    Route::get('contabilidad/estados-financieros/cambio-patrimonio/reporte/{ext}/{id_period}/{mes}', [ReportesContabilidadController::class, 'generarReporteCambioPatrimonio']);
    Route::get('contabilidad/estados-financieros/balanza-anual/reporte/{ext}/{id_nivel_cuenta}/{id_periodo}/{mes}', [ReportesContabilidadController::class, 'generarReporteBalanzaComprobacion']);
    Route::post('contabilidad/estados-financieros/notas/reporte', [ReportesFinancierosController::class, 'reporteNotasBGER']);
    Route::post('contabilidad/estados-financieros/anexo-flujo/reporte', [ReportesFinancierosController::class, 'reporteAnexoFlujo']);
    Route::post('contabilidad/estados-financieros/flujo-efectivo/reporte', [ReportesFinancierosController::class, 'reporteFlujoEfectivo']);
    Route::post('contabilidad/estados-financieros/centro-costos/reporte', [ReportesFinancierosController::class, 'reporteMovimientosCentroCosto']);
    Route::get('contabilidad/estados-financieros/razones-financieras-comparativo/reporte/{ext}/{id_periodo_act}/{mes_act}/{id_periodo_ant}/{mes_ant}', [ReportesContabilidadController::class, 'generarReporteRazonesFinancieras']);

    Route::get('contabilidad/movimiento-con-saldos/{ext}/{id_bodega}/{f_inicial}/{f_final}', [ReportesContabilidadController::class, 'generarReporteMovimientosConSaldos']);
    Route::get('contabilidad/reporte/detallado-facturas/{ext}/{f_inicial}/{f_final}', [ReportesContabilidadController::class, 'generarReporteFacturasDetallado']);
//Fin Route contabilidad -> Reportes Financieros

// contabilidad contabilidad -> Tipos Cuentas
    Route::post('contabilidad/tipos-cuenta/obtener-tipos-cuenta', [TiposCuentasController::class, 'obtenerTiposCuenta']);
    Route::get('contabilidad/tipos-cuenta/obtener-tipos-cuenta-todas', [TiposCuentasController::class, 'obtenerTodosTiposCuenta']);
    Route::post('contabilidad/tipos-cuenta/obtener-tipo-cuenta-contable', [TiposCuentasController::class, 'obtenerTipoCuenta']);
    Route::put('contabilidad/tipos-cuenta/actualizar', [TiposCuentasController::class, 'actualizar']);
    Route::get('contabilidad/tipos-cuenta/reporte/{ext}', [ReportesContabilidadController::class, 'generarReporteTipoCuentas']);
//Fin contabilidad contabilidad -> Tipos Cuentas

// contabilidad contabilidad -> Tipos Documentos
    Route::post('contabilidad/tipos-documentos/obtener', [TiposDocumentosController::class, 'obtener']);
    Route::get('contabilidad/tipos-documentos/obtener-todos', [TiposDocumentosController::class, 'obtenerTodos']);
    Route::post('contabilidad/tipos-documentos/obtener-tipo-documento', [TiposDocumentosController::class, 'obtenerTipoDocumento']);
    Route::post('contabilidad/tipos-documentos/registrar', [TiposDocumentosController::class, 'registrar']);
    Route::put('contabilidad/tipos-documentos/actualizar', [TiposDocumentosController::class, 'actualizar']);
    Route::put('contabilidad/tipos-documentos/desactivar', [TiposDocumentosController::class, 'desactivar']);
    Route::put('contabilidad/tipos-documentos/activar', [TiposDocumentosController::class, 'activar']);
    Route::get('contabilidad/tipos-documentos/reporte/{ext}', [ReportesContabilidadController::class, 'generarReporteTipoDocumentos']);
// Fin contabilidad contabilidad -> Tipos Documentos

    //Rutas pantalla configuracion de CD contabilidad
    Route::post('contabilidad/obtener-configuracion', [ConfiguracionComprobantesController::class, 'obtener']);
    Route::put('contabilidad/actualizar-configuracion', [ConfiguracionComprobantesController::class, 'actualizar']);

    //Rutas Tasas de cambio
    Route::post('contabilidad/tasas-cambio/descargar', [TasasCambioController::class, 'descargarTasasCambio']);
    Route::post('contabilidad/tasas-cambio/dia', [TasasCambioController::class, 'obtenerTC']);
    Route::post('contabilidad/tasas-cambio/dia/paralela', [TasasCambioController::class, 'obtenerTCParalela']);
    Route::post('contabilidad/tasas-cambio/obtener-tasas', [TasasCambioController::class, 'obtenerTasasCambio']);
    Route::post('contabilidad/tasas-cambio/obtener-tasas-reporte', [TasasCambioController::class, 'obtenerTasasReporte']);
    Route::get('contabilidad/tasas-cambio/reporte/{ext}/{periodo}/{mes}', [ReportesContabilidadController::class, 'generarReporteTasaCambio']);
    Route::get('contabilidad/comprobantes-descuadrados/reporte/{ext}/{periodo}/{mes}', [ReportesContabilidadController::class, 'generarReporteComprobantesDescuadrados']);
    Route::get('contabilidad/comparativo-salidas-contabilidad/reporte/{ext}/{fecha_inicial}/{fecha_final}', [ReportesContabilidadController::class, 'generarReporteComparativoSalidasContabilidad']);
    Route::put('contabilidad/tasas-cambio/paralelas/actualizar', [TasasCambioController::class, 'actualizarTCParalelas']);

    //Rutas Inventaio

    //Rutas bodegas
    Route::post('bodegas/obtener', [BodegasController::class, 'obtener']);
    Route::get('bodegas/obtener-bodegas-todas', [BodegasController::class, 'obtenerTodas']);
    Route::get('bodegas/obtener-bodegas-productos', [BodegasController::class, 'obtenerBodegaProductos']);
    Route::get('bodegas/obtener-productos_venta', [BodegasController::class, 'obtenerBodegaProductos']);
    Route::post('bodegas/obtener-bodegas-productos-garantia', [BodegasController::class, 'obtenerBodegaProductosGarantia']);
    Route::get('bodegas/obtener-bodegas-productos-venta', [BodegasController::class, 'obtenerBodegaProductosVenta']);
    Route::post('bodegas/obtener-bodegas-productos-recuperados', [BodegasController::class, 'obtenerBodegaProductosRecuperados']);
    Route::post('bodegas/obtener-bodegas-productos-obsoleto', [BodegasController::class, 'obtenerBodegaProductosObsoletos']);
    Route::post('bodegas/obtener-bodega', [BodegasController::class, 'obtenerBodega']);
    Route::post('bodegas/registrar', [BodegasController::class, 'registrar']);
    Route::put('bodegas/actualizar', [BodegasController::class, 'actualizar']);
    Route::put('bodegas/activar', [BodegasController::class, 'activar']);
    Route::put('bodegas/desactivar', [BodegasController::class, 'desactivar']);
    Route::get('bodegas/buscar', [BodegasController::class, 'buscar']);
    Route::get('bodegas/reporte/{ext}', [ReportesController::class, 'generarReporteBodegas']);
    Route::get('bodegas/reporte-articulos-bodega/{ext}/{id_bodega}', [ReportesController::class, 'generarReporteArticulosxBodega']);
    Route::get('bodegas/reporte-existencia-minima/{ext}/{id_grupo}', [ReportesController::class, 'generarReporteExitenciaMinima']);
    Route::post('bodegas/nuevo', [BodegasController::class, 'nuevo']);
    //Rutas bodegas fin

    // Rutas proyectos
    Route::post('proyectos/obtener', [ProyectosController::class, 'obtenerProyectos']);
    Route::post('proyectos/obtener-proyecto', [ProyectosController::class, 'obtenerProyecto']);
    Route::get('proyectos/obtener-proyectos-todas', [ProyectosController::class, 'obtenerTodosProyectos']);
    Route::post('proyectos/registrar', [ProyectosController::class, 'registrar']);
    Route::put('proyectos/actualizar', [ProyectosController::class, 'actualizar']);
    Route::put('proyectos/activar', [ProyectosController::class, 'activar']);
    Route::put('proyectos/desactivar', [ProyectosController::class, 'desactivar']);
    Route::post('proyectos/obtener-proyectos-cliente', [ProyectosController::class, 'obtenerProyectosCliente']);
    // Fin rutas proyeectos

    // Rutas codigos de invitacion
    Route::post('invites/obtener', [InviteController::class, 'obtener']);
    Route::post('invites/obtener-invites', [InviteController::class, 'obtenerProyecto']);
    Route::get('invites/obtener-invites-todas', [InviteController::class, 'obtenerTodosProyectos']);
    Route::post('invites/registrar', [InviteController::class, 'store']);
    Route::put('invites/actualizar', [InviteController::class, 'actualizar']);
    Route::put('invites/activar', [InviteController::class, 'activar']);
    Route::put('invites/desactivar', [InviteController::class, 'desactivar']);
    // Fin rutas codigos de invitación
    //Rutas categorias productos
    Route::post('categorias/obtener', [CategoriasController::class, 'obtener']);
    Route::get('categorias/obtener-categorias-todas', [CategoriasController::class, 'obtenerTodas']);
    Route::post('categorias/obtener-categoria', [CategoriasController::class, 'obtenerCategoria']);
    Route::post('categorias/registrar', [CategoriasController::class, 'registrar']);
    Route::put('categorias/actualizar', [CategoriasController::class, 'actualizar']);
    Route::put('categorias/activar', [CategoriasController::class, 'activar']);
    Route::put('categorias/desactivar', [CategoriasController::class, 'desactivar']);
    Route::get('categorias/reporte/{ext}', [CategoriasController::class, 'generarReporte']);
    //Rutas categorias fin


    #region Rutas Solicitudes Compra

    Route::controller('Compras\SolicitudCompraController')->group(function () {
        Route::post('compras/solicitudes/obtener', 'obtener');
        Route::get('compras/solicitudes/nueva', 'nueva');
        Route::post('compras/solicitudes/obtener-solicitud-compra', 'obtenerSolicitud');
        Route::post('compras/solicitudes/registrar', 'registrar');
        Route::put('compras/solicitudes/actualizar', 'actualizar');
        Route::put('compras/solicitudes/cambiar-estado', 'cambiarEstado');
        Route::get('compras/solicitudes/reporte/{id_solicitud_compra}', 'reporte');
    });
    #endregion
    #region orden de compra
    Route::controller('Compras\OrdenCompraController')->group(function () {
        Route::post('compras/ordenes/obtener', 'obtener');
        Route::post('compras/ordenes/nueva', 'nueva');
        // Route::post('compras/ordenes/nueva-orden-compra-solicitud', 'OrdenCompraController@nuevaOrdenSolicitud');
        Route::post('compras/ordenes/obtener-orden-compra', 'obtenerOrdenCompra');
        Route::post('compras/ordenes/registrar', 'registrar');
        Route::put('compras/ordenes/actualizar', 'actualizar');
        Route::put('compras/ordenes/cambiar-estado', 'cambiarEstado');
        Route::get('compras/ordenes/reporte/{id_orden_compra}', 'reporte');
        Route::get('compras/ordenes/buscar', 'buscar');
        Route::post('compras/facturas-compra/obtener-facturas-proveedor', 'obtenerFacturasCompraProveedor');
    });
    #endregion
    #region Ordenes Compra SERVICIOS EXTERNOS
    Route::controller('Compras\OrdenCompraPSController')->group(function () {
        Route::post('compras-ps/ordenes/obtener', 'obtener');
        Route::post('compras-ps/ordenes/nueva', 'nueva');
        // Route::post('compras/ordenes/nueva-orden-compra-solicitud', 'OrdenCompraController@nuevaOrdenSolicitud');
        Route::post('compras-ps/ordenes/obtener-orden-compra', 'obtenerOrdenCompra');
        Route::post('compras-ps/ordenes/registrar', 'registrar');
        Route::put('compras-ps/ordenes/actualizar', 'actualizar');
        Route::put('compras-ps/ordenes/cambiar-estado', 'cambiarEstado');
        Route::get('compras-ps/ordenes/reporte/{id_orden_servicio}', 'reporte');
    });
    #endregion
    #region Clasificacion de compras
    Route::controller('Compras\ClasificacionComprasController')->group(function () {
        Route::post('compras/clasificacion-compra/obtener', 'obtener');
        Route::get('compras/clasificacion-compra/obtener-clasificacion-todos', 'obtenerTodasClasificacion');
        Route::post('compras/clasificacion-compra/obtener-clasificacion', 'obtenerClasificacion');
        Route::post('compras/clasificacion-compra/registrar', 'registrar');
        Route::put('compras/clasificacion-compra/actualizar', 'actualizar');
        Route::put('compras/clasificacion-compra/desactivar', 'desactivar');
        Route::put('compras/clasificacion-compra/activar', 'activar');
        Route::post('compras/clasificacion-compra/nuevo', 'nuevo');
        //Route::get('contabilidad/bancos/reporte/{ext}', 'ClasificacionComprasController@generarReporte');
    });
    #endregion

    //Rutas Productos
    Route::post('productos/obtener-productos', [ProductosController::class, 'obtenerProductos']);
    Route::post('productos/obtener-producto', [ProductosController::class, 'obtenerProducto']);
    Route::post('productos/registrarPS', [ProductosController::class, 'registrarPS']);
    Route::put('productos/actualizarPS', [ProductosController::class, 'actualizarPS']);
    Route::post('productos/nuevo-producto-servicio', [ProductosController::class, 'nuevoPS']);
    Route::get('productos/ps/reporte/{ext}', [ReportesController::class, 'generarReporteProductos']);
    Route::get('productos/ps/reporte-articulos/{ext}', [ReportesController::class, 'generarReporteGeneralArticulos']);
    Route::get('productos/ps/reporte-servicios/{ext}', [ReportesController::class, 'generarReporteGeneralServicios']);
//    Route::post('inventario/productos/buscar', [ProductosController::class, 'buscarPS']);
    Route::get('inventario/productos/buscar', [ProductosController::class, 'buscarPS']);
    Route::put('producto/desactivar', [ProductosController::class, 'desactivaProducto']);
    Route::put('producto/activar', [ProductosController::class, 'activaProducto']);
    Route::post('productos/obtener-productos-bodega', [ProductosController::class, 'obtenerProductosBodega']);
    Route::get('productos/obtener-productos-validos', [ProductosController::class, 'obtenerProductosValidos']);
    Route::put('productos/obtener-codigo-producto', [ProductosController::class, 'obtenerCodigoProducto']);
    Route::get('productos/buscar-bodega', [ProductosController::class, 'buscarProductosBodega']);
    Route::get('productos/buscar-bodega-venta', [ProductosController::class, 'buscarProductosBodegaVenta']);
    Route::get('productos/reportes-codigobarra/{ext}', [ReportesController::class, 'generarReporteCodigoBArra']);
    //Rutas Productos Fin

    //Rutas tipo productos
    Route::post('tipo-producto/obtener', [TiposProductosController::class, 'obtener']);
    Route::get('tipo-producto/obtener-todas', [TiposProductosController::class, 'obtenerTodas']);
    Route::post('tipo-producto/obtener-tipo', [TiposProductosController::class, 'obtenerTipoProducto']);
    Route::post('tipo-producto/registrar', [TiposProductosController::class, 'registrar']);
    Route::put('tipo-producto/actualizar', [TiposProductosController::class, 'actualizar']);
    Route::put('tipo-producto/activar', [TiposProductosController::class, 'activar']);
    Route::put('tipo-producto/desactivar', [TiposProductosController::class, 'desactivar']);
    Route::get('tipo-producto/reporte/{ext}', [ReportesController::class, 'generarReporteTipoProductos']);
    //Rutas tipo productos fin

    //Rutas Unidad de Medida
    Route::post('unidad-medida/obtener', [UnidadMedidaController::class, 'obtener']);
    Route::get('unidad-medida/obtener-unidad-medidas-todas', [UnidadMedidaController::class, 'obtenerTodos']);
    Route::post('unidad-medida/obtener-unidad-medida', [UnidadMedidaController::class, 'obtenerUnidadMedida']);
    Route::post('unidad-medida/registrar', [UnidadMedidaController::class, 'registrar']);
    Route::put('unidad-medida/actualizar', [UnidadMedidaController::class, 'actualizar']);
    Route::put('unidad-medida/activar', [UnidadMedidaController::class, 'activar']);
    Route::put('unidad-medida/desactivar', [UnidadMedidaController::class, 'desactivar']);
    Route::get('unidad-medida/reporte/{ext}', [ReportesController::class, 'generarReporteUnidadesMedida']);
    //Rutas Unidad de Medida fin

    //Rutas inventario inicial
    Route::post('entradas/inventario-inicial/obtener-entrada', [InventarioInicialController::class, 'obtenerEntradaInvInicial']);
    Route::post('entradas/inventario-inicial/obtener', [InventarioInicialController::class, 'obtener']);
    Route::post('entradas/nuevo-inventario-inicial', [InventarioInicialController::class, 'nuevo']);
    Route::put('entradas/inventario-inicial/recibir', [InventarioInicialController::class, 'recibir']);
    Route::put('entradas/inventario-inicial/actualizar', [InventarioInicialController::class, 'actualizar']);
    Route::put('entradas/inventario-inicial/registrar', [InventarioInicialController::class, 'registrar']);
    Route::get('entradas/inventario-inicial/reporte/{ext}/{id_entrada_inicial}', [InventarioInicialController::class, 'generarReporteInventarioInicial']);
    Route::post('entradas/nuevo-inventario-inicial-varios', [InventarioInicialController::class, 'nuevoManual']);
    Route::post('entradas/inventario-inicial-manual/registrar', [InventarioInicialController::class, 'registrarInvManual']);
    Route::put('entradas/inventario-inicial/actualizar-manual', [InventarioInicialController::class, 'actualizarManual']);

    //Rutas control de entradas
    // Rutas Entradas
    Route::post('entradas/obtener', [EntradasController::class, 'obtener']);
    Route::post('entradas/obtener-entrada', [EntradasController::class, 'obtenerEntrada']);
    Route::post('entradas/obtener-proveedores', [EntradasController::class, 'obtenerProveedores']);
    //Route::get('entradas/obtener-entrada-por-codigo', 'InventarioEntradasController@obtenerEntradaPorCodigo');
    Route::post('entradas/registrar', [EntradasController::class, 'registrar']);
    Route::post('entradas/nuevo', [EntradasController::class, 'nuevo']);
    Route::post('entradas/autosave-entrada', [EntradasController::class, 'autosaveEntradaProducto']);
    Route::put('entradas/actualizar', [EntradasController::class, 'actualizar']);
    Route::put('entradas/recibir', [EntradasController::class, 'recibir']);
    Route::put('entradas/recibir-compra', [EntradasController::class, 'recibirCompra']);
    Route::get('entradas/reporte/{ext}/{id_entrada}', [EntradasController::class, 'reporteEntrada']);
    Route::get('entradas/reporte-entrada-bodega/{ext}/{id_bodega}/{f_inicial}/{f_final}', [ReportesController::class, 'generarReporteEntradaxBodega']);

    //Rutas Inventario Fin

    //Rutas Tipos de bodega
    Route::post('inventario/tipos-bodegas/obtener', [TipoBodegaController::class, 'obtener']);
    Route::get('inventario/tipos-bodegas/obtener-todos', [TipoBodegaController::class, 'obtenerTodos']);
    Route::post('inventario/tipos-bodegas/obtener-tipo-bodega', [TipoBodegaController::class, 'obtenerTipoBodega']);
    Route::post('inventario/tipos-bodegas/registrar', [TipoBodegaController::class, 'registrar']);
    Route::put('inventario/tipos-bodegas/actualizar', [TipoBodegaController::class, 'actualizar']);
    Route::put('inventario/tipos-bodegas/activar', [TipoBodegaController::class, 'activar']);
    Route::put('inventario/tipos-bodegas/desactivar', [TipoBodegaController::class, 'desactivar']);
    Route::get('inventario/tipos-bodegas/reporte/{ext}', [ReportesController::class, 'generarReporteTipoBodegas']);
    //Rutas Tipos de bodega Fin

    //Rutas Tipos de Entradas
    Route::post('inventario/tipos-entradas/obtener', [TipoEntradaController::class, 'obtener']);
    Route::get('inventario/tipos-entradas/obtener-tipos-entradas-todos', [TipoEntradaController::class, 'obtenerTodosTiposEntrada']);
    Route::post('inventario/tipos-entradas/obtener-tipo-entrada', [TipoEntradaController::class, 'obtenerTipoEntrada']);
    Route::post('inventario/tipos-entradas/registrar', [TipoEntradaController::class, 'registrar']);
    Route::put('inventario/tipos-entradas/actualizar', [TipoEntradaController::class, 'actualizar']);
    Route::put('inventario/tipos-entradas/activar', [TipoEntradaController::class, 'activar']);
    Route::put('inventario/tipos-entradas/desactivar', [TipoEntradaController::class, 'desactivar']);
    Route::get('inventario/tipos-entradas/reporte/{ext}', [ReportesController::class, 'generarReporteTipoEntrada']);
    //Rutas Tipos de Entradas Fin

    //Rutas Tipos de Salidas
    Route::post('inventario/tipos-salidas/obtener', [TipoSalidaController::class, 'obtener']);
    Route::get('inventario/tipos-salidas/obtener-todos-tipos-salidas', [TipoSalidaController::class, 'obtenerTodosTiposSalida']);
    Route::post('inventario/tipos-salidas/obtener-tipo-salida', [TipoSalidaController::class, 'obtenerTipoSalida']);
    Route::post('inventario/tipos-salidas/registrar', [TipoSalidaController::class, 'registrar']);
    Route::put('inventario/tipos-salidas/actualizar', [TipoSalidaController::class, 'actualizar']);
    Route::put('inventario/tipos-salidas/activar', [TipoSalidaController::class, 'activar']);
    Route::put('inventario/tipos-salidas/desactivar', [TipoSalidaController::class, 'desactivar']);
    Route::get('inventario/tipos-salidas/reporte/{ext}', [ReportesController::class, 'generarReporteTipoSalida']);
    Route::get('inventario/tipos-salidas-bodegas/reporte/{ext}/{id_bodega}/{f_inicial}/{f_final}', [ReportesController::class, 'generarReporteSalidaxBodega']);
    //Rutas Tipos de Salidas Fin

    //Rutas Tipos de proveedores
    Route::post('inventario/tipo-proveedor/obtener', [TipoProveedorController::class, 'obtener']);
    Route::get('inventario/tipo-proveedor/obtener-todos', [TipoProveedorController::class, 'obtenerTodos']);
    Route::post('inventario/tipo-proveedor/obtener-tipo-proveedor', [TipoProveedorController::class, 'obtenerTipoProveedor']);
    Route::post('inventario/tipo-proveedor/registrar', [TipoProveedorController::class, 'registrar']);
    Route::put('inventario/tipo-proveedor/actualizar', [TipoProveedorController::class, 'actualizar']);
    Route::put('inventario/tipo-proveedor/activar', [TipoProveedorController::class, 'activar']);
    Route::put('inventario/tipo-proveedor/desactivar', [TipoProveedorController::class, 'desactivar']);
    Route::get('inventario/tipo-proveedor/reporte/{ext}', [ReportesController::class, 'generarReporteTipoProveedores']);
    //Rutas Tipos de proveedores Fin

    //Rutas Proveedores
    Route::post('inventario/proveedores/obtener', [ProveedoresControllers::class, 'obtener']);
    Route::get('inventario/proveedores/obtener-proveedores-todos', [ProveedoresControllers::class, 'obtenerTodos']);
    Route::get('inventario/proveedores/obtener-proveedores-producto', [ProveedoresControllers::class, 'obtenerProveedoresProducto']);
    Route::post('inventario/proveedores/obtener-proveedor', [ProveedoresControllers::class, 'obtenerProveedor']);
    Route::post('inventario/proveedores/registrar', [ProveedoresControllers::class, 'registrar']);
    Route::put('inventario/proveedores/actualizar', [ProveedoresControllers::class, 'actualizar']);
    Route::put('inventario/proveedores/activar', [ProveedoresControllers::class, 'activar']);
    Route::put('inventario/proveedores/desactivar', [ProveedoresControllers::class, 'desactivar']);
    Route::get('inventario/proveedores/buscar', [ProveedoresControllers::class, 'buscar']);
    Route::get('inventario/proveedores/reporte/{ext}', [ReportesController::class, 'generarReporteProveedores']);
    //Rutas Proveedores Fin

    //Rutas Marcas
    /*    Route::post('inventario/marcas/obtener', [MarcasController::class,'obtener']);
        Route::get('inventario/marcas/obtener-todos', [MarcasController::class,'obtenerTodasMarcas']);
        Route::post('inventario/marcas/obtener-marca', [MarcasController::class,'obtenerMarca']);
        Route::post('inventario/marcas/registrar', [MarcasController::class,'registrar']);
        Route::put('inventario/marcas/actualizar', [MarcasController::class,'actualizar']);
        Route::put('inventario/marcas/activar', [MarcasController::class,'activar']);
        Route::put('inventario/marcas/desactivar', [MarcasController::class,'desactivar']);
        Route::get('inventario/marcas/reporte/{ext}', [ReportesController::class,'generarReporteMarcas']);
        Route::get('inventario/marcas/reporte-exitencia/{ext}/{id_bodega}/{id_marca}', [ReportesController::class,'generarReporteExistenciaxMarca']);
        Route::post('inventario/marcas/importar-excel', [MarcasController::class,'importExcel']);*/

    Route::controller('Inventario\MarcasController')->group(function () {
        Route::post('inventario/marcas/obtener', 'obtener');
        Route::get('inventario/marcas/obtener-todos', 'obtenerTodasMarcas');
        Route::post('inventario/marcas/obtener-marca', 'obtenerMarca');
        Route::post('inventario/marcas/registrar', 'registrar');
        Route::put('inventario/marcas/actualizar', 'actualizar');
        Route::put('inventario/marcas/activar', 'activar');
        Route::put('inventario/marcas/desactivar', 'desactivar');
        Route::get('inventario/marcas/reporte/{ext}', 'generarReporteMarcas');
        Route::get('inventario/marcas/reporte-exitencia/{ext}/{id_bodega}/{id_marca}', [ReportesController::class,'generarReporteExistenciaxMarca']);
        Route::post('inventario/marcas/importar-excel', 'importExcel');
    });

    //Rutas Marcas Fin

    /*    //Rutas Rubros
        Route::post('rubro/obtener',[RubrosController::class,'obtener']);
        Route::get('rubro/obtener-rubro-todos',[RubrosController::class,'obtenerTodos']);
        Route::post('rubro/obtener-rubro',[RubrosController::class,'obtenerRubro']);
        Route::post('rubro/registrar',[RubrosController::class,'registrar']);
        Route::put('rubro/actualizar',[RubrosController::class,'actualizar']);*/

    /*    Route::put('unidad-medida/activar',[UnidadMedidaController::class,'activar']);
        Route::put('unidad-medida/desactivar',[UnidadMedidaController::class,'desactivar']);
       Route::get('unidad-medida/reporte/{ext}',[ReportesController::class,'generarReporteUnidadesMedida']);*/

    //Rutas Rubros fin


    /* //Rutas SubRubros
     Route::post('subrubro/obtener',[SubRubrosController::class,'obtener']);
     Route::get('subrubro/obtener-subrubro-todos',[SubRubrosController::class,'obtenerTodos']);
     Route::post('subrubro/obtener-subrubro',[SubRubrosController::class,'obtenerSubRubro']);
     Route::post('subrubro/registrar',[SubRubrosController::class,'registrar']);
     Route::put('subrubro/actualizar',[SubRubrosController::class,'actualizar']);
     Route::post('subrubro/obtener-rubro-subrubro', [SubRubrosController::class,'obtenerRubrosSubrubro']);*/
    /*    Route::put('unidad-medida/activar',[UnidadMedidaController::class,'activar']);
        Route::put('unidad-medida/desactivar',[UnidadMedidaController::class,'desactivar']);
       Route::get('unidad-medida/reporte/{ext}',[ReportesController::class,'generarReporteUnidadesMedida']);*/

    //Rutas SubRubros fin

    //Rutas Configuracion comprabantes inventario
    Route::post('inventario/obtener-configuracion', [ConfiguracionInventarioController::class, 'obtener']);
    Route::put('inventario/actualizar-configuracion', [ConfiguracionInventarioController::class, 'actualizar']);
    //Rutas Configuracion comprobantes inventario fin

    // Rutas Salidas
    Route::post('salidas/obtener', [SalidasController::class, 'obtener']);
    Route::post('salidas/obtener-salida', [SalidasController::class, 'obtenerSalida']);
    Route::post('salidas/nueva', [SalidasController::class, 'nueva']);
    Route::post('salidas/registrar', [SalidasController::class, 'registrar']);
    Route::post('salidas/guardar', [SalidasController::class, 'guardarSalida']);
    Route::post('salidas/registrar-manual', [SalidasController::class, 'registrarSalidaManual']);
    Route::put('salidas/anular', [SalidasController::class, 'anular']);
    Route::post('salidas/registrar-traslado', [SalidasController::class, 'registrarTraslado']);
    Route::post('salidas/registrar-devolucion', [SalidasController::class, 'registrarDevolucion']);
    Route::get('salidas/reporte/{ext}/{id_salida}', [SalidasController::class, 'reporteSalida']);

    Route::put('salidas/despachar', [SalidasController::class, 'despachar']);

    Route::post('salidas/crear-salida-devolucion', [SalidasController::class, 'crearSalidaPorDevolucion']);
    Route::get('salidas/obtener-numero-salida', [SalidasController::class, 'obtenerNumeroSalida']);
    Route::post('salidas/obtener-salida-por-codigo', [SalidasController::class, 'obtenerSalidaPorCodigo']);
    /*
        Route::post('salidas/reporte', 'InventarioSalidasController@reporte');
        Route::post('entradas/reporte', 'InventarioEntradasController@reporte');*/

    //Rutas Facturación
    //Rutas Vendedores
    Route::post('ventas/vendedores/obtener', [VendedoresController::class, 'obtener']);
    Route::post('ventas/vendedores/obtener-vendedor', [VendedoresController::class, 'obtenerVendedor']);
    Route::get('ventas/vendedores/buscar', [VendedoresController::class, 'buscar']);
    Route::post('ventas/vendedores/registrar', [VendedoresController::class, 'registrar']);
    Route::put('ventas/vendedores/actualizar', [VendedoresController::class, 'actualizar']);
    Route::put('ventas/vendedores/activar', [VendedoresController::class, 'activar']);
    Route::put('ventas/vendedores/desactivar', [VendedoresController::class, 'desactivar']);
    Route::get('ventas/vendedores/reporte/{ext}', [ReportesCjaBncoController::class, 'generarReporteVendedores']);
    //Rutas Vendedores Fin

    //Rutas tipos clientes
    Route::post('ventas/tipo-cliente/obtener', [TipoClienteController::class, 'obtener']);
    Route::get('ventas/tipo-cliente/obtener-todos', [TipoClienteController::class, 'obtenerTodos']);
    Route::post('ventas/tipo-cliente/obtener-tipo-cliente', [TipoClienteController::class, 'obtenerTipoCliente']);
    Route::post('ventas/tipo-cliente/registrar', [TipoClienteController::class, 'registrar']);
    Route::put('ventas/tipo-cliente/actualizar', [TipoClienteController::class, 'actualizar']);
    Route::put('ventas/tipo-cliente/activar', [TipoClienteController::class, 'activar']);
    Route::put('ventas/tipo-cliente/desactivar', [TipoClienteController::class, 'desactivar']);
    Route::get('ventas/tipo-cliente/reporte/{ext}', [ReportesCjaBncoController::class, 'generarReporteTipoClientes']);
    //Rutas tipos clientes FIn

    //Rutas Bancos
    Route::post('cajabanco/bancos/obtener-bancos', [BancosController::class, 'obtenerBancos']);
    Route::get('cajabanco/bancos/obtener-bancos-todos', [BancosController::class, 'obtenerTodosBancos']);
    Route::post('cajabanco/bancos/obtener-banco', [BancosController::class, 'obtenerBanco']);
    Route::post('cajabanco/bancos/registrar', [BancosController::class, 'registrar']);
    Route::put('cajabanco/bancos/actualizar', [BancosController::class, 'actualizar']);
    Route::put('cajabanco/bancos/activar', [BancosController::class, 'activar']);
    Route::put('cajabanco/bancos/desactivar', [BancosController::class, 'desactivar']);
    Route::get('cajabanco/bancos/reporte/{ext}', [ReportesCjaBncoController::class, 'generarReporteBancos']);
    //Rutas Bancos Fin


    //Rutas kardex

    Route::post('inventario/kardex/obtener-por-producto', [MovimientosProductosController::class, 'obtenerMovimientosPorProducto']);
    Route::get('inventario/kardex-consolidado/{ext}/{id_bodega}/{f_inicial}/{f_final}', [ReportesController::class, 'generarReporteKardexConsolidado']);
    Route::get('inventario/kardex-consolidado-fecha/{ext}/{id_bodega}/{id_producto}/{f_inicial}/{f_final}', [ReportesController::class, 'generarReporteKardexConsolidadoFecha']);
    Route::get('inventario/kardex-movimiento-productos/{ext}/{id_bodega}/{id_producto}', [ReportesController::class, 'generarReporteKardexMovimientoProductos']);

    // Rutas Inventario Fisico
    Route::post('inventario/conteo-fisico/obtener', [InventarioFisicoController::class, 'obtener']);
    Route::get('inventario/conteo-fisico/nuevo', [InventarioFisicoController::class, 'nuevo']);
    Route::post('inventario/conteo-fisico/obtener-conteo', [InventarioFisicoController::class, 'obtenerConteo']);
    Route::post('inventario/conteo-fisico/registrar', [InventarioFisicoController::class, 'registrar']);
    Route::put('inventario/conteo-fisico/actualizar', [InventarioFisicoController::class, 'actualizar']);
    Route::get('inventario/conteo-fisico/reporte/{ext}/{id_inventario_fisico}', [InventarioFisicoController::class, 'reporte']);
    Route::get('inventario/conteo-fisico/reporte-comparativo/{ext}/{id_inventario_fisico}', [InventarioFisicoController::class, 'reporteComparativo']);

    //Rutas clientes
    Route::post('cuentas-por-cobrar/clientes/obtener', [ClientesController::class, 'obtener']);
    Route::get('cuentas-por-cobrar/clientes/obtener-todos', [ClientesController::class, 'obtenerTodos']);
    Route::post('cuentas-por-cobrar/clientes/obtener-cliente', [ClientesController::class, 'obtenerCliente']);
    Route::post('cuentas-por-cobrar/clientes/registrar', [ClientesController::class, 'registrar']);
    Route::post('cuentas-por-cobrar/clientes/registrar-basico', [ClientesController::class, 'registrarBasico']);
    Route::post('cuentas-por-cobrar/clientes/nuevo', [ClientesController::class, 'nuevo']);
    Route::put('cuentas-por-cobrar/clientes/actualizar', [ClientesController::class, 'actualizar']);
    Route::put('cuentas-por-cobrar/clientes/desactivar', [ClientesController::class, 'desactivar']);
    Route::put('cuentas-por-cobrar/clientes/activar', [ClientesController::class, 'activar']);
    Route::get('cuentas-por-cobrar/clientes/buscar', [ClientesController::class, 'buscar']);
    Route::get('cuentas-por-cobrar/clientes/{ext}', [ReportesCjaBncoController::class, 'generarReporteclientes']);

    // Rutas Facturas
    Route::post('inventario/facturas/obtener', [FacturasController::class, 'obtener']);
    Route::post('inventario/facturas/obtener-factura', [FacturasController::class, 'obtenerFactura']);
    Route::post('inventario/facturas/obtener-consecutivo', [FacturasController::class, 'obtenerConsecutivo']);
    Route::post('inventario/facturas/registrar', [FacturasController::class, 'registrar']);
    Route::post('inventario/facturas/enviar', [FacturasController::class, 'enviarFactura']);

    /*    Route::post('inventario/facturas/reporte/clientes', 'CajaBancoReportesController@generarReporteVentasClienteDetallado');
        Route::post('inventario/facturas/reporte/sucursales', 'CajaBancoReportesController@generarReporteVentasSucursalDetallado');

        Route::post('inventario/facturas/reporte/comisiones', 'CajaBancoReportesController@generarReporteComisiones');*/

    Route::post('inventario/facturas/cancelar', [FacturasController::class, 'anular']);
    Route::post('inventario/facturas/nueva', [FacturasController::class, 'nueva']);
    Route::post('inventario/facturas/obtener-facturas-cliente', [FacturasController::class, 'obtenerFacturasCliente']);
    Route::get('inventario/facturas/reporte/{ext}/{id_factura}', [FacturasController::class, 'reporte']);

    Route::post('inventario/facturas/obtener-configuracion', [FacturasConfiguracionController::class, 'obtener']);
    Route::put('inventario/facturas/actualizar-configuracion', [FacturasConfiguracionController::class, 'actualizar']);

    // Rutas Cuentas Cobrar
    Route::post('cuentas-cobrar/obtener', [CuentasXCobrarController::class, 'obtener']);
    Route::post('cuentas-cobrar/obtener-cc', [CuentasXCobrarController::class, 'obtenerCuentasXCobrar']);
    Route::post('cuentas-cobrar/obtener-cuentas-cliente', [CuentasXCobrarController::class, 'obtenerCuentasCliente']);
    Route::post('cuentas-cobrar/obtener-cuentas-trabajador', [CuentasXCobrarController::class, 'obtenerCuentasTrabajador']);

    // Reportes de cuentas por cobrar

    Route::post('cuentas-cobrar/Reportes/antiguedad', [CuentasXCobrarController::class, 'generarReporteAntiguedad']);
    Route::post('cuentas-cobrar/Reportes/estado-cuenta-detallado', [CuentasXCobrarController::class, 'generarReporteEstadoCuentadetallado']);
    Route::post('cuentas-cobrar/Reportes/estado-cuenta-consolidado', [CuentasXCobrarController::class, 'generarReporteEstadoCuentaConsolidado']);

    Route::post('cuentas-cobrar/Reportes/estado-cuenta-detallado-trabajador', [CuentasXCobrarController::class, 'generarReporteEstadoCuentaDetalladoEmpleado']);
    Route::post('cuentas-cobrar/Reportes/estado-cuenta-consolidado-trabajador', [CuentasXCobrarController::class, 'generarReporteEstadoCuentaConsolidadoEmpleado']);

    Route::post('cuentas-cobrar/Reportes/estado-cuenta-detallado-occ', [CuentasXCobrarController::class, 'generarReporteEstadoCuentadetalladoOCC']);
    Route::post('cuentas-cobrar/Reportes/estado-cuenta-consolidado-occ', [CuentasXCobrarController::class, 'generarReporteEstadoCuentaConsolidadoOCC']);

    Route::get('cuentas-cobrar/Reportes/recibos/{ext}/{id_recibo}', [RecibosController::class,'reporteRecibos']);

    // Importación de cuentas por cobrar

    Route::post('cuentas-cobrar/cuentasxcobrar/importar', [CuentasXCobrarController::class,'importar_datos']);
    Route::post('cuentas-cobrar/cuentasxcobrar/registrar-importacion', [CuentasXCobrarController::class, 'registrarImportacioncuentasPorCobrar']);

    // Rutas Tipos Notas Credito
    Route::post('cuentas-cobrar/tipos-notas-credito/obtener', [TiposNotaCreditoController::class,'obtener']);
    Route::get('cuentas-cobrar/tipos-notas-credito/obtener-todos', [TiposNotaCreditoController::class, 'obtenerTodos']);
    Route::post('cuentas-cobrar/tipos-notas-credito/obtener-tipo-nc', [TiposNotaCreditoController::class, 'obtenerTipoNC']);
    Route::post('cuentas-cobrar/tipos-notas-credito/registrar', [TiposNotaCreditoController::class, 'registrar']);
    Route::put('cuentas-cobrar/tipos-notas-credito/actualizar', [TiposNotaCreditoController::class, 'actualizar']);
    Route::put('cuentas-cobrar/tipos-notas-credito/desactivar', [TiposNotaCreditoController::class, 'desactivar']);
    Route::put('cuentas-cobrar/tipos-notas-credito/activar', [TiposNotaCreditoController::class, 'activar']);
    Route::post('cuentas-cobrar/tipos-notas-credito/nuevo', [TiposNotaCreditoController::class, 'nuevo']);

    // Rutas Recibos oficiales caja
    Route::post('cuentas-cobrar/roc/obtener', [RecibosController::class, 'obtener']);
    Route::post('cuentas-cobrar/roc/obtener-recibo', [RecibosController::class, 'obtenerRecibo']);
    Route::post('cuentas-cobrar/roc/registrar', [RecibosController::class, 'registrar']);
    Route::post('cuentas-cobrar/roc/empleado/registrar', [RecibosController::class, 'registrarROCTrabajador']);
    Route::post('cuentas-cobrar/roc/nuevo', [RecibosController::class, 'nuevo']);
    Route::post('cuentas-cobrar/roc/reporte', [RecibosController::class, 'generarReporte']);

    // Rutas Tipos Notas Debito
    Route::post('cuentas-cobrar/tipos-notas-debito/obtener', [TiposNotaDebitoController::class, 'obtener']);
    Route::get('cuentas-cobrar/tipos-notas-debito/obtener-todos', [TiposNotaDebitoController::class, 'obtenerTodos']);
    Route::post('cuentas-cobrar/tipos-notas-debito/obtener-tipo-nc', [TiposNotaDebitoController::class, 'obtenerTipoND']);
    Route::post('cuentas-cobrar/tipos-notas-debito/registrar', [TiposNotaDebitoController::class, 'registrar']);
    Route::put('cuentas-cobrar/tipos-notas-debito/actualizar', [TiposNotaDebitoController::class, 'actualizar']);
    Route::put('cuentas-cobrar/tipos-notas-debito/desactivar', [TiposNotaDebitoController::class, 'desactivar']);
    Route::put('cuentas-cobrar/tipos-notas-debito/activar', [TiposNotaDebitoController::class, 'activar']);
    Route::post('cuentas-cobrar/tipos-notas-debito/nuevo', [TiposNotaDebitoController::class, 'nuevo']);

    // Rutas Notas de Credito
    Route::post('cuentas-cobrar/notas-credito/obtener', [NotaCreditoController::class, 'obtener']);
    Route::post('cuentas-cobrar/notas-credito/obtener-nota-credito', [NotaCreditoController::class, 'obtenerNotaCredito']);
    Route::post('cuentas-cobrar/notas-credito/registrar', [NotaCreditoController::class, 'registrar']);
    Route::post('cuentas-cobrar/notas-credito/nueva', [NotaCreditoController::class, 'nueva']);
    Route::post('cuentas-cobrar/notas-credito/reporte', [NotaCreditoController::class, 'generarReporte']);


    // Rutas Notas de Debito
    Route::post('cuentas-cobrar/notas-debito/obtener', [NotaDebitoController::class, 'obtener']);
    Route::post('cuentas-cobrar/notas-debito/obtener-nota-debito', [NotaDebitoController::class, 'obtenerNotaDebito']);
    Route::post('cuentas-cobrar/notas-debito/registrar', [NotaDebitoController::class, 'registrar']);
    Route::post('cuentas-cobrar/notas-debito/nueva', [NotaDebitoController::class, 'nueva']);
    Route::post('cuentas-cobrar/notas-debito/reporte', [NotaDebitoController::class, 'generarReporte']);

    // Rutas Otras cuentas por cobrar - Catalogo auxiliares
    Route::post('cuentas-cobrar/auxiliares/obtener', [CatOtrasCuentasController::class, 'obtener']);
    Route::get('cuentas-cobrar/auxiliares/obtener-todos', [CatOtrasCuentasController::class, 'obtenerTodos']);
    Route::post('cuentas-cobrar/auxiliares/obtener-auxiliar-cxc', [CatOtrasCuentasController::class, 'obtenerOCuentasCobrar']);
    Route::post('cuentas-cobrar/auxiliares/registrar', [CatOtrasCuentasController::class, 'registrar']);
    Route::put('cuentas-cobrar/auxiliares/actualizar', [CatOtrasCuentasController::class, 'actualizar']);
    Route::put('cuentas-cobrar/auxiliares/desactivar', [CatOtrasCuentasController::class, 'desactivar']);
    Route::put('cuentas-cobrar/auxiliares/activar', [CatOtrasCuentasController::class, 'activar']);
    Route::post('cuentas-cobrar/auxiliares/nuevo', [CatOtrasCuentasController::class, 'nuevo']);

    #endregion

    #region Cuentas por pagar
    // Autor: Rommel Betancourt

    // Rutas Cuentas Pagar
/*    Route::post('cuentas-pagar/obtener', [CuentasXPagarController::class, 'obtener']);
    Route::post('cuentas-pagar/obtener-cp', [CuentasXPagarController::class, 'obtenerCuentasXPagar']);
    Route::post('cuentas-pagar/obtener-cuentas-proveedor', [CuentasXPagarController::class, 'obtenerCuentasProveedor']);
    Route::get('cuentas-pagar/send/email', [CuentasXPagarController::class, 'mail']);*/

    // Rutas para Catalogo auxiliares

    Route::post('cuentas-pagar/auxiliares/obtener',[CatOtrasCuentasControllerCP::class, 'obtener']);
    Route::get('cuentas-pagar/auxiliares/obtener-todos',[CatOtrasCuentasControllerCP::class, 'obtenerTodos']);
    Route::post('cuentas-pagar/auxiliares/obtener-auxiliar-cxp',[CatOtrasCuentasControllerCP::class, 'obtenerOCuentasPagar']);
    Route::post('cuentas-pagar/auxiliares/registrar',[CatOtrasCuentasControllerCP::class, 'registrar']);
    Route::put('cuentas-pagar/auxiliares/actualizar',[CatOtrasCuentasControllerCP::class, 'actualizar']);
    Route::put('cuentas-pagar/auxiliares/activar',[CatOtrasCuentasControllerCP::class, 'activar']);
    Route::put('cuentas-pagar/auxiliares/desactivar',[CatOtrasCuentasControllerCP::class, 'desactivar']);
    Route::post('cuentas-pagar/auxiliares/nuevo',[CatOtrasCuentasControllerCP::class, 'nuevo']);


    // Rutas para tipos notas crédito proveedores

    Route::post('cuentas-pagar/tipos-notas-credito/obtener', [TiposNotaCreditoControllerCP::class, 'obtener']);
    Route::get('cuentas-pagar/tipos-notas-credito/obtener-todos', [TiposNotaCreditoControllerCP::class, 'obtenerTodos']);
    Route::post('cuentas-pagar/tipos-notas-credito/obtener-tipo-nc', [TiposNotaCreditoControllerCP::class, 'obtenerTipoNC']);
    Route::post('cuentas-pagar/tipos-notas-credito/registrar', [TiposNotaCreditoControllerCP::class, 'registrar']);
    Route::put('cuentas-pagar/tipos-notas-credito/actualizar', [TiposNotaCreditoControllerCP::class, 'actualizar']);
    Route::put('cuentas-pagar/tipos-notas-credito/desactivar', [TiposNotaCreditoControllerCP::class, 'desactivar']);
    Route::put('cuentas-pagar/tipos-notas-credito/activar', [TiposNotaCreditoControllerCP::class, 'activar']);
    Route::post('cuentas-pagar/tipos-notas-credito/nuevo', [TiposNotaCreditoControllerCP::class, 'nuevo']);

    //Rutas para tipos notas débito proveedores

    Route::post('cuentas-pagar/tipos-notas-debito/obtener', [TiposNotaDebitoControllerCP::class, '@obtener']);
    Route::get('cuentas-pagar/tipos-notas-debito/obtener-todos', [TiposNotaDebitoControllerCP::class, 'obtenerTodos']);
    Route::post('cuentas-pagar/tipos-notas-debito/obtener-tipo-nd', [TiposNotaDebitoControllerCP::class, 'obtenerTipoND']);
    Route::post('cuentas-pagar/tipos-notas-debito/registrar', [TiposNotaDebitoControllerCP::class, 'registrar']);
    Route::put('cuentas-pagar/tipos-notas-debito/actualizar', [TiposNotaDebitoControllerCP::class, 'actualizar']);
    Route::put('cuentas-pagar/tipos-notas-debito/desactivar', [TiposNotaDebitoControllerCP::class, 'desactivar']);
    Route::put('cuentas-pagar/tipos-notas-debito/activar', [TiposNotaDebitoControllerCP::class, 'activar']);
    Route::post('cuentas-pagar/tipos-notas-debito/nuevo', [TiposNotaDebitoControllerCP::class, 'nuevo']);

    // Rutas Notas de Credito Proveedores
    Route::post('cuentas-pagar/notas-credito/obtener', [NotaCreditoControllerCP::class, 'obtener']);
    Route::post('cuentas-pagar/notas-credito/obtener-nota-credito', [NotaCreditoControllerCP::class, 'obtenerNotaCredito']);
    Route::post('cuentas-pagar/notas-credito/registrar', [NotaCreditoControllerCP::class, 'registrar']);
    Route::post('cuentas-pagar/notas-credito/nueva', [NotaCreditoControllerCP::class, 'nueva']);

    // Rutas Notas de Debito Proveedores
    Route::post('cuentas-pagar/notas-debito/obtener', [NotaDebitoControllerCP::class, 'obtener']);
    Route::post('cuentas-pagar/notas-debito/obtener-nota-debito', [NotaDebitoControllerCP::class, 'obtenerNotaDebito']);
    Route::post('cuentas-pagar/notas-debito/registrar', [NotaDebitoControllerCP::class, 'registrar']);
    Route::post('cuentas-pagar/notas-debito/nueva', [NotaDebitoControllerCP::class, 'nueva']);

    // Reportes

    Route::post('cuentas-pagar/reportes/antiguedad', [CuentasXPagarController::class, 'generarReporteAntiguedad']);
    Route::post('cuentas-pagar/reportes/estado-cuenta-detallado', [CuentasXPagarController::class, 'generarReporteEstadoCuentadetallado']);
    Route::post('cuentas-pagar/reportes/estado-cuenta-consolidado', [CuentasXPagarController::class, 'generarReporteEstadoCuentaConsolidado']);

    #endregion

    // Rutas Proformas
    Route::post('cajabanco/proformas/obtener', [ProformasController::class, 'obtener']);
    Route::post('cajabanco/proformas/obtener-factura', [ProformasController::class, 'obtenerProforma']);
    Route::post('cajabanco/proformas/obtener-detalle-proforma', [ProformasController::class, 'obtenerDetalleProforma']);
    Route::post('cajabanco/proformas/registrar', [ProformasController::class, 'registrar']);
    Route::put('cajabanco/proformas/actualizar', [ProformasController::class, 'actualizar']);
    Route::put('cajabanco/proformas/archivar', [ProformasController::class, 'archivar']);
    Route::put('cajabanco/proformas/anular', [ProformasController::class, 'anular']);
    Route::get('cajabanco/proformas/reporte/{ext}/{id_proforma}', [ProformasController::class, 'reporte']);
    Route::post('cajabanco/proformas/nueva', [ProformasController::class, 'nueva']);
    Route::get('cajabanco/proformas/buscar', [ProformasController::class, 'buscar']);

    //Rutas proformmas seguimiento
    Route::post('cajabanco/proformas-seguimiento/obtener', [ProformaSeguimientoController::class, 'obtener']);
    Route::post('cajabanco/proformas-seguimiento/obtener-seguimiento', [ProformaSeguimientoController::class, 'obtenerSeguimiento']);
    Route::post('cajabanco/proformas-seguimiento/registrar', [ProformaSeguimientoController::class, 'registrar']);
//    Route::get('cajabanco/proformas-seguimiento/reporte/{ext}/{id_factura}', 'CajaBancoProformaSeguimientoController@reporte');
    Route::post('cajabanco/proformas-seguimiento/nueva', [ProformaSeguimientoController::class, 'nueva']);

    // Rutas Recibos oficiales caja
    Route::post('cuentas-cobrar/roc/obtener', [RecibosController::class, 'obtener']);
    Route::post('cuentas-cobrar/roc/obtener-recibo', [RecibosController::class, 'obtenerRecibo']);
    Route::post('cuentas-cobrar/roc/registrar', [RecibosController::class, 'registrar']);
    Route::post('cuentas-cobrar/roc/empleado/registrar', [RecibosController::class, 'registrarROCTrabajador']);
    Route::post('cuentas-cobrar/roc/nuevo', [RecibosController::class, 'nuevo']);
    Route::post('cuentas-cobrar/roc/obtener-recibos-cliente', [RecibosController::class, 'obtenerRecibosCliente']);
    Route::post('cuentas-cobrar/roc/cancelar', [RecibosController::class, 'anular']);

    //Rutas Solicitudes pagos
    Route::post('tesoreria/solicitudespagos/obtener', [SolicitudesPagoController::class, 'obtener']);
    Route::post('tesoreria/solicitudespagos/obtener-solicitudes-aprobacion', [SolicitudesPagoController::class, 'obtenerSolicitudesAprobacion']);
    Route::post('tesoreria/solicitudespagos/obtener-solicitud', [SolicitudesPagoController::class, 'obtenerSolicitud']);
    Route::post('tesoreria/solicitudespagos/registrar', [SolicitudesPagoController::class, 'registrar']);
    Route::put('tesoreria/solicitudespagos/revisar', [SolicitudesPagoController::class, 'revisar']);
    Route::put('tesoreria/solicitudespagos/contabilizar-solicitud-pago', [SolicitudesPagoController::class, 'contabilizarSolicitudPago']);
    Route::put('tesoreria/solicitudespagos/aprobar', [SolicitudesPagoController::class, 'aprobar']);
    Route::put('tesoreria/solicitudespagos/cambiar-estado-solicitud-pago', [SolicitudesPagoController::class, 'cambiarEstadoSolicitudPago']);
    Route::put('tesoreria/solicitudespagos/cambiar-estado', [SolicitudesPagoController::class, 'cambiarEstado']);
    Route::put('tesoreria/solicitudespagos/entregar', [SolicitudesPagoController::class, 'entregarComprobante']);
    Route::get('tesoreria/solicitudespagos/reporte/{id_solicitud_pago}/{ext}', [SolicitudesPagoController::class, 'generarReporte']);
    Route::post('tesoreria/solicitudespagos/reporte-cheque', [SolicitudesPagoController::class, 'generarReporteCheque']);
    Route::post('tesoreria/solicitudespagos/nueva-contabilizacion', [SolicitudesPagoController::class, 'nuevaContabilizacion']);
    Route::put('tesoreria/solicitudespagos/emitir', [SolicitudesPagoController::class, 'emitir']);
    Route::put('tesoreria/solicitudespagos/anular', [SolicitudesPagoController::class, 'anular']);
    Route::post('tesoreria/solicitudespagos/nueva', [SolicitudesPagoController::class, 'nueva']);

    //Rutas cajachica-Normativas
    Route::post('tesoreria/cajachica/normativas/obtener', [NormativaController::class, 'obtener']);
    Route::get('tesoreria/cajachica/normativas/obtener-todas', [NormativaController::class, 'obtenerTodas']);
    Route::post('tesoreria/cajachica/normativas/obtener-normativa', [NormativaController::class, 'obtenerNormativa']);
    Route::post('tesoreria/cajachica/normativas/registrar', [NormativaController::class, 'registrar']);
    Route::put('tesoreria/cajachica/normativas/actualizar', [NormativaController::class, 'actualizar']);
    Route::put('tesoreria/cajachica/normativas/desactivar', [NormativaController::class, 'desactivar']);
    Route::put('tesoreria/cajachica/normativas/activar', [NormativaController::class, 'activar']);
    Route::get('tesoreria/cajachica/normativas/reporte/{ext}', [NormativaController::class, 'generarReporte']);

    //Viaticos
    Route::post('cajabanco/viaticos/obtener-viaticos', [ViaticosController::class, 'obtenerViaticos']);
    Route::post('cajabanco/viaticos/obtener-viatico', [ViaticosController::class, 'obtenerViatico']);
    Route::post('cajabanco/viaticos/nuevo', [ViaticosController::class, 'nuevo']);
    Route::post('cajabanco/viaticos/registrar', [ViaticosController::class, 'registrar']);
    Route::put('cajabanco/viaticos/actualizar', [ViaticosController::class, 'actualizar']);
    Route::put('cajabanco/viaticos/activar', [ViaticosController::class, 'activar']);
    Route::put('cajabanco/viaticos/desactivar', [ViaticosController::class, 'desactivar']);
    Route::put('cajabanco/viaticos/reporte/{ext}', [ViaticosController::class, 'generarReporte']);

    //Via pagos
    Route::post('cajabanco/via-pagos/obtener-viapagos', [ViapagosController::class, 'obtenerViaPagos']);
    Route::post('cajabanco/via-pagos/obtener-viapago', [ViapagosController::class, 'obtenerViaPago']);
    Route::post('cajabanco/via-pagos/nuevo', [ViapagosController::class, 'nuevo']);
    Route::post('cajabanco/via-pagos/registrar', [ViapagosController::class, 'registrar']);
    Route::put('cajabanco/via-pagos/actualizar', [ViapagosController::class, 'actualizar']);
    Route::put('cajabanco/via-pagos/activar', [ViapagosController::class, 'activar']);
    Route::put('cajabanco/via-pagos/desactivar', [ViapagosController::class, 'desactivar']);
    Route::put('cajabanco/via-pagos/reporte/{ext}', [ViapagosController::class, 'generarReporte']);

    //Afectaciones Caja banco
    Route::post('inventario/facturas/afectaciones/obtener', [AfectacionesController::class, 'obtener']);
    Route::post('inventario/facturas/afectaciones/nueva', [AfectacionesController::class, 'nuevo']);
    Route::get('inventario/facturas/afectaciones/obtener-todos', [AfectacionesController::class, 'obtenerTodos']);
    Route::post('inventario/facturas/afectaciones/obtener-afectacion', [AfectacionesController::class, 'obtenerAfectacion']);
    Route::post('inventario/facturas/afectaciones/registrar', [AfectacionesController::class, 'registrar']);
    Route::put('inventario/facturas/afectaciones/actualizar', [AfectacionesController::class, 'actualizar']);
    Route::put('inventario/facturas/afectaciones/desactivar', [AfectacionesController::class, 'desactivar']);
    Route::put('inventario/facturas/afectaciones/activar', [AfectacionesController::class, 'activar']);

    //Rutas Arqueo caja
    Route::post('cajabanco/arqueo/obtener-datos', [ArqueoCajaController::class, 'obtenerDatosArqueo']);
    Route::post('cajabanco/arqueo/obtener-arqueo', [ArqueoCajaController::class, 'obtenerArqueoEspecifico']);
    Route::post('cajabanco/arqueo/obtener', [ArqueoCajaController::class, 'obtenerArqueos']);
    Route::post('cajabanco/arqueo/nuevo', [ArqueoCajaController::class, 'nuevo']);
    Route::post('cajabanco/arqueo/registrar', [ArqueoCajaController::class, 'registrar']);
    Route::put('cajabanco/arqueo/actualizar', [ArqueoCajaController::class, 'actualizar']);
    Route::post('cajabanco/arqueo/anular', [ArqueoCajaController::class, 'anular']);
    Route::get('cajabanco/arqueo/reporte/{ext}/{id_arqueo}', [ArqueoCajaController::class, 'generarReporteArqueo']);
    Route::post('cajabanco/arqueo/obtener-facturas-tt', [ArqueoCajaController::class, 'obtenerFacturasTT']);
    Route::put('cajabanco/arqueo/actualizar-metodos-pago', [ArqueoCajaController::class, 'actualizarMetodosPago']);

    // Rutas Grupos Activos
/*    Route::post('activosfijos/grupos/obtener', [GruposController::class, 'obtener']);
    Route::get('activosfijos/grupos/obtener-todos', [GruposController::class, 'obtenerTodos']);
    Route::post('activosfijos/grupos/obtener-grupo', [GruposController::class, 'obtenerGrupo']);
    Route::post('activosfijos/grupos/registrar', [GruposController::class, 'registrar']);
    Route::put('activosfijos/grupos/actualizar', [GruposController::class, 'actualizar']);*/
    /*    Route::put('activosfijos/grupos/desactivar', [GruposController::class,'desactivar']);
        Route::put('activosfijos/grupos/activar', [GruposController::class,'activar']);*/


    //Rutas Grupo
    Route::post('grupo/obtener', [GruposController::class, 'obtener']);
    Route::get('grupo/obtener-grupo-todos', [GruposController::class, 'obtenerTodos']);
    Route::post('grupo/obtener-grupo', [GruposController::class, 'obtenerGrupo']);
    Route::post('grupo/registrar', [GruposController::class, 'registrar']);
    Route::put('grupo/actualizar', [GruposController::class, 'actualizar']);


    //Rutas SubGrupos
    Route::post('subgrupo/obtener', [SubGruposController::class, 'obtener']);
    Route::get('subgrupo/obtener-subgrupo-todos', [SubGruposController::class, 'obtenerTodos']);
    Route::post('subgrupo/obtener-subgrupo', [SubGruposController::class, 'obtenerSubgrupo']);
    Route::post('subgrupo/registrar', [SubGruposController::class, 'registrar']);
    Route::put('subgrupo/actualizar', [SubGruposController::class, 'actualizar']);
    Route::post('subgrupo/obtener-grupo-subgrupo', [SubGruposController::class, 'obtenerGruposSubgrupos']);
    /*    Route::put('unidad-medida/activar',[UnidadMedidaController::class,'activar']);
        Route::put('unidad-medida/desactivar',[UnidadMedidaController::class,'desactivar']);
       Route::get('unidad-medida/reporte/{ext}',[ReportesNominaController::class,'generarReporteUnidadesMedida']);*/
    // Rutas Propietario Activos
    Route::post('activosfijos/propietarios/obtener', [PropietariosController::class, 'obtener']);
    Route::get('activosfijos/propietarios/obtener-todos', [PropietariosController::class, 'obtenerTodos']);
    Route::post('activosfijos/propietarios/obtener-propietario', [PropietariosController::class, 'obtenerPropietario']);
    Route::post('activosfijos/propietarios/registrar', [PropietariosController::class, 'registrar']);
    Route::put('activosfijos/propietarios/actualizar', [PropietariosController::class, 'actualizar']);
    Route::put('activosfijos/propietarios/desactivar', [PropietariosController::class, 'desactivar']);
    Route::put('activosfijos/propietarios/activar', [PropietariosController::class, 'activar']);

    // Rutas Tipos Activos
    Route::post('activosfijos/tipos/obtener', [TiposActivosController::class, 'obtener']);
    Route::get('activosfijos/tipos/obtener-todos', [TiposActivosController::class, 'obtenerTodos']);
    Route::post('activosfijos/tipos/obtener-tipo', [TiposActivosController::class, 'obtenerTipo']);
    Route::post('activosfijos/tipos/registrar', [TiposActivosController::class, 'registrar']);
    Route::put('activosfijos/tipos/actualizar', [TiposActivosController::class, 'actualizar']);
    Route::put('activosfijos/tipos/desactivar', [TiposActivosController::class, 'desactivar']);
    Route::put('activosfijos/tipos/activar', [TiposActivosController::class, 'activar']);

    // Rutas Colores Activos
    Route::post('activosfijos/colores/obtener', [ColoresController::class, 'obtener']);
    Route::get('activosfijos/colores/obtener-todos', [ColoresController::class, 'obtenerTodos']);
    Route::post('activosfijos/colores/obtener-color', [ColoresController::class, 'obtenerColor']);
    Route::post('activosfijos/colores/registrar', [ColoresController::class, 'registrar']);
    Route::put('activosfijos/colores/actualizar', [ColoresController::class, 'actualizar']);
    Route::put('activosfijos/colores/desactivar', [ColoresController::class, 'desactivar']);
    Route::put('activosfijos/colores/activar', [ColoresController::class, 'activar']);

    // Rutas Activos Fijos
    Route::post('activosfijos/activos/obtener', [ActivosController::class, 'obtener']);
    Route::post('activosfijos/activos/obtener-solicitud-autorizacion', [ActivosController::class, 'obtenerSolicitudAutorizacion']);
    Route::post('activosfijos/activos/obtener-por-trabajador', [ActivosController::class, 'obtenerActivosTrabajador']);
    Route::post('activosfijos/activos/obtener-por-sucursal', [ActivosController::class, 'obtenerActivosSucursal']);
    Route::post('activosfijos/activos/nuevo-cierre', [ActivosController::class, 'nuevoCierre']);
    Route::post('activosfijos/activos/nuevo', [ActivosController::class, 'nuevo']);
    Route::post('activosfijos/activos/obtener-activo-fijo', [ActivosController::class, 'obtenerActivoFijo']);
    Route::post('activosfijos/activos/registrar', [ActivosController::class, 'registrar']);
    Route::put('activosfijos/activos/actualizar', [ActivosController::class, 'actualizar']);
    Route::put('activosfijos/activos/reasignar', [ActivosController::class, 'reasignar']);
    Route::put('activosfijos/activos/revaluar', [ActivosController::class, 'revaluar']);
    Route::put('activosfijos/activos/reasignar-lote', [ActivosController::class, 'reasignarLote']);
    Route::put('activosfijos/activos/generar-cierre', [ActivosController::class, 'generarCierre']);
    Route::put('activosfijos/activos/desactivar', [ActivosController::class, 'desactivar']);
    Route::put('activosfijos/activos/activar', [ActivosController::class, 'activar']);
    Route::post('activosfijos/obtener-ubicacion-activo', [ActivosController::class, 'obtenerUbicacionActivo']);
    Route::put('activosfijos/activos/cambiar-estado', [ActivosController::class, 'cambiarEstadoSolicitudBaja']);
    Route::post('activosfijos/activos/obtener-cierres', [ActivosController::class, 'obtenerCierres']);
    Route::get('activosfijos/activos/reporte/{ext}', [ActivosController::class, 'generarReporte']);
    Route::post('activosfijos/activos/obtener-traslados', [TrasladosController::class, 'obtenerTraslados']);
    Route::post('activosfijos/activos/obtener-cuentas-activos', [CuentasController::class, 'obtenerCuentasActivos']);
    Route::put('activosfijos/activos/actualizar-cuentas-activos', [CuentasController::class, 'actualizar']);
    Route::post('activosfijos/altas-activos/reporte', [ActivosController::class, 'generarReporteAltaActivo']);
    Route::post('activosfijos/bajas-activos/reporte', [ActivosController::class, 'generarReporteBajaActivo']);
    Route::post('activosfijos/activos-depreciandose/reporte', [ActivosController::class, 'generarReporteActivoDepreciandose']);
    Route::post('activosfijos/activos-depreciado-uso/reporte', [ActivosController::class, 'generarReporteActivoDepreciadoEnUso']);
    Route::post('activosfijos/traslados-activo/reporte', [ActivosController::class, 'generarReporteTrasladosActivo']);
    Route::post('activosfijos/bienes-depreciados/reporte', [ActivosController::class, 'generarReporteBienesDepreciados']);
    Route::post('activosfijos/valor-adquisicion/reporte', [ActivosController::class, 'generarReporteAdquisicionActivos']);
    Route::post('activosfijos/historia-compra/reporte', [ActivosController::class, 'generarReporteAdquisicionActivos']);
    Route::post('activosfijos/vida-util/reporte', [ActivosController::class, 'generarReporteVidaUtil']);
    Route::post('activosfijos/general-activos-tipo/reporte', [ActivosController::class, 'generarReporteGeneralActivo']);
    Route::post('activosfijos/acta-entrega/reporte', [ActivosController::class, 'generarActaEntrega']);
    Route::post(' activosfijos/reporte/sticker', [ActivosController::class, 'generarSticker']);

    //Rutas CRM

    //Origenes
    Route::post('crm/origen-prospecto/obtener', [OrigenesLeadsController::class, 'obtener']);
    Route::post('crm/origen-prospecto/obtener-origen-lead', [OrigenesLeadsController::class, 'obtenerOrigenLead']);
    Route::post('crm/origen-prospecto/registrar', [OrigenesLeadsController::class, 'registrar']);
    Route::put('crm/origen-prospecto/actualizar', [OrigenesLeadsController::class, 'actualizar']);
    Route::put('crm/origen-prospecto/activar', [OrigenesLeadsController::class, 'activar']);
    Route::put('crm/origen-prospecto/desactivar', [OrigenesLeadsController::class, 'desactivar']);
    Route::get('crm/origen-prospecto/reporte', [OrigenesLeadsController::class, 'generarReporte']);

    //Clasificacion llamada
    Route::post('crm/clasificacion-llamada/obtener', [ClasificacionLlamadasController::class, 'obtener']);
    Route::post('crm/clasificacion-llamada/obtener-clasificacion-llamada', [ClasificacionLlamadasController::class, 'obtenerClasifiacionLlamada']);
    Route::post('crm/clasificacion-llamada/registrar', [ClasificacionLlamadasController::class, 'registrar']);
    Route::put('crm/clasificacion-llamada/actualizar', [ClasificacionLlamadasController::class, 'actualizar']);
    Route::put('crm/clasificacion-llamada/activar', [ClasificacionLlamadasController::class, 'activar']);
    Route::put('crm/clasificacion-llamada/desactivar', [ClasificacionLlamadasController::class, 'desactivar']);
    Route::get('crm/clasificacion-llamada/reporte', [ClasificacionLlamadasController::class, 'generarReporte']);

    //Giros de negocio
    Route::post('crm/giro-negocio/obtener', [GirosNegociosController::class, 'obtener']);
    Route::post('crm/giro-negocio/obtener-giro-negocio', [GirosNegociosController::class, 'obtenerGiroNegocio']);
    Route::post('crm/giro-negocio/registrar', [GirosNegociosController::class, 'registrar']);
    Route::put('crm/giro-negocio/actualizar', [GirosNegociosController::class, 'actualizar']);
    Route::put('crm/giro-negocio/activar', [GirosNegociosController::class, 'activar']);
    Route::put('crm/giro-negocio/desactivar', [GirosNegociosController::class, 'desactivar']);
    Route::put('crm/giro-negocio/reporte', [GirosNegociosController::class, 'generarReporte']);

    //Cargos
    Route::post('crm/cargo/obtener', [CargosController::class, 'obtener']);
    Route::post('crm/cargo/obtener-cargo', [CargosController::class, 'obtenerCargos']);
    Route::post('crm/cargo/registrar', [CargosController::class, 'registrar']);
    Route::put('crm/cargo/actualizar', [CargosController::class, 'actualizar']);
    Route::put('crm/cargo/activar', [CargosController::class, 'activar']);
    Route::put('crm/cargo/desactivar', [CargosController::class, 'desactivar']);

    //Servicios
    Route::post('crm/servicio/obtener', [ServiciosOferAcorController::class, 'obtener']);
    Route::post('crm/servicio/obtener-servicio', [ServiciosOferAcorController::class, 'obtenerServio_ofer_acor']);
    Route::post('crm/servicio/registrar', [ServiciosOferAcorController::class, 'registrar']);
    Route::put('crm/servicio/actualizar', [ServiciosOferAcorController::class, 'actualizar']);
    Route::put('crm/servicio/activar', [ServiciosOferAcorController::class, 'activar']);
    Route::put('crm/servicio/desactivar', [ServiciosOferAcorController::class, 'desactivar']);
    Route::get('crm/servicio/reporte', [ServiciosOferAcorController::class, 'generarReporte']);

    //Leads
    Route::post('crm/lead/obtener', [LeadsController::class, 'obtener']);
    Route::post('crm/lead/obtener-lead', [LeadsController::class, 'obtenerLead']);
    Route::post('crm/lead/obtener-servicios', [LeadsController::class, 'obtenerServicios']);
    Route::post('crm/lead/nuevo', [LeadsController::class, 'nuevo']);
    Route::post('crm/lead/registrar', [LeadsController::class, 'registrar']);
    Route::put('crm/lead/actualizar', [LeadsController::class, 'actualizar']);
    Route::put('crm/lead/activar', [LeadsController::class, 'activar']);
    Route::put('crm/lead/desactivar', [LeadsController::class, 'desactivar']);
    Route::get('crm/lead/reporte', [LeadsController::class, 'generarReporte']);
    Route::post('crm/lead/obtener-leads-por-responsable', [LeadsController::class, 'obtenerLeadsPorResponsable']);
    Route::post('crm/lead/reasignar', [LeadsController::class, 'reasignarLeads']);
    Route::post('crm/lead/registro-contacto', [LeadsController::class, 'registroContacto']);
    Route::post('crm/lead/registro-compania', [LeadsController::class, 'registroCompania']);
    Route::post('crm/lead/registro-contacto-compania', [LeadsController::class, 'registroContactoyCompania']);

    //Seguimiento Leads
    Route::post('crm/seguimiento-lead/obtener', [SeguimientoLeadsController::class, 'obtener']);
    Route::post('crm/seguimiento-lead/obtener-seguimiento', [SeguimientoLeadsController::class, 'obtenerSeguimientoLead']);
    Route::post('crm/seguimiento-lead/obtener-seguimiento-especifico', [SeguimientoLeadsController::class, 'obtenerSeguimentoEspecifico']);
    Route::post('crm/seguimiento-lead/nuevo', [SeguimientoLeadsController::class, 'nuevo']);
    Route::post('crm/seguimiento-lead/registrar', [SeguimientoLeadsController::class, 'registrar']);
    Route::put('crm/seguimiento-lead/actualizar', [SeguimientoLeadsController::class, 'actualizar']);
    Route::put('crm/seguimiento-lead/desactivar', [SeguimientoLeadsController::class, 'desactivar']);

    //Tipo contacto
    Route::post('crm/tipo_contacto/obtener-tipo-contacto', [TipoContactosControllers::class, 'obtenerTipoContacto']);
    Route::post('crm/tipo_contacto/obtener', [TipoContactosControllers::class, 'obtener']);
    Route::post('crm/tipo_contacto/nuevo', [TipoContactosControllers::class, 'nuevo']);
    Route::post('crm/tipo_contacto/registrar', [TipoContactosControllers::class, 'registrar']);
    Route::put('crm/tipo_contacto/actualizar', [TipoContactosControllers::class, 'actualizar']);
    Route::put('crm/tipo_contacto/activar', [TipoContactosControllers::class, 'activar']);
    Route::put('crm/tipo_contacto/desactivar', [TipoContactosControllers::class, 'desactivar']);
    Route::get('crm/tipo_contacto/generar/{ext}', [TipoContactosControllers::class, 'generarReporte']);


    //Clasificacion Cliente
    Route::post('crm/clasificacion_contacto/obtener', [ClasificacionContactosController::class, 'obtener']);
    Route::post('crm/clasificacion_contacto/obtener-clasificacion-contacto', [ClasificacionContactosController::class, 'obtenerClasificacionContacto']);
    Route::post('crm/clasificacion_contacto/registrar', [ClasificacionContactosController::class, 'registrar']);
    Route::put('crm/clasificacion_contacto/actualizar', [ClasificacionContactosController::class, 'actualizar']);
    Route::put('crm/clasificacion_contacto/activar', [ClasificacionContactosController::class, 'activar']);
    Route::put('crm/clasificacion_contacto/desactivar', [ClasificacionContactosController::class, 'desactivar']);
    Route::get('crm/clasificacion_contacto/reporte/{ext}', [ClasificacionContactosController::class, 'generarReporte']);

    //Medios de contacto
    Route::post('crm/medio_contacto/obtener', [MediosContactoController::class, 'obtener']);
    Route::post('crm/medio_contacto/obtener-medio_contacto', [MediosContactoController::class, 'obtenerMedioContacto']);
    Route::post('crm/medio_contacto/registrar', [MediosContactoController::class, 'registrar']);
    Route::put('crm/medio_contacto/actualizar', [MediosContactoController::class, 'actualizar']);
    Route::put('crm/medio_contacto/activar', [MediosContactoController::class, 'activar']);
    Route::put('crm/medio_contacto/desactivar', [MediosContactoController::class, 'desactivar']);
    Route::get('crm/medio_contacto/reporte', [MediosContactoController::class, 'generarReporte']);

    //Contactos
    Route::controller('CRM\ContactosController')->group(function () {
        Route::get('crm/contacto/buscar', 'buscar');
        Route::post('crm/contacto/obtener-detalles', 'obtenerDetalles');
        Route::post('crm/contacto/obtener-contacto', 'obtenerContacto');
        Route::post('crm/contacto/obtener-compania', 'obtenerCompania');
        Route::post('crm/contacto/obtener', 'obtener');
        Route::post('crm/contacto/nuevo', 'nuevo');
        Route::post('crm/contacto/registrar', 'registrar');
        Route::put('crm/contacto/actualizar', 'actualizar');
        Route::put('crm/contacto/activar', 'activar');
        Route::put('crm/contacto/desactivar', 'desactivar');
        Route::get('crm/contacto/reporte/{ext}', 'generarReporte');
    });

    //Compañia
    Route::controller('CRM\CompaniasController')->group(function () {
    Route::get('crm/compania/buscar', 'buscar');
    Route::post('crm/compania/obtener', 'obtener');
    Route::post('crm/compania/obtener-compania', 'obtenerCompania');
    Route::post('crm/compania/obtener-contacto', 'obtenerContacto');
    Route::post('crm/compania/nuevo', 'nuevo');
    Route::post('crm/compania/registrar', 'registrar');
    Route::put('crm/compania/actualizar', 'actualizar');
    Route::put('crm/compania/activar', 'activar');
    Route::put('crm/compania/desactivar', 'desactivar');
    Route::get('crm/compania/reporte/{ext}', 'generarReporte');
    });

    //Reportes Leads
    Route::get('crm/lead-seguimiento/reporte/{ext}/{f_inicial}/{f_final}', [ReportesControllerCRM::class, 'generarReporteSeguimientosRangoFecha']);
    Route::get('crm/lead-seguimiento/reporte-leads-descartados/{ext}/{f_inicial}/{f_final}', [ReportesControllerCRM::class, 'generarReporteLeadsDescartados']);
    Route::get('crm/lead-seguimiento/reporte-leads-seguimiento/{ext}/{f_inicial}/{f_final}', [ReportesControllerCRM::class, 'generarReporteLeadsSeguimiento']);

    //Fin Rutas CRM

  #region rutas Nomina

    // Rutas contratos internos
    Route::post('nomina/contrato-interno/obtener', [ContratoGeneralInternoController::class, 'obtener']);
    Route::get('nomina/contrato-interno/obtener-todas', [ContratoGeneralInternoController::class, 'obtenerTodas']);
    Route::post('nomina/contrato-interno/obtener-contrato', [ContratoGeneralInternoController::class, 'obtenerContratoGeneral']);
    Route::post('nomina/contrato-interno/registrar', [ContratoGeneralInternoController::class, 'registrar']);
    Route::put('nomina/contrato-interno/actualizar', [ContratoGeneralInternoController::class, 'actualizar']);
    Route::put('nomina/contrato-interno/desactivar', [ContratoGeneralInternoController::class, 'desactivar']);
    Route::put('nomina/contrato-interno/activar', [ContratoGeneralInternoController::class, 'activar']);
    Route::get('nomina/contrato-interno/reporte/{ext}', [ContratoGeneralInternoController::class, 'generarReporte']);

    // Rutas contratos merecedor
    Route::post('nomina/contrato-merecedor/obtener', [ContratoGeneralMerecedorController::class, 'obtener']);
    Route::get('nomina/contrato-merecedor/obtener-todas', [ContratoGeneralMerecedorController::class, 'obtenerTodas']);
    Route::post('nomina/contrato-merecedor/obtener-contrato', [ContratoGeneralMerecedorController::class, 'obtenerContratoGeneral']);
    Route::post('nomina/contrato-merecedor/registrar', [ContratoGeneralMerecedorController::class, 'registrar']);
    Route::put('nomina/contrato-merecedor/actualizar', [ContratoGeneralMerecedorController::class, 'actualizar']);
    Route::put('nomina/contrato-merecedor/desactivar', [ContratoGeneralMerecedorController::class, 'desactivar']);
    Route::put('nomina/contrato-merecedor/activar', [ContratoGeneralMerecedorController::class, 'activar']);
    Route::get('nomina/contrato-merecedor/reporte/{ext}', [ContratoGeneralMerecedorController::class, 'generarReporte']);

    // Rutas contratos solicitud
    Route::post('nomina/contrato-solicitud/obtener', [ContratoSolicitudController::class,'obtener']);
    Route::get('nomina/contrato-solicitud/obtener-todas', [ContratoSolicitudController::class,'obtenerTodas']);
    Route::post('nomina/contrato-solicitud/obtener-contrato', [ContratoSolicitudController::class,'obtenerContratoSolicitud']);
    Route::post('nomina/contrato-solicitud/registrar', [ContratoSolicitudController::class,'registrar']);
    Route::put('nomina/contrato-solicitud/actualizar', [ContratoSolicitudController::class,'actualizar']);
    Route::put('nomina/contrato-solicitud/desactivar', [ContratoSolicitudController::class,'desactivar']);
    Route::put('nomina/contrato-solicitud/activar', [ContratoSolicitudController::class,'activar']);
    Route::get('nomina/contrato-solicitud/reporte/{id_contrato_solicitud}', [ContratoSolicitudController::class,'generarReporte']);

    // Rutas ingreso deduccion
    Route::post('nomina/ingreso_deduccion/obtener-ingresos', [IngresosDeduccionesController::class,'obtenerIngresos']);
    Route::post('nomina/ingreso_deduccion/obtener-deducciones', [IngresosDeduccionesController::class,'obtenerDeducciones']);
    Route::post('nomina/ingreso_deduccion/obtener-ingresos-deducciones', [IngresosDeduccionesController::class,'obtenerIngresosDeducciones']);
    Route::get('nomina/ingreso_deduccion/obtener-todas', [IngresosDeduccionesController::class,'obtenerTodas']);
    Route::post('nomina/ingreso_deduccion/obtener-ingreso-deduccion', [IngresosDeduccionesController::class,'obtenerIngresoDeduccion']);
    Route::post('nomina/ingreso_deduccion/registrar', [IngresosDeduccionesController::class,'registrar']);
    Route::post('nomina/ingreso_deduccion/nueva', [IngresosDeduccionesController::class,'nueva']);
    Route::put('nomina/ingreso_deduccion/actualizar', [IngresosDeduccionesController::class,'actualizar']);
    Route::put('nomina/ingreso_deduccion/desactivar', [IngresosDeduccionesController::class,'desactivar']);
    Route::put('nomina/ingreso_deduccion/activar', [IngresosDeduccionesController::class,'activar']);
    Route::get('nomina/ingreso_deduccion/reporte/{ext}', [IngresosDeduccionesController::class,'generarReporte']);

// Rutas de marcadas
    Route::post('nomina/marcadas/obtener', [MarcadasController::class,'obtener']);
    Route::get('nomina/marcadas/obtener-todas', [MarcadasController::class,'obtenerTodas']);
    Route::post('nomina/marcadas/obtener-marcada', [MarcadasController::class,'obtenerMarcada']);
    Route::post('nomina/marcadas/registrar', [MarcadasController::class,'registrar']);
    Route::post('nomina/marcadas/nuevo', [MarcadasController::class,'nuevo']);
    Route::put('nomina/marcadas/actualizar', [MarcadasController::class,'actualizar']);
    Route::put('nomina/marcadas/desactivar', [MarcadasController::class,'desactivar']);
    Route::put('nomina/marcadas/activar', [MarcadasController::class,'activar']);
    Route::get('nomina/marcadas/reporte/{id_trabajador}', [MarcadasController::class,'generarReporte']);
    Route::post('nomina/marcadas/obtener-ingreso', [MarcadasController::class,'obtenerPlanilla']);

    // Rutas Cargos
    Route::post('nomina/cargos/obtener', [CargosController::class,'obtenerCargos']);
    Route::get('nomina/cargos/obtener-todos', [CargosController::class,'obtenerTodosCargos']);
    Route::post('nomina/cargos/obtener-cargo', [CargosController::class,'obtenerCargo']);
    Route::post('nomina/cargos/registrar', [CargosController::class,'registrar']);
    Route::put('nomina/cargos/actualizar', [CargosController::class,'actualizar']);
    Route::put('nomina/cargos/desactivar', [CargosController::class,'desactivar']);
    Route::put('nomina/cargos/activar', [CargosController::class,'activar']);
    Route::get('nomina/cargos/reporte/{ext}', [CargosController::class,'generarReporte']);
    // Lista de asignacion ingresos y deducciones
    Route::post('nomina/asignacion-ingreso-deduccion/obtener', [IngresosDeduccionesTrabajadoresController::class,'obtener']);
    Route::get('nomina/asignacion-ingreso-deduccion/obtener-todas', [IngresosDeduccionesTrabajadoresController::class,'obtenerTodas']);
    Route::post('nomina/asignacion-ingreso-deduccion/obtener-solicitud', [IngresosDeduccionesTrabajadoresController::class,'obtenerIngresoDeduccionTrabajador']);
    Route::post('nomina/asignacion-ingreso-deduccion/registrar', [IngresosDeduccionesTrabajadoresController::class,'registrar']);
    Route::post('nomina/asignacion-ingreso-deduccion/nuevo', [IngresosDeduccionesTrabajadoresController::class,'nuevo']);
    Route::put('nomina/asignacion-ingreso-deduccion/actualizar', [IngresosDeduccionesTrabajadoresController::class,'actualizar']);
    Route::put('nomina/asignacion-ingreso-deduccion/desactivar', [IngresosDeduccionesTrabajadoresController::class,'desactivar']);
    Route::put('nomina/asignacion-ingreso-deduccion/activar', [IngresosDeduccionesTrabajadoresController::class,'activar']);
    Route::get('nomina/asignacion-ingreso-deduccion/reporte/{id_trabajador}', [IngresosDeduccionesTrabajadoresController::class,'generarReporte']);
    Route::post('nomina/asignacion-ingreso-deduccion/obtener-ingreso', [IngresosDeduccionesTrabajadoresController::class,'obtenerPlanilla']);

// Rutas Trabajadores
    Route::post('nomina/trabajadores/obtener', [TrabajadoresController::class,'obtener']);
    Route::post('nomina/trabajadores/obtener-trabajador', [TrabajadoresController::class,'obtenerTrabajador']);
    Route::post('nomina/trabajadores/registrar', [TrabajadoresController::class,'registrar']);
    Route::put('nomina/trabajadores/actualizar', [TrabajadoresController::class,'actualizar']);
    Route::put('nomina/trabajadores/desactivar', [TrabajadoresController::class,'desactivar']);
    Route::put('nomina/trabajadores/activar', [TrabajadoresController::class,'activar']);
    Route::get('nomina/trabajadores/buscar', [TrabajadoresController::class,'buscar']);
    Route::get('nomina/trabajadores/buscar_trabajador', [TrabajadoresController::class,'buscarTrabajador']);
    Route::get('nomina/trabajadores/reporte/expediente/{id_trabajador}', [TrabajadoresController::class,'reporteExpedientePersonal']);
    // Rutas planillas controles
    Route::post('nomina/planillas-controles/obtener', [PlanillasControlesController::class, 'obtener']);
    Route::get('nomina/planillas-controles/obtener-todas', [PlanillasControlesController::class, 'obtenerTodas']);
    Route::post('nomina/planillas-controles/obtener-planilla-control', [PlanillasControlesController::class, 'obtenerPlanillaControl']);
    Route::post('nomina/planillas-controles/registrar', [PlanillasControlesController::class, 'registrar']);
    Route::post('nomina/planillas-controles/nueva', [PlanillasControlesController::class, 'nueva']);
    Route::put('nomina/planillas-controles/actualizar', [PlanillasControlesController::class, 'actualizar']);
    Route::put('nomina/planillas-controles/desactivar', [PlanillasControlesController::class, 'desactivar']);
    Route::put('nomina/planillas-controles/activar', [PlanillasControlesController::class, 'activar']);
    Route::get('nomina/planillas-controles/reporte/{ext}', [PlanillasControlesController::class, 'generarReporte']);

    // Rutas configuracion ir
    Route::post('nomina/configuracion-ir/obtener', [ConfiguracionIRController::class, 'obtener']);
    Route::get('nomina/configuracion-ir/obtener-todas', [ConfiguracionIRController::class, 'obtenerTodas']);
    Route::post('nomina/configuracion-ir/obtener-configuracion', [ConfiguracionIRController::class, 'obtenerConfiguracionIR']);
    Route::post('nomina/configuracion-ir/registrar', [ConfiguracionIRController::class, 'registrar']);
    Route::post('nomina/configuracion-ir/nueva', [ConfiguracionIRController::class, 'nueva']);
    Route::put('nomina/configuracion-ir/actualizar', [ConfiguracionIRController::class, 'actualizar']);
    Route::put('nomina/configuracion-ir/desactivar', [ConfiguracionIRController::class, 'desactivar']);
    Route::put('nomina/configuracion-ir/activar', [ConfiguracionIRController::class, 'activar']);
    Route::get('nomina/configuracion-ir/reporte/{ext}', [ConfiguracionIRController::class, 'generarReporte']);

    // Rutas nivel academico
    Route::post('nomina/nivel-academico/obtener', [NivelesAcademicosController::class,'obtener']);
    Route::get('nomina/nivel-academico/obtener-todas', [NivelesAcademicosController::class,'obtenerTodas']);
    Route::post('nomina/nivel-academico/obtener-nivel', [NivelesAcademicosController::class,'obtenerNivelAcademico']);
    Route::post('nomina/nivel-academico/registrar', [NivelesAcademicosController::class,'registrar']);
    Route::put('nomina/nivel-academico/actualizar', [NivelesAcademicosController::class,'actualizar']);
    Route::put('nomina/nivel-academico/desactivar', [NivelesAcademicosController::class,'desactivar']);
    Route::put('nomina/nivel-academico/activar', [NivelesAcademicosController::class,'activar']);

    // Rutas nivel estudio
    Route::post('nomina/nivel-estudio/obtener', [NivelesEstudiosController::class,'obtener']);
    Route::get('nomina/nivel-estudio/obtener-todas', [NivelesEstudiosController::class,'obtenerTodas']);
    Route::post('nomina/nivel-estudio/obtener-nivel', [NivelesEstudiosController::class,'obtenerNivelEstudio']);
    Route::post('nomina/nivel-estudio/registrar', [NivelesEstudiosController::class,'registrar']);
    Route::put('nomina/nivel-estudio/actualizar', [NivelesEstudiosController::class,'actualizar']);
    Route::put('nomina/nivel-estudio/desactivar', [NivelesEstudiosController::class,'desactivar']);
    Route::put('nomina/nivel-estudio/activar', [NivelesEstudiosController::class,'activar']);

    // Rutas feriados
    Route::post('nomina/feriados/obtener', [FeriadosController::class,'obtener']);
    Route::get('nomina/feriados/obtener-todas', [FeriadosController::class,'obtenerTodas']);
    Route::post('nomina/feriados/obtener-feriado', [FeriadosController::class,'obtenerFeriado']);
    Route::post('nomina/feriados/registrar', [FeriadosController::class,'registrar']);
    Route::put('nomina/feriados/actualizar', [FeriadosController::class,'actualizar']);
    Route::put('nomina/feriados/desactivar', [FeriadosController::class,'desactivar']);
    Route::put('nomina/feriados/activar', [FeriadosController::class,'activar']);

    //Rutas tipos justificaciones marcadas
    Route::post('nomina/marcadas-justificaciones/obtener', [TiposJustificacionesMarcadasController::class,'obtener']);
    Route::get('nomina/marcadas-justificaciones/obtener-todas', [TiposJustificacionesMarcadasController::class,'obtenerTodas']);
    Route::post('nomina/marcadas-justificaciones/obtener-justificacion', [TiposJustificacionesMarcadasController::class,'obtenerJustificacion']);

    // Rutas datos medicos
    Route::post('nomina/datos-medicos/obtener', [DatosMedicosController::class,'obtener']);
    Route::get('nomina/datos-medicos/obtener-todas', [DatosMedicosController::class,'obtenerTodas']);
    Route::post('nomina/datos-medicos/obtener-datos', [DatosMedicosController::class,'otenerDatoMedico']);

    // Rutas parentesco
    Route::post('nomina/parentesco/obtener', [ParentescoController::class,'obtener']);
    Route::get('nomina/parentesco/obtener-todas', [ParentescoController::class,'obtenerTodas']);
    Route::post('nomina/parentesco/obtener-datos', [ParentescoController::class,'obtenerParentesco']);

    // Rutas saldo vacacion
    Route::post('nomina/saldo-vacacion/obtener', [SaldosVacacionesController::class,'obtener']);
    Route::get('nomina/saldo-vacacion/obtener-todas', [SaldosVacacionesController::class,'obtenerTodas']);
    Route::post('nomina/saldo-vacacion/obtener-saldos', [SaldosVacacionesController::class,'obtenerSaldo']);

// Lista de solicitud vacaciones
    Route::post('nomina/solicitud-vacaciones/obtener', [SolicitudVacacionesController::class,'obtener']);
    Route::get('nomina/solicitud-vacaciones/obtener-todas', [SolicitudVacacionesController::class,'obtenerTodas']);
    Route::post('nomina/solicitud-vacaciones/obtener-solicitud', [SolicitudVacacionesController::class,'obtenerSolicitud']);
    Route::post('nomina/solicitud-vacaciones/registrar', [SolicitudVacacionesController::class,'registrar']);
    Route::post('nomina/solicitud-vacaciones/nuevo', [SolicitudVacacionesController::class,'nuevo']);
    Route::put('nomina/solicitud-vacaciones/actualizar', [SolicitudVacacionesController::class,'actualizar']);
    Route::put('nomina/solicitud-vacaciones/desactivar', [SolicitudVacacionesController::class,'desactivar']);
    Route::put('nomina/solicitud-vacaciones/activar', [SolicitudVacacionesController::class,'activar']);
    Route::get('nomina/solicitud-vacaciones/reporte/{id_trabajador}', [SolicitudVacacionesController::class,'generarReporte']);
    Route::put('nomina/solicitud-vacaciones/cambiarEstadoSolicitud', [SolicitudVacacionesController::class,'cambiarEstadoSolicitud']);

    // Rutas acto juridico
    Route::post('nomina/tipo-acto-juridico/obtener', [TipoActoJuridicoController::class,'obtener']);
    Route::get('nomina/tipo-acto-juridico/obtener-todas', [TipoActoJuridicoController::class,'obtenerTodas']);
    Route::post('nomina/tipo-acto-juridico/obtener-tipo', [TipoActoJuridicoController::class,'obtenerActoJuridico']);

    // Rutas tipo contrato
    Route::post('nomina/contrato-tipos/obtener', [TipoActoJuridicoController::class,'obtener']);
    Route::get('nomina/contrato-tipos/obtener-todas', [TipoActoJuridicoController::class,'obtenerTodas']);
    Route::post('nomina/contrato-tipos/obtener-datos', [TipoActoJuridicoController::class,'obtenerContratoTipo']);

    // Rutas planilla control
    Route::post('nomina/control-planilla/obtener', [PlanillaControlController::class,'obtener']);
    Route::get('nomina/control-planilla/obtener-todas', [PlanillaControlController::class,'obtenerTodas']);
    Route::post('nomina/control-planilla/obtener-datos', [PlanillaControlController::class,'obtenerControlPlanilla']);

    //Generar planilla
    Route::post('nomina/modulo-nomina/planillas/obtener', [GenerarPlanillasController::class,'obtenerPlanilla']);
    Route::put('nomina/modulo-nomina/planillas/obtener-planilla-procesar', [GenerarPlanillasController::class,'obtenerPlanillaProcesar']);
    Route::post('nomina/modulo-nomina/planillas/nuevo', [GenerarPlanillasController::class,'nuevo']);
    Route::post('nomina/modulo-nomina/planillas/registrar', [GenerarPlanillasController::class,'registrar']);
    Route::post('nomina/modulo-nomina/planillas/procesar', [GenerarPlanillasController::class,'procesarPlanilla']);
    #endregion rutas Nomina

// Rutas Cargos
    Route::post('nomina/areas/obtener', [AreasController::class,'obtener']);
    Route::get('nomina/areas/obtener-todas', [AreasController::class,'obtenerTodasAreas']);
    Route::post('nomina/areas/obtener-area', [AreasController::class,'obtenerArea']);
    Route::post('nomina/areas/registrar', [AreasController::class,'registrar']);
    Route::put('nomina/areas/actualizar', [AreasController::class,'actualizar']);
    Route::put('nomina/areas/desactivar', [AreasController::class,'desactivar']);
    Route::put('nomina/areas/activar', [AreasController::class,'activar']);
    Route::get('nomina/areas/buscar', [AreasController::class,'buscar']);

    Route::post('nomina/direcciones/obtener', [DireccionesController::class,'obtenerDirecciones']);
    Route::get('nomina/direcciones/obtener-todos', [DireccionesController::class,'obtenerTodasDirecciones']);
    Route::post('nomina/direcciones/obtener-direccion', [DireccionesController::class,'obtenerDireccion']);
    Route::post('nomina/direcciones/registrar', [DireccionesController::class,'registrar']);
    Route::put('nomina/direcciones/actualizar', [DireccionesController::class,'actualizar']);
    Route::put('nomina/direcciones/desactivar', [DireccionesController::class,'desactivar']);
    Route::put('nomina/direcciones/activar', [DireccionesController::class,'activar']);
    Route::get('nomina/direcciones/reporte/{ext}', [DireccionesController::class,'generarReporte']);
    Route::post('nomina/direcciones/nuevo', [DireccionesController::class,'nuevo']);
    //Rutas cajachica-viaticos

    Route::post('viaticos/obtener', 'CajaChicaViaticoController@obtener');
    Route::get('viaticos/obtener-todas', 'CajaChicaViaticoController@obtenerTodas');
    Route::post('viaticos/obtener-viatico', 'CajaChicaViaticoController@obtenerViatico');
    Route::post('viaticos/registrar', 'CajaChicaViaticoController@registrar');
    Route::put('viaticos/actualizar', 'CajaChicaViaticoController@actualizar');
    Route::put('viaticos/desactivar', 'CajaChicaViaticoController@desactivar');
    Route::post('viaticos/nuevo', 'CajaChicaViaticoController@nuevo');
    Route::put('viaticos/activar', 'CajaChicaViaticoController@activar');
    Route::get('viaticos/reporte/{ext}', 'CajaChicaViaticoController@generarReporte');

    //Reporte facturas diario
    Route::get('cajabanco/reporte-venta/reporte/{ext}/{id_grupo}/{fecha_inicial}/{fecha_final}', [ReportesCjaBncoController::class,'generarReporteVentasDiaria']);
    Route::get('mandar-correo', [AjustesController::class,'enviar']);

#region
    Route::controller('Informes\InformesController')->group(function () {
        Route::post('informes/ventas-brutas', 'obtenerVentasBrutas');
        Route::post('informes/ventas-netas', 'obtenerVentasNetas');
        Route::post('informes/beneficio-bruto', 'obtenerBeneficioBruto');
        Route::post('informes/ventas-mes', 'obtenerVentasPorMes');
        Route::post('informes/ventas-dia', 'obtenerVentasPorDia');
    });
#endregion
});


// Rutas fortify

Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
    $enableViews = config('fortify.views', true);

    // Authentication...
    /*    if ($enableViews) {
            Route::get('/login', [AuthenticatedSessionController::class, 'create'])
                ->middleware(['guest:' . config('fortify.guard')])
                ->name('login');
        }*/

    $limiter = config('fortify.limiters.login');
    $twoFactorLimiter = config('fortify.limiters.two-factor');
    $verificationLimiter = config('fortify.limiters.verification', '6,1');

    /*    Route::post('/login', [AuthController::class, 'login'])
            ->middleware(array_filter([
                'guest:' . config('fortify.guard'),
                $limiter ? 'throttle:' . $limiter : null,
            ]));*/

    /*    Route::post('/logout', [AuthController::class, 'logout'])
            ->name('logout');*/

    // Password Reset...
    /*if (Features::enabled(Features::resetPasswords())) {
        if ($enableViews) {
            Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->middleware(['guest:' . config('fortify.guard')])
                ->name('password.request');

            Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->middleware(['guest:' . config('fortify.guard')])
                ->name('password.reset');
        }

        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
            ->middleware(['guest:' . config('fortify.guard')])
            ->name('password.email');

        Route::post('/reset-password', [NewPasswordController::class, 'store'])
            ->middleware(['guest:' . config('fortify.guard')])
            ->name('password.update');
    }

    // Email Verification...
    if (Features::enabled(Features::emailVerification())) {
        Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
            ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard'), 'signed', 'throttle:' . $verificationLimiter])
            ->name('verification.verify');

        Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard'), 'throttle:' . $verificationLimiter])
            ->name('verification.send');
    }

    // Profile Information...
    if (Features::enabled(Features::updateProfileInformation())) {
        Route::put('/user/profile-information', [ProfileInformationController::class, 'update'])
            ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
            ->name('user-profile-information.update');
    }

    // Passwords...
    if (Features::enabled(Features::updatePasswords())) {
        Route::put('/user/password', [PasswordController::class, 'update'])
            ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
            ->name('user-password.update');
    }*/

    // Password Confirmation...
    /*if ($enableViews) {
        Route::get('/user/confirm-password', [ConfirmablePasswordController::class, 'show'])
            ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
            ->name('password.confirm');
    }

    Route::get('/user/confirmed-password-status', [ConfirmedPasswordStatusController::class, 'show'])
        ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
        ->name('password.confirmation');

    Route::post('/user/confirm-password', [ConfirmablePasswordController::class, 'store'])
        ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')]);*/

    // Two Factor Authentication...
/*    if (Features::enabled(Features::twoFactorAuthentication())) {
        if ($enableViews) {
            Route::get('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'create'])
                ->middleware(['guest:' . config('fortify.guard')])
                ->name('two-factor.login');
        }

        Route::post('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'store'])
            ->middleware(array_filter([
                'guest:' . config('fortify.guard'),
                $twoFactorLimiter ? 'throttle:' . $twoFactorLimiter : null,
            ]));

        $twoFactorMiddleware = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
            ? [config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard'), 'password.confirm']
            : [config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')];

        Route::post('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store'])
            ->middleware($twoFactorMiddleware)
            ->name('two-factor.enable');

        Route::delete('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy'])
            ->middleware($twoFactorMiddleware)
            ->name('two-factor.disable');

        Route::get('/user/two-factor-qr-code', [TwoFactorQrCodeController::class, 'show'])
            ->middleware($twoFactorMiddleware)
            ->name('two-factor.qr-code');

        Route::get('/user/two-factor-recovery-codes', [RecoveryCodeController::class, 'index'])
            ->middleware($twoFactorMiddleware)
            ->name('two-factor.recovery-codes');

        Route::post('/user/two-factor-recovery-codes', [RecoveryCodeController::class, 'store'])
            ->middleware($twoFactorMiddleware);
    }*/
});





