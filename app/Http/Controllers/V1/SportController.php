<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\V1\Controller;
use App\Models\V1\Sport;
use App\Http\Requests\V1\StoreSportRequest;
use App\Http\Requests\V1\UpdateSportRequest;
use App\Http\Resources\V1\SportResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @OA\Tag(
 *     name="Deportes",
 *     description="Endpoints para gestionar deportes"
 * )
 */
class SportController extends Controller
{
    /**
     * Obtener lista de deportes
     *
     * @OA\Get(
     *     path="/api/v1/sports",
     *     tags={"Deportes"},
     *     security={{"sanctum":{}}},
     *     summary="Obtener lista de deportes",
     *     description="Devuelve un listado paginado de deportes junto con sus canchas asociadas",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de deportes",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/SportResource")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $sports = Sport::with('courts:id,name,sport_id')->orderBy('id', 'asc')->paginate(5);
        return SportResource::collection($sports);
    }

    /**
     * Obtener un deporte específico
     *
     * @OA\Get(
     *     path="/api/v1/sports/{id}",
     *     tags={"Deportes"},
     *     security={{"sanctum":{}}},
     *     summary="Obtener un deporte por ID",
     *     description="Devuelve los datos de un deporte específico junto con sus canchas asociadas",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del deporte",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Deporte encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/SportResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Deporte no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $sport = Sport::with('courts:id,name,sport_id')->findOrFail($id);
            return new SportResource($sport);
        } catch (ModelNotFoundException $e) {
            return $this->resourceNotFoundResponse('Sport');
        }
    }

    /**
     * Crear un nuevo deporte
     *
     * @OA\Post(
     *     path="/api/v1/sports",
     *     tags={"Deportes"},
     *     security={{"sanctum":{}}},
     *     summary="Crear un nuevo deporte",
     *     description="Registra un nuevo deporte en la base de datos",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreSportRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Deporte creado correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/SportResource")
     *     )
     * )
     */
    public function store(StoreSportRequest $request)
    {
        $sport = Sport::create($request->validated());

        return response()
            ->json(new SportResource($sport), 201)
            ->header('Location', route('sports.show', ['sport' => $sport->id]));
    }

    /**
     * Actualizar un deporte
     *
     * @OA\Put(
     *     path="/api/v1/sports/{id}",
     *     tags={"Deportes"},
     *     security={{"sanctum":{}}},    
     *     summary="Actualizar un deporte",
     *     description="Modifica un deporte existente en la base de datos",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del deporte",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateSportRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Deporte actualizado correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/SportResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Deporte no encontrado"
     *     )
     * )
     */
    public function update(UpdateSportRequest $request, $id)
    {
        $sport = Sport::find($id);

        if (!$sport) {
            return $this->resourceNotFoundResponse('Sport');
        }

        $sport->update($request->validated());
        return new SportResource($sport);
    }

    /**
     * Actualizar parcialmente un deporte
     *
     * @OA\Patch(
     *     path="/api/v1/sports/{id}",
     *     tags={"Deportes"},
     *     security={{"sanctum":{}}},
     *     summary="Actualizar parcialmente un deporte",
     *     description="Modifica solo los campos enviados en la solicitud",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del deporte",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateSportRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Deporte actualizado correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/SportResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Deporte no encontrado"
     *     )
     * )
     */
    public function updatePartial(UpdateSportRequest $request, $id)
    {
        $sport = Sport::find($id);

        if (!$sport) {
            return $this->resourceNotFoundResponse('Sport');
        }

        $validatedData = $request->validated();

        if (!empty($validatedData)) {
            $sport->update($validatedData);
        }

        return new SportResource($sport);
    }

    /**
     * Eliminar un deporte
     *
     * @OA\Delete(
     *     path="/api/v1/sports/{id}",
     *     tags={"Deportes"},
     *     security={{"sanctum":{}}},
     *     summary="Eliminar un deporte",
     *     description="Borra un deporte usando su ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del deporte",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Deporte eliminado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Deporte no encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $sport = Sport::find($id);

        if (!$sport) {
            return $this->resourceNotFoundResponse('Sport');
        }

        $sport->delete();
        return response()->noContent();
    }
}
