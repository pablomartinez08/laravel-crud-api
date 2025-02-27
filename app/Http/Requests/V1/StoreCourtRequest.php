<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreCourtRequest",
 *     type="object",
 *     title="Store Court Request",
 *     description="Datos necesarios para crear una pista",
 *     required={"name", "sport_id"},
 *     @OA\Property(property="name", type="string", example="Cancha 1"),
 *     @OA\Property(property="sport_id", type="integer", example=1)
 * )
 */
class StoreCourtRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|unique:courts,name',
            'sport_id' => 'required|exists:sports,id'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The court name is required.',
            'name.unique' => 'This court name is already taken.',
            'sport_id.required' => 'A sport ID is required.',
            'sport_id.exists' => 'The selected sport does not exist.'
        ];
    }
}
