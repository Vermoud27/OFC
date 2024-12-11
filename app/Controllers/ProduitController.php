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

	public function page_produits(): string
	{
		// Récupérer les paramètres de la requête
		$categorie = $this->request->getGet('categorie');
		$recherche = $this->request->getGet('search');
		$prix = $this->request->getGet('prix');
		$popularite = $this->request->getGet('popularite');
		$ingredients = $this->request->getGet('ingredients');

		// Initialiser la requête des produits
		$produitQuery = $this->produitModel->select('produit.*')->where('actif', 't'); // Filtrer les produits actifs

		// Tri par popularité
		if ($popularite) {
			$produitQuery = $this->produitModel->getProduitsParPopularite( $popularite); // Tri par popularité
		}

		// Filtrage par catégorie
		if ($categorie) {
			$categorieData = $this->categorieModel->where('nom', $categorie)->first();
			if ($categorieData) {
				$produitQuery->where('id_categorie', $categorieData['id_categorie']);
			} else {
				$produitQuery->where('id_categorie', -1); // Aucun produit
			}
		}

		// Filtrage par recherche
		if ($recherche) {
			$produitQuery->like('nom', $recherche);
		}

		if ($ingredients) {
			$produitQuery->join('produit_ingredient', 'produit_ingredient.id_produit = produit.id_produit', 'left')  // Jointure avec la table ingredient_produit
						 ->join('ingredient', 'produit_ingredient.id_ingredient = ingredient.id_ingredient', 'left') // Jointure avec la table ingredient
						 ->like('ingredient.nom', $ingredients); // Recherche par le nom de l'ingrédient
		}

		// Tri par prix
		if ($prix) {
			$produitQuery->orderBy('prixttc', $prix); // Tri par prix croissant ou décroissant
		}

		// Récupérer les produits paginés
		$produits = $produitQuery->paginate(16);

		// Ajouter les images pour chaque produit
		foreach ($produits as &$produit) {
			$images = $this->imageModel->getImagesByProduit($produit['id_produit']);
			$produit['images'] = $images;
		}

		// Passer les données à la vue
		$data['produits'] = $produits;
		$data['pager'] = \Config\Services::pager();
		$data['selectedCategorie'] = $categorie ?: 'Tous les produits';
		$data['categories'] = $this->categorieModel->orderBy('nom')->findAll();
		$data['selectedPrix'] = $prix;
		$data['selectedPopularite'] = $popularite;
		$data['selectedIngredients'] = $ingredients;
		$data['ingredients'] = $this->ingredientModel->orderBy('nom')->findAll();

		return view('pageProduits', $data);
	}




	public function produitsParCategorie($nomCategorie)
    {
        $produitModel = new ProduitModel();
    
        $produits = $produitModel->getProduitsByCategorie($nomCategorie);
    
        if (empty($produits)) {
            return $this->response->setJSON(['message' => 'Aucun produit trouvé pour cette catégorie']);
        }
    
        return $this->response->setJSON($produits);
    }

	public function produitsParGamme($idGamme)
    {
        $produitModel = new ProduitModel();
    
        $produits = $produitModel->getProduitsByGamme($idGamme);
    
        if (empty($produits)) {
            return $this->response->setJSON(['message' => 'Aucun produit trouvé pour cette gamme']);
        }

		// Ajouter les images pour chaque produit
		foreach ($produits as &$produit) {
			$images = $this->imageModel->getImagesByProduit($produit['id_produit']);
			$produit['images'] = $images;
		}

		$data['produits'] = $produits;
		$data['pager'] = \Config\Services::pager();
		$data['categories'] = $this->categorieModel->orderBy('nom')->findAll();
		$data['ingredients'] = $this->ingredientModel->findAll();
    
        return view('pageProduits', $data);
    }

    public function index()
	{
		$filtre = $this->request->getGet('filtre') ?? 't'; 
		$produits = $this->produitModel->where('actif', $filtre)->orderBy('id_produit')->paginate(6);
		
		foreach ($produits as &$produit) {
			$images = $this->imageModel->getImagesByProduit($produit['id_produit']);
			$produit['images'] = !empty($images) ? $images : [['chemin' => '/assets/img/user.png']];
		}

		$fav = $this->produitModel->getTopProduits(5);

		$data['fav'] = $fav;
		$data['produits'] = $produits;
		$data['pager'] = \Config\Services::pager();
		$data['filtre'] = $filtre;
		
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
				
				// Si l'ingrédient n'existe pas, le créer
				if (!$ingredient) {
					$ingredientId = $this->ingredientModel->insert(['nom' => $ingredientName], true);
				} else {
					$ingredientId = $ingredient['id_ingredient'];
				}

				// Ajouter la relation dans la table pivot
				$this->produit_ingredient->insert([
					'id_produit' => $produitId,
					'id_ingredient' => $ingredientId,
				]);
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

		// Traitement des ingrédients

		// 1. Obtenir les ingrédients envoyés par le formulaire
		$ingredients = $this->request->getPost('ingredients') ?? '[]';
		$ingredients = json_decode($ingredients, true); // Décoder les données JSON
		if (!is_array($ingredients)) {
			$ingredients = []; // S'assurer que c'est un tableau
		}
		$ingredients = array_unique(array_map('trim', $ingredients)); // Supprimer doublons et espaces inutiles

		// 2. Récupérer les ingrédients existants associés à ce produit
		$existingIngredients = $this->produit_ingredient
			->where('id_produit', $id)
			->join('ingredient', 'ingredient.id_ingredient = produit_ingredient.id_ingredient') // Jointure pour obtenir les noms
			->findAll();

		$existingIngredientNames = array_column($existingIngredients, 'nom');

		// 3. Déterminer les ingrédients à ajouter et à supprimer
		$ingredientsToAdd = array_diff($ingredients, $existingIngredientNames); // Nouveaux ingrédients
		$ingredientsToRemove = array_diff($existingIngredientNames, $ingredients); // Ingrédients à supprimer

		// 4. Supprimer les relations pour les ingrédients à retirer
		if (!empty($ingredientsToRemove)) {
			$this->produit_ingredient
				->where('id_produit', $id)
				->whereIn('id_ingredient', function ($builder) use ($ingredientsToRemove) {
					return $builder->select('id_ingredient')->from('ingredient')->whereIn('nom', $ingredientsToRemove);
				})
				->delete();
		}

		// 5. Ajouter les nouvelles relations pour les ingrédients à ajouter
		foreach ($ingredientsToAdd as $ingredientName) {
			// Vérifier si l'ingrédient existe déjà dans la table `ingredient`
			$ingredient = $this->ingredientModel->where('nom', $ingredientName)->first();

			// Si l'ingrédient n'existe pas, le créer
			if (!$ingredient) {
				$ingredientId = $this->ingredientModel->insert(['nom' => $ingredientName], true);
			} else {
				$ingredientId = $ingredient['id_ingredient'];
			}

			// Ajouter la relation dans la table pivot
			$this->produit_ingredient->insert([
				'id_produit' => $id,
				'id_ingredient' => $ingredientId,
			]);
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
        // Récupérer l'état actuel du produit
		$produit = $this->produitModel->find($id);

		if (!$produit) {
			return redirect()->to('admin/produits')->with('error', 'Produit introuvable.');
		}

		// Inverser l'état
		$nouvelEtat = ($produit['actif'] === 't') ? 'f' : 't';

		// Mettre à jour l'état dans la base de données
		$this->produitModel->update($id, ['actif' => $nouvelEtat]);

		return redirect()->to('admin/produits')->with('success', 'État du produit mis à jour avec succès.');
    }
}
