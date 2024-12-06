<?php

namespace App\Controllers;

use App\Models\CodePromoModel;
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

        // Calculer le total TTC
        $totalTTC = 0;
        foreach ($produits as &$produit) {
            $totalTTC += $produit['prixttc'] * $produit['quantite'];
        }

        // Code promo
        $request = service('request');
        $codePromo = $request->getCookie('code_promo');
        $messagePromo = null;
        $promo = null;

        if ($codePromo) {
            $result = $this->isPromoValid($codePromo);

            if ($result['valid']) {
                $promo = $result['promo'];
                if($promo['valeur'] != 0) {
                    $totalTTC -= $promo['valeur'];
                    $messagePromo = "Code promo appliqué : -" . $promo['valeur'] . "€";
                }
                else {
                    $totalTTC -= $totalTTC * ($promo['pourcentage'] / 100);
                    $messagePromo = "Code promo appliqué : -" . $promo['pourcentage'] . "%";
                }
            } else {
                $messagePromo =  $result['message'];
            }
        }

        $data['messagePromo'] = $messagePromo;
        $data['code_promo'] = $promo;
        $data['totalPromo'] = $totalTTC ;
        $data['produits'] = $produits;

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

    public function retirerProduit($idProduit)
    {
        $panier = $this->getPanier();

        $produitModel = new ProduitModel();
        $produit = $produitModel->find($idProduit);

        // Vérifie si le produit est dans le panier
        if (isset($panier[$idProduit])) {
            unset($panier[$idProduit]); // Supprime le produit du panier

            // Ajoute un message de confirmation
            session()->setFlashdata('success', "Le produit '{$produit['nom']}' a été retiré du panier.");
        } else {
            // Message si le produit n'est pas trouvé dans le panier
            session()->setFlashdata('error', "Le produit demandé n'est pas dans le panier.");
        }

        // Met à jour le panier
        $this->setPanier($panier);

        // Redirige vers la page précédente ou la vue panier
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

        // Code promo
        $request = service('request');
        $codePromo = $request->getCookie('code_promo');
        $symbole = null;
        $promo = null;

        if ($codePromo) {
            $result = $this->isPromoValid($codePromo);

            if ($result['valid']) {
                $promo = $result['promo'];
                if($promo['valeur'] != 0) {
                    $totalTTC -= $promo['valeur'];
                    $symbole = "€";                }
                else {
                    $totalTTC -= $totalTTC * ($promo['pourcentage'] / 100);
                    $symbole = "%";
                }
            } 
        }

        // Récupérer l'utilisateur connecté et son adresse
        $utilisateur = $utilisateurModel->find(session()->get('idutilisateur'));

        $data = [
            'symbole' => $symbole,
            'code_promo' => $promo,
            'produits' => $produits,
            'totalPromo' => $totalTTC,
            'utilisateur' => $utilisateur,
            'modesLivraison' => ['Standard', 'Express', 'Point relais'], // Options de livraison
            'modesPaiement' => ['Carte bancaire', 'PayPal', 'Virement bancaire'], // Moyens de paiement
        ];

        return view('recapitulatif', $data);
    }

    public function update()
    {
        $request = $this->request->getJSON();

        if (!isset($request->id_produit) || !isset($request->delta)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Paramètres invalides.']);
        }

        $idProduit = (int)$request->id_produit;
        $delta = (int)$request->delta;

        $produitModel = new ProduitModel();
        $produit = $produitModel->find($idProduit);

        if (!$produit) {
            return $this->response->setJSON(['success' => false, 'message' => 'Produit introuvable.']);
        }

        // Met à jour la quantité
        $panier = $this->getPanier();
        $nouvelleQuantite = max(0, ($panier[$idProduit] ?? 0) + $delta);

        if ($nouvelleQuantite > 0) {
            $panier[$idProduit] = $nouvelleQuantite;
        } else {
            unset($panier[$idProduit]);
        }

        $this->setPanier($panier);

        // Recalculer le total TTC
        $totalTTC = 0;
        foreach ($panier as $id => $quantite) {
            $p = $produitModel->find($id);
            if ($p) {
                $totalTTC += $p['prixttc'] * $quantite;
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'newQuantity' => $nouvelleQuantite,
            'newPrice' => $produit['prixttc'] * $nouvelleQuantite,
            'totalTTC' => $totalTTC,
        ]);
    }

    public function appliquerPromo()
    {
        $response = service('response');
        
        $codePromo = $this->request->getPost('code_promo');
        if ($codePromo) {
            $response->setCookie('code_promo', $codePromo, (60 * 60));

            service('response')->send(); 
        }
        else {
            $response->setCookie('code_promo', '', 1);

            service('response')->send(); 
        }
        return redirect()->to('/panier'); 
    }

    public function isPromoValid($codePromo)
    {
        $codePromoModel = new CodePromoModel();
        $promo = $codePromoModel->where('code', $codePromo)->first();

        if (!$promo || !$promo['actif']) {
            return [
                'valid' => false,
                'message' => "Le code promo n'existe pas ou est inactif."
            ];
        }

        $now = new \DateTime();

        // Vérifier si le code promo est dans les dates d'utilisation
        $dateDebut = new \DateTime($promo['date_debut']);
        $dateFin = new \DateTime($promo['date_fin']);

        if ($now < $dateDebut || $now > $dateFin) {
            return [
                'valid' => false,
                'message' => "Le code promo n'est pas valide à cette date."
            ];
        }

        // Vérifier si le nombre d'utilisations est dans la limite
        if ($promo['utilisation_actuelle'] >= $promo['utilisation_max']) {
            return [
                'valid' => false,
                'message' => "Le code promo a atteint sa limite d'utilisation."
            ];
        }

        // Code promo valide
        return [
            'valid' => true,
            'promo' => $promo,
            'message' => "Code promo valide."
        ];
    }


}
?>