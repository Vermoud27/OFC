<?php

namespace App\Models;

use CodeIgniter\Model;

class FAQModel extends Model
{
    // Nom de la table
    protected $table = 'FAQ';

    // Clé primaire
    protected $primaryKey = 'id_faq';

    // Type de retour des résultats
    protected $returnType = 'array';

    // Champs autorisés pour les opérations d'insertion ou de mise à jour
    protected $allowedFields = [
        'question',
        'reponse'
    ];

    // Validation des données
    protected $validationRules = [
        'question' => 'required',
        'reponse' => 'required'
    ];

    // Messages personnalisés pour la validation
    protected $validationMessages = [
        'question' => [
            'required' => 'La question est obligatoire.',
        ],
        'reponse' => [
            'required' => 'La réponse est obligatoire.',
        ],
    ];
}
