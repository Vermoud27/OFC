<?php

namespace App\Models;

use CodeIgniter\Model;

class BundleModel extends Model
{
    // Nom de la table
    protected $table = 'bundle';

    // Clé primaire de la table
    protected $primaryKey = 'id_bundle';

    // Type de retour des résultats
    protected $returnType = 'array';

    // Champs autorisés pour les opérations d'insertion ou de mise à jour
    protected $allowedFields = [
        'description',
        'prix'
    ];

    // Validation des données
    protected $validationRules = [
        'description' => 'permit_empty',
        'prix' => 'required|decimal'
    ];

    // Messages personnalisés pour la validation
    protected $validationMessages = [
        'prix' => [
            'required' => 'Le prix du bundle est obligatoire.',
            'decimal' => 'Le prix du bundle doit être un nombre décimal valide.'
        ]
    ];

    public function getTopBundles($limit)
    {
        return $this->select('bundle.*, SUM(details_commande.quantite) as total_quantite')
            ->join('details_commande', 'bundle.id_bundle = details_commande.id_bundle')
            ->join('commande', 'details_commande.id_commande = commande.id_commande')
            ->whereNotIn('commande.statut', ['fini', 'annulé'])
            ->groupBy('bundle.id_bundle')
            ->orderBy('total_quantite', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

}
