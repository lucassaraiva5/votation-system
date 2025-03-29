<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Slate;
use App\Models\Vote;

class VoteController extends Controller
{
    public function showVoteForm($hash)
    {
        // Busca empresa pelo hash
        $company = Company::where('hash', $hash)->firstOrFail();
        
        // Busca todas as chapas
        $chapas = Slate::all();

        // Retorna uma view (exemplo: resources/views/vote.blade.php)
        return view('vote', compact('company', 'chapas'));
    }

    public function submitVote(Request $request, $hash)
    {
        // Busca empresa pelo hash
        $company = Company::where('hash', $hash)->firstOrFail();

        // Se quiser garantir que a empresa só vote 1 vez,
        // verifique se ela já votou:
        $existingVote = Vote::where('company_id', $company->id)->first();
        if ($existingVote) {
            return redirect()->back()->with('error', 'Você já realizou seu voto!');
        }

        // Valida a chapa
        $request->validate([
            'slate_id' => 'required|exists:slates,id',
        ]);

        // Salva o voto
        Vote::create([
            'company_id' => $company->id,
            'slate_id' => $request->input('slate_id'),
        ]);


        return redirect()->back()->with('success', 'Voto registrado com sucesso!');
    }
}
