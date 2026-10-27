<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuardarTransaccionRequest;
use App\Http\Resources\TransaccionResource;
use App\Models\Transaccion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * Controlador de la API: devuelve JSON, nunca vistas.
 */
class TransaccionApiController extends Controller
{
    /**
     * Lista las transacciones, de la más nueva a la más vieja.
     * ?estado=Completada filtra; sin el parámetro salen todas.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $estado = $request->query('estado');

        return TransaccionResource::collection(
            Transaccion::when($estado, fn ($q) => $q->where('estado', $estado))
                ->latest()
                ->get()
        );
    }

    /**
     * Crea una transacción. 201 Created con el objeto creado.
     *
     * Reutiliza el mismo Form Request del formulario web: las reglas
     * y los mensajes viven en un solo lugar. Si falla, Laravel responde
     * 422 con los errores en JSON (si la petición manda Accept: application/json).
     */
    public function store(GuardarTransaccionRequest $request): JsonResponse
    {
        $transaccion = Transaccion::create($request->validated());

        // refresh() relee la fila: sin esto, 'estado' saldría null en la
        // respuesta, porque el valor lo pone el default de la columna y
        // el objeto en memoria no se enteró.
        $transaccion->refresh();

        return (new TransaccionResource($transaccion))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Una transacción. Si el id no existe, Route Model Binding da 404.
     */
    public function show(Transaccion $transaccion): TransaccionResource
    {
        return new TransaccionResource($transaccion);
    }

    /**
     * Actualiza y devuelve el objeto ya modificado.
     */
    public function update(
        GuardarTransaccionRequest $request,
        Transaccion $transaccion
    ): TransaccionResource {
        $transaccion->update($request->validated());

        return new TransaccionResource($transaccion);
    }

    /**
     * Elimina. 204 No Content: se borró y no hay nada que devolver.
     */
    public function destroy(Transaccion $transaccion): Response
    {
        $transaccion->delete();

        return response()->noContent();
    }
}
