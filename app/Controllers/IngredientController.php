<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\IngredientModel;

class IngredientController extends BaseController
{
    protected $ingredientModel;

    public function __construct()
    {
        $this->ingredientModel = new IngredientModel();
		helper(['form']);
    }

    public function index()
	{
		$ingredients = $this->ingredientModel->orderBy('id_ingredient')->findAll();
		
		$data['ingredients'] = $ingredients;
		return view('administrateur/ingredients/liste', $data);
	}

	public function creation()
    {
        return view('administrateur/ingredients/creation');
    }
	
    public function creer()
	{
		// Sauvegarder les données du produit
		$produitId = $this->ingredientModel->insert([
			'nom' => $this->request->getPost('nom'),
			'provenance' => $this->request->getPost('provenance'),
		]);

		return redirect()->to('admin/ingredients');
	}

	public function modification($id)
	{
		// Récupérer les informations du produit avec l'ID
		$ingredient = $this->ingredientModel->find($id);
		
		// Vérifier si le produit existe
		if (!$ingredient) {
			// Si le produit n'existe pas, rediriger vers la liste des produits avec un message d'erreur
			return redirect()->to('/admin/ingredients')->with('error', 'Ingrédient non trouvé');
		}

		// Charger la vue de modification avec les données du produit et ses images
		return view('administrateur/ingredients/modification', [
			'ingredient' => $ingredient,
		]);
	}



    public function modifier($id)
    {
		$this->ingredientModel->update($id, [
			'nom' => $this->request->getPost('nom'),
			'provenance' => $this->request->getPost('provenance'),
		]);
		return redirect()->to('admin/ingredients');
    }

    /*public function supprimer($id)
    {
        $this->produitModel->delete($id);
        return redirect()->to('admin/categories');
    }*/

	/*public function desactiver($id)
    {
        $this->produitModel->update($id, ['actif' => 'f']);
        return redirect()->to('admin/categories');
    }*/
}
