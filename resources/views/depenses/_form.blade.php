<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Catégorie</label>
        <input type="text" name="categorie" list="categories"
               value="{{ old('categorie', $depense->categorie) }}" maxlength="100" required
               class="form-control @error('categorie') is-invalid @enderror">
        <datalist id="categories">
            <option value="Transport">
            <option value="Emballage">
            <option value="Internet / data">
            <option value="Publicité">
            <option value="Autre">
        </datalist>
        @error('categorie') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Montant</label>
        <input type="number" name="montant" min="1" step="1"
               value="{{ old('montant', $depense->montant ? (int) $depense->montant : '') }}" required
               class="form-control @error('montant') is-invalid @enderror">
        @error('montant') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Date</label>
        <input type="date" name="date_depense"
               value="{{ old('date_depense', optional($depense->date_depense)->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required
               class="form-control @error('date_depense') is-invalid @enderror">
        @error('date_depense') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <label class="form-label">Description (facultatif)</label>
        <textarea name="description" rows="2" class="form-control @error('description') is-invalid @enderror">{{ old('description', $depense->description) }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>