<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    protected $table = 'ventes';

    protected $fillable = [
        'client_id', 'utilisateur_id', 'date_vente', 'montant_total',
        'frais_livraison', 'mode_livraison', 'statut_paiement',
        'annulee_le', 'motif_annulation',
    ];

    protected $casts = [
        'date_vente' => 'datetime',
        'annulee_le' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function detailsVentes()
    {
        return $this->hasMany(DetailVente::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    // Une vente est annulée dès que la date d'annulation est remplie
    public function estAnnulee(): bool
    {
        return $this->annulee_le !== null;
    }

    // Filtre réutilisable : uniquement les ventes non annulées
    public function scopeNonAnnulees($query)
    {
        return $query->whereNull('ventes.annulee_le');
    }

    // Argent réellement encaissé : les paiements remboursés ne comptent plus
    public function getMontantPayeAttribute(): float
    {
        return (float) $this->paiements->whereNull('rembourse_le')->sum('montant');
    }

    public function getResteAPayerAttribute(): float
    {
        if ($this->estAnnulee()) {
            return 0;
        }

        return max(0, (float) $this->montant_total - $this->montant_paye);
    }

    // Le statut se déduit toujours des paiements : on ne le saisit jamais à la main
    public function recalculerStatut(): void
    {
        $paye  = (float) $this->paiements()->whereNull('rembourse_le')->sum('montant');
        $total = (float) $this->montant_total;

        $statut = match (true) {
            $paye <= 0      => 'impaye',
            $paye >= $total => 'paye',
            default         => 'partiel',
        };

        $this->update(['statut_paiement' => $statut]);
    }
}