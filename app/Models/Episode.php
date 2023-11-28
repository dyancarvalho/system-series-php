<?php

namespace App\Models;


use  App\Models\Season;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

}
