<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backend\AdminsController;
use App\Http\Controllers\Backend\Auth\ForgotPasswordController;
use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\Backend\CamposPorProcesosController;
use App\Http\Controllers\Backend\CatalogosController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\ConfiguracionesCamposReporteController;
use App\Http\Controllers\Backend\PantallasController;
use App\Http\Controllers\Backend\ProcesosController;
use App\Http\Controllers\Backend\ReportesController;
use App\Http\Controllers\Backend\SeccionPantallasController;
use App\Http\Controllers\Backend\SecuenciaProcesosController;
use App\Http\Controllers\Backend\TipoCatalogosController;
use App\Http\Controllers\Backend\TramitesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', 'HomeController@redirectAdmin')->name('index');
Route::get('/home', 'HomeController@index')->name('home');
/*Route::post('/getReporteByFilters',[ReportesController::class, 'getReporteByFilters'])->middleware('auth:admin');
*/

/*Route::get('tramites/{id}/edit', [TramitesController::class, 'edit'])->name('tramites.edit')->middleware('auth:admin');
Route::put('tramites/{id}/edit', [TramitesController::class, 'update'])->name('tramites.update')->middleware('auth:admin');
*/
Route::post('/getProcesosByFilters',[ProcesosController::class, 'getProcesosByFilters'])->middleware('auth:admin');

Route::post('/getSecuenciaProcesosByFilters/{proceso_id}',[SecuenciaProcesosController::class, 'getSecuenciaProcesosByFilters'])->middleware('auth:admin');

Route::post('/getCamposPorProcesosByFilters/{proceso_id}',[CamposPorProcesosController::class, 'getCamposPorProcesosByFilters'])->middleware('auth:admin');

Route::post('/getSecuenciaProcesosByProceso',[SecuenciaProcesosController::class, 'getSecuenciaProcesosByProceso'])->middleware('auth:admin');

Route::post('/getBandejaTramitesByFilters',[TramitesController::class, 'getBandejaTramitesByFilters'])->middleware('auth:admin');
Route::post('/getTramitesByFilters',[TramitesController::class, 'getTramitesByFilters'])->middleware('auth:admin');
Route::post('/getListaCamposByTramite',[TramitesController::class, 'getListaCamposByTramite'])->middleware('auth:admin');
Route::post('/getTramitesParaReasignarByFilters',[TramitesController::class, 'getTramitesParaReasignarByFilters'])->middleware('auth:admin');

Route::post('/getTipoCatalogosByFilters',[TipoCatalogosController::class, 'getTipoCatalogosByFilters'])->middleware('auth:admin');
Route::post('/getCatalogosByFilters',[CatalogosController::class, 'getCatalogosByFilters'])->middleware('auth:admin');
Route::post('/getCatalogoByTipoCatalogoId',[CatalogosController::class, 'getCatalogoByTipoCatalogoId'])->middleware('auth:admin');

/*Route::post('/getSeccionPantallasByFilters',[SeccionPantallasController::class, 'getSeccionPantallasByFilters'])->middleware('auth:admin');

Route::post('/getConfiguracionesValidacion',[ConfiguracionesValidacionController::class, 'getConfiguracionesValidacion'])->middleware('auth:admin');
Route::post('/getLogsConfiguracionesValidacion',[LogsConfiguracionesValidacionController::class, 'getLogsConfiguracionesValidacion'])->middleware('auth:admin');
*/
Route::post('/getConfiguracionesCamposReporteByFilters',[ConfiguracionesCamposReporteController::class, 'getConfiguracionesCamposReporteByFilters'])->middleware('auth:admin');
Route::post('/getCamposPorProceso',[ConfiguracionesCamposReporteController::class, 'getCamposPorProceso'])->middleware('auth:admin');
Route::post('/consultarSCI',[TramitesController::class, 'consultarSCI'])->middleware('auth:admin');
Route::post('/getTiposReporteByProcesoId',[ReportesController::class, 'getTiposReporteByProcesoId'])->middleware('auth:admin');
Route::post('/getCamposByTipoReporte',[ReportesController::class, 'getCamposByTipoReporte'])->middleware('auth:admin');
Route::post('/generarReporteByTipoReporte',[ReportesController::class, 'generarReporteByTipoReporte'])->middleware('auth:admin');

