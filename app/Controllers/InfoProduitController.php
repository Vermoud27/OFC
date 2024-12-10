<?php

namespace App\Controllers;

use App\Models\ImageModel;
use App\Models\ProduitIngredientModel;
use App\Models\ProduitModel;
use App\Models\IngredientModel;
use App\Models\CommentModel;
use App\Models\RatingModel;

class InfoProduitController extends BaseController
{
    public function index($idProduit)
    {
        helper(['form']);

        $produitModel = new ProduitModel();
        $produitIngredientModel = new ProduitIngredientModel();
        $ingredientModel = new IngredientModel();
        $imageModel = new ImageModel();
        $commentModel = new CommentModel();
        $ratingModel = new RatingModel();

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

        $produitsAleatoires = $produitModel->whereNotIn('id_produit', [$idProduit])->where('actif', 't')->orderBy('RANDOM()')->limit(4)->findAll();

        foreach ($produitsAleatoires as &$rand) {
			$images = $imageModel->getImagesByProduit($rand['id_produit']);
			$rand['images'] = $images;
		}

        // Données à
        $data['produit'] = $produit;
        $data['produitsAleatoires'] = $produitsAleatoires;
        $data['images'] = $imageModel->getImagesByProduit($produit['id_produit']);
        $data['produit'] = $produit;
        $data['ingredients'] = $ingredients;
        $data['commentaires'] = $commentaires;
        $data['all'] = $all;
        $data['averageRating'] = $ratingModel->getAverageRating($produit['id_produit']);
        $data['totalRatings'] = $ratingModel->getTotalRatings($produit['id_produit']);
        $data['existingRating'] = $ratingModel->where('id_produit', $idProduit)
                                              ->where('id_utilisateur', session()->get('idutilisateur'))
                                              ->first();

        return view('infoProduit', $data);
    }
}

?>