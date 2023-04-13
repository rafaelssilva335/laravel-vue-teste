<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EnderecoController;
use App\Models\Endereco;

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

Route::resource('clientes', ClienteController::class);
Route::get('clientes-search', [ClienteController::class, 'search']);
Route::get('/cidades/{estado}', [EnderecoController::class, 'getCidadesPorEstado']);
Route::get('/estados', [EnderecoController::class, 'getEstados']);