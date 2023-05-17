<?php

use App\Http\Controllers\Admin\{
    GponOnusDashboardController,
};

use App\Http\Controllers\Web\LoginController;


use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/', [LoginController::class, 'auth'])->name('login.auth');

Route::middleware('auth:web')->group(function () {
});


Route::prefix('onus')->as('onus.')->group(function () {
    Route::get('/', [GponOnusDashboardController::class, 'index'])->name('index');

    // Rotas Proxy
    Route::prefix('proxy')->as('proxy.')->group(function () {
        Route::get('/ports/{equipament}', [GponOnusDashboardController::class, 'ports'])->name('ports.get');
        Route::get('/names', [GponOnusDashboardController::class, 'names'])->name('names.get');
        Route::get('/datas-onus', [GponOnusDashboardController::class, 'datasOnus'])->name('datas.onus.get');
    });
});
