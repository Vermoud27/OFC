<?php

namespace App\Models;

use CodeIgniter\Model;

class CategorieModel extends Model
{
    // Nom de la table
    protected $table = 'Categorie';

    // Clé primaire de la table
    protected $primaryKey = 'id_categorie';

    // Type de retour des résultats
    protected $returnType = 'array';

    // Champs autorisés pour les opérations d'insertion ou de mise à jour
    protected $allowedFields = [
        'nom',
        'description'
    ];

    // Validation des données
    protected $validationRules = [
        'nom' => 'required|max_length[100]',
        'description' => 'permit_empty'
    ];

    // Messages personnalisés pour la validation
    protected $validationMessages = [
        'nom' => [
            'required' => 'Le nom de la catégorie est obligatoire.',
            'max_length' => 'Le nom de la catégorie ne doit pas dépasser 100 caractères.'
        ]
    ];
}
