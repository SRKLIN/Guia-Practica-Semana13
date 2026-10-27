<?php

use App\Http\Controllers\Api\TransaccionApiController;
use App\Http\Resources\ComercioResource;
use App\Models\Comercio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
| Rutas de la API. Llevan el prefijo /api y no usan sesión ni cookies,
| así que tampoco piden token CSRF.
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Listado: sin transacciones, para no hacer una consulta por comercio
Route::get('/comercios', function () {
    return ComercioResource::collection(Comercio::all());
});

// Detalle: aquí sí se cargan las transacciones
Route::get('/comercios/{comercio}', function (Comercio $comercio) {
    return new ComercioResource($comercio->load('transacciones'));
});

// apiResource crea 5 rutas: index, store, show, update y destroy.
// No crea create ni edit porque una API no tiene formularios.
//
// parameters() renombra el parámetro: por su cuenta Laravel lo llama
// {transaccione} y entonces no coincide con el $transaccion del
// controlador, así que el modelo nunca se carga.
//
// names() es obligatorio aquí: por defecto estas rutas se llamarían
// transacciones.store, transacciones.index..., los mismos nombres que
// las rutas web de la Semana 11. El último registrado gana, así que
// route('transacciones.store') en las vistas Blade apuntaría a la API
// y el formulario del navegador dejaría de funcionar.
Route::apiResource('transacciones', TransaccionApiController::class)
    ->parameters(['transacciones' => 'transaccion'])
    ->names('api.transacciones');
