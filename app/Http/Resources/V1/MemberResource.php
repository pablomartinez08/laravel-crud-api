<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="MemberResource",
 *     type="object",
 *     title="Member Resource",
 *     description="Representación de un socio en las respuestas de la API",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="phone", type="string", example="+34123456789"),
 *     @OA\Property(
 *         property="reservations",
 *         type="array",
 *         @OA\Items(
 *             @OA\Property(property="court_id", type="integer", example=3),
 *             @OA\Property(property="date", type="string", format="date", example="2025-01-01"),
 *             @OA\Property(property="time", type="string", example="10:00")
 *         )
 *     )
 * )
 */
class MemberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'reservations' => $this->reservations->map(function ($reservation) {
                return [
                    'court_id' => $reservation->court_id,
                    'date' => $reservation->date,
                    'time' => $reservation->time,
                ];
            }),
        ];
    }
}
