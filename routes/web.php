<?php

use App\Http\Controllers\TimesController;
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

Route::get('/', [TimesController::class, 'index']);
Route::get('/create', [TimesController::class, 'create']);
Route::post('/post', [TimesController::class, 'store']);
Route::get('/massivo', [TimesController::class, 'readCsv']);
