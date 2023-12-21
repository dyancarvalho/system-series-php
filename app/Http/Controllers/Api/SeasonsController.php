<?php

namespace App\Http\Controllers\Api;

use App\Models\Series;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SeasonsController extends Controller
{
    public function index(Series $series)
    {
        return $series->seasons;    
    }

    public function show(Series $series)
    {
        return $series->episodes;    
    }
}
