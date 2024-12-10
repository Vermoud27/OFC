<?php

namespace App\Controllers;

use App\Models\FAQModel;
use App\Models\GammeModel;
use App\Models\ImageModel;
use App\Models\ProduitModel;

class ControllerOFC extends BaseController
{
    public function index(): string
    {
        //return view('pageAccueil');

        $faqModel = new FaqModel(); // Instanciez le modèle
        $produitModel = new ProduitModel();
        $imageModel = new ImageModel();
        $gammeModel = new GammeModel();

        $gammes = $gammeModel->getTopGammes(2);

        if (empty($gammes)) {
            $gammes = $gammeModel->limit(2)->findAll();
        }

        $produits = $produitModel->where('actif', 't')->getTopProduits(8);

        if (empty($produits)) {
            $produits = $produitModel->where('actif', 't')->limit(8)->findAll();
        }

        foreach ($produits as &$produit) {
			$images = $imageModel->getImagesByProduit($produit['id_produit']);
            $produit['images'] = !empty($images) ? $images : [['chemin' => '/assets/img/produits/placeholder.png']];		
        }

        $data['produits'] = $produits;
        $data['gammes'] = $gammes;
        $data['recherche'] = $produit['nom'];

        // Récupérez les 10 premières questions
        $data['faqs'] = $faqModel->orderBy('id_faq', 'ASC')->findAll(10);

        // Chargez la vue avec les questions
        return view('pageAccueil', $data);
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