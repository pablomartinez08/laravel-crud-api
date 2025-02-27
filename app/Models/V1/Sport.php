<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    use HasFactory;

    protected $table = 'sports';

    protected $fillable = ['name', 'description'];

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function courts()
    {
        return $this->hasMany(Court::class);
    }
}
