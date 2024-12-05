<?php

namespace App\Models;

use CodeIgniter\Model;

class UtilisateurModel extends Model
{
    // Nom de la table
    protected $table = 'utilisateur';

    // Clé primaire de la table
    protected $primaryKey = 'id_utilisateur';

    // Retour des données sous forme de tableau associatif
    protected $returnType = 'array';

    // Champs autorisés pour les opérations d'insertion ou de mise à jour
    protected $allowedFields = [
        'nom',
        'prenom',
        'role',
        'mail',
        'telephone',
        'adresse',
        'code_postal',
        'ville',
        'mdp',
        'reset_token',
        'reset_token_expiration',
        'is_active',
    ];

    // Validation des données
    protected $validationRules = [
        'nom'      => 'required|max_length[50]',
        'prenom'   => 'required|max_length[50]',
        'role'     => 'required|max_length[20]',
        'mail'     => 'required|valid_email|max_length[100]|is_unique[utilisateur.mail,id_utilisateur,{id_utilisateur}]',
        'telephone'=> 'permit_empty|max_length[15]',
        'mdp'      => 'required|min_length[8]',
    ];

    // Messages personnalisés pour la validation
    protected $validationMessages = [
        'mail' => [
            'is_unique' => 'Cette adresse e-mail est déjà utilisée.'
        ],
        'mdp' => [
            'min_length' => 'Le mot de passe doit contenir au moins 8 caractères.'
        ]
    ];
}
