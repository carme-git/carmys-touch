<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nom</label>
        <input type="text" name="nom" value="{{ old('nom', $fournisseur->nom) }}" maxlength="150" required
               class="form-control @error('nom') is-invalid @enderror">
        @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Téléphone</label>
        <input type="text" name="telephone" value="{{ old('telephone', $fournisseur->telephone) }}" maxlength="20"
               class="form-control @error('telephone') is-invalid @enderror">
        @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">E-mail</label>
        <input type="email" name="email" value="{{ old('email', $fournisseur->email) }}" maxlength="150"
               class="form-control @error('email') is-invalid @enderror">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Adresse</label>
        <input type="text" name="adresse" value="{{ old('adresse', $fournisseur->adresse) }}" maxlength="191"
               class="form-control @error('adresse') is-invalid @enderror">
        @error('adresse') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>