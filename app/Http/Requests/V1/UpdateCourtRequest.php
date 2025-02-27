<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateCourtRequest",
 *     type="object",
 *     title="Update Court Request",
 *     description="Datos para actualizar una pista",
 *     @OA\Property(property="name", type="string", example="Cancha 1"),
 *     @OA\Property(property="sport_id", type="integer", example=1)
 * )
 */
class UpdateCourtRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->isMethod('patch')) {
            return [
                'name'     => 'sometimes|string|unique:courts,name,' . $this->route('court'),
                'sport_id' => 'sometimes|exists:sports,id'
            ];
        }

        return [
            'name'     => 'required|string|unique:courts,name,' . $this->route('court'),
            'sport_id' => 'required|exists:sports,id'
        ];
    }
}
