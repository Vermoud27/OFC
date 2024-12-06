<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Routes publiques (sans authentification)
// Accueil et pages statiques
$routes->get('/', 'ControllerOFC::index');
$routes->get('/ControllerOFC', 'ControllerOFC::index');
$routes->get('/produits', 'ProduitController::page_produits');
$routes->get('/navbar/entreprise', 'EntrepriseController::index');

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

// Admin - FAQ
$routes->get('/faq/admin', 'FaqController::admin');
$routes->get('/faq/create', 'FaqController::viewCreate');
$routes->post('/faq/create', 'FaqController::create');
$routes->get('/faq/edit/(:num)', 'FaqController::edit/$1');
$routes->post('/faq/update/(:num)', 'FaqController::update/$1');
$routes->get('/faq/delete/(:num)', 'FaqController::delete/$1');

// Routes protégées (requièrent une session active)
$routes->group('', ['filter' => 'auth'], function ($routes) {
    // Page d'accueil et ses fonctionnalités
    $routes->get('/PanierController', 'PanierController::index');

    // Panier
    $routes->get('/panier', 'PanierController::index');
    $routes->post('/panier/ajouter/(:num)', 'PanierController::ajouterPanier/$1');
    $routes->post('/panier/update', 'PanierController::update');
    $routes->get('/panier/retirer/(:num)', 'PanierController::retirerProduit/$1');
    $routes->get('/panier/vider', 'PanierController::viderPanier');
    $routes->get('/panier/modifier/(:num)/(:any)', 'PanierController::modifierPanier/$1/$2');
    $routes->get('/panier/commande', 'PanierController::recapitulatif');
    $routes->post('/panier/appliquerPromo', 'PanierController::appliquerPromo');

    // Commande
    $routes->post('/commande/traiterPaiement', 'PaymentController::traiterPaiement');
    $routes->get('/commande/retourPayPal', 'PaymentController::retourPayPal');
    $routes->post('/commande/enregistrer', 'CommandeController::enregistrerCommande');

    // Les tâches

    // Les Commentaires

    // Se déconnecter

    // Profil utilisateur
    $routes->get('/profile', 'ProfileController::index');
    $routes->get('/profile/edit', 'ProfileController::edit');
    $routes->post('/profile/update', 'ProfileController::update');    
    $routes->post('/profile/update-password', 'ProfileController::updatePassword');
    $routes->get('profile/edit-password', 'ProfileController::editPassword');

    // Changer le mot de passe

    // Admin - Produits

    // Déconnexion
    $routes->get('/logout', 'ProfileController::logout');
});

// Administrateur

// Liste des entités avec leurs contrôleurs respectifs
$entites = [
    'produits' => 'ProduitController',
    'categories' => 'CategorieController',
    'ingredients' => 'IngredientController',
    'codes-promos' => 'CodePromoController',
    'gammes' => 'GammeController',
    'bundles' => 'BundleController',
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
$routes->post('/admin/ingredients/supprimer/(:num)', "IngredientController::supprimer/$1");

$routes->post('/admin/gammes/ajouter-produit/(:num)', "GammeController::ajouter_produit/$1");
$routes->post('/admin/gammes/enlever-produit/(:num)', "GammeController::enlever_produit/$1");

$routes->post('/admin/bundles/ajouter-produit/(:num)', "BundleController::ajouter_produit/$1");
$routes->post('/admin/bundles/enlever-produit/(:num)', "BundleController::enlever_produit/$1");
