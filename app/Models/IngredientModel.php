<?php

namespace App\Models;

use CodeIgniter\Model;

class IngredientModel extends Model
{
    // Nom de la table
    protected $table = 'Ingredient';

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
}
