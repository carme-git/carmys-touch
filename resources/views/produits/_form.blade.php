@csrf
<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Nom *</label>
        <input type="text" name="nom" value="{{ old('nom', $produit->nom) }}" class="form-control @error('nom') is-invalid @enderror">
        @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
        <div class="col-md-4">
        <label class="form-label">Référence</label>
        <input type="text" name="reference" value="{{ old('reference', $produit->reference) }}" class="form-control @error('reference') is-invalid @enderror">
        @error('reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Catégorie</label>
        <select name="category_id" class="form-select">
            <option value="">Aucune</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}" @selected(old('category_id', $produit->category_id) == $c->id)>{{ $c->nom }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Photo</label>
        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Prix d'achat (F) *</label>
        <input type="number" step="0.01" name="prix_achat" value="{{ old('prix_achat', $produit->prix_achat) }}" class="form-control @error('prix_achat') is-invalid @enderror">
        @error('prix_achat')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Prix de vente (F) *</label>
        <input type="number" step="0.01" name="prix_vente" value="{{ old('prix_vente', $produit->prix_vente) }}" class="form-control @error('prix_vente') is-invalid @enderror">
        @error('prix_vente')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Stock *</label>
        <input type="number" name="quantite_stock" value="{{ old('quantite_stock', $produit->quantite_stock ?? 0) }}" class="form-control @error('quantite_stock') is-invalid @enderror">
        @error('quantite_stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Seuil d'alerte *</label>
        <input type="number" name="seuil_alerte" value="{{ old('seuil_alerte', $produit->seuil_alerte ?? 3) }}" class="form-control @error('seuil_alerte') is-invalid @enderror">
        @error('seuil_alerte')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" rows="3" class="form-control">{{ old('description', $produit->description) }}</textarea>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('produits.index') }}" class="btn btn-outline-secondary">Annuler</a>
    <button type="submit" class="btn btn-rose">Enregistrer</button>
</div>