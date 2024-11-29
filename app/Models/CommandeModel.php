<?php

namespace App\Models;

use CodeIgniter\Model;

class CommandeModel extends Model
{
    // Nom de la table
    protected $table = 'Commande';

    // Clé primaire de la table
    protected $primaryKey = 'id_commande';

    // Retour des données sous forme de tableau associatif
    protected $returnType = 'array';

    // Champs autorisés pour les opérations d'insertion ou de mise à jour
    protected $allowedFields = [
        'date_creation',
        'statut',
        'prixht',
        'prixttc',
        'id_utilisateur'
    ];

    // Validation des données
    protected $validationRules = [
        'date_creation' => 'required|valid_date',
        'statut'        => 'required|max_length[20]',
        'prixht'        => 'required|decimal',
        'prixttc'       => 'required|decimal',
        'id_utilisateur'=> 'required|integer|is_not_unique[Utilisateur.id_utilisateur]',
    ];

    // Messages personnalisés pour la validation
    protected $validationMessages = [
        'id_utilisateur' => [
            'is_not_unique' => 'L\'utilisateur associé n\'existe pas.'
        ]
    ];
}
