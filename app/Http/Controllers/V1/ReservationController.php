<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\V1\Controller;
use App\Models\V1\Reservation;
use App\Http\Requests\V1\StoreReservationRequest;
use Illuminate\Http\Request;
use App\Http\Resources\V1\ReservationResource;
use App\Http\Requests\V1\UpdateReservationRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @OA\Tag(
 *     name="Reservas",
 *     description="Endpoints para gestionar reservas de pistas"
 * )
 */
class ReservationController extends Controller
{
    /**
     * Listado de Reservas
     *
     * @OA\Get(
     *     path="/api/v1/reservations",
     *     tags={"Reservas"},
     *     security={{"sanctum":{}}},
     *     summary="Obtener lista de reservas",
     *     description="Devuelve un listado paginado de reservas con información del socio y la pista",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de reservas",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/ReservationResource")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $reservations = Reservation::with('member:id,name')->orderBy('id', 'asc')->paginate(5);
        return ReservationResource::collection($reservations);
    }

    /**
     * Obtener una reserva específica
     *
     * @OA\Get(
     *     path="/api/v1/reservations/{id}",
     *     tags={"Reservas"},
     *     security={{"sanctum":{}}},
     *     summary="Obtener una reserva por ID",
     *     description="Devuelve los datos de una reserva específica junto con información del socio y la pista",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la reserva",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reserva encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/ReservationResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reserva no encontrada"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $reservation = Reservation::with('member:id,name')->findOrFail($id);
            return new ReservationResource($reservation);
        } catch (ModelNotFoundException $e) {
            return $this->resourceNotFoundResponse('Reservation');
        }
    }

    /**
     * Crear una nueva Reserva
     *
     * @OA\Post(
     *     path="/api/v1/reservations",
     *     tags={"Reservas"},
     *     security={{"sanctum":{}}},
     *     summary="Crear una nueva reserva",
     *     description="Registra una reserva en la base de datos",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreReservationRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reserva creada correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/ReservationResource")
     *     )
     * )
     */
    public function store(StoreReservationRequest $request)
    {

        $reservation = Reservation::create($request->validated());

        return response()
            ->json(new ReservationResource($reservation), 201)
            ->header('Location', route('reservations.show', ['reservation' => $reservation->id]));
    }

    /**
     * Actualizar una Reserva
     *
     * @OA\Put(
     *     path="/api/v1/reservations/{id}",
     *     tags={"Reservas"},
     *     security={{"sanctum":{}}},
     *     summary="Actualizar una reserva",
     *     description="Modifica una reserva existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateReservationRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reserva actualizada correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/ReservationResource")
     *     )
     * )
     */
    public function update(UpdateReservationRequest $request, $id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return $this->resourceNotFoundResponse('Reservation');
        }

        $reservation->update($request->validated());
        return new ReservationResource($reservation);
    }

    /**
     * Actualizar parcialmente una Reserva
     *
     * @OA\Patch(
     *     path="/api/v1/reservations/{id}",
     *     tags={"Reservas"},
     *     security={{"sanctum":{}}},
     *     summary="Actualizar parcialmente una reserva",
     *     description="Modifica solo los campos enviados en la solicitud",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la reserva",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateReservationRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reserva actualizada correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/ReservationResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reserva no encontrada"
     *     )
     * )
     */
    public function updatePartial(UpdateReservationRequest $request, $id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return $this->resourceNotFoundResponse('Reservation');
        }

        $validatedData = $request->validated();

        if (!empty($validatedData)) {
            $reservation->update($validatedData);
        }

        return new ReservationResource($reservation);
    }

    /**
     * Eliminar una Reserva
     *
     * @OA\Delete(
     *     path="/api/v1/reservations/{id}",
     *     tags={"Reservas"},
     *     security={{"sanctum":{}}},
     *     summary="Eliminar una reserva",
     *     description="Borra una reserva usando su ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la reserva",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Reserva eliminada correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reserva no encontrada"
     *     )
     * )
     */
    public function destroy($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return $this->resourceNotFoundResponse('Reservation');
        }

        $reservation->delete();
        return response()->noContent();
    }

    /**
     * Obtener reservas por día
     *
     * @OA\Get(
     *     path="/api/v1/reservations/by-day",
     *     tags={"Reservas"},
     *     security={{"sanctum":{}}},
     *     summary="Obtener reservas por día",
     *     description="Devuelve todas las reservas de una fecha específica",
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         required=true,
     *         description="Fecha en formato YYYY-MM-DD",
     *         @OA\Schema(type="string", format="date", example="2025-06-15")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de reservas en la fecha especificada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="reservations",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/ReservationResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en la validación de la fecha"
     *     )
     * )
     */
    public function reservationsByDay(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);

        $date = $validated['date'];

        $reservations = Reservation::where('date', $date)
            ->with([
                'member:id,name',
                'court:id,name,sport_id',
                'court.sport:id,name',
            ])
            ->orderBy('time')
            ->get();

        return response()->json(['reservations' => $reservations]);
    }
}
