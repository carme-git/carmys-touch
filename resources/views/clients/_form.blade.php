@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nom *</label>
        <input type="text" name="nom" value="{{ old('nom', $client->nom) }}" class="form-control @error('nom') is-invalid @enderror">
        @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Prénom</label>
        <input type="text" name="prenom" value="{{ old('prenom', $client->prenom) }}" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Téléphone (WhatsApp)</label>
        <input type="text" name="telephone" value="{{ old('telephone', $client->telephone) }}" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">E-mail</label>
        <input type="text" name="email" value="{{ old('email', $client->email) }}" class="form-control @error('email') is-invalid @enderror">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label">Adresse</label>
        <input type="text" name="adresse" value="{{ old('adresse', $client->adresse) }}" class="form-control">
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">Annuler</a>
    <button type="submit" class="btn btn-rose">Enregistrer</button>
</div>