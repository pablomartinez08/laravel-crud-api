<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreSportRequest",
 *     type="object",
 *     title="Store Sport Request",
 *     description="Datos necesarios para registrar un nuevo deporte",
 *     required={"name"},
 *     @OA\Property(property="name", type="string", example="Fútbol"),
 *     @OA\Property(property="description", type="string", example="Deporte en el que dos equipos compiten para anotar goles")
 * )
 */
class StoreSportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:sports,name',
            'description' => 'nullable|string|max:255',
        ];
    }
}
