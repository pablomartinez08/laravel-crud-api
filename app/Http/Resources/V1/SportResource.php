<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="SportResource",
 *     type="object",
 *     title="Sport Resource",
 *     description="Representación de un deporte en la API",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Tenis"),
 *     @OA\Property(property="description", type="string", example="Deporte de raqueta jugado en una cancha rectangular."),
 *     @OA\Property(
 *         property="courts",
 *         type="array",
 *         @OA\Items(
 *             @OA\Property(property="id", type="integer", example=3),
 *             @OA\Property(property="name", type="string", example="Court A"),
 *             @OA\Property(property="sport_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-14T12:05:09Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-14T12:10:00Z")
 * )
 */
class SportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'courts' => $this->courts->map(function ($court) {
                return [
                    'id' => $court->id,
                    'name' => $court->name,
                    'sport_id' => $court->sport_id,
                ];
            }),
            'created_at' => $this->created_at->utc()->toIso8601ZuluString(),
            'updated_at' => $this->updated_at->utc()->toIso8601ZuluString(),
        ];
    }
}
