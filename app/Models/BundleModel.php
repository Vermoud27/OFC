<?php

namespace App\Models;

use CodeIgniter\Model;

class BundleModel extends Model
{
    // Nom de la table
    protected $table = 'Bundle';

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
}
