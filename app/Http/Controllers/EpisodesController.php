<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Season;
use App\Models\Episode;

class EpisodesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Season $season)
    { 
        return view('episodes.index', [
            'episodes' => $season->episodes,
            'mensagemSucesso' =>session('mensagem.sucesso') 
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Season $season)
    {
        $watchedEpisodes = $request->episodes;

        if (!is_array($watchedEpisodes)) {
            $watchedEpisodes = [];
        }

        $season->episodes->each(function (Episode $episode) use ($watchedEpisodes) {
            $episode->watched = in_array($episode->id, $watchedEpisodes);            
        });

        $season->push();

        return to_route('episodes.index', $season->id)
            ->with('mensagem.sucesso', 'Episódios marcados como assistidos');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
