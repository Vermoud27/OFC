<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailsCommandeModel extends Model
{
    // Nom de la table
    protected $table = 'details_commande';

    // Clé primaire de la table
    protected $primaryKey = 'id_details_commande';

    // Type de retour des résultats
    protected $returnType = 'array';

    // Champs autorisés pour les opérations d'insertion ou de mise à jour
    protected $allowedFields = [
        'id_commande',
        'id_produit',
        'id_bundle',
        'id_gamme',
        'quantite'
    ];

    // Validation des données
    protected $validationRules = [
        'id_commande' => 'required|integer',
        'quantite' => 'required|integer'
    ];

    // Messages personnalisés pour la validation
    protected $validationMessages = [
        'id_commande' => [
            'required' => 'L\'ID de la commande est obligatoire.',
            'integer' => 'L\'ID de la commande doit être un entier.'
        ],
        'quantite' => [
            'required' => 'La quantité est obligatoire.',
            'integer' => 'La quantité doit être un entier.'
        ]
    ];

    // Requête de jointure pour récupérer les détails avec les informations des produits et bundles
    public function getDetailsWithProductsAndBundles($id_commande)
    {
        return $this->select('Details_Commande.*, Produit.nom AS produit_nom, Bundle.description AS bundle_description')
                    ->join('Produit', 'Produit.id_produit = Details_Commande.id_produit', 'left')
                    ->join('Bundle', 'Bundle.id_bundle = Details_Commande.id_bundle', 'left')
                    ->where('id_commande', $id_commande)
                    ->findAll();
    }
}
