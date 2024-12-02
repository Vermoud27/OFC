<?php

namespace App\Models;

use CodeIgniter\Model;

class ImageModel extends Model
{
    // Nom de la table
    protected $table = 'image';

    // Clé primaire
    protected $primaryKey = 'id_image';

    // Type de retour des résultats
    protected $returnType = 'array';

    // Champs autorisés pour les opérations d'insertion ou de mise à jour
    protected $allowedFields = [
        'chemin',
        'description',
        'id_produit'
    ];

    // Validation des données
    protected $validationRules = [
        'chemin' => 'required|max_length[255]',
        'id_produit' => 'required|integer|is_not_unique[produit.id_produit]',
    ];

    // Messages personnalisés pour la validation
    protected $validationMessages = [
        'chemin' => [
            'required' => 'Le chemin de l\'image est obligatoire.',
            'max_length' => 'Le chemin de l\'image ne peut pas dépasser 255 caractères.',
        ],
        'id_produit' => [
            'required' => 'L\'identifiant du produit est obligatoire.',
            'integer' => 'L\'identifiant du produit doit être un entier.',
            'is_not_unique' => 'Le produit spécifié n\'existe pas.',
        ],
    ];

    // Méthode pour récupérer toutes les images d'un produit
    public function getImagesByProduit($id_produit)
    {
        return $this->where('id_produit', $id_produit)->findAll();
    }
}
