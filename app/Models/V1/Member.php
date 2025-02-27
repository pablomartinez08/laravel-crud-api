<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';

    protected $fillable = ['name', 'email', 'phone'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
