<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategorieModel;
use App\Models\ImageModel;
use App\Models\IngredientModel;
use App\Models\ProduitIngredientModel;
use App\Models\ProduitModel;

class ProduitController extends BaseController
{
    protected $produitModel;
	protected $ingredientModel;
	protected $categorieModel;
	protected $produit_ingredient;
	protected $imageModel;
	
    public function __construct()
    {
        $this->produitModel = new ProduitModel();
		$this->ingredientModel = new IngredientModel();
		$this->categorieModel = new CategorieModel();
		$this->produit_ingredient = new ProduitIngredientModel();
		$this->imageModel = new ImageModel();

		helper(['form']);
    }

    public function index()
	{
		$produits = $this->produitModel->where('actif', 't')->orderBy('id_produit')->findAll();
		foreach ($produits as &$produit) {
			$images = $this->imageModel->getImagesByProduit($produit['id_produit']);
			$produit['images'] = !empty($images) ? $images : [['chemin' => '/assets/img/user.png']];
		}

		$data['produits'] = $produits;
		
		return view('administrateur/produits/liste', $data);
	}

	public function creation()
    {

		$data['ingredients'] = $this->ingredientModel->orderBy('nom')->findAll();
		$data['categories'] = $this->categorieModel->orderBy('nom')->findAll();

		return view('administrateur/produits/creation', $data);
    }
	
    public function creer()
	{
		//$categorieid = null;
		//$categorieid = $categorieModel->where('nom', $this->request->getPost('categorie'))->first();
	
		$isValid = $this->validate([
			'nom' => 'required|max_length[100]',
			'description' => 'permit_empty',
			'prixht' => 'required|decimal|greater_than[0]|less_than[1000]',
			'prixttc' => 'required|decimal|greater_than[0]|less_than[1000]',
			'qte_stock' => 'required|integer|greater_than[0]|less_than[10000]',
			'unite_mesure' => 'required|max_length[2]',
			'promotion' => 'permit_empty|decimal',
			'contenu' => 'required|integer|greater_than[0]|less_than[1000]',
		]);

		if (!$isValid)
		{
			return view('administrateur/produits/creation', [
				'validation' => \Config\Services::validation(),
				'categories' => $this->categorieModel->orderBy('nom')->findAll(),
				'ingredients' => $this->ingredientModel->orderBy('nom')->findAll(),
			]);
		}

		$data = [
			'nom' => $this->request->getPost('nom'),
			'description' => $this->request->getPost('description'),
			'prixht' => $this->request->getPost('prixht'),
			'prixttc' => $this->request->getPost('prixttc'),
			'qte_stock' => $this->request->getPost('qte_stock'),
			'unite_mesure' => $this->request->getPost('unite_mesure'),
			'id_categorie' => $this->request->getPost('categorie') ?: null,
			'contenu' => $this->request->getPost('contenu'),
		];

		$produitId = $this->produitModel->insert($data);

		// Vérifier si des fichiers sont téléchargés
		$files = $this->request->getFiles();
		if (isset($files['images']) && $files['images'][0]->isValid()) {
			foreach ($files['images'] as $file) {
				// Sauvegarder chaque image dans un répertoire
				$newName = $file->getRandomName();
				$file->move(WRITEPATH . '../public/assets/img/produits/', $newName);

				// Enregistrer le chemin dans la table Image
				$this->imageModel->insert([
					'chemin' => '/assets/img/produits/' . $newName,
					'id_produit' => $produitId,
				]);
			}
		}

		// Récupérer les ingrédients
		$ingredientNames = $this->request->getPost('ingredients');
		if ($ingredientNames && is_array($ingredientNames)) {
	
			foreach ($ingredientNames as $ingredientName) {
				$ingredient = $this->ingredientModel->where('nom', $ingredientName)->first();
				if ($ingredient) {
					$this->produit_ingredient->insert([
						'id_produit' => $produitId,
						'id_ingredient' => $ingredient['id_ingredient'],
					]);
				}
			}
		}

		return redirect()->to('admin/produits');
	}

