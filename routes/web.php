<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CuentaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaccionController;
use Illuminate\Support\Facades\Route;

// La página principal lleva al panel. Si no hay sesión, Laravel manda al login.
Route::redirect('/', '/dashboard');

// Todo lo que está aquí adentro solo lo ven usuarios autenticados.
Route::middleware('auth')->group(function () {

    // Panel de inicio
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // CRUD de categorías  (categorias.index, .create, .store, .edit, .update, .destroy)
    Route::resource('categorias', CategoriaController::class)
        ->parameters(['categorias' => 'categoria'])
        ->except('show');

    // CRUD de cuentas
    Route::resource('cuentas', CuentaController::class)
        ->parameters(['cuentas' => 'cuenta'])
        ->except('show');

    // CRUD de transacciones (ingresos y gastos)
    Route::resource('transacciones', TransaccionController::class)
        ->parameters(['transacciones' => 'transaccion'])
        ->except('show');

    // Perfil del usuario (creado por Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas de login, registro y cierre de sesión (creadas por Breeze)
require __DIR__.'/auth.php';
