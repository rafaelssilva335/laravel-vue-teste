<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('ClientesList');
});

Route::get('/clientes', function () {
    return Inertia::render('ClientesList');
})->name('clientes');

Route::get('/clientes/create', function () {
    return Inertia::render('Clientes');
})->name('clientes');

Route::get('/clientes/{id}/editar', function ($id) {
    return Inertia::render('ClientesEdit', ['id' => $id]);
})->name('clientes');

Route::get('/clientes/busca', function () {
    return Inertia::render('ClientesBusca');
})->name('clientes');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
