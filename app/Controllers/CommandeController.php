<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CommandeModel;
use App\Models\DetailsCommandeModel;
use App\Models\ProduitModel;

class CommandeController extends BaseController
{
    public function enregistrerCommande()
    {
        
		// Récupérer les informations du panier
        $session = session();
        $panier = $this->getPanier();
        $userId = $session->get('idutilisateur'); // L'ID de l'utilisateur connecté

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
            'id_utilisateur' => $userId,
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
		$response->setCookie('panier', "", 30 * 24 * 60 * 60);

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
}
