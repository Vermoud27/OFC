<?php

namespace App\Controllers;

use App\Models\ImageModel;
use App\Models\ProduitModel;
use App\Models\UtilisateurModel;

class PanierController extends BaseController
{
    public function index(): string
    {
        $panier = $this->getPanier();

        // Charger les produits du panier depuis la base de données
        $produitModel = new ProduitModel();
        $produits = [];
        foreach ($panier as $idProduit => $quantite) {
            $produit = $produitModel->find($idProduit);
            if ($produit) {
                $produit['quantite'] = $quantite;
                $produits[] = $produit;
            }
        }

        $imageModel = new ImageModel();

        foreach ($produits as &$produit) {
			$images = $imageModel->getImagesByProduit($produit['id_produit']);
            $produit['images'] = !empty($images) ? $images : [['chemin' => '/assets/img/user.png']];		
        }

        $data = ['produits' => $produits];
        return view('panier', $data);
    }

    public function modifierPanier($idProduit, $delta)
    {
        $panier = $this->getPanier();
        
        $produitModel = new ProduitModel();
        $produit = $produitModel->find($idProduit);
    
        // Vérifie si le produit est dans le panier
        if (isset($panier[$idProduit])) {
            // Modifie la quantité en fonction du delta
            $panier[$idProduit] += $delta;
    
            // Si la quantité devient 0 ou moins, retirer le produit
            if ($panier[$idProduit] <= 0) {
                unset($panier[$idProduit]);
                session()->setFlashdata('success', "Le produit '{$produit['nom']}' a été retiré du panier.");
            } else {
                session()->setFlashdata('success', "Quantité de '{$produit['nom']}' mise à jour.");
            }
        } elseif ($delta > 0) {
            // Si le produit n'est pas dans le panier mais le delta est positif, on l'ajoute
            $panier[$idProduit] = $delta;
            session()->setFlashdata('success', "Le produit '{$produit['nom']}' a été ajouté au panier.");
        }
    
        // Met à jour le panier
        $this->setPanier($panier);
    
        // Redirige vers la page précédente sans écraser les flashdata définies plus haut
        return redirect()->back();
    } 

    public function viderPanier()
    {
        $this->setPanier([]);
        return redirect()->back()->with('success', 'Panier vidé.');
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

    private function setPanier(array $panier)
    {
        $response = service('response');
        $cookieValue = json_encode($panier);

        $response->setCookie('panier', $cookieValue, 30 * 24 * 60 * 60);

        service('response')->send(); 
    }

    public function recapitulatif(): string
    {
        $produitModel = new ProduitModel();
        $utilisateurModel = new UtilisateurModel(); // Modèle pour les utilisateurs

        // Récupérer les produits dans le panier
        $panier = $this->getPanier(); // Méthode à adapter pour récupérer les produits du panier
        $produits = [];
        $totalTTC = 0;

        foreach ($panier as $idProduit => $quantite) {
            $produit = $produitModel->find($idProduit);
            if ($produit) {
                $produit['quantite'] = $quantite;
                $produit['total'] = $quantite * $produit['prixttc'];
                $produits[] = $produit;
                $totalTTC += $produit['total'];
            }
        }

        // Récupérer l'utilisateur connecté et son adresse
        $utilisateur = $utilisateurModel->find(session()->get('idutilisateur'));

        $data = [
            'produits' => $produits,
            'totalTTC' => $totalTTC,
            'utilisateur' => $utilisateur,
            'modesLivraison' => ['Standard', 'Express', 'Point relais'], // Options de livraison
            'modesPaiement' => ['Carte bancaire', 'PayPal', 'Virement bancaire'], // Moyens de paiement
        ];

        return view('recapitulatif', $data);
    }

}
?>
