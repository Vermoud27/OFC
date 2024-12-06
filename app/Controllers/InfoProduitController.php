<?php

namespace App\Controllers;

use App\Models\ImageModel;
use App\Models\ProduitIngredientModel;
use App\Models\ProduitModel;
use App\Models\IngredientModel;
use App\Models\CommentModel;

class InfoProduitController extends BaseController
{
    public function index($idProduit)
    {
        $produitModel = new ProduitModel();
        $produitIngredientModel = new ProduitIngredientModel();
        $ingredientModel = new IngredientModel();
        $imageModel = new ImageModel();
        $commentModel = new CommentModel();

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

        $all = ($this->request->getGet('all_comments') === 'true');

        $commentaires = $commentModel->getCommentsByProductId($produit['id_produit'], $all);

        // Données à
        $data['produit'] = $produit;
        $data['images'] = $imageModel->getImagesByProduit($produit['id_produit']);
        $data['produit'] = $produit;
        $data['ingredients'] = $ingredients;
        $data['commentaires'] = $commentaires;
        $data['all'] = $all; 

        return view('infoProduit', $data);
    }
}

?>