<?php

namespace App\Models;

use CodeIgniter\Model;

class CodePromoModel extends Model
{
    // Nom de la table
    protected $table = 'CodePromo';

    // Clé primaire
    protected $primaryKey = 'id_codepromo';

    // Type de retour des résultats
    protected $returnType = 'array';

    // Champs autorisés pour les opérations d'insertion ou de mise à jour
    protected $allowedFields = [
        'code',
        'valeur',
        'actif'
    ];

    // Validation des données
    protected $validationRules = [
        'code' => 'required|max_length[50]|is_unique[CodePromo.code]',
        'valeur' => 'required|decimal',
        'actif' => 'required|boolean'
    ];

    // Messages personnalisés pour la validation
    protected $validationMessages = [
        'code' => [
            'required' => 'Le code promo est obligatoire.',
            'max_length' => 'Le code promo ne peut pas dépasser 50 caractères.',
            'is_unique' => 'Le code promo doit être unique.'
        ],
        'valeur' => [
            'required' => 'La valeur du code promo est obligatoire.',
            'decimal' => 'La valeur du code promo doit être un nombre décimal.'
        ],
        'actif' => [
            'required' => 'L\'état du code promo est obligatoire.',
            'boolean' => 'L\'état doit être soit vrai, soit faux.'
        ]
    ];

    // Méthode pour récupérer un code promo actif par son code
    public function getActiveCodeByCode($code)
    {
        return $this->where('code', $code)->where('actif', true)->first();
    }

    // Méthode pour désactiver un code promo
    public function deactivateCode($id_codepromo)
    {
        return $this->update($id_codepromo, ['actif' => false]);
    }
}
