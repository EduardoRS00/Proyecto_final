<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingAdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;



/*
|--------------------------------------------------------------------------
| Rutas públicas (Clientes)
|--------------------------------------------------------------------------
*/

// Página principal de un restaurante (bienvenida)
Route::get('/reservas/{id}', [BookingController::class, 'index'])
->name('restaurant.view');

Route::post('/reservar', [BookingController::class, 'store'])->name('reservar');

// Formulario para hacer una reserva
//Route::middleware(['auth'])->group(function () {
    // Ruta principal para la gestión de reservas
Route::get('/index', [AdminController::class, 'index'])->name('index');

Route::get('/restaurante/{id}', [BookingAdminController::class, 'index'])->name('reservas.index');
Route::get('/reservas/{id}/filtrar', action: [BookingAdminController::class, 'filter'])->name('reservas.filtrar');

Route::get('/login', [AdminController::class, 'login'])->name('login');
Route::post('/index', [AdminController::class, 'index'])->name('login.process');
Route::post('/reservas/{id}/llegada', [BookingAdminController::class, 'marcarLlegada'])->name('reservas.arrival');

Route::get('/reservas/{id}/editar', [BookingAdminController::class, 'edit'])->name('reservas.edit');

Route::get('/fechas-bloqueadas/{restaurante}', [BookingController::class, 'fechasBloqueadas']);


Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
Route::get('/admin/user/index', [AdminUserController::class, 'index'])->name('admin.users.index');

Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
Route::get('/users/{user}/reservas', [AdminUserController::class, 'verReservas'])->name('admin.users.reservas');
Route::post('/admin/users/{user}/toggle', [AdminUserController::class, 'togglePago'])->name('admin.users.toggle');

Route::post('/admin/users/store', [AdminUserController::class, 'store'])->name('admin.users.store');
// Mostrar formulario de alta
Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');

// Guardar nuevo usuario
Route::post('/admin/users/store', [AdminUserController::class, 'alta'])->name('admin.users.store');


// En routes/web.php
Route::get('/booking/availability/{restaurant}/{year}/{month}', [BookingController::class, 'monthAvailability']);

// Ruta para mostrar formulario de edición (detalles de reserva)
Route::get('/reservas/{id}/edit', [BookingAdminController::class, 'edit'])
    ->name('reservas.edit');

// Ruta para actualizar una reserva existente
Route::put('/reservas/{id}', [BookingAdminController::class, 'update'])
    ->name('reservas.update');

// Ruta para eliminar una reserva existente
Route::delete('/reservas/{id}', [BookingAdminController::class, 'destroy'])
    ->name('reservas.destroy');Route::get('/reservas', [BookingAdminController::class, 'index'])->name('reservas.index');

Route::put('/reservas/{id}/no-llegado', [BookingAdminController::class, 'marcarNoLlegado'])->name('reservas.marcarNoLlegado');

Route::post('/chatbot/enviar', [ChatbotController::class, 'enviar']);

Route::get('/set-locale/{lang}', function ($lang) {
    if (!in_array($lang, ['en', 'es'])) {
        abort(400, 'Idioma no soportado');
    }

    Session::put('locale', $lang);
    App::setLocale($lang);

    return redirect()->back(); // o redirect('/');
});
