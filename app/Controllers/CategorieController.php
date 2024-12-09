<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategorieModel;
use App\Models\ImageModel;
use App\Models\ProduitModel;

class CategorieController extends BaseController
{
    protected $categorieModel;

    public function __construct()
    {
        $this->categorieModel = new CategorieModel();
		helper(['form']);
    }

    public function index()
	{
		$categories = $this->categorieModel->orderBy('id_categorie')->paginate(8);
		
		$data['categories'] = $categories;
		$data['pager'] = \Config\Services::pager();

		$fav = $this->categorieModel->getTopCategories(5);

		$data['fav'] = $fav;

		return view('administrateur/categories/liste', $data);
	}

	public function creation()
    {
        return view('administrateur/categories/creation');
    }
	
    public function creer()
	{
		$isValid = $this->validate([
			'nom' => 'required|max_length[100]',
			'description' => 'permit_empty',
		]);

		if (!$isValid)
		{
			return view('administrateur/categories/creation', [
				'validation' => \Config\Services::validation(),
			]);
		}

		$data = [
			'nom' => $this->request->getPost('nom'),
			'description' => $this->request->getPost('description'),
		];

		$this->categorieModel->insert($data);
		return redirect()->to('admin/categories');
	}

	public function modification($id)
	{
		// Récupérer les informations du produit avec l'ID
		$categorie = $this->categorieModel->find($id);
		
		// Vérifier si le produit existe
		if (!$categorie) {
			// Si le produit n'existe pas, rediriger vers la liste des produits avec un message d'erreur
			return redirect()->to('/admin/categories')->with('error', 'Categorie non trouvé');
		}

		// Charger la vue de modification avec les données du produit et ses images
		return view('administrateur/categories/modification', [
			'categorie' => $categorie,
		]);
	}



    public function modifier($id)
    {
		$isValid = $this->validate([
			'nom' => 'required|max_length[100]',
			'description' => 'permit_empty',
		]);

		if (!$isValid)
		{
			return view('administrateur/categories/creation', [
				'validation' => \Config\Services::validation(),
			]);
		}

		$data = [
			'nom' => $this->request->getPost('nom'),
			'description' => $this->request->getPost('description'),
		];

		$this->categorieModel->update($id, $data);
		
		return redirect()->to('admin/categories');
    }

    public function supprimer($id)
    {
		$produitModel = new ProduitModel();
		$produitModel->where('id_categorie', $id)->set('id_categorie', NULL)->update();

		$this->categorieModel->delete($id);
        return redirect()->to('admin/categories');
    }
}
