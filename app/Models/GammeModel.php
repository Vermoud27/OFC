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
}
