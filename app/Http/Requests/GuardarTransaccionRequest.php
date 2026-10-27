<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validación del formulario "Nueva Transacción".
 * Antes estaba dentro de store(); aquí queda ordenada y se puede reutilizar.
 */
class GuardarTransaccionRequest extends FormRequest
{
    /**
     * ¿Tiene permiso de hacer esto? Es distinto a si los datos son válidos.
     * La API todavía no tiene usuario (eso llega con los tokens), así
     * que aquí no se exige sesión: el dueño se revisa en las reglas.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación.
     *
     * Desde el navegador, el comercio_id no solo debe existir: debe ser
     * del usuario con sesión. Filtrar el <select> en la vista no basta,
     * porque el HTML se puede editar.
     *
     * Desde la API no hay usuario todavía, así que solo se comprueba que
     * el comercio exista. Cuando la API tenga tokens, este mismo if
     * volverá a filtrar por dueño sin tocar nada más.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $comercioValido = Rule::exists('comercios', 'id');

        if ($this->user()) {
            $comercioValido->where('user_id', $this->user()->id);
        }

        return [
            'comercio_id' => ['required', $comercioValido],
            'cliente_nombre' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0.01',
        ];
    }

    /**
     * Mensajes propios, en español.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'comercio_id.required' => 'Debes indicar el comercio.',
            'comercio_id.exists' => 'El comercio seleccionado no es válido.',
            'cliente_nombre.required' => 'Debes indicar el nombre del cliente.',
            'monto.required' => 'Debes indicar un monto.',
            'monto.numeric' => 'El monto debe ser un número.',
            'monto.min' => 'El monto debe ser mayor a cero.',
        ];
    }

    /**
     * Nombres bonitos para los mensajes que no personalicé arriba.
     * Así se lee "comercio" y no "comercio_id".
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'cliente_nombre' => 'nombre del cliente',
            'monto' => 'monto de la transacción',
            'comercio_id' => 'comercio',
        ];
    }
}
