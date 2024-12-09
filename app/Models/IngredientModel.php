<?php

namespace App\Models;

use CodeIgniter\Model;

class IngredientModel extends Model
{
    // Nom de la table
    protected $table = 'ingredient';

    // Clé primaire
    protected $primaryKey = 'id_ingredient';

    // Type de retour des résultats
    protected $returnType = 'array';

    // Champs autorisés pour les opérations d'insertion ou de mise à jour
    protected $allowedFields = [
        'nom',
        'provenance'
    ];

    // Validation des données
    protected $validationRules = [
        'nom' => 'required|string|max_length[100]',
        'provenance' => 'permit_empty|string|max_length[100]'
    ];

    // Messages personnalisés pour la validation
    protected $validationMessages = [
        'nom' => [
            'required' => 'Le nom de l\'ingrédient est obligatoire.',
            'string' => 'Le nom de l\'ingrédient doit être une chaîne de caractères.',
            'max_length' => 'Le nom de l\'ingrédient ne doit pas dépasser 100 caractères.'
        ],
        'provenance' => [
            'string' => 'La provenance doit être une chaîne de caractères.',
            'max_length' => 'La provenance ne doit pas dépasser 100 caractères.'
        ]
    ];

    public function getTopIngredients($limit)
    {
        return $this->select('ingredient.*, SUM(details_commande.quantite) as total_quantite')
            ->join('produit_ingredient', 'ingredient.id_ingredient = produit_ingredient.id_ingredient')
            ->join('produit', 'produit_ingredient.id_produit = produit.id_produit')
            ->join('details_commande', 'produit.id_produit = details_commande.id_produit')
            ->join('commande', 'details_commande.id_commande = commande.id_commande')
            ->whereNotIn('commande.statut', ['fini', 'annulé'])
            ->groupBy('ingredient.id_ingredient')
            ->orderBy('total_quantite', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

}
