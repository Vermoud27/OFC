<?php

namespace App\Controllers;

use App\Models\ImageModel;
use App\Models\ProduitIngredientModel;
use App\Models\ProduitModel;
use App\Models\IngredientModel;

class InfoProduitController extends BaseController
{
    public function index($idProduit)
    {
        $produitModel = new ProduitModel();
        $produitIngredientModel = new ProduitIngredientModel();
        $ingredientModel = new IngredientModel();
        $imageModel = new ImageModel();

        // Recherche du produit
        $produit = $produitModel->find($idProduit);

        // Vérification si le produit existe
        if (!$produit) {
            // Redirige vers la page précédente avec un message d'erreur
            return redirect()->back()->with('error', 'Produit non trouvé.');
        }

        // Récupérer les ingrédients liés au produit
        $produitIngredients = $produitIngredientModel->where('id_produit', $idProduit)->findAll();

        // Récupérer les détails des ingrédients
        $ingredients = [];
        foreach ($produitIngredients as $pi) {
            $ingredient = $ingredientModel->find($pi['id_ingredient']);
            if ($ingredient) {
                $ingredients[] = array_merge($ingredient);
            }
        }

		$data['images']         = $imageModel->getImagesByProduit($produit['id_produit']);		
        $data['produit']        = $produit;
        $data['ingredients']    = $ingredients;

        return view('infoProduit', $data);
    }

    public function ajouterPanier($idProduit)
    {
        $response = service('response');
        $request = service('request');

        $response->setCookie('panier', $request->getCookie('panier') . "-" . $idProduit, 0);
        
        //TODO : voire si mettre popup ou message du genre
        return redirect()->to('admin/produit/' . $idProduit);
    }
}

?>  