	public function modification($id)
	{
		$data['ingredients'] = $this->ingredientModel->orderBy('nom')->findAll();
		$data['categories'] = $this->categorieModel->orderBy('nom')->findAll();
		
		// Récupérer les informations du produit avec l'ID
		$data['produit'] = $this->produitModel->find($id);
		
		// Vérifier si le produit existe
		if (!$data['produit']) {
			return redirect()->to('/admin/produits')->with('error', 'Produit non trouvé');
		}

		$data['ingredientsMis'] = $this->produit_ingredient->select('ingredient.*') 
        ->join('ingredient', 'produit_ingredient.id_ingredient = ingredient.id_ingredient') 
        ->where('produit_ingredient.id_produit', $data['produit']['id_produit']) 
        ->get()
        ->getResultArray();

		// Récupérer les images associées à ce produit
		$imageModel = new ImageModel();
		$data['images'] = $imageModel->where('id_produit', $id)->orderBy('id_image')->findAll();

		// Charger la vue de modification avec les données du produit et ses images
		return view('administrateur/produits/modification', $data);
	}



	public function modifier($id)
	{
		$isValid = $this->validate([
			'nom' => 'required|max_length[100]',
			'description' => 'permit_empty',
			'prixht' => 'required|decimal|greater_than[0]|less_than[1000]',
			'prixttc' => 'required|decimal|greater_than[0]|less_than[1000]',
			'qte_stock' => 'required|integer|greater_than[0]|less_than[10000]',
			'unite_mesure' => 'required|max_length[2]',
			'promotion' => 'permit_empty|decimal',
			'contenu' => 'required|integer|greater_than[0]|less_than[1000]',
		]);

		if (!$isValid) {
			return view('administrateur/produits/modification/' . $id, [
				'validation' => \Config\Services::validation(),
				'categories' => $this->categorieModel->orderBy('nom')->findAll(),
				'ingredients' => $this->ingredientModel->orderBy('nom')->findAll(),
			]);
		}

		// Mise à jour des informations du produit
		$data = [
			'nom' => $this->request->getPost('nom'),
			'description' => $this->request->getPost('description'),
			'prixht' => $this->request->getPost('prixht'),
			'prixttc' => $this->request->getPost('prixttc'),
			'qte_stock' => $this->request->getPost('qte_stock'),
			'unite_mesure' => $this->request->getPost('unite_mesure'),
			'id_categorie' => $this->request->getPost('categorie') ?: null,
			'contenu' => $this->request->getPost('contenu'),
		];

		$this->produitModel->update($id, $data);

		$existingImageIds = $this->request->getPost('existing_images') ?? [];
    
		// Récupérer les images à supprimer
		$deletedImageIds = $this->request->getPost('deleted_images') ?? [];
		
		// Supprimer les images qui ne sont plus sélectionnées
		foreach ($deletedImageIds as $deletedImageId) {
			$image = $this->imageModel->find($deletedImageId);
			if ($image) {
				// Supprimer du disque
				unlink(WRITEPATH . '../public' . $image['chemin']);
				// Supprimer de la base de données
				$this->imageModel->delete($deletedImageId);
			}
		}

		// Ajout des nouvelles images
		$files = $this->request->getFiles();
		if (isset($files['images']) && $files['images'][0]->isValid()) {
			foreach ($files['images'] as $file) {
				$newName = $file->getRandomName();
				$file->move(WRITEPATH . '../public/assets/img/produits/', $newName);

				$this->imageModel->insert([
					'chemin' => '/assets/img/produits/' . $newName,
					'id_produit' => $id,
				]);
			}
		}

		// ** Suppression des anciennes associations d'ingrédients **
		$this->produit_ingredient->where('id_produit', $id)->delete();

		// Ajout des nouvelles associations d'ingrédients
		$ingredientNames = $this->request->getPost('ingredients');
		if ($ingredientNames && is_array($ingredientNames)) {
			foreach ($ingredientNames as $ingredientName) {
				$ingredient = $this->ingredientModel->where('nom', $ingredientName)->first();
				if ($ingredient) {
					$this->produit_ingredient->insert([
						'id_produit' => $id,
						'id_ingredient' => $ingredient['id_ingredient'],
					]);
				}
			}
		}

		return redirect()->to('admin/produits')->with('success', 'Produit modifié avec succès.');
	}

	
	//Méthode non utilisé car sinon onj détruis tt
    /*public function supprimer($id)
    {
        $this->produitModel->delete($id);
        return redirect()->to('admin/produits');
    }*/

	public function desactiver($id)
    {
        $this->produitModel->update($id, ['actif' => 'f']);
        return redirect()->to('admin/produits');
    }
}
