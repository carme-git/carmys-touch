@php
    $f = fn ($n) => number_format($n, 0, ',', ' ') . ' F CFA';

    // Coordonnées affichées en haut de la facture : laisse vide ce que tu ne veux pas montrer
    $entreprise = [
        'nom'       => "Carmy's Touch",
        'devise'    => 'De belles senteurs, selon votre budget et qui vous respectent.',
        'telephone' => '',
    ];

    $statuts = ['paye' => 'Payée', 'partiel' => 'Paiement partiel', 'impaye' => 'Non payée'];
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $facture->numero_facture }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1f2933; }
        h1 { font-size: 24px; color: #2b7bb9; margin: 0; }
        .devise { color: #6b7785; font-size: 10px; margin-top: 4px; }
        .entete { width: 100%; margin-bottom: 28px; }
        .entete td { vertical-align: top; }
        .droite { text-align: right; }
        .titre-facture { font-size: 16px; font-weight: bold; }
        .bloc { margin-bottom: 22px; }
        .etiquette { color: #6b7785; font-size: 10px; text-transform: uppercase; }
        table.lignes { width: 100%; border-collapse: collapse; }
        table.lignes th { background: #e8f2fa; color: #2b7bb9; text-align: left; padding: 7px; font-size: 10px; }
        table.lignes td { padding: 7px; border-bottom: 1px solid #e3eaf0; }
        table.totaux { width: 45%; margin-left: 55%; margin-top: 14px; border-collapse: collapse; }
        table.totaux td { padding: 5px 7px; }
        .total td { font-weight: bold; font-size: 13px; border-top: 2px solid #2b7bb9; }
        .reste td { color: #c0392b; font-weight: bold; }
        .merci { margin-top: 40px; text-align: center; color: #6b7785; font-size: 10px; }
    </style>
</head>
<body>
    <table class="entete">
        <tr>
            <td>
                <h1>{{ $entreprise['nom'] }}</h1>
                <div class="devise">{{ $entreprise['devise'] }}</div>
                @if($entreprise['telephone'])
                    <div class="devise">Tél. : {{ $entreprise['telephone'] }}</div>
                @endif
            </td>
            <td class="droite">
                <div class="titre-facture">Facture {{ $facture->numero_facture }}</div>
                <div>Date : {{ $facture->date_facture->format('d/m/Y') }}</div>
                <div>Vente du {{ $vente->date_vente->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    <div class="bloc">
        <div class="etiquette">Facturé à</div>
        @if($vente->client)
            <strong>{{ $vente->client->nom_complet }}</strong>
            @if($vente->client->telephone)<br>{{ $vente->client->telephone }}@endif
        @else
            <strong>Cliente de passage</strong>
        @endif
    </div>

    <table class="lignes">
        <thead>
            <tr>
                <th style="width:42%">Parfum</th>
                <th>Qté</th>
                <th>Prix unitaire</th>
                <th>Remise</th>
                <th class="droite">Sous-total</th>
            </tr>
        </thead>
        <tbody>
        @foreach($vente->detailsVentes as $ligne)
            <tr>
                <td>{{ $ligne->produit->nom ?? 'Produit supprimé' }}</td>
                <td>{{ $ligne->quantite }}</td>
                <td>{{ $f($ligne->prix_unitaire) }}</td>
                <td>{{ $ligne->remise > 0 ? $f($ligne->remise) : '—' }}</td>
                <td class="droite">{{ $f($ligne->sous_total) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <table class="totaux">
        @if($vente->frais_livraison > 0)
            <tr>
                <td>Livraison</td>
                <td class="droite">{{ $f($vente->frais_livraison) }}</td>
            </tr>
        @endif
        <tr class="total">
            <td>Total</td>
            <td class="droite">{{ $f($vente->montant_total) }}</td>
        </tr>
        <tr>
            <td>Déjà payé</td>
            <td class="droite">{{ $f($vente->montant_paye) }}</td>
        </tr>
        @if($vente->reste_a_payer > 0)
            <tr class="reste">
                <td>Reste à payer</td>
                <td class="droite">{{ $f($vente->reste_a_payer) }}</td>
            </tr>
        @endif
        <tr>
            <td>Statut</td>
            <td class="droite">{{ $statuts[$vente->statut_paiement] ?? $vente->statut_paiement }}</td>
        </tr>
    </table>

    <div class="merci">Merci pour votre confiance.</div>
</body>
</html>