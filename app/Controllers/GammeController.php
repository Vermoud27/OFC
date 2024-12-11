<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GammeModel;
use App\Models\ProduitModel;
use App\Models\ImageModel;

class GammeController extends BaseController
{
	protected $gammeModel;
	protected $produitModel;
	protected $imageModel;

	public function __construct()
	{
		$this->gammeModel = new GammeModel();
		$this->produitModel = new ProduitModel();
		$this->imageModel = new ImageModel();
		helper(['form']);
	}

	public function index()
	{
		$gammes = $this->gammeModel->orderBy('id_gamme')->paginate(8);

		foreach ($gammes as &$gamme) {
			$gamme['produit_count'] = $this->produitModel->where('id_gamme', $gamme['id_gamme'])->countAllResults();
		}

		$fav = $this->gammeModel->getTopGammes(5);

		$data['fav'] = $fav;

		$data['produits'] = $this->produitModel->findAll();
		$data['gammes'] = $gammes;
		$data['pager'] = \Config\Services::pager();

		return view('administrateur/gammes/liste', $data);
	}

	public function creation()
	{
		$data['produits'] = $this->produitModel->orderBy('nom')->findAll();

		return view('administrateur/gammes/creation', $data);
	}

	public function creer()
	{
		$isValid = $this->validate([
			'nom' => 'required|max_length[100]',
			'description' => 'permit_empty',
			'prixht' => 'required|decimal|greater_than[0]|less_than[1000]',
			'prixttc' => 'required|decimal|greater_than[0]|less_than[1000]',
		]);

		if (!$isValid) {
			return view('administrateur/gammes/creation', [
				'validation' => \Config\Services::validation(),
			]);
		}

		$data = [
			'nom' => $this->request->getPost('nom'),
			'description' => $this->request->getPost('description'),
			'prixht' => $this->request->getPost('prixht'),
			'prixttc' => $this->request->getPost('prixttc'),
			'image' => $this->request->getPost(index: 'image'),
		];

		$files = $this->request->getFiles();
		if (isset($files['images']) && $files['images'][0]->isValid()) {
			$file = $files['images'][0];
			$newName = $file->getRandomName();
			$file->move(WRITEPATH . '../public/assets/img/produits/', $newName);

			$data['image'] = '/assets/img/produits/' . $newName;
		}

		$this->gammeModel->insert($data);

		return redirect()->to('admin/gammes');
	}

	public function modification($id)
	{
		// Récupérer les informations de la gamme avec l'ID
		$data['gamme'] = $this->gammeModel->find($id);

		// Vérifier si la gamme existe
		if (!$data['gamme']) {
			// Si la gamme n'existe pas, rediriger vers la liste des gammes avec un message d'erreur
			return redirect()->to('/admin/gammes')->with('error', 'Gamme non trouvée');
		}

		// Récupérer seulement les produits actifs (actif = true)
		$data['produits'] = $this->produitModel
			->where('actif', true) // Filtrer par actif = true
			->orderBy('nom')
			->findAll();

		// Charger la vue de modification avec les données de la gamme et des produits actifs
		return view('administrateur/gammes/modification', $data);
	}

	public function modifier($id)
	{
		$isValid = $this->validate([
			'nom' => 'required|max_length[100]',
			'description' => 'permit_empty',
			'prixht' => 'required|decimal|greater_than[0]|less_than[1000]',
			'prixttc' => 'required|decimal|greater_than[0]|less_than[1000]',
		]);

		if (!$isValid) {
			return view('administrateur/gammes/creation', [
				'validation' => \Config\Services::validation(),
			]);
		}

		$data = [
			'nom' => $this->request->getPost('nom'),
			'description' => $this->request->getPost('description'),
			'prixht' => $this->request->getPost('prixht'),
			'prixttc' => $this->request->getPost('prixttc'),
		];

		$this->gammeModel->update($id, $data);

		return redirect()->to('admin/gammes');
	}

	public function supprimer($id)
	{
		$this->produitModel->where('id_gamme', $id)->set('id_gamme', NULL)->update();

		$this->gammeModel->delete($id);
		return redirect()->to('admin/gammes');
	}

	public function ajouter_produit($gamme_id)
	{
		$produit_id = $this->request->getPost('produit_id');
		$this->produitModel->update($produit_id, ['id_gamme' => $gamme_id]);

		return redirect()->to('/admin/gammes/modification/' . $gamme_id);
	}

	public function enlever_produit($gamme_id)
	{
		$produit_id = $this->request->getPost('produit_id');
		$this->produitModel->update($produit_id, ['id_gamme' => NULL]);

		return redirect()->to('/admin/gammes/modification/' . $gamme_id);
	}

	public function page_gammes(): string
	{
		$gammeModel = new GammeModel();

		// Récupérer les produits paginés
		$gammes = $gammeModel->orderBy('id_gamme')->paginate(16);

		// Ajouter les images pour chaque produit
		foreach ($gammes as &$gamme) {
			$images = $this->imageModel->getImagesByProduit($gamme['id_gamme']);
			$gamme['images'] = $images;
		}

		// Passer les données à la vue
		$data['gammes'] = $gammes;
		$data['pager'] = \Config\Services::pager();

		return view('pageGammes', $data);
	}

}
