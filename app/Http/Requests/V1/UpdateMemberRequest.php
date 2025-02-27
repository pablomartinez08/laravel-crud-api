<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateMemberRequest",
 *     type="object",
 *     title="Update Member Request",
 *     description="Datos necesarios para actualizar un socio",
 *     @OA\Property(property="name", type="string", maxLength=255, example="Jane Doe"),
 *     @OA\Property(property="email", type="string", format="email", maxLength=255, example="jane@example.com"),
 *     @OA\Property(property="phone", type="string", maxLength=20, example="+34123456789")
 * )
 */
class UpdateMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('patch')) {
            return [
                'name'  => 'sometimes|string|max:255',
                'email' => 'sometimes|string|email|max:255|unique:members,email,' . $this->route('member'),
                'phone' => 'sometimes|string|max:20|unique:members,phone,' . $this->route('member'),
            ];
        }

        return [
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:members,email,' . $this->route('member'),
            'phone' => 'required|string|max:20|unique:members,phone,' . $this->route('member'),
        ];
    }
}
