<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backend\AdminsController;
use App\Http\Controllers\Backend\Auth\ForgotPasswordController;
use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\ConfiguracionesCamposReporteController;
use App\Http\Controllers\Backend\PantallasController;
use App\Http\Controllers\Backend\ProcesosController;
use App\Http\Controllers\Backend\ReportesController;
use App\Http\Controllers\Backend\SeccionPantallasController;

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

Route::post('/getPantallasByFilters','backend\PantallasController@getPantallasByFilters')->middleware('auth:admin');

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
