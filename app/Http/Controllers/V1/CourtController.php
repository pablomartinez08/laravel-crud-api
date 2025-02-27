<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\V1\Controller;
use App\Http\Requests\V1\StoreCourtRequest;
use App\Http\Requests\V1\UpdateCourtRequest;
use App\Http\Resources\V1\CourtResource;
use App\Models\V1\Court;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\V1\Reservation;
use Carbon\Carbon;

/**
 * @OA\Tag(
 *     name="Pistas",
 *     description="Endpoints relacionados con la gestión de pistas deportivas"
 * )
 */
class CourtController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/courts",
     *     summary="Listar todas las pistas",
     *     tags={"Pistas"},
     *     security={{"sanctum":{}}},   
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pistas",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/CourtResource"))
     *     )
     * )
     */
    public function index()
    {
        $courts = Court::with('sport:id,name')->orderBy('id', 'asc')->paginate(5);
        return CourtResource::collection($courts);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/courts/{id}",
     *     summary="Obtener una pista por ID",
     *     tags={"Pistas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la pista",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Datos de la pista",
     *         @OA\JsonContent(ref="#/components/schemas/CourtResource")
     *     ),
     *     @OA\Response(response=404, description="Pista no encontrada")
     * )
     */
    public function show($id)
    {
        try {
            $court = Court::with('sport:id,name')->findOrFail($id);
            return new CourtResource($court);
        } catch (ModelNotFoundException $e) {
            return $this->resourceNotFoundResponse('Court');
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/courts",
     *     summary="Registrar una nueva pista",
     *     tags={"Pistas"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreCourtRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pista registrada correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/CourtResource")
     *     )
     * )
     */
    public function store(StoreCourtRequest $request)
    {

        $court = Court::create($request->validated());

        return response()
            ->json(new CourtResource($court), 201)
            ->header('Location', route('courts.show', ['court' => $court->id]));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/courts/{id}",
     *     summary="Actualizar una pista",
     *     tags={"Pistas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la pista",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateCourtRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pista actualizada correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/CourtResource")
     *     )
     * )
     */
    public function update(UpdateCourtRequest $request, $id)
    {
        $court = Court::find($id);

        if (!$court) {
            return $this->resourceNotFoundResponse('Court');
        }

        $court->update($request->validated());
        return new CourtResource($court);
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/courts/{id}",
     *     summary="Actualizar parcialmente una pista",
     *     tags={"Pistas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la pista",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateCourtRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pista actualizada parcialmente",
     *         @OA\JsonContent(ref="#/components/schemas/CourtResource")
     *     ),
     *     @OA\Response(response=404, description="Pista no encontrada")
     * )
     */
    public function updatePartial(UpdateCourtRequest $request, $id)
    {
        $court = Court::find($id);

        if (!$court) {
            return $this->resourceNotFoundResponse('Court');
        }

        $validatedData = $request->validated();

        if (!empty($validatedData)) {
            $court->update($validatedData);
        }

        return new CourtResource($court);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/courts/{id}",
     *     summary="Eliminar una pista",
     *     tags={"Pistas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la pista",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Pista eliminada correctamente"),
     *     @OA\Response(response=404, description="Pista no encontrada")
     * )
     */
    public function destroy($id)
    {
        $court = Court::find($id);

        if (!$court) {
            return $this->resourceNotFoundResponse('Court');
        }

        $court->delete();
        return response()->noContent();
    }

    /**
     * @OA\Get(
     *     path="/api/v1/courts/available",
     *     summary="Buscar pistas disponibles",
     *     tags={"Pistas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         required=true,
     *         description="Fecha de la reserva en formato Y-m-d",
     *         @OA\Schema(type="string", format="date", example="2025-02-28")
     *     ),
     *     @OA\Parameter(
     *         name="sport_id",
     *         in="query",
     *         required=true,
     *         description="ID del deporte para filtrar las pistas",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="member_id",
     *         in="query",
     *         required=true,
     *         description="ID del socio que realiza la consulta",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Parameter(
     *         name="time",
     *         in="query",
     *         required=false,
     *         description="Hora específica para verificar disponibilidad (opcional)",
     *         @OA\Schema(type="string", format="time", example="10:00")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pistas disponibles",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/CourtResource"))
     *     ),
     *     @OA\Response(response=400, description="Parámetros inválidos"),
     *     @OA\Response(response=500, description="Error en el servidor")
     * )
     */
    public function availableCourts(Request $request)
    {
        $validated = $request->validate([
            'date'      => 'required|date_format:Y-m-d',
            'sport_id'  => 'required|exists:sports,id',
            'member_id' => 'required|exists:members,id',
            'time'      => 'nullable|date_format:H:i',
        ]);

        try {
            $date = $validated['date'];
            $sportId = $validated['sport_id'];
            $memberId = $validated['member_id'];
            $requestedTime = $validated['time'] ?? null;

            $memberReservationsCount = Reservation::where('member_id', $memberId)
                ->where('date', $date)
                ->count();
            if ($memberReservationsCount >= 3) {
                return response()->json([
                    'message' => 'El socio ya tiene el máximo de 3 reservas para ese día.'
                ], 200);
            }

            $courts = Court::where('sport_id', $sportId)->get();

            $startOfDay = Carbon::parse("$date 08:00");
            $endOfDay   = Carbon::parse("$date 22:00");

            $availableCourts = $courts->filter(function ($court) use ($date, $requestedTime, $startOfDay, $endOfDay) {
                $reservations = $court->reservations()->where('date', $date)->get();

                if ($requestedTime) {
                    $candidate = Carbon::parse("$date $requestedTime");
                    foreach ($reservations as $reservation) {
                        try {
                            $existing = Carbon::parse($reservation->date . ' ' . $reservation->time);
                        } catch (Exception $e) {
                            continue;
                        }
                        if ($existing->diffInMinutes($candidate, true) < 60) {
                            return false;
                        }
                    }
                    return true;
                }

                $slotFound = false;
                $current = $startOfDay->copy();
                while ($current->lte($endOfDay)) {
                    $isAvailable = true;
                    foreach ($reservations as $reservation) {
                        try {
                            $existing = Carbon::parse($reservation->date . ' ' . $reservation->time);
                        } catch (Exception $e) {
                            continue;
                        }
                        if ($existing->diffInMinutes($current, true) < 60) {
                            $isAvailable = false;
                            break;
                        }
                    }
                    if ($isAvailable) {
                        $slotFound = true;
                        break;
                    }
                    $current->addMinutes(15);
                }
                return $slotFound;
            })->values();

            return response()->json($availableCourts);
        } catch (Exception $e) {
            Log::error('Error en availableCourts: ' . $e->getMessage());
            return response()->json(['message' => 'Server Error'], 500);
        }
    }
}
