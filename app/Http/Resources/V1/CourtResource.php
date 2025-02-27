<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CourtResource",
 *     type="object",
 *     title="Court Resource",
 *     description="Datos de la pista deportiva",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Cancha 1"),
 *     @OA\Property(
 *         property="sport",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Tenis")
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-02-26T12:05:09Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-26T12:05:09Z")
 * )
 */
class CourtResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sport' => [
                'id' => $this->sport->id,
                'name' => $this->sport->name,
            ],
            'created_at' => $this->created_at->utc()->toIso8601ZuluString(),
            'updated_at' => $this->updated_at->utc()->toIso8601ZuluString(),
        ];
    }
}
