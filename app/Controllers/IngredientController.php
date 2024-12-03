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
