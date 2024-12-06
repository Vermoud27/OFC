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
        return $this->select('produit.*, SUM(details_commande.quantite) as total_quantite')
        ->join('details_commande', 'produit.id_produit = details_commande.id_produit')
        ->join('commande', 'details_commande.id_commande = commande.id_commande')
        ->whereNotIn('statut', ['fini', 'annulé'])
        ->groupBy('produit.id_produit')
        ->orderBy('total_quantite', 'DESC')
        ->limit(8)
        ->get()
        ->getResultArray();
    }

    public function getProduitsByCategorie($nomCategorie)
    {
        // Charger le modèle de catégorie
        $categorieModel = new \App\Models\CategorieModel();

        // Rechercher l'ID de la catégorie en fonction de son nom
        $categorie = $categorieModel->where('nom', $nomCategorie)->first();

        if (!$categorie) {
            return []; // Si la catégorie n'existe pas, retourner un tableau vide
        }

        // Obtenir les produits liés à cette catégorie
        return $this->where('id_categorie', $categorie['id_categorie'])->findAll();
    }

    public function getProduitsByGamme($idGamme)
    {
        // Charger le modèle de gamme
        $gammeModel = new \App\Models\GammeModel();

        // Rechercher l'ID de la gamme en fonction de son nom
        $gamme = $gammeModel->where('id_gamme', $idGamme)->first();

        if (!$gamme) {
            return []; // Si la gamme n'existe pas, retourner un tableau vide
        }

        // Obtenir les produits liés à cette gamme
        return $this->where('id_gamme', $gamme['id_gamme'])->findAll();
    }

    public function rechercherProduitsParNom($nom, $limite = 10)
    {
        return $this->like('nom', $nom, 'both')->findAll($limite);
    }

    public function getProduits()
    {
        return $this->findAll(); // Retourne tous les produits
    }

}