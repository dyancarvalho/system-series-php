<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\SeriesFormRequest;
use App\Models\Season;
use App\Models\Episode;
use App\Models\Series;

class EloquentSeriesRepository implements SeriesRepository
{
    public function add(SeriesFormRequest $request): Series
    {
        return DB::transaction(function () use ($request, &$series) {
            $series = Series::create([
                'nome' => $request->nome,
                'cover'=> $request->coverPath,
            ]);
            $seasons = [];
            for($i = 1; $i <= $request->seasonsQty; $i++) {  
                $seasons[] = [
                    'series_id' => $series->id,
                    'number' => $i,
                ];
            }
            Season::insert($seasons);
    
            $episodes = [];
            foreach ($series->seasons as $season) {
                for($j = 1; $j < $request->episodesPerSeason; $j++) {
                    $episodes[] = [
                        'season_id' => $season->id,
                        'number' => $j
                    ];
                }
            }
            Episode::insert($episodes); 

            return $series;
        });
    }
}