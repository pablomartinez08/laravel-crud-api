<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\V1\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Schema(
 *     schema="UpdateReservationRequest",
 *     type="object",
 *     title="Update Reservation Request",
 *     description="Datos para actualizar una reserva",
 *     @OA\Property(property="member_id", type="integer", example=1, description="ID del miembro"),
 *     @OA\Property(property="court_id", type="integer", example=3, description="ID de la pista"),
 *     @OA\Property(property="date", type="string", format="date", example="2025-06-15"),
 *     @OA\Property(property="time", type="string", example="14:00")
 * )
 */
class UpdateReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        if ($this->isMethod('patch')) {
            return [
                'member_id' => 'sometimes|exists:members,id',
                'court_id'  => 'sometimes|exists:courts,id',
                'date'      => 'sometimes|date_format:Y-m-d',
                'time'      => 'sometimes|date_format:H:i',
            ];
        }

        return [
            'member_id' => 'required|exists:members,id',
            'court_id'  => 'required|exists:courts,id',
            'date'      => 'required|date_format:Y-m-d',
            'time'      => 'required|date_format:H:i',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $date    = $this->input('date');
            $time    = $this->input('time');
            $courtId = $this->input('court_id');
            $memberId = $this->input('member_id');

            $reservationId = $this->route('reservation');
            $currentReservation = Reservation::find($reservationId);

            if (!$currentReservation) {
                $validator->errors()->add('reservation', 'La reserva no existe.');
                return;
            }

            $date = $date ?? $currentReservation->date;
            $time = $time ?? $currentReservation->time;
            $courtId = $courtId ?? $currentReservation->court_id;
            $memberId = $memberId ?? $currentReservation->member_id;

            try {
                $newReservationTime = Carbon::parse("$date $time");
            } catch (\Exception $e) {
                return;
            }

            $exists = Reservation::where('court_id', $courtId)
                ->where('date', $date)
                ->where('time', $time)
                ->where('id', '!=', $reservationId)
                ->exists();
            if ($exists) {
                $validator->errors()->add('court_id', 'Esta pista ya tiene una reserva para esa hora.');
            }

            $memberHasReservation = Reservation::where('member_id', $memberId)
                ->where('date', $date)
                ->where('time', $time)
                ->where('id', '!=', $reservationId)
                ->exists();
            if ($memberHasReservation) {
                $validator->errors()->add('member_id', 'El socio ya tiene una reserva para esa hora.');
            }

            $reservationsCount = Reservation::where('member_id', $memberId)
                ->where('date', $date)
                ->where('id', '!=', $reservationId)
                ->count();
            if ($reservationsCount >= 3) {
                $validator->errors()->add('member_id', 'El socio no puede realizar más de 3 reservas en el mismo día.');
            }

            $existingReservations = Reservation::where('court_id', $courtId)
                ->where('date', $date)
                ->where('id', '!=', $reservationId)
                ->get();

            foreach ($existingReservations as $reservation) {
                try {
                    $existingReservationDateTime = Carbon::parse($reservation->date . ' ' . $reservation->time);
                } catch (\Exception $e) {
                    continue;
                }

                $diffInMinutes = $existingReservationDateTime->diffInMinutes($newReservationTime, true);

                Log::info("Comparando nueva reserva ($newReservationTime) con existente ($existingReservationDateTime): diferencia = $diffInMinutes minutos");

                if ($diffInMinutes < 60) {
                    $validator->errors()->add('time', 'Debe haber al menos 1 hora de diferencia con otras reservas en la misma pista.');
                    break;
                }
            }
        });
    }
}
