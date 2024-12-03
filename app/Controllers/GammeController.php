<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GammeModel;
use App\Models\IngredientModel;

class GammeController extends BaseController
{
    protected $gammeModel;

    public function __construct()
    {
        $this->gammeModel = new GammeModel();
		helper(['form']);
    }

    public function index()
	{
		$gammes = $this->gammeModel->orderBy('id_ingredient')->paginate(8);
		
		$data['gammes'] = $gammes;
		$data['pager'] = \Config\Services::pager();

		return view('administrateur/gammes/liste', $data);
	}

	public function creation()
    {
        return view('administrateur/gammes/creation');
    }
	
    public function creer()
	{
		$isValid = $this->validate([
			'nom' => 'required|max_length[100]',
			'description' => 'permit_empty',
			'prixht' => 'required|decimal|greater_than[0]|less_than[1000]',
			'prixttc' => 'required|decimal|greater_than[0]|less_than[1000]',
		]);

		if (!$isValid)
		{
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

		$this->gammeModel->insert($data);
		
		return redirect()->to('admin/gammes');
	}

	public function modification($id)
	{
		// Récupérer les informations du produit avec l'ID
		$gamme = $this->gammeModel->find($id);
		
		// Vérifier si le produit existe
		if (!$gamme) {
			// Si le produit n'existe pas, rediriger vers la liste des produits avec un message d'erreur
			return redirect()->to('/admin/gammes')->with('error', 'Gamme non trouvé');
		}

		// Charger la vue de modification avec les données du produit et ses images
		return view('administrateur/gammes/modification', [
			'gamme' => $gamme,
		]);
	}



    public function modifier($id)
    {
		$isValid = $this->validate([
			'nom' => 'required|max_length[100]',
			'description' => 'permit_empty',
			'prixht' => 'required|decimal|greater_than[0]|less_than[1000]',
			'prixttc' => 'required|decimal|greater_than[0]|less_than[1000]',
		]);

		if (!$isValid)
		{
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
        //TODO supprimer les idgamme dans produit
		
		$this->gammeModel->delete($id);
        return redirect()->to('admin/gammes');
    }

}
