<?php

namespace App\Controllers;

use App\Models\FAQModel;
use App\Models\ProduitModel;

class ProduitsController extends BaseController
{
    public function index(): string
    {
        $produitModel = new ProduitModel();

        $produits = $produitModel->get_all();
        
        return view('pageProduits', ['produits' => $produits]);
    }
}

?>  