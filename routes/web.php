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
Route::post('/getReporteByFilters','backend\ReportesController@getReporteByFilters')->middleware('auth:admin');

Route::post('/getProcesosByFilters','backend\ProcesosController@getProcesosByFilters')->middleware('auth:admin');

Route::post('/getSecuenciaProcesosByFilters/{proceso_id}','backend\SecuenciaProcesosController@getSecuenciaProcesosByFilters')->middleware('auth:admin');

Route::post('/getCamposPorProcesosByFilters/{proceso_id}','backend\CamposPorProcesosController@getCamposPorProcesosByFilters')->middleware('auth:admin');

Route::post('/getTipoCatalogosByFilters','backend\TipoCatalogosController@getTipoCatalogosByFilters')->middleware('auth:admin');
Route::post('/getCatalogosByFilters','backend\CatalogosController@getCatalogosByFilters')->middleware('auth:admin');
Route::post('/getCatalogoByTipoCatalogoId','backend\CatalogosController@getCatalogoByTipoCatalogoId')->middleware('auth:admin');

Route::post('/getSeccionPantallasByFilters','backend\SeccionPantallasController@getSeccionPantallasByFilters')->middleware('auth:admin');

Route::post('/getConfiguracionesValidacion','backend\ConfiguracionesValidacionController@getConfiguracionesValidacion')->middleware('auth:admin');
Route::post('/getLogsConfiguracionesValidacion','backend\LogsConfiguracionesValidacionController@getLogsConfiguracionesValidacion')->middleware('auth:admin');

Route::post('/getConfiguracionesCamposReporteByFilters','backend\ConfiguracionesCamposReporteController@getConfiguracionesCamposReporteByFilters')->middleware('auth:admin');

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
    Route::resource('tipoCatalogos', TipoCatalogosController::class);
    Route::resource('catalogos', CatalogosController::class);
    Route::resource('pantallas', PantallasController::class);
    Route::resource('seccionPantallas', SeccionPantallasController::class);
    /*Route::resource('configuracionesCamposReporte', ConfiguracionesCamposReporteController::class);*/
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
