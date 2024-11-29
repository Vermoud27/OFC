<?php

namespace App\Models;

use CodeIgniter\Model;

class BundleProduitModel extends Model
{
    // Nom de la table
    protected $table = 'Bundle_Produit';

    // Clé primaire composée
    protected $primaryKey = ['id_bundle', 'id_produit'];

    // Type de retour des résultats
    protected $returnType = 'array';

    // Champs autorisés pour les opérations d'insertion ou de mise à jour
    protected $allowedFields = [
        'id_bundle',
        'id_produit',
        'quantite'
    ];

    // Validation des données
    protected $validationRules = [
        'id_bundle' => 'required|integer',
        'id_produit' => 'required|integer',
        'quantite' => 'required|integer'
    ];

    // Messages personnalisés pour la validation
    protected $validationMessages = [
        'id_bundle' => [
            'required' => 'L\'ID du bundle est obligatoire.',
            'integer' => 'L\'ID du bundle doit être un entier.'
        ],
        'id_produit' => [
            'required' => 'L\'ID du produit est obligatoire.',
            'integer' => 'L\'ID du produit doit être un entier.'
        ],
        'quantite' => [
            'required' => 'La quantité est obligatoire.',
            'integer' => 'La quantité doit être un entier.'
        ]
    ];

    // Requête de jointure pour récupérer les produits associés à un bundle
    public function getProductsByBundle($id_bundle)
    {
        return $this->select('Bundle_Produit.*, Produit.nom AS produit_nom, Produit.prixht, Produit.prixttc')
                    ->join('Produit', 'Produit.id_produit = Bundle_Produit.id_produit')
                    ->where('id_bundle', $id_bundle)
                    ->findAll();
    }

    // Requête de jointure pour récupérer les bundles associés à un produit
    public function getBundlesByProduct($id_produit)
    {
        return $this->select('Bundle_Produit.*, Bundle.description AS bundle_description')
                    ->join('Bundle', 'Bundle.id_bundle = Bundle_Produit.id_bundle')
                    ->where('id_produit', $id_produit)
                    ->findAll();
    }
}
