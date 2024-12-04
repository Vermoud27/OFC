<?php

namespace App\Controllers;

use App\Models\ProduitModel;


class InfoProduitController extends BaseController
{
    public function index($idProduit): string
    {
        $produitModel = new ProduitModel();
        
        $data['produit'] = $produitModel->find($idProduit);

        
        return view('infoProduit', $data);
    }
}

?>  