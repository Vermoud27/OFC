<?php

namespace App\Models;

use CodeIgniter\Model;

class ProduitModel extends Model
{
    // Nom de la table
    protected $table = 'produit';

    // Clé primaire de la table
    protected $primaryKey = 'id_produit';

    // Type de retour des résultats
    protected $returnType = 'array';

    // Champs autorisés pour les opérations d'insertion ou de mise à jour
    protected $allowedFields = [
        'nom',
        'description',
        'prixht',
        'prixttc',
        'qte_stock',
        'unite_mesure',
        'promotion',
        'id_categorie',
        'id_gamme',
        'actif',
        'contenu',
    ];

    // Validation des données
    protected $validationRules = [
        'nom' => 'required|max_length[100]',
        'description' => 'permit_empty',
        'prixht' => 'required|decimal',
        'prixttc' => 'required|decimal',
        'qte_stock' => 'required|integer',
        'unite_mesure' => 'required|max_length[2]',
        'promotion' => 'permit_empty|decimal',
        'contenu' => 'required|integer',
    ];

    

    // Relations (si tu veux inclure les relations avec Categorie et Gamme)
    public function getCategorie()
    {
        return $this->hasOne('App\Models\CategorieModel', 'id_categorie', 'id_categorie');
    }

    public function getGamme()
    {
        return $this->hasOne('App\Models\GammeModel', 'id_gamme', 'id_gamme');
    }

    public function get_all()
	{
		return $this->findAll();
	}

    public function getTopProduits()
    {
        return $this->select('produit.id_produit, produit.nom, SUM(details_commande.quantite) as total_quantite')
        ->join('details_commande', 'produit.id_produit = details_commande.id_produit')
        ->join('commande', 'details_commande.id_commande = commande.id_commande')
        ->groupBy('produit.id_produit')
        ->orderBy('total_quantite', 'DESC')
        ->limit(8)
        ->get()
        ->getResultArray();
    }
}
