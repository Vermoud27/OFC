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
		$categories = $this->categorieModel->orderBy('id_categorie')->findAll();
		
		$data['categories'] = $categories;
		return view('administrateur/categories/liste', $data);
	}

	public function creation()
    {
        return view('administrateur/categories/creation');
    }
	
    public function creer()
	{
		// Sauvegarder les données du produit
		$produitId = $this->categorieModel->insert([
			'nom' => $this->request->getPost('nom'),
			'description' => $this->request->getPost('description'),
		]);

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
		$this->categorieModel->update($id, [
			'nom' => $this->request->getPost('nom'),
			'description' => $this->request->getPost('description'),
		]);
		return redirect()->to('admin/categories');
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
