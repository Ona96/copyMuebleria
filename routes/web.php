<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BancoController;
use App\Http\Controllers\CuentaBancariaController;
use App\Http\Controllers\ChequeController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\ProyeccionesController;
use App\Http\Controllers\BackupController;  // ← NUEVO: Controlador de sincronización

// Ruta de bienvenida
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Rutas de autenticación
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {

    // Dashboard principal
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Usuarios (Administración)
    Route::resource('usersAdmin', \App\Http\Controllers\UsersAdminController::class)->only(['index', 'store', 'destroy']);

    // Bancos
    Route::resource('bancos', BancoController::class);

    // Cuentas Bancarias
    Route::resource('cuentas', CuentaBancariaController::class);

    // Cheques
    Route::resource('cheques', ChequeController::class);

    // Proveedores
    Route::get('proveedores', [ProveedorController::class, 'index'])->name('proveedores.index');
    Route::get('proveedores/create', [ProveedorController::class, 'create'])->name('proveedores.create');
    Route::post('proveedores', [ProveedorController::class, 'store'])->name('proveedores.store');
    Route::get('proveedores/{id}', [ProveedorController::class, 'show'])->name('proveedores.show');
    Route::get('proveedores/{id}/edit', [ProveedorController::class, 'edit'])->name('proveedores.edit');
    Route::put('proveedores/{id}', [ProveedorController::class, 'update'])->name('proveedores.update');
    Route::delete('proveedores/{id}', [ProveedorController::class, 'destroy'])->name('proveedores.destroy');
    Route::get('/buscar-proveedor', [ProveedorController::class, 'buscar']);
    Route::get('/buscar-factura',[FacturaController::class, 'buscar']);

    // Facturas
    Route::get('facturas', [FacturaController::class, 'index'])->name('facturas.index');
    Route::get('facturas/create', [FacturaController::class, 'create'])->name('facturas.create');
    Route::post('facturas', [FacturaController::class, 'store'])->name('facturas.store');
    Route::get('facturas/{id}', [FacturaController::class, 'show'])->name('facturas.show');
    Route::get('facturas/{id}/edit', [FacturaController::class, 'edit'])->name('facturas.edit');
    Route::put('facturas/{id}', [FacturaController::class, 'update'])->name('facturas.update');
    Route::delete('facturas/{id}', [FacturaController::class, 'destroy'])->name('facturas.destroy');

    // Proyecciones
    Route::get('proyecciones', [ProyeccionesController::class, 'index'])->name('proyecciones.index');
    Route::get('proyecciones/exportar-pdf', [ProyeccionesController::class, 'exportarPDF'])->name('proyecciones.exportarPDF');

    // Mostrar la vista de sincronización
    Route::get('sincronizacion', function () {
        return view('sincronizacion');
    })->name('sincronizacion');
    // SINCRONIZACIÓN / BACKUP ENTRE PCs
    Route::post('backup/exportar', [BackupController::class, 'exportarANube'])->name('backup.exportar');
    Route::post('backup/importar', [BackupController::class, 'importarDeNube'])->name('backup.importar');
    Route::get('backup/info', [BackupController::class, 'infoSincronizacion'])->name('backup.info');
});