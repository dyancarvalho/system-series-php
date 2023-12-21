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

    public function show(Series $series, int $seasonId)
    {
        $season = $series->seasons()->find($seasonId);    

        if(!$season) {
            return response()->json(['error' => 'Temporada não encontrada.'], 404);
        }

        return $season;
    }

    public function destroy(Series $series, int $seasonId)
    {
        $season = $series->seasons()->find($seasonId);
    
        if (!$season) {
            return response()->json(['error' => 'Temporada não encontrada.'], 404);
        }
    
        $season->delete();            
        return response()->noContent();
    }
}
