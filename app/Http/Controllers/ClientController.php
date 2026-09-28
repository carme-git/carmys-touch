<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = Client::with('ventes.paiements')
            ->when($request->q, function ($query, $terme) {
                $query->where(function ($q) use ($terme) {
                    $q->where('nom', 'like', "%{$terme}%")
                      ->orWhere('prenom', 'like', "%{$terme}%")
                      ->orWhere('telephone', 'like', "%{$terme}%");
                });
            })
            ->orderBy('nom')
            ->get();

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create', ['client' => new Client()]);
    }

    public function store(ClientRequest $request)
    {
        $client = Client::create($request->validated());

        return redirect()->route('clients.show', $client)->with('success', 'Cliente ajoutée.');
    }

    public function show(Client $client)
    {
        $client->load(['ventes' => fn ($q) => $q->with('paiements')->orderByDesc('date_vente')]);

        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(ClientRequest $request, Client $client)
    {
        $client->update($request->validated());

        return redirect()->route('clients.show', $client)->with('success', 'Cliente modifiée.');
    }

    public function destroy(Client $client)
    {
        if ($client->ventes()->exists()) {
            return redirect()->route('clients.index')
                ->with('error', 'Impossible de supprimer : cette cliente a des ventes enregistrées.');
        }

        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Cliente supprimée.');
    }
}