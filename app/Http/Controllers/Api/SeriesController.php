<?php

namespace App\Http\Controllers\Api;

use App\Models\Series;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\SeriesRepository;
use App\Http\Requests\SeriesFormRequest;

class SeriesController extends Controller
{
    public function __construct(private SeriesRepository $seriesRepository)
    {        
    }


    public function index(Request $request)
    {
        $query = Series::query();
        if($request->has('nome')) {
            $query->where('nome', $request->nome);
        } 

        return $query->paginate(5);
    }

    public function store(SeriesFormRequest $request)
    {
        return 
        response()->json(Series::create($request->all()), 201);
    }

    public function show(int $series)
    {       
        $seriesModel = Series::with('seasons.episodes')->find($series);
        if($seriesModel === null) {
            return response()->json(['message' => 'Series not found'], 404);
        }

        return $seriesModel;
    }

    public function update(Series $series, SeriesFormRequest $request)
    {
        $series->fill($request->all());
        $series->save();

        return $series;
    }
    
    public function destroy(int $series)
    {
        Series::destroy($series);
        return response()->noContent();
    }
}
