<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateSportRequest",
 *     type="object",
 *     title="Update Sport Request",
 *     description="Datos necesarios para actualizar un deporte",
 *     @OA\Property(property="name", type="string", example="Fútbol"),
 *     @OA\Property(property="description", type="string", example="Deporte en el que dos equipos compiten para anotar goles")
 * )
 */
class UpdateSportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('patch')) {
            return [
                'name' => 'sometimes|string|unique:sports,name,' . $this->route('sport'),
                'description' => 'nullable|string|max:255',
            ];
        }

        return [
            'name' => 'required|string|unique:sports,name,' . $this->route('sport'),
            'description' => 'nullable|string|max:255',
        ];
    }
}
