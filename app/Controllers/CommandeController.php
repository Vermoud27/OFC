<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CodePromoModel;
use App\Models\CommandeModel;
use App\Models\DetailsCommandeModel;
use App\Models\ProduitModel;
use App\Models\UtilisateurModel;

class CommandeController extends BaseController
{
    public function __construct()
	{
		helper(['form']);
    }

    public function index()
    {
        $commandeModel = new CommandeModel();

        // Récupérer les commandes (exclure les statuts 'fini' et 'annulé')
        $commandes = $commandeModel->select('commande.*, utilisateur.mail')
        ->join('utilisateur', 'utilisateur.id_utilisateur = commande.id_utilisateur')
        ->whereNotIn('statut', ['fini', 'annulé'])
        ->orderBy('id_commande')->paginate(8);
        
        // Calculer les statistiques (nombre de commandes dans chaque état)
        $statistiques = [
            'en_attente' => $commandeModel->where('statut', 'en attente')->countAllResults(),
            'expedie' => $commandeModel->where('statut', 'expédié')->countAllResults(),
            'livre' => $commandeModel->where('statut', 'livré')->countAllResults(),
            'fini' => $commandeModel->where('statut', 'fini')->countAllResults(),
            'annule' => $commandeModel->where('statut', 'annulé')->countAllResults(),
            'total' => $commandeModel->countAllResults(), // Total des commandes
        ];

        // Passer les données à la vue
        $data['commandes'] = $commandes;
        $data['pager'] = \Config\Services::pager();
        $data['statistiques'] = $statistiques;

        return view('administrateur/commandes/liste', $data);
    }


    public function modifier($id)
    {
		$commandeModel = new CommandeModel();

        $this->validate([
			'statut' => 'required',
		]);
        
        $data = [
            'statut' => $this->request->getPost('statut'),
        ];

		$commandeModel->update($id, $data);
		
		return redirect()->to('admin/commandes');
    }
    
    public function enregistrerCommande()
    {
		$session = session();
        $panier = $this->getPanier();
        $userId = $session->get('idutilisateur'); 
		
		$isValid = $this->validate([
			'nom' => 'required|max_length[50]',
			'adresse' => 'required|max_length[100]',
			'code_postal' => 'required|max_length[100]',
			'ville' => 'required|max_length[100]',
		]);

		if (!$isValid)
		{
			return view('recapitulatif', [
				'validation' => \Config\Services::validation(),
			]);
		}

		$data = [
			'nom' => $this->request->getPost('nom'),
			'adresse' => $this->request->getPost('adresse'),
			'code_postal' => $this->request->getPost('code_postal'),
			'ville' => $this->request->getPost('ville'),
		];

        $prixpromo = $this->request->getPost('prix_total') ?: null;
        $idpromo = $this->request->getPost('idpromo') ?: null;

        if(!empty($idpromo))
        {
            $codepromoModel = new CodePromoModel();
            $code = $codepromoModel->find($idpromo);
            $util = $code['utilisation_actuelle'] + 1;
            $codepromoModel->update($idpromo, ['utilisation_actuelle' => $util]);
        }

		$utilisateurModel = new UtilisateurModel();
		$utilisateurModel->update($userId, $data);
        
		$utilisateur = $utilisateurModel->find($userId);

        // Calculer le prix total TTC de la commande
        $prixTotalHT = 0;
        $prixTotalTTC = 0;

		$produitModel = new ProduitModel();
        $produits = [];
        foreach ($panier as $idProduit => $quantite) {
            $produit = $produitModel->find($idProduit);
            if ($produit) {
                $produit['quantite'] = $quantite;
                $produits[] = $produit;
            }
        }

		foreach ($produits as $produit) {
			$prixTotalHT += $produit['prixht'] * $produit['quantite'];
        	$prixTotalTTC += $produit['prixttc'] * $produit['quantite'];
		}

        // Enregistrer la commande
        $commandeModel = new CommandeModel();
        $commandeData = [
            'date_creation' => date('Y-m-d H:i:s'),
            'statut' => 'en attente', // Statut initial
            'prixht' => $prixTotalHT,
            'prixttc' => $prixTotalTTC,
            'prixpromo' => $prixpromo,
            'id_utilisateur' => $userId,
			'informations' => $utilisateur['adresse'] . ' ' . $utilisateur['code_postal'] . ' ' .$utilisateur['ville'],
        ];

        $commandeId = $commandeModel->insert($commandeData);

        // Enregistrer les détails de la commande
        $detailsCommandeModel = new DetailsCommandeModel();

		foreach ($produits as $produit) {
			$detailsCommandeData = [
                'id_commande' => $commandeId,
                'id_produit' => $produit['id_produit'],
                'quantite' => $produit['quantite'],
            ];

            // Ajouter les détails de commande
            $detailsCommandeModel->insert($detailsCommandeData);
		}

		$response = service('response');
		$response->setCookie('panier', '', time() - 3600);

		$response->send();

        // Rediriger vers la page de confirmation
        return redirect()->to('/ControllerOFC')->with('message', 'Commande enregistrée avec succès.');
    }

	private function getPanier(): array
    {
        $request = service('request');
        $cookie = $request->getCookie('panier');

        if (!$cookie) {
            return [];
        }

        $panier = json_decode($cookie, true);
        return is_array($panier) ? $panier : [];
    }

    public function mescommandes()
    {
        $commandeModel = new CommandeModel();

        // Récupérer les commandes (exclure les statuts 'fini' et 'annulé')
        $commandes = $commandeModel->where('id_utilisateur', session()->get('idutilisateur'))->whereNotIn('statut', ['fini', 'annulé'])->orderBy('id_commande')->paginate(9);

        // Passer les données à la vue
        $data['commandes'] = $commandes;
        $data['pager'] = \Config\Services::pager();

        return view('/commande', $data);
    }
}
