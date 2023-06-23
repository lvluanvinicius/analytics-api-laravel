<?php

use App\Http\Controllers\Api\{
    AuthController,
    EquipamentController,
    GponGetDatesController,
    GponOnusController,
    GponOnusPerPortsController,
    HostsController,
    InterconnectionController,
    JiraAtlassianController,
    PortsController,
    ProxmoxController,
    PercentilController,
    PopsController,
};

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function (Response $response) {
    return response()->json("Hello! Welcome to api Analytics Services!");
})->name('login');

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);




Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('equipaments')->as('equipaments.')->group(function () {
        Route::get('/', [EquipamentController::class, 'index'])->name('index');
        Route::post('/store', [EquipamentController::class, 'store'])->name('store');
    });

    Route::prefix('ports')->as('ports.')->group(function () {
        Route::get('/{equipament_name}', [PortsController::class, 'index'])->name('index');
    });

    Route::prefix('onus')->as('onus.')->group(function () {
        Route::get('/', [GponOnusController::class, 'index'])->name('index');
        Route::get('/names', [GponOnusController::class, 'names'])->name('names');
        Route::get('/get-dates', [GponGetDatesController::class, 'getDates'])->name('get.dates');
        Route::get('/onus-per-port', [GponOnusPerPortsController::class, 'onusPerPorts'])->name('onus.per.ports');
        Route::get('/datas-onus', [GponOnusController::class, 'onusDatasPerPeriod'])->name('datasOnus');
    });

    Route::prefix('hosts')->as('hosts.')->group(function () {
        Route::get('/', [HostsController::class, 'index'])->name('index');
        Route::get('/interfaces', [HostsController::class, 'getInterface'])->name('get.interfaces');
        Route::get('/macros', [HostsController::class, 'getMacros'])->name('get.macros');
        Route::post('/register', [HostsController::class, 'register'])->name('register');
        Route::post('/interfaces/register', [HostsController::class, 'interfacesRegister'])->name('interfaces.register');
        Route::post('/macros/register', [HostsController::class, 'macrosRegister'])->name('macros.register');
    });

    Route::prefix('proxmox')->as('proxmox.')->group(function () {
        Route::post('/request-all', [ProxmoxController::class, 'requestApp'])->name('index');
    });

    Route::prefix('zabbix')->as('zabbix.')->group(function () {
        Route::prefix('percentil')->as('percentil.')->group(function () {
            Route::get('/', [PercentilController::class, 'index'])->name('index');
        });

        Route::prefix('pops')->as('pops.')->group(function () {
            Route::get('/', [PopsController::class, 'index'])->name('index');
        });

        Route::prefix('interconnection')->as('interconnection.')->group(function () {
            Route::get('/', [InterconnectionController::class, 'index'])->name('index');
        });
    });

    Route::prefix('jira-atlassian')->as('jiraatlassian.')->group(function () {
        Route::get('/request-sla', [JiraAtlassianController::class, 'requestSla'])->name('requestsla');
        Route::get('/request-severity',  [JiraAtlassianController::class, 'requestSla'])->name('requestseverity');
    });
});
