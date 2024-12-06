<?php

namespace App\Controllers;

use App\Models\ProduitModel;

class HeaderController extends BaseController
{
    public function index(): string
    {

        $produitModel = new ProduitModel();

        $produits = $produitModel->getTopProduits();

        // Chargez la vue avec les questions
        return view('header', $produits);
    }

    public function rechercherProduits()
    {
        if ($this->request->isAJAX()) {
            $query = $this->request->getPost('query');

            if ($query) {
                $produitModel = new ProduitModel();
                $produits = $produitModel->like('nom', $query, 'both')->findAll(10); // Limitez les résultats à 10

                return $this->response->setJSON($produits);
            }
        }

        return $this->response->setJSON([]);
    }
}

?>  