<?php

use App\Controllers\CommandeController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Routes publiques (sans authentification)
// Accueil et pages statiques
$routes->get('/', 'ControllerOFC::index');
$routes->get('/ControllerOFC', 'ControllerOFC::index');
$routes->get('/produits', 'ProduitController::page_produits');
$routes->get('/produits/(:num)', 'ProduitController::produitsParGamme/$1');
$routes->get('/gammes', 'GammeController::page_gammes');
$routes->get('/navbar/entreprise', 'EntrepriseController::index');
$routes->get('/test-recherche', 'HeaderController::rechercherProduits');

// Connexion
$routes->get('/signin', 'SigninController::index');
$routes->post('/signin', 'SigninController::loginAuth');

// Inscription
$routes->get('/signup', 'SignupController::index');
$routes->post('/signup', 'SignupController::enregistrer');
$routes->get('/signup/activateAccount/(:any)', 'SignupController::activateAccount/$1');

// Mot de passe oublié
$routes->get('/forgot-password', 'ForgotPasswordController::index');
$routes->post('/forgot-password/sendResetLink', 'ForgotPasswordController::sendResetLink');
$routes->get('/reset-password/(:any)', 'ResetPasswordController::index/$1');
$routes->post('/reset-password/updatePassword', 'ResetPasswordController::updatePassword');

// FAQ
$routes->get('/faq', 'FaqController::index');

// Contact
$routes->post('/contact/send', 'ContactController::sendMail');
$routes->post('/faq/send', 'FaqController::sendMail');

// Produits
$routes->get('/produit/(:any)', 'InfoProduitController::index/$1');

// Section droite footer
$routes->get('/mentionslegales', 'FooterController::mentionlegales');
$routes->get('/polconf', 'FooterController::polconf');
$routes->get('/polremb', 'FooterController::polremb');
$routes->get('/polcook', 'FooterController::polcook');
$routes->get('/rgpd', 'FooterController::rgpd');
$routes->get('/condutil', 'FooterController::condutil');
$routes->get('/condvente', 'FooterController::condvente');

// Routes protégées (requièrent une session active)
$routes->group('', ['filter' => 'auth'], function ($routes) {
    // Page d'accueil et ses fonctionnalités
    $routes->get('/PanierController', 'PanierController::index');

    // Panier
    $routes->get('/panier', 'PanierController::index');
    $routes->post('/panier/ajouter/(:num)', 'PanierController::ajouterPanier/$1');
    $routes->post('/panier/update', 'PanierController::update');
    $routes->post('/panier/updateGamme', 'PanierController::updateGamme');
    $routes->get('/panier/retirer/(:num)', 'PanierController::retirerProduit/$1');
    $routes->get('/panier/retirerGamme/(:num)', 'PanierController::retirerGamme/$1');
    $routes->get('/panier/vider', 'PanierController::viderPanier');
    $routes->get('/panier/modifier/(:num)/(:any)', 'PanierController::modifierPanier/$1/$2');
    $routes->get('/panier/modifierGamme/(:num)/(:any)', 'PanierController::modifierPanierGamme/$1/$2');
    $routes->get('/panier/commande', 'PanierController::recapitulatif');
    $routes->post('/panier/appliquerPromo', 'PanierController::appliquerPromo');

    // Commande
    $routes->get('/commande', 'CommandeController::mescommandes');
    $routes->post('/commande/enregistrer', 'CommandeController::enregistrerCommande');
    $routes->get('/commande/annuler/(:num)', 'CommandeController::annuler/$1');
    $routes->get('/commande/produits/(:num)', 'CommandeController::afficherProduitsCommande/$1');

    // Les tâches

    // Les Commentaires
    $routes->get('CommentaireController/supprimer/(:num)', 'CommentaireController::supprimer/$1');
    $routes->post('/submitRating', 'RatingController::submitRating');
    $routes->get('/getRatings/(:num)', 'RatingController::getRatings/$1');

    // Se déconnecter

    // Profil utilisateur
    $routes->get('/profile', 'ProfileController::index');
    $routes->get('/profile/edit', 'ProfileController::edit');
    $routes->post('/profile/update', 'ProfileController::update');
    $routes->post('/profile/update-password', 'ProfileController::updatePassword');
    $routes->get('profile/edit-password', 'ProfileController::editPassword');

    // Changer le mot de passe

    // Admin 
    if (session()->get('role') == 'Admin')
    {
        // Liste des entités avec leurs contrôleurs respectifs
        $entites = [
            'produits' => 'ProduitController',
            'categories' => 'CategorieController',
            'ingredients' => 'IngredientController',
            'codes-promos' => 'CodePromoController',
            'gammes' => 'GammeController',
            'bundles' => 'BundleController',
            'commandes' => 'CommandeController',
        ];
        
        // Boucle pour générer les routes
        foreach ($entites as $entite => $controller) {
            $routes->group("admin/$entite", function ($routes) use ($controller) {
                $routes->get('', "$controller::index");
                $routes->get('creation', "$controller::creation");
                $routes->post('creer', "$controller::creer");
                $routes->get('modification/(:num)', "$controller::modification/$1");
                $routes->post('modifier/(:num)', "$controller::modifier/$1");
                $routes->get('supprimer/(:num)', "$controller::supprimer/$1");
                $routes->get('desactiver/(:num)', "$controller::desactiver/$1");
            });
        }
        $routes->get('/admin/commande/produits/(:num)', "CommandeController::afficherProduitsCommande/$1/true");

        $routes->post('/admin/ingredients/supprimer/(:num)', "IngredientController::supprimer/$1");
        
        $routes->post('/admin/gammes/ajouter-produit/(:num)', "GammeController::ajouter_produit/$1");
        $routes->post('/admin/gammes/enlever-produit/(:num)', "GammeController::enlever_produit/$1");
        
        $routes->post('/admin/bundles/ajouter-produit/(:num)', "BundleController::ajouter_produit/$1");
        $routes->post('/admin/bundles/enlever-produit/(:num)', "BundleController::enlever_produit/$1");

        $routes->get('/faq/admin', 'FaqController::admin');
        $routes->get('/faq/create', 'FaqController::viewCreate');
        $routes->post('/faq/create', 'FaqController::create');
        $routes->get('/faq/edit/(:num)', 'FaqController::edit/$1');
        $routes->post('/faq/update/(:num)', "FaqController::update/$1");
        $routes->post('/faq/delete/(:num)', "FaqController::delete/$1");
    }

    // Déconnexion
    $routes->get('/logout', 'ProfileController::logout');
});





