<?php

namespace App\Models;

use  App\Models\Season;
use  App\Models\Episode;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;

    public function series()
    {
        return $this->belongsTo(Serie::class);
    }

    public function episode()
    {
        return $this->hasMany(Episode::class);
    }
}
