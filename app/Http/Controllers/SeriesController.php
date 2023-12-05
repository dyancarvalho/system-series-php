<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeriesFormRequest;
use App\Repositories\SeriesRepository;
use App\Models\Series;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\SeriesCreated;
use Illuminate\Support\Facades\Mail;

class SeriesController extends Controller
{

    public function __construct(private SeriesRepository $repository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $series = Series::all();
        $mensagemSucesso = $request->session()->get('mensagem.sucesso');

        return view('series.index')->with('series', $series)
        ->with('mensagemSucesso', $mensagemSucesso);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('series.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SeriesFormRequest $request)
    {
        $serie = $this->repository->add($request);

        $userList = User::all();
        foreach ($userList as $user) {
            $email = new SeriesCreated(
                $serie->nome,
                $serie->id,
                $request->seasonsQty,
                $request->episodesPerSeason,
            );
            Mail::to($user)->send($email);
        }

        return to_route('series.index')
            ->with('mensagem.sucesso', "Série '{$serie->nome}' adicionada com sucesso");
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
    public function edit(Series $series)
    {
        return view('series.edit')->with('serie', $series);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SeriesFormRequest $request, Series $series)
    {
        $series->nome = $request->nome;
        $series->save();

        return to_route('series.index')
        ->with('mensagem.sucesso', "Série '{$series->nome}' atualizada com sucesso");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Series $series)
    {        
        $series->delete();
        
        return redirect()->route('series.index')
        ->with('mensagem.sucesso', "Série '{$series->nome}' removida com sucesso");
    
    }
}
