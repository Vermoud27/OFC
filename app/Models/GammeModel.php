<?php

namespace App\Models;

use CodeIgniter\Model;

class GammeModel extends Model
{
    // Nom de la table
    protected $table = 'gamme';

    // Clé primaire de la table
    protected $primaryKey = 'id_gamme';

    // Type de retour des résultats
    protected $returnType = 'array';

    // Champs autorisés pour les opérations d'insertion ou de mise à jour
    protected $allowedFields = [
        'nom',
        'description',
        'prixht',
        'prixttc'
    ];

    // Validation des données
    protected $validationRules = [
        'nom' => 'required|max_length[100]',
        'description' => 'permit_empty',
        'prixht' => 'permit_empty|decimal',
        'prixttc' => 'permit_empty|decimal'
    ];

    // Messages personnalisés pour la validation
    protected $validationMessages = [
        'nom' => [
            'required' => 'Le nom de la gamme est obligatoire.',
            'max_length' => 'Le nom de la gamme ne doit pas dépasser 100 caractères.'
        ],
        'prixht' => [
            'decimal' => 'Le prix HT doit être un nombre décimal valide.'
        ],
        'prixttc' => [
            'decimal' => 'Le prix TTC doit être un nombre décimal valide.'
        ]
    ];

    public function getTopGammes($limit)
    {
        return $this->select('gamme.*, SUM(details_commande.quantite) as total_quantite')
            ->join('produit', 'gamme.id_gamme = produit.id_gamme')
            ->join('details_commande', 'produit.id_produit = details_commande.id_produit')
            ->join('commande', 'details_commande.id_commande = commande.id_commande')
            ->whereNotIn('commande.statut', ['fini', 'annulé'])
            ->groupBy('gamme.id_gamme')
            ->orderBy('total_quantite', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

}
