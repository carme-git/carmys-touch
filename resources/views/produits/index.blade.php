<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des produits</title>
</head>
<body>
    <h1>Nos produits</h1>
    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Prix de vente</th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produits as $produit)
                <tr>
                    <td>{{ $produit->nom }}</td>
                    <td>{{ $produit->categorie->nom }}</td>
                    <td>{{ $produit->prix_vente }} FCFA</td>
                    <td>{{ $produit->quantite_stock }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>