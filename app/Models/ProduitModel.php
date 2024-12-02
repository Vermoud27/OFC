<?php

namespace App\Models;

use CodeIgniter\Model;

class ProduitModel extends Model
{
    // Nom de la table
    protected $table = 'produit';

    // Clé primaire de la table
    protected $primaryKey = 'id_produit';

    // Type de retour des résultats
    protected $returnType = 'array';

    // Champs autorisés pour les opérations d'insertion ou de mise à jour
    protected $allowedFields = [
        'nom',
        'description',
        'prixht',
        'prixttc',
        'qte_stock',
        'unite_mesure',
        'promotion',
        'id_categorie',
        'id_gamme',
        'actif',
    ];

    // Validation des données
    protected $validationRules = [
        'nom' => 'required|max_length[100]',
        'description' => 'permit_empty',
        'prixht' => 'required|decimal',
        'prixttc' => 'required|decimal',
        'qte_stock' => 'required|integer',
        'unite_mesure' => 'permit_empty|max_length[50]',
        'promotion' => 'permit_empty|decimal',
    ];

    // Messages personnalisés pour la validation
    protected $validationMessages = [
        'nom' => [
            'required' => 'Le nom du produit est obligatoire.',
            'max_length' => 'Le nom du produit ne doit pas dépasser 100 caractères.'
        ],
        'prixht' => [
            'required' => 'Le prix HT est obligatoire.',
            'decimal' => 'Le prix HT doit être un nombre décimal valide.'
        ],
        'prixttc' => [
            'required' => 'Le prix TTC est obligatoire.',
            'decimal' => 'Le prix TTC doit être un nombre décimal valide.'
        ],
        'qte_stock' => [
            'required' => 'La quantité en stock est obligatoire.',
            'integer' => 'La quantité en stock doit être un nombre entier.'
        ],
        'id_categorie' => [
            'required' => 'La catégorie du produit est obligatoire.',
            'integer' => 'L\'ID de la catégorie doit être un entier valide.'
        ],
        'id_gamme' => [
            'required' => 'La gamme du produit est obligatoire.',
            'integer' => 'L\'ID de la gamme doit être un entier valide.'
        ]
    ];

    // Relations (si tu veux inclure les relations avec Categorie et Gamme)
    public function getCategorie()
    {
        return $this->hasOne('App\Models\CategorieModel', 'id_categorie', 'id_categorie');
    }

    public function getGamme()
    {
        return $this->hasOne('App\Models\GammeModel', 'id_gamme', 'id_gamme');
    }
}
