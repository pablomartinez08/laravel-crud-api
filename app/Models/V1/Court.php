<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    use HasFactory;

    protected $table = 'courts';
    protected $fillable = ['name', 'sport_id'];

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
