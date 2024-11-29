<?php

namespace App\Models;

use CodeIgniter\Model;

class ArticleModel extends Model
{
    // Nom de la table
    protected $table = 'Article';

    // Clé primaire
    protected $primaryKey = 'id_article';

    // Type de retour des résultats
    protected $returnType = 'array';

    // Champs autorisés pour les opérations d'insertion ou de mise à jour
    protected $allowedFields = [
        'texte',
        'date'
    ];

    // Validation des données
    protected $validationRules = [
        'texte' => 'required',
        'date' => 'required|valid_date'
    ];

    // Messages personnalisés pour la validation
    protected $validationMessages = [
        'texte' => [
            'required' => 'Le texte de l\'article est obligatoire.',
        ],
        'date' => [
            'required' => 'La date de l\'article est obligatoire.',
            'valid_date' => 'La date de l\'article doit être valide.'
        ],
    ];

}
