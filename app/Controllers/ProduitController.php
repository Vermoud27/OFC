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

    public function __construct()
    {
        $this->produitModel = new ProduitModel();
		helper(['form']);
    }

    public function index()
	{
		$imageModel = new ImageModel();

		$produits = $this->produitModel->where('actif', 't')->orderBy('id_produit')->findAll();
		foreach ($produits as &$produit) {
			$images = $imageModel->getImagesByProduit($produit['id_produit']);
			$produit['images'] = !empty($images) ? $images : [['chemin' => '/assets/img/user.png']];
		}

		$data['produits'] = $produits;
		
		return view('administrateur/produits/liste', $data);
	}

	public function creation()
    {
        $ingredientModel = new IngredientModel();
		$categorieModel = new CategorieModel();

		$data['ingredients'] = $ingredientModel->orderBy('nom')->findAll();
		$data['categories'] = $categorieModel->orderBy('nom')->findAll();

		return view('administrateur/produits/creation', $data);
    }
	
    public function creer()
	{
		//$categorieModel = new CategorieModel();

		//$categorieid = null;
		//$categorieid = $categorieModel->where('nom', $this->request->getPost('categorie'))->first();
	
		// Sauvegarder les données du produit
		$produitId = $this->produitModel->insert([
			'nom' => $this->request->getPost('nom'),
			'description' => $this->request->getPost('description'),
			'prixht' => $this->request->getPost('prixht'),
			'prixttc' => $this->request->getPost('prixttc'),
			'qte_stock' => $this->request->getPost('qte_stock'),
			'unite_mesure' => $this->request->getPost('quantity') . " " . $this->request->getPost('unite_mesure'),
			'id_categorie' => $this->request->getPost('categorie'),
		]);

		$imageModel = new ImageModel();

		// Vérifier si des fichiers sont téléchargés
		$files = $this->request->getFiles();
		if (isset($files['images']) && $files['images'][0]->isValid()) {
			foreach ($files['images'] as $file) {
				// Sauvegarder chaque image dans un répertoire
				$newName = $file->getRandomName();
				$file->move(WRITEPATH . '../public/assets/img/produits/', $newName);

				// Enregistrer le chemin dans la table Image
				$imageModel->insert([
					'chemin' => '/assets/img/produits/' . $newName,
					'id_produit' => $produitId,
				]);
			}
		}

		// Récupérer les ingrédients
		$ingredientNames = $this->request->getPost('ingredients');
		if ($ingredientNames && is_array($ingredientNames)) {

			$ingredientModel = new IngredientModel();
			$produit_ingredientModel = new ProduitIngredientModel();
	
			foreach ($ingredientNames as $ingredientName) {
				// Vérifier si l'ingrédient existe déjà
				$ingredient = $ingredientModel->where('nom', $ingredientName)->first();
	
				$ingredientId = $ingredient['id_ingredient'];
	
				// Associer l'ingrédient au produit
				$produit_ingredientModel->insert([
					'id_produit' => $produitId,
					'id_ingredient' => $ingredientId,
				]);
			}
		}

		return redirect()->to('admin/produits');
	}

	public function modification($id)
	{
		$ingredientModel = new IngredientModel();
		$categorieModel = new CategorieModel();
		$produit_ingredient = new ProduitIngredientModel();

		$data['ingredients'] = $ingredientModel->orderBy('nom')->findAll();
		$data['categories'] = $categorieModel->orderBy('nom')->findAll();
		

		// Récupérer les informations du produit avec l'ID
		$data['produit'] = $this->produitModel->find($id);
		
		// Vérifier si le produit existe
		if (!$data['produit']) {
			// Si le produit n'existe pas, rediriger vers la liste des produits avec un message d'erreur
			return redirect()->to('/admin/produits')->with('error', 'Produit non trouvé');
		}

		$data['ingredientsMis'] = $produit_ingredient->select('ingredient.*') 
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
		$this->produitModel->update($id, [
			'nom' => $this->request->getPost('nom'),
			'description' => $this->request->getPost('description'),
			'prixht' => $this->request->getPost('prixht'),
			'prixttc' => $this->request->getPost('prixttc'),
			'qte_stock' => $this->request->getPost('qte_stock'),
			'unite_mesure' => $this->request->getPost('unite_mesure'),
			'promotion' => $this->request->getPost('promotion'),
			'id_categorie' => $this->request->getPost('id_categorie'),
			'id_gamme' => $this->request->getPost('id_gamme')
		]);
		return redirect()->to('admin/produits');
    }

	//Méthode non utilisé car sinon onj détruis tt
    public function supprimer($id)
    {
        $this->produitModel->delete($id);
        return redirect()->to('admin/produits');
    }

	public function desactiver($id)
    {
        $this->produitModel->update($id, ['actif' => 'f']);
        return redirect()->to('admin/produits');
    }
}
