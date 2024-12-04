<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CodePromoModel;
use App\Models\ImageModel;
use App\Models\ProduitModel;

class CodePromoController extends BaseController
{
	protected $codePromoModel;

	public function __construct()
	{
        $this->codePromoModel = new CodePromoModel();
		helper(['form']);
    }

    public function index()
    {

        $codes = $this->codePromoModel->orderBy('id_codepromo')->paginate(8);
		
		$data['codes'] = $codes;
		$data['pager'] = \Config\Services::pager();


        return view('administrateur/codes-promos/liste', $data);
    }

    public function creation()
    {
        return view('administrateur/codes-promos/creation');
    }
	
    public function creer()
    {
        $isValid = $this->validate([
            'code' => 'required|max_length[100]',
            'valeur' => 'permit_empty|numeric',
            'pourcentage' => 'permit_empty|numeric',
            'date_debut' => 'required',
            'date_fin' => 'required',
            'max_utilisations' => 'permit_empty|integer',
            'conditions_utilisation' => 'permit_empty',
            'actif' => 'required|in_list[TRUE,FALSE]',
        ]);
    
        if (!$isValid) {
            return view('administrateur/codes-promos/creation', [
                'validation' => \Config\Services::validation(),
            ]);
        }
    
        $data = [
            'code' => $this->request->getPost('code'),
            'valeur' => $this->request->getPost('valeur') ?: null,
            'pourcentage' => $this->request->getPost('pourcentage') ?: null,
            'date_debut' => $this->request->getPost('date_debut'),
            'date_fin' => $this->request->getPost('date_fin'),
            'max_utilisations' => $this->request->getPost('max_utilisations') ?: null,
            'conditions_utilisation' => $this->request->getPost('conditions_utilisation'),
            'actif' => $this->request->getPost('actif'),
        ];
    
        $this->codePromoModel->insert($data);
        return redirect()->to('/admin/codes-promos')->with('success', 'Code promo créé avec succès.');
    }

	public function modification($id)
	{
		// Récupérer les informations du produit avec l'ID
		$codePromoModel = $this->codePromoModel->find($id);
		
		// Vérifier si le produit existe
		if (!$codePromoModel) {
			// Si le produit n'existe pas, rediriger vers la liste des produits avec un message d'erreur
			return redirect()->to('/admin/codes-promos')->with('error', 'Code promo non trouvé');
		}

		// Charger la vue de modification avec les données du produit et ses images
		return view('administrateur/codes-promos/modification', [
			'codepromo' => $codePromoModel,
		]);
	}



    public function modifier($id)
    {
		$isValid = $this->validate([
            'code' => 'required|max_length[100]',
            'valeur' => 'permit_empty|numeric',
            'pourcentage' => 'permit_empty|numeric',
            'date_debut' => 'required',
            'date_fin' => 'required',
            'max_utilisations' => 'permit_empty|integer',
            'conditions_utilisation' => 'permit_empty',
            'actif' => 'required|in_list[TRUE,FALSE]',
        ]);
    
        if (!$isValid) {
            return view('administrateur/codes-promos/modification', [
                'validation' => \Config\Services::validation(),
            ]);
        }
    
        $data = [
            'code' => $this->request->getPost('code'),
            'valeur' => $this->request->getPost('valeur') ?: null,
            'pourcentage' => $this->request->getPost('pourcentage') ?: null,
            'date_debut' => $this->request->getPost('date_debut'),
            'date_fin' => $this->request->getPost('date_fin'),
            'max_utilisations' => $this->request->getPost('max_utilisations') ?: null,
            'conditions_utilisation' => $this->request->getPost('conditions_utilisation'),
            'actif' => $this->request->getPost('actif'),
        ];

		$this->codePromoModel->update($id, $data);
		
		return redirect()->to('admin/codes-promos');
    }

    public function supprimer($id)
    {
		$this->codePromoModel->delete($id);
        return redirect()->to('admin/codes-promos');
    }

    public function appliquerCode()
    {
        $codePromoModel = new CodePromoModel();
        $code = $this->request->getPost('code');

        $codePromo = $codePromoModel->verifierCodePromo($code);

        if ($codePromo) {
            // Code promo valide
            $codePromoModel->incrementerUtilisation($codePromo['id_codepromo']);
            return redirect()->back()->with('success', 'Code promo appliqué avec succès !');
        } else {
            // Code promo invalide
            return redirect()->back()->with('error', 'Code promo invalide ou expiré.');
        }
    }
}