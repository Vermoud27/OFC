<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BundleModel;
use App\Models\BundleProduitModel;
use App\Models\ProduitModel;

class BundleController extends BaseController
{
    protected $bundleModel;
	protected $produitModel;

    public function __construct()
    {
        $this->bundleModel = new BundleModel();
		$this->produitModel = new ProduitModel();
		helper(['form']);
    }

    public function index()
	{
		$bundles = $this->bundleModel->orderBy('id_bundle')->paginate(8);
		
		$data['produits'] = $this->produitModel->findAll();
		$data['bundles'] = $bundles;
		$data['pager'] = \Config\Services::pager();

		return view('administrateur/bundles/liste', $data);
	}

	public function creation()
    {
		return view('administrateur/bundles/creation');
    }
	
    public function creer()
	{
		$isValid = $this->validate([
			'description' => 'permit_empty',
			'prix' => 'required|decimal|greater_than[0]|less_than[1000]',
		]);

		if (!$isValid)
		{
			return view('administrateur/bundles/creation', [
				'validation' => \Config\Services::validation(),
			]);
		}

		$data = [
			'description' => $this->request->getPost('description'),
			'prix' => $this->request->getPost('prix'),
		];

		$this->bundleModel->insert($data);
		
		return redirect()->to('admin/bundles');
	}

	public function modification($id)
	{
		// Récupérer les informations du produit avec l'ID
		$data['bundle']  = $this->bundleModel->find($id);
		$data['produits']  = $this->produitModel->orderBy('nom')->findAll();
		
		$data['produits_non_assignes'] = $this->getProduitsNonAssignes();
		$data['produits_assignes'] = $this->getProduitsAssignes($id);

		// Vérifier si le produit existe
		if (!$data['bundle']) {
			// Si le produit n'existe pas, rediriger vers la liste des produits avec un message d'erreur
			return redirect()->to('/admin/bundles')->with('error', 'bundle non trouvé');
		}

		// Charger la vue de modification avec les données du produit et ses images
		return view('administrateur/bundles/modification', $data);
	}



    public function modifier($id)
    {
		$isValid = $this->validate([
			'description' => 'permit_empty',
			'prix' => 'required|decimal|greater_than[0]|less_than[1000]',
		]);

		if (!$isValid)
		{
			return view('administrateur/bundles/creation', [
				'validation' => \Config\Services::validation(),
			]);
		}

		$data = [
			'description' => $this->request->getPost('description'),
			'prix' => $this->request->getPost('prix'),
		];
		
		$this->bundleModel->update($id, $data);

		return redirect()->to('admin/bundles');
    }

    public function supprimer($id)
    {
		$bundleProduitModel = new BundleProduitModel();
   		$bundleProduitModel->where('id_bundle', $id)->delete();

		$this->bundleModel->delete($id);
        return redirect()->to('admin/bundles');
    }

	public function ajouter_produit($bundle_id)
	{
		$produit_id = $this->request->getPost('produit_id');
		$quantite = $this->request->getPost('quantite'); // Récupération de la quantité
		
		$bundleProduitModel = new BundleProduitModel();

		// Vérifier si le produit est déjà dans le bundle
		$exists = $bundleProduitModel->where('id_bundle', $bundle_id)
									->where('id_produit', $produit_id)
									->first();

		if ($exists) {
			// Si le produit est déjà dans le bundle, mettre à jour la quantité
			$bundleProduitModel->update(
				$exists['id_bundle_produit'], // id de la relation dans la table bundle_produit
				['quantite' => $exists['quantite'] + $quantite] // Ajouter la quantité
			);
		} else {
			// Si le produit n'est pas encore dans le bundle, l'ajouter
			$bundleProduitModel->insert([
				'id_bundle'  => $bundle_id,
				'id_produit' => $produit_id,
				'quantite'   => $quantite
			]);
		}

		return redirect()->to('/admin/bundles/modification/' . $bundle_id);
	}


	public function enlever_produit($bundle_id)
	{
		$produit_id = $this->request->getPost('produit_id');

		$bundleProduitModel = new BundleProduitModel();

		// Supprimer l'association entre le produit et le bundle
		$bundleProduitModel->where('id_bundle', $bundle_id)
						->where('id_produit', $produit_id)
						->delete();

		return redirect()->to('/admin/bundles/modification/' . $bundle_id);
	}


	// Récupérer les produits qui ne sont pas dans un bundle
	public function getProduitsNonAssignes()
	{
		return $this->produitModel->findAll();
	}

	// Récupérer les produits associés à un bundle spécifique
	public function getProduitsAssignes($id_bundle)
	{
		$produitModel = new ProduitModel();
		$bundleProduitModel = new BundleProduitModel();
		
		// Récupérer les produits associés à un bundle
		$builder = $produitModel->builder();
		$builder->join('bundle_produit', 'bundle_produit.id_produit = produit.id_produit');
		$builder->where('bundle_produit.id_bundle', $id_bundle);

		return $builder->get()->getResultArray();
	}



}
