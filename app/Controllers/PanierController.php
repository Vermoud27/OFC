<?php

namespace App\Controllers;

use App\Models\BundleModel;
use App\Models\BundleProduitModel;
use App\Models\CodePromoModel;
use App\Models\ImageModel;
use App\Models\ProduitModel;
use App\Models\GammeModel;
use App\Models\UtilisateurModel;

class PanierController extends BaseController
{
    public function index(): string
    {
        $panier = $this->getPanier();
        $panierGamme = $this->getPanierGamme();
        $panierBundle = $this->getPanierBundle();

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
            $produit['images'] = !empty($images) ? $images : [['chemin' => '/assets/img/produits/placeholder.png']];		
        }

        // Calculer le total TTC
        $totalTTC = 0;
        foreach ($produits as &$produit) {
            $totalTTC += $produit['prixttc'] * $produit['quantite'];
        }
        

        // Charger les gammes du panier
        $gammeModel = new GammeModel();
        $gammes = [];
        $produitsParGamme = [];

        foreach ($panierGamme as $idGamme => $quantite) {
            $gamme = $gammeModel->find($idGamme);

            if ($gamme) {
                $gamme['quantite'] = $quantite;
                $gammes[] = $gamme;

                // Charger les produits de la gamme
                $produitsDeGamme = $produitModel->where('id_gamme', $idGamme)->findAll();
                foreach ($produitsDeGamme as &$produit) {
                    $produit['quantite'] = $quantite; // Quantité basée sur la gamme
                    $produit['images'] = $imageModel->getImagesByProduit($produit['id_produit']);
                    $produit['images'] = !empty($produit['images']) ? $produit['images'] : [['chemin' => '/assets/img/produits/placeholder.png']];
                }
                $produitsParGamme[$idGamme] = $produitsDeGamme;
            }
        }

        // Ajouter les images pour chaque gamme
        foreach ($gammes as &$gamme) {
            $images = $imageModel->getImagesByProduit($gamme['id_gamme']);
            $gamme['images'] = !empty($images) ? $images : [['chemin' => '/assets/img/produits/placeholder.png']];
        }

        foreach ($gammes as &$gamme) {
            $totalTTC += $gamme['prixttc'] * $gamme['quantite'];
        }


        // Charger les gammes du panier
        $bundleModel = new BundleModel();
        $bundleProduitModel = new BundleProduitModel();
        $bundles = [];
        $produitsParBundle = [];

        foreach ($panierBundle as $idBundle => $quantite) {
            $bundle = $bundleModel->find($idBundle);

            if ($bundle) {
                $bundle['quantite'] = $quantite;
                $bundles[] = $bundle;

                // Charger les produits de la gamme
                $produitsDeBundle = $bundleProduitModel->getProductsByBundle($idBundle);
                foreach ($produitsDeBundle as &$produit) {
                    $produit['images'] = $imageModel->getImagesByProduit($produit['id_produit']);
                    $produit['images'] = !empty($produit['images']) ? $produit['images'] : [['chemin' => '/assets/img/produits/placeholder.png']];
                }
                $produitsParBundle[$idBundle] = $produitsDeBundle;
            }
        }

        foreach ($bundles as &$bundle) {
            $totalTTC += $bundle['prix'] * $bundle['quantite'];
        }

        // Gestion du code promo
        $request = service('request');
        $codePromo = $request->getCookie('code_promo');
        $messagePromo = null;
        $promo = null;

        if ($codePromo) {
            $result = $this->isPromoValid($codePromo);

            if ($result['valid']) {
                $promo = $result['promo'];
                if ($promo['valeur'] != 0) {
                    $totalTTC -= $promo['valeur'];
                    $messagePromo = "Code promo appliqué : -" . $promo['valeur'] . "€";
                } else {
                    $totalTTC -= $totalTTC * ($promo['pourcentage'] / 100);
                    $messagePromo = "Code promo appliqué : -" . $promo['pourcentage'] . "%";
                }
            } else {
                $messagePromo = $result['message'];
            }
        }

