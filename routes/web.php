<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\inventarioController;

// =========================
// RUTAS DE AUTENTICACIÓN
// =========================

// Mostrar formulario login
Route::get('/login', [inventarioController::class, 'showLoginForm'])->name('login');

// Procesar login
Route::post('/login', [inventarioController::class, 'login']);

// Cerrar sesión
Route::post('/logout', [inventarioController::class, 'logout'])->name('logout');

// ===================================
// RUTAS PROTEGIDAS CON AUTH
// ===================================
Route::middleware('auth')->group(function () {

    // Página principal (dashboard)
    Route::get('/', [inventarioController::class, 'mostrarInicio'])->name('mostrar.inicio');

    // Registrar usuario
    Route::get('/register', function () {
        return view('register');
    })->name('register_form');

    Route::post('/register', [inventarioController::class, 'register'])->name('register');

    // Insertar préstamo
    Route::post('/insertar-prestamo', [inventarioController::class, 'insertarPrestamo'])->name('insertarPrestamo');

    // Insertar artículo dañado
    Route::post('/insertar-daño', [inventarioController::class, 'insertarDaño'])->name('insertarDaño');

    // Inventario
    Route::get('/inventario', [inventarioController::class, 'mostrarInventario'])->name('mostrar.Inventario');

    Route::post('/insertar-inventario', [inventarioController::class, 'insertarInventario'])->name('insertarInventario');

    Route::post('/inventario/subir-imagen', [inventarioController::class, 'subirImagen'])->name('subirImagen');

    Route::get('/catalogo-imagenes', [inventarioController::class, 'mostrarCatalogo'])->name('catalogo.imagenes');

    // Cambiar estatus
    Route::get('/inventario/estatus/{id}', [inventarioController::class, 'cambiarEstatus'])->name('inventario.cambiarEstatus');

    Route::get('/inventario/estatus_pres/{id}', [inventarioController::class, 'cambiarEstatusPrestamo'])->name('inventario.cambiarEstatusPrestamo');

    Route::get('/inventario/estatus_dañito/{id}', [inventarioController::class, 'cambiarEstatusDañado'])->name('inventario.cambiarEstatusDañado');

    // Reportes
    Route::get('/reportes', [inventarioController::class, 'ConsultaReportes'])->name('Consulta.Reportes');

    Route::get('/reportes/exportar', [inventarioController::class, 'exportExcel'])->name('reportes.exportar');
});

// ================================
// Fallback → si no encuentra ruta
// ================================
Route::fallback(function () {
    return redirect(Auth::check() ? '/' : '/login');
});
