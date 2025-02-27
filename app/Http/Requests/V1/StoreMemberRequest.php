<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreMemberRequest",
 *     type="object",
 *     title="Solicitud para crear un socio",
 *     description="Datos necesarios para registrar un nuevo socio",
 *     required={"name", "email", "phone"},
 *     @OA\Property(property="name", type="string", maxLength=255, example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", maxLength=255, example="john@example.com"),
 *     @OA\Property(property="phone", type="string", maxLength=20, example="+34123456789")
 * )
 */
class StoreMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:members,email',
            'phone' => 'required|string|max:20|unique:members,phone',
        ];
    }
}
