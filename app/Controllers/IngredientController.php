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
		$ingredients = $this->ingredientModel->orderBy('nom')->paginate(8);

		$fav = $this->ingredientModel->getTopIngredients(5);

		$data['fav'] = $fav;
		
		$data['ingredients'] = $ingredients;
		$data['pager'] = \Config\Services::pager();

		return view('administrateur/ingredients/liste', $data);
	}

	public function creation()
    {
        return view('administrateur/ingredients/creation');
    }
	
    public function creer()
	{
		$isValid = $this->validate([
			'nom' => 'required|max_length[100]',
			'provenance' => 'permit_empty',
		]);

		if (!$isValid)
		{
			return view('administrateur/ingredients/creation', [
				'validation' => \Config\Services::validation(),
			]);
		}

		$data = [
			'nom' => $this->request->getPost('nom'),
			'provenance' => $this->request->getPost('provenance'),
		];

		$this->ingredientModel->insert( $data);

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
		$isValid = $this->validate([
			'nom' => 'required|max_length[100]',
			'provenance' => 'permit_empty',
		]);

		if (!$isValid)
		{
			return view('administrateur/ingredients/creation', [
				'validation' => \Config\Services::validation(),
			]);
		}

		$data = [
			'nom' => $this->request->getPost('nom'),
			'provenance' => $this->request->getPost('provenance'),
		];

		$this->ingredientModel->update( $id,$data);

		return redirect()->to('admin/ingredients');
    }

	public function supprimer($id)
	{
		// Charger le modèle Produit_Ingredient si ce n'est pas déjà fait
		$produitIngredientModel = new \App\Models\ProduitIngredientModel();
	

		if ($this->request->getPost('confirm') === 'yes') {
			// Supprimez les références dans Produit_Ingredient
			$produitIngredientModel->where('id_ingredient', $id)->delete();
			// Supprimez l'ingrédient
			$this->ingredientModel->delete($id);
	
			session()->setFlashdata('success', 'Ingrédient supprimé avec succès.');
			return redirect()->to('/admin/ingredients');
		}

		$references = $produitIngredientModel->where('id_ingredient', $id)->findAll();

		if ($references) {
			return redirect()->to("/admin/ingredients?warning=1&id=$id");
		}
	
		// Si pas de références, supprimer directement
		$this->ingredientModel->delete($id);
		session()->setFlashdata('success', 'Ingrédient supprimé avec succès.');
		return redirect()->to('/admin/ingredients');
	}
	

}