        $data['messagePromo'] = $messagePromo;
        $data['code_promo'] = $promo;
        $data['totalPromo'] = $totalTTC;
        $data['produits'] = $produits;
        $data['gammes'] = $gammes;
        $data['bundles'] = $bundles;
        $data['produitsParGamme'] = $produitsParGamme;
        $data['produitsParBundle'] = $produitsParBundle;

        return view('panier', $data);
    }


    function getQuantiteMaxPourGamme($idGamme)
    {
        // Modèle pour récupérer les produits d'une gamme
        $produitModel = new ProduitModel();
    
        // Récupérer tous les produits associés à la gamme
        $produits = $produitModel->getProduitsByGamme($idGamme);
    
        if (empty($produits)) {
            // Si la gamme n'a pas de produits associés, retourner 0
            return 0;
        }
    
        // Initialiser le stock minimum à une valeur très élevée
        $stockMinimum = PHP_INT_MAX;
    
        // Parcourir tous les produits pour trouver le stock minimal
        foreach ($produits as $produit) {
            if (isset($produit['qte_stock'])) {
                $stockMinimum = min($stockMinimum, (int)$produit['qte_stock']);
            }
        }
    
        // Si aucun produit n'a de stock défini, retourner 0
        if ($stockMinimum === PHP_INT_MAX) {
            return 0;
        }
    
        return $stockMinimum;
    }
   
    
    private function calculateMaxQuantityForGamme($idGamme)
    {
        $produitModel = new ProduitModel();
        $produits = $produitModel->where('id_gamme', $idGamme)->findAll();
    
        $maxQuantity = PHP_INT_MAX;
        foreach ($produits as $produit) {
            $stock = $produit['qte_stock'] ?? 0;
            $maxQuantity = min($maxQuantity, $stock);
        }
    
        return $maxQuantity === PHP_INT_MAX ? 0 : $maxQuantity;
    }

    private function calculateMaxQuantityForBundle($idBundle)
    {
        $bundleproduitModel = new BundleProduitModel();
        $produits = $bundleproduitModel->getProductsByBundle($idBundle);
    
        $maxQuantity = PHP_INT_MAX;
        foreach ($produits as $produit) {
            $stock = ($produit['qte_stock'] / $produit['quantite'])?? 0;
            $maxQuantity = min($maxQuantity, $stock);
        }
    
        return $maxQuantity === PHP_INT_MAX ? 0 : $maxQuantity;
    }
    
    public function calculateTotalsForCart($panier)
    {
        $totalHT = 0;  // Total Hors Taxes
        $totalTTC = 0; // Total Toutes Taxes Comprises
        $totalTaxes = 0; // Total des taxes

        // Si le panier contient des gammes
        if (isset($panier['gammes'])) {
            foreach ($panier['gammes'] as $idGamme => $quantiteGamme) {
                // Récupérer la gamme
                $gammeModel = new GammeModel();
                $gamme = $gammeModel->find($idGamme);

                if ($gamme) {
                    // Calcul du prix de la gamme
                    $prixUnitaireTTC = $gamme['prixttc'];  // Prix TTC de la gamme
                    $prixUnitaireHT = $gamme['prixht'];    // Prix HT de la gamme
                    $totalTTC += $prixUnitaireTTC * $quantiteGamme; // Ajouter au total TTC
                    $totalHT += $prixUnitaireHT * $quantiteGamme;   // Ajouter au total HT
                    $totalTaxes += ($prixUnitaireTTC - $prixUnitaireHT) * $quantiteGamme; // Calcul des taxes
                }
            }
        }

        // Si le panier contient des produits
        if (isset($panier['produits'])) {
            foreach ($panier['produits'] as $idProduit => $quantiteProduit) {
                // Récupérer le produit
                $produitModel = new ProduitModel();
                $produit = $produitModel->find($idProduit);

                if ($produit) {
                    // Calcul du prix du produit
                    $prixUnitaireTTC = $produit['prixttc'];  // Prix TTC du produit
                    $prixUnitaireHT = $produit['prixht'];    // Prix HT du produit
                    $totalTTC += $prixUnitaireTTC * $quantiteProduit; // Ajouter au total TTC
                    $totalHT += $prixUnitaireHT * $quantiteProduit;   // Ajouter au total HT
                    $totalTaxes += ($prixUnitaireTTC - $prixUnitaireHT) * $quantiteProduit; // Calcul des taxes
                }
            }
        }

        // Calculs des autres éléments du panier (si nécessaire)
        // Par exemple, des frais de livraison ou des remises
        // Vous pouvez ajouter d'autres règles de calcul ici

        // Retourner les totaux
        return [
            'totalHT' => $totalHT,
            'totalTTC' => $totalTTC,
            'totalTaxes' => $totalTaxes,
        ];
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

    public function modifierPanierGamme($idGamme, $delta){

        $panier = $this->getPanierGamme();

        $gammeModel = new GammeModel();
        $gamme = $gammeModel->find($idGamme);
    
        // Vérifie si le produit est dans le panier
        if (isset($panier[$idGamme])) {
            // Modifie la quantité en fonction du delta
            $panier[$idGamme] += $delta;
    
            // Si la quantité devient 0 ou moins, retirer le produit
            if ($panier[$idGamme] <= 0) {
                unset($panier[$idGamme]);
                session()->setFlashdata('success', "La gamme '{$gamme['nom']}' a été retiré du panier.");
            } else {
                session()->setFlashdata('success', "Quantité de '{$gamme['nom']}' mise à jour.");
            }
        } elseif ($delta > 0) {
            // Si le produit n'est pas dans le panier mais le delta est positif, on l'ajoute
            $panier[$idGamme] = $delta;
            session()->setFlashdata('success', "La gamme '{$gamme['nom']}' a été ajouté au panier.");
        }

        // Met à jour le panier
        $this->setPanierGamme($panier);
    
        // Redirige vers la page précédente sans écraser les flashdata définies plus haut
        return redirect()->back();
    }

    public function modifierPanierBundle($idBundle, $delta){

        $panier = $this->getPanierBundle();

        $bundleModel = new BundleModel();
        $bundle = $bundleModel->find($idBundle);
    
        // Vérifie si le produit est dans le panier
        if (isset($panier[$idBundle])) {
            // Modifie la quantité en fonction du delta
            $panier[$idBundle] += $delta;
    
            // Si la quantité devient 0 ou moins, retirer le produit
            if ($panier[$idBundle] <= 0) {
                unset($panier[$idBundle]);
                session()->setFlashdata('success', "Le bundle a été retiré du panier.");
            } else {
                session()->setFlashdata('success', "Quantité du bundle mise à jour.");
            }
        } elseif ($delta > 0) {
            // Si le produit n'est pas dans le panier mais le delta est positif, on l'ajoute
            $panier[$idBundle] = $delta;
            session()->setFlashdata('success', "La bundle a été ajouté au panier.");
        }

        // Met à jour le panier
        $this->setPanierBundle($panier);
    
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

    public function retirerGamme($idGamme)
    {
        // Récupérer le panier actuel depuis la session
        $panier = $this->getPanierGamme();

        // Initialiser le modèle de la gamme pour obtenir les informations nécessaires
        $gammeModel = new GammeModel();
        $gamme = $gammeModel->find($idGamme);

        // Vérifier si la gamme existe dans le panier
        if (isset($panier[$idGamme])) {
            // Retirer la gamme du panier
            unset($panier[$idGamme]);

            // Ajouter un message de confirmation
            session()->setFlashdata('success', "La gamme '{$gamme['nom']}' a été retirée du panier.");
        } else {
            // Si la gamme n'est pas dans le panier, ajouter un message d'erreur
            session()->setFlashdata('error', "La gamme demandée n'est pas dans le panier.");
        }

        // Mettre à jour le panier dans la session
        $this->setPanierGamme($panier);

        // Rediriger vers la page du panier après la suppression ou vers une autre page si nécessaire
        return redirect()->to('/panier'); // ou redirect()->back() selon le flux de votre application
    }

    public function retirerBundle($idBundle)
    {
        // Récupérer le panier actuel depuis la session
        $panier = $this->getPanierBundle();

        // Initialiser le modèle de la gamme pour obtenir les informations nécessaires
        $bundleModel = new BundleModel();
        $bundle = $bundleModel->find($idBundle);

        // Vérifier si la gamme existe dans le panier
        if (isset($panier[$idBundle])) {
            // Retirer la gamme du panier
            unset($panier[$idBundle]);

            // Ajouter un message de confirmation
            session()->setFlashdata('success', "Le bundle a été retirée du panier.");
        } else {
            session()->setFlashdata('error', "Le bundle demandé n'est pas dans le panier.");
        }

        // Mettre à jour le panier dans la session
        $this->setPanierBundle($panier);

        // Rediriger vers la page du panier après la suppression ou vers une autre page si nécessaire
        return redirect()->to('/panier'); // ou redirect()->back() selon le flux de votre application
    }



    public function viderPanier()
    {
        $this->setPanier([]);
        $this->setPanierGamme([]);
        $this->setPanierBundle([]);
        return redirect()->back()->with('success', 'Panier vidé.');
    }


    //Méthodes avec acces aux cookies
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

   
    private function getPanierGamme(): array
    {
        // Lecture du cookie via $_COOKIE pour s'assurer que la valeur est bien récupérée
        log_message('debug', 'Contenu du cookie panierGamme (via $_COOKIE) : ' . json_encode($_COOKIE['panierGamme'] ?? 'Non défini'));
    
        // Récupération du cookie via la requête
        $request = service('request');
        $cookie = $request->getCookie('panierGamme');
        log_message('debug', 'Contenu du cookie panierGamme (via service) : ' . $cookie);
    
        if (!$cookie) {
            log_message('debug', 'Cookie panierGamme non trouvé.');
            return [];
        }
    
        $panierGamme = json_decode($cookie, true);
        log_message('debug', 'Données panier après décodage : ' . json_encode($panierGamme));
        return is_array($panierGamme) ? $panierGamme : [];
    }
    

    private function setPanierGamme(array $panierGamme)
    {
        $response = service('response');
        $cookieValue = json_encode($panierGamme);
        $response->setCookie('panierGamme', $cookieValue, 30 * 24 * 60 * 60);
    
        // Log des en-têtes avant l'envoi
        log_message('debug', 'En-têtes de réponse: ' . json_encode($response->getHeaders()));
    
        // Envoi de la réponse avec le cookie
        $response->send();
        log_message('debug', 'Cookie panierGamme mis à jour : ' . $cookieValue);
    }

    private function getPanierBundle(): array
    {
        // Récupération du cookie via la requête
        $request = service('request');
        $cookie = $request->getCookie('panierBundle');
    
        if (!$cookie) {
            return [];
        }
    
        $panierBundle = json_decode($cookie, true);
        return is_array($panierBundle) ? $panierBundle : [];
    }
    

    private function setPanierBundle(array $panierBundle)
    {
        $response = service('response');
        $cookieValue = json_encode($panierBundle);
        $response->setCookie('panierBundle', $cookieValue, 30 * 24 * 60 * 60);

        // Envoi de la réponse avec le cookie
        $response->send();
    }
    

    

    //Méthodes pour changer les quantités du panier
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
    
        // Vérification du stock disponible
        $stockDisponible = (int)$produit['qte_stock'];
        $panier = $this->getPanier();
        $quantiteActuelle = $panier[$idProduit] ?? 0;
        $nouvelleQuantite = $quantiteActuelle + $delta;
    
        if ($nouvelleQuantite > $stockDisponible) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "La quantité demandée dépasse le stock disponible ({$stockDisponible} articles)."
            ]);
        }
    
        // Mise à jour de la quantité
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


    
    public function updateGamme()
    {
        try {
            $request = $this->request->getJSON();
        
            // Vérification des paramètres
            if (!isset($request->id_gamme) || !isset($request->delta)) {
                log_message('error', 'Paramètres invalides: id_gamme ou delta manquants');
                return $this->response->setJSON(['success' => false, 'message' => 'Paramètres invalides.']);
            }
        
            $idGamme = (int)$request->id_gamme;
            $delta = (int)$request->delta;
        
            log_message('debug', 'Requête reçue: id_gamme = ' . $idGamme . ', delta = ' . $delta);
        
            // Récupérer le panier
            $panier['gammes'] = $this->getPanierGamme();
        
            log_message('debug', 'Panier actuel : ' . json_encode($panier));
        
            // Vérifier que la clé 'gammes' existe
            if (!isset($panier['gammes'])) {
                $panier['gammes'] = [];
            }

            // Recherche de la gamme
            $gammeModel = new GammeModel();
            $gamme = $gammeModel->find($idGamme);

            if (!$gamme) {
                log_message('error', 'Gamme introuvable: ' . $idGamme);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gamme introuvable.',
                ]);
            }

            // Calcul de la quantité maximale
            $maxQuantity = $this->calculateMaxQuantityForGamme($idGamme);
            log_message('debug', 'Quantité maximale pour la gamme ' . $idGamme . ': ' . $maxQuantity);

            // Vérification avant la modification
            log_message('debug', 'Avant modification : ' . json_encode($panier['gammes']));

            if (isset($panier['gammes'][$idGamme])) {
                // Modification de la quantité
                $newQuantity = ((int)$panier['gammes'][$idGamme]) + $delta;
                log_message('debug', "Nouveau quantity pour la gamme {$idGamme} : {$newQuantity}");
                
                if ($newQuantity <= 0) {
                    unset($panier['gammes'][$idGamme]);
                } elseif ($newQuantity > $maxQuantity) {
                    log_message('error', "Quantité dépassée pour la gamme {$idGamme}");
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => "Vous ne pouvez pas dépasser la quantité maximale de {$maxQuantity} pour cette gamme.",
                    ]);
                } else {
                    $panier['gammes'][$idGamme] = $newQuantity;
                    log_message('debug', "Quantité mise à jour pour la gamme {$idGamme} : {$newQuantity}");
                }
            } elseif ($delta > 0) {
                // Ajout de la gamme
                if ($delta > $maxQuantity) {
                    log_message('error', "Quantité trop élevée pour la gamme {$idGamme}");
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => "Vous ne pouvez pas dépasser la quantité maximale de {$maxQuantity} pour cette gamme.",
                    ]);
                }
                $panier['gammes'][$idGamme] = $delta;
            }

            // Enregistrement du panier complet
            log_message('debug', "Avant d'enregistrer : " . json_encode($panier));
            $this->setPanierGamme($panier['gammes']);  // Enregistrer l'ensemble du panier, pas seulement les gammes
            log_message('debug', "Panier après mise à jour : " . json_encode($panier));

    
            // Calcul du total du panier
            $totals = $this->calculateTotalsForCart($panier);
    
            // Retourne uniquement les informations essentielles
            return $this->response->setJSON([
                'success' => true,
                'newQuantity' => $panier['gammes'][$idGamme] ?? 0,
                'newPrice' => $gamme['prixttc'] * ($panier['gammes'][$idGamme] ?? 0),
                'totalTTC' => $totals['totalTTC'],
                'message' => 'Quantité mise à jour avec succès.',
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Erreur lors de la mise à jour de la gamme: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Une erreur s\'est produite.',
                'error_details' => $e->getMessage()
            ]);
        }
    }

    public function updateBundle()
    {
        try {
            $request = $this->request->getJSON();
        
            // Vérification des paramètres
            if (!isset($request->id_bundle) || !isset($request->delta)) {
                log_message('error', 'Paramètres invalides: id_bundle ou delta manquants');
                return $this->response->setJSON(['success' => false, 'message' => 'Paramètres invalides.']);
            }
        
            $idBundle = (int)$request->id_bundle;
            $delta = (int)$request->delta;
        
            log_message('debug', 'Requête reçue: id_bundle = ' . $idBundle . ', delta = ' . $delta);
        
            // Récupérer le panier
            $panier['bundles'] = $this->getPanierBundle();
                
            // Vérifier que la clé 'gammes' existe
            if (!isset($panier['bundles'])) {
                $panier['bundles'] = [];
            }

            // Recherche de la gamme
            $bundleModel = new BundleModel();
            $bundle = $bundleModel->find($idBundle);

            if (!$bundle) {
                log_message('error', 'Bundle introuvable: ' . $idBundle);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Bundle introuvable.',
                ]);
            }

            // Calcul de la quantité maximale
            $maxQuantity = $this->calculateMaxQuantityForBundle($idBundle);
            
            if (isset($panier['bundles'][$idBundle])) {
                // Modification de la quantité
                $newQuantity = ((int)$panier['bundles'][$idBundle]) + ((int)$delta);
                
                if ($newQuantity <= 0) {
                    unset($panier['bundles'][$idBundle]);
                } elseif ($newQuantity > $maxQuantity) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => "Vous ne pouvez pas dépasser la quantité maximale de {$maxQuantity} pour cette gamme. {$newQuantity}, {$panier['bundles'][$idBundle]}, {$delta}",
                    ]);
                } else {
                    $panier['bundles'][$idBundle] = $newQuantity;
                }
            } elseif ($delta > 0) {
                // Ajout de la gamme
                if ($delta > $maxQuantity) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => "Vous ne pouvez pas dépasser la quantité maximale de {$maxQuantity} pour cette gamme.",
                    ]);
                }
                $panier['bundles'][$idBundle] = $delta;
            }

            // Enregistrement du panier complet
            log_message('debug', "Avant d'enregistrer : " . json_encode($panier));
            $this->setPanierBundle($panier['bundles']);  // Enregistrer l'ensemble du panier, pas seulement les gammes
            log_message('debug', "Panier après mise à jour : " . json_encode($panier));

    
            // Calcul du total du panier
            $totals = $this->calculateTotalsForCart($panier);
    
            // Retourne uniquement les informations essentielles
            return $this->response->setJSON([
                'success' => true,
                'newQuantity' => $panier['bundles'][$idBundle] ?? 0,
                'newPrice' => $bundle['prix'] * ($panier['bundles'][$idBundle] ?? 0),
                'totalTTC' => $totals['totalTTC'],
                'message' => 'Quantité mise à jour avec succès.',
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Erreur lors de la mise à jour de la gamme: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Une erreur s\'est produite.' . $e->getMessage(),
                'error_details' => $e->getMessage()
            ]);
        }
    }
       



    //Les code promo
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


    //Page de recap du panier pour la commande
    public function recapitulatif(): string
    {
        $produitModel = new ProduitModel();
        $utilisateurModel = new UtilisateurModel(); // Modèle pour les utilisateurs
        $gammeModel = new GammeModel();
        $bundleModel = new BundleModel();

        // Récupérer les produits dans le panier
        $panier = $this->getPanier();
        $paniergamme = $this->getPanierGamme();
        $panierbundle = $this->getPanierBundle();

        $gammes = [];
        $bundles = [];
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

        foreach ($paniergamme as $idGamme => $quantite) {
            $gamme = $gammeModel->find($idGamme);
            if ($gamme) {
                $gamme['quantite'] = $quantite;
                $gamme['total'] = $quantite * $gamme['prixttc'];
                $gammes[] = $gamme;
                $totalTTC += $gamme['total'];
            }
        }

        foreach ($panierbundle as $idBundle => $quantite) {
            $bundle = $bundleModel->find($idBundle);
            if ($bundle) {
                $bundle['quantite'] = $quantite;
                $bundle['total'] = $quantite * $bundle['prix'];
                $bundles[] = $bundle;
                $totalTTC += $bundle['total'];
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
            'gammes' => $gammes,
            'bundles' => $bundles,
            'totalPromo' => $totalTTC,
            'utilisateur' => $utilisateur,
            'modesLivraison' => ['Standard', 'Express', 'Point relais'], // Options de livraison
            'modesPaiement' => ['Carte bancaire', 'PayPal', 'Virement bancaire'], // Moyens de paiement
        ];

        return view('recapitulatif', $data);
    }


}
?>