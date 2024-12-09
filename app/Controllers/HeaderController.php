<?php

namespace App\Controllers;

use App\Models\ProduitModel;

class HeaderController extends BaseController
{
    public function index(): string
    {
        $produitModel = new ProduitModel();
        $produits = $produitModel->getProduits(); // Remplir avec les meilleurs produits

        return view('header', $produits); // Passez les produits à la vue
    }

    public function rechercherProduits()
    {
        $query = $this->request->getGet('query');

        // Si "query" n'est pas défini ou vide, retournez une réponse vide
        if (!$query || trim($query) === '') {
            return $this->response->setJSON([]);
        }

        // Instanciez le modèle Produit
        $produitModel = new ProduitModel();

        // Recherchez les produits correspondant à la requête
        $produits = $produitModel
            ->like('nom', $query) // Recherche par nom
            ->findAll();

        // Retournez les produits trouvés en JSON
        return $this->response->setJSON($produits);
    }
}

?>  