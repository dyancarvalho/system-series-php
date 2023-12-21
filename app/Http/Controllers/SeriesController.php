<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\User;
use App\Models\Series;
use App\Mail\SeriesCreated;
use App\Events\SeriesCreated as SeriesCreatedEvent;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use App\Repositories\SeriesRepository;
use App\Http\Requests\SeriesFormRequest;

class SeriesController extends Controller
{

    public function __construct(private SeriesRepository $repository)
    {
    }
    
    public function index(Request $request)
    {
        $series = Series::all();
        $mensagemSucesso = $request->session()->get('mensagem.sucesso');

        return view('series.index')->with('series', $series)
        ->with('mensagemSucesso', $mensagemSucesso);
    }

    public function create()
    {
        return view('series.create');
    }

    public function store(SeriesFormRequest $request)
    {        
        $request->validate([
            'cover' => 'image|mimes:jpeg,png,jpg,gif', 
        ]);
            
        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
            $coverPath = $request->file('cover')->store('series_cover', 'public');
        } else {
            $coverPath = 'series_cover/default.png';
        }
            
        $request->coverPath = $coverPath;
    
        $serie = $this->repository->add($request);
        if (!$request->hasFile('cover') || !$request->file('cover')->isValid()) {
            $serie->cover = $coverPath;
            $serie->save();
        }
    
        \App\Events\SeriesCreated::dispatch(
            $serie->nome,
            $serie->id,
            $request->seasonsQty,
            $request->episodesPerSeason
        );
    
        return to_route('series.index')
            ->with('mensagem.sucesso', "Série '{$serie->nome}' adicionada com sucesso");
    }
    
    public function edit(Series $series)
    {
        return view('series.edit')->with('serie', $series);
    }

    public function update(SeriesFormRequest $request, Series $series)
    {
        $series->nome = $request->nome;
        $series->save();

        return to_route('series.index')
        ->with('mensagem.sucesso', "Série '{$series->nome}' atualizada com sucesso");
    }

    public function destroy(Series $series)
    {
        if ($series->cover && $series->cover !== 'series_cover/default.png') {
            $coverPath = $series->cover;  
            $series->delete();  

            if (\Storage::disk('public')->exists($coverPath)) {
                \Storage::disk('public')->delete($coverPath);
            }    

            return redirect()->route('series.index')
                ->with('mensagem.sucesso', "Série '{$series->nome}' removida com sucesso");
        } else {
            $series->delete();

            return redirect()->route('series.index')
                ->with('mensagem.sucesso', "Série '{$series->nome}' removida com sucesso");
        }
    }
    
}
