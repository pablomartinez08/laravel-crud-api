<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    protected $fillable = ['member_id', 'court_id', 'date', 'time'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function court()
    {
        return $this->belongsTo(Court::class);
    }
}
