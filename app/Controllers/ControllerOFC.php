<?php

namespace App\Controllers;

use App\Models\FAQModel;
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

        $produits = $produitModel->getTopProduits();

        if (empty($produits)) {
            $produits = $produitModel->limit(8)->findAll();
        }

        foreach ($produits as &$produit) {
			$images = $imageModel->getImagesByProduit($produit['id_produit']);
            $produit['images'] = !empty($images) ? $images : [['chemin' => '/assets/img/user.png']];		}

        $data['produits'] = $produits;

        

        // Récupérez les 10 premières questions
        $data['faqs'] = $faqModel->orderBy('id_faq', 'ASC')->findAll(10);

        // Chargez la vue avec les questions
        return view('pageAccueil', $data);
    }
}

?>  