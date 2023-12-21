<?php

namespace App\Http\Controllers\Api;

use App\Models\Series;
use App\Models\Episode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EpisodesController extends Controller
{
    public function index(Series $series)
    {   
        return $series->episodes; 
    }

    public function show(Series $series, int $episodeId)
    {
        $episode = $series->episodes()->find($episodeId);
        
        if (!$episode) {
            return response()->json(['error' => 'Episódio não encontrado.'], 404);
        }

        return $episode;
    }

    public function update(Request $request, Episode $episode)
    {
        $episode->watched = $request->watched;
        $episode->save();
        return $episode;
    }

    public function destroy(Series $series, int $episodeId)
    {
        $episode = $series->episodes()->find($episodeId);
    
        if (!$episode) {
            return response()->json(['error' => 'Episódio não encontrado.'], 404);
        }
    
        $episode->delete();            
        return response()->noContent();
    }
}
