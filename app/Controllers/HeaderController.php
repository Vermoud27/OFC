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
        if ($this->request->isAJAX()) {
            $query = $this->request->getPost('query');

            if ($query) {
                $produitModel = new ProduitModel();
                $produits = $produitModel->like('nom', $query, 'both')->findAll(10); // Limiter à 10 résultats

                return $this->response->setJSON($produits); // Retourner les produits en format JSON
            }
        }

        return $this->response->setJSON([]); // Retourner un tableau vide si la requête est invalide
    }
}

?>  