/**
 * Admin routes
 */
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('roles', RolesController::class);
    Route::resource('admins', AdminsController::class);
    // Catalogos
    /*Route::resource('provincias', ProvinciasController::class);
    Route::resource('cantones', CantonesController::class);
    Route::resource('parroquias', ParroquiasController::class);*/
    Route::resource('procesos', ProcesosController::class);

    Route::get('/secuenciaProcesos/{proceso_id}', [SecuenciaProcesosController::class, 'index']);
    Route::get('/secuenciaProcesos/{proceso_id}/create', [SecuenciaProcesosController::class, 'create']);
    Route::post('/secuenciaProcesos/{proceso_id}/create', [SecuenciaProcesosController::class, 'store']);
    Route::get('/secuenciaProcesos/{proceso_id}/{id}/edit', [SecuenciaProcesosController::class, 'edit']);
    Route::put('/secuenciaProcesos/{proceso_id}/{id}/edit', [SecuenciaProcesosController::class, 'update']);
    Route::delete('/secuenciaProcesos/{proceso_id}/{id}/delete', [SecuenciaProcesosController::class, 'destroy']);
    //Route::resource('secuenciaProcesos/{proceso_id}/', SecuenciaProcesosController::class);

    Route::get('/camposPorProcesos/{proceso_id}', [CamposPorProcesosController::class, 'index']);
    Route::get('/camposPorProcesos/{proceso_id}/create', [CamposPorProcesosController::class, 'create']);
    Route::post('/camposPorProcesos/{proceso_id}/create', [CamposPorProcesosController::class, 'store']);
    Route::get('/camposPorProcesos/{proceso_id}/{id}/edit', [CamposPorProcesosController::class, 'edit']);
    Route::put('/camposPorProcesos/{proceso_id}/{id}/edit', [CamposPorProcesosController::class, 'update']);
    Route::delete('/camposPorProcesos/{proceso_id}/{id}/delete', [CamposPorProcesosController::class, 'destroy']);

    Route::get('/tramites', [TramitesController::class, 'index'])->name('tramites.index');
    Route::get('/tramites/inbox', [TramitesController::class, 'inbox'])->name('tramites.inbox');
    Route::get('/tramites/reassign', [TramitesController::class, 'reassign'])->name('tramites.reassign');
    Route::post('/tramites/procesarTramites', [TramitesController::class, 'procesarTramites'])->name('tramites.procesarTramites');
    Route::post('/tramites/reasignarTramites', [TramitesController::class, 'reasignarTramites'])->name('tramites.reasignarTramites');
    Route::get('/tramites/{proceso_id}/create', [TramitesController::class, 'create'])->name('tramites.create');
    Route::post('/tramites/{proceso_id}/create', [TramitesController::class, 'store'])->name('tramites.store');
    Route::get('/tramites/{id}/edit', [TramitesController::class, 'edit'])->name('tramites.edit');
    Route::put('/tramites/{id}/edit', [TramitesController::class, 'update'])->name('tramites.update');

    Route::resource('tipoCatalogos', TipoCatalogosController::class);
    Route::resource('catalogos', CatalogosController::class);
    //Route::resource('pantallas', PantallasController::class);
    //Route::resource('seccionPantallas', SeccionPantallasController::class);
    Route::resource('configuracionesCamposReporte', ConfiguracionesCamposReporteController::class);
    Route::resource('reportes', ReportesController::class);

    // Login Routes.
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login/submit', [LoginController::class, 'login'])->name('login.submit');

    // Logout Routes.
    Route::post('/logout/submit', [LoginController::class, 'logout'])->name('logout.submit');

    // Forget Password Routes.
    //Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    //::post('/password/reset/submit', [ForgotPasswordController::class, 'reset'])->name('password.update');

    
})->middleware('auth:admin');
