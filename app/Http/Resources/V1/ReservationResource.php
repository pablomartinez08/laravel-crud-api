<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ReservationResource",
 *     type="object",
 *     title="Reservation Resource",
 *     description="Representación de una reserva en la API",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="member", type="object",
 *         @OA\Property(property="id", type="integer", example=10),
 *         @OA\Property(property="name", type="string", example="John Doe")
 *     ),
 *     @OA\Property(property="court", type="object",
 *         @OA\Property(property="id", type="integer", example=5),
 *         @OA\Property(property="name", type="string", example="Court A")
 *     ),
 *     @OA\Property(property="date", type="string", format="date", example="2025-06-15"),
 *     @OA\Property(property="time", type="string", example="14:00"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-14T12:05:09Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-14T12:10:00Z")
 * )
 */
class ReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'member' => [
                'id' => $this->member->id,
                'name' => $this->member->name,
            ],
            'court' => [
                'id' => $this->court->id,
                'name' => $this->court->name,
            ],
            'date' => $this->date,
            'time' => $this->time,
            'created_at' => $this->created_at->utc()->toIso8601ZuluString(),
            'updated_at' => $this->updated_at->utc()->toIso8601ZuluString(),
        ];
    }
}
