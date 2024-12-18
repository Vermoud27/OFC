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
		
        $now = date('Y-m-d H:i:s');

        // Liste des codes expirés ou ayant atteint leur limite
        $codesExpirésOuMaxUtilisation = $this->codePromoModel
            ->groupStart()
                ->where('date_fin <', $now)
                ->orWhere('utilisation_actuelle >= utilisation_max')
            ->groupEnd()
            ->findAll();
    
        // Nombre total de codes actifs
        $totalCodesActifs = $this->codePromoModel
            ->where('actif', true)
            ->countAllResults();
    
        // Nombre total de codes inactifs
        $totalCodesInactifs = $this->codePromoModel
            ->where('actif', false)
            ->countAllResults();

            $data = [
                'codes' => $codes,
                'pager' => \Config\Services::pager(),
                'stats' => [
                    'codes_expirés_ou_max' => $codesExpirésOuMaxUtilisation,
                    'total_actifs' => $totalCodesActifs,
                    'total_inactifs' => $totalCodesInactifs
                ]
            ];


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
            'utilisation_max' => 'permit_empty|integer',
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
            'valeur' => $this->request->getPost('valeur') ?: 0,
            'pourcentage' => $this->request->getPost('pourcentage') ?: 0,
            'date_debut' => $this->request->getPost('date_debut'),
            'date_fin' => $this->request->getPost('date_fin'),
            'utilisation_max' => $this->request->getPost('utilisation_max') ?: null,
            'conditions_utilisation' => $this->request->getPost('conditions_utilisation'),
            'actif' => $this->request->getPost('actif'),
            'utilisation_actuelle' => 0,
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
            'utilisation_max' => 'permit_empty|integer',
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
            'valeur' => $this->request->getPost('valeur') ?: 0,
            'pourcentage' => $this->request->getPost('pourcentage') ?: 0,
            'date_debut' => $this->request->getPost('date_debut'),
            'date_fin' => $this->request->getPost('date_fin'),
            'utilisation_max' => $this->request->getPost('utilisation_max') ?: null,
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