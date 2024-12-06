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
        if ($this->request->getMethod() === 'post') {
            $query = $this->request->getJSON()->query; // Récupère la requête depuis le body JSON

            if (!$query || strlen($query) < 2) {
                return $this->response->setJSON(['error' => 'Requête trop courte'])->setStatusCode(400);
            }

            $produitModel = new ProduitModel();

            try {
                // Recherche des produits
                $produits = $produitModel->like('nom', $query)->findAll(10); // Limité à 10 résultats

                // Vérification des résultats
                if (empty($produits)) {
                    return $this->response->setJSON(['message' => 'Aucun produit trouvé.']);
                }

                // Retour des produits trouvés
                return $this->response->setJSON($produits);
            } catch (\Exception $e) {
                log_message('error', $e->getMessage());
                return $this->response->setJSON(['error' => 'Erreur serveur'])->setStatusCode(500);
            }
        }
        else
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

        // Méthode non autorisée
        return $this->response->setStatusCode(405)->setJSON(['error' => 'Méthode non autorisée']);
    }


}

?>  