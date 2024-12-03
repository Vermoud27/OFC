<?php

namespace App\Models;

use CodeIgniter\Model;

class ProduitIngredientModel extends Model
{
    // Nom de la table
    protected $table = 'produit_ingredient';

    // Clé primaire composite
    protected $primaryKey = 'id_produit_ingredient';

    // Type de retour des résultats
    protected $returnType = 'array';

    // Champs autorisés pour les opérations d'insertion ou de mise à jour
    protected $allowedFields = [
        'id_produit',
        'id_ingredient'
    ];

    // Validation des données
    protected $validationRules = [
        'id_produit' => 'required|integer',
        'id_ingredient' => 'required|integer'
    ];

    // Messages personnalisés pour la validation
    protected $validationMessages = [
        'id_produit' => [
            'required' => 'L\'ID du produit est obligatoire.',
            'integer' => 'L\'ID du produit doit être un entier.'
        ],
        'id_ingredient' => [
            'required' => 'L\'ID de l\'ingrédient est obligatoire.',
            'integer' => 'L\'ID de l\'ingrédient doit être un entier.'
        ]
    ];

    // Méthode pour récupérer les produits associés à un ingrédient
    public function getProduitsByIngredient($id_ingredient)
    {
        return $this->where('id_ingredient', $id_ingredient)->findAll();
    }

    // Méthode pour récupérer les ingrédients associés à un produit
    public function getIngredientsByProduit($id_produit)
    {
        return $this->where('id_produit', $id_produit)->findAll();
    }
}
