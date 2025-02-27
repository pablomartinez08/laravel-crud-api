<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\V1\Controller;
use App\Http\Requests\V1\StoreMemberRequest;
use App\Http\Requests\V1\UpdateMemberRequest;
use App\Models\V1\Member;
use App\Http\Resources\V1\MemberResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @OA\Tag(
 *     name="Socios",
 *     description="Endpoints para gestionar socios"
 * )
 */
class MemberController extends Controller
{
    /**
     * Listado paginado de Socios
     *
     * @OA\Get(
     *     path="/api/v1/members",
     *     tags={"Socios"},
     *     security={{"sanctum":{}}},
     *     summary="Obtener lista de socios",
     *     description="Retorna un listado paginado de socios con sus reservas asociadas",
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número de página para la paginación",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de socios",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/MemberResource")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $members = Member::with('reservations:court_id,date,time')->orderBy('id', 'asc')->paginate(5);
        return MemberResource::collection($members);
    }

    /**
     * Mostrar un Socio específico
     *
     * @OA\Get(
     *     path="/api/v1/members/{id}",
     *     tags={"Socios"},
     *     security={{"sanctum":{}}},
     *     summary="Obtener un socio por ID",
     *     description="Retorna un socio en base a su ID",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del Socio",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Socio encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/MemberResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Socio no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $member = Member::with('reservations:court_id,date,time')->findOrFail($id);
            return new MemberResource($member);
        } catch (ModelNotFoundException $e) {
            return $this->resourceNotFoundResponse('Member');
        }
    }

    /**
     * Crear un nuevo Socio
     *
     * @OA\Post(
     *     path="/api/v1/members",
     *     tags={"Socios"},
     *     security={{"sanctum":{}}},
     *     summary="Crear un nuevo socio",
     *     description="Crea un socio con la información proporcionada",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para crear un socio",
     *         @OA\JsonContent(ref="#/components/schemas/StoreMemberRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Socio creado correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/MemberResource")
     *     )
     * )
     */
    public function store(StoreMemberRequest $request)
    {
        $member = Member::create($request->validated());

        return response()
            ->json(new MemberResource($member), 201)
            ->header('Location', route('members.show', ['member' => $member->id]));
    }

    /**
     * Actualizar un Socio (PUT)
     *
     * @OA\Put(
     *     path="/api/v1/members/{id}",
     *     tags={"Socios"},
     *     security={{"sanctum":{}}},
     *     summary="Actualizar un socio (reemplazo total)",
     *     description="Actualiza la información completa de un socio",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del socio",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para actualizar el socio",
     *         @OA\JsonContent(ref="#/components/schemas/UpdateMemberRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Socio actualizado",
     *         @OA\JsonContent(ref="#/components/schemas/MemberResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Socio no encontrado"
     *     )
     * )
     */
    public function update(UpdateMemberRequest $request, $id)
    {
        $member = Member::find($id);

        if (!$member) {
            return $this->resourceNotFoundResponse('Member');
        }

        $member->update($request->validated());
        return new MemberResource($member);
    }

    /**
     * Actualizar parcialmente un Socio (PATCH)
     *
     * @OA\Patch(
     *     path="/api/v1/members/{id}",
     *     tags={"Socios"},
     *     security={{"sanctum":{}}},
     *     summary="Actualizar parcialmente un socio",
     *     description="Sólo actualiza los campos enviados en el request",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del socio",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\RequestBody(
     *         required=false,
     *         description="Datos para actualizar parcialmente el socio",
     *         @OA\JsonContent(ref="#/components/schemas/UpdateMemberRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Socio actualizado",
     *         @OA\JsonContent(ref="#/components/schemas/MemberResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Socio no encontrado"
     *     )
     * )
     */
    public function updatePartial(UpdateMemberRequest $request, $id)
    {
        $member = Member::find($id);

        if (!$member) {
            return $this->resourceNotFoundResponse('Member');
        }

        $validatedData = $request->validated();

        if (!empty($validatedData)) {
            $member->update($validatedData);
        }

        return new MemberResource($member);
    }

    /**
     * Eliminar un Socio
     *
     * @OA\Delete(
     *     path="/api/v1/members/{id}",
     *     tags={"Socios"},
     *     security={{"sanctum":{}}},
     *     summary="Eliminar un socio",
     *     description="Borra un socio usando su ID",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del socio",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Socio eliminado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Socio no encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $member = Member::find($id);

        if (!$member) {
            return $this->resourceNotFoundResponse('Member');
        }

        $member->delete();
        return response()->noContent();
    }
}
