<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Routes publiques (sans authentification)
$routes->get('/', 'SigninController::index');
$routes->get('/signin', 'SigninController::index');
$routes->post('/signin', 'SigninController::loginAuth');

$routes->get('/signup', 'SignupController::index');
$routes->post('/signup', 'SignupController::enregistrer');
$routes->get('/signup/activateAccount/(:any)', 'SignupController::activateAccount/$1');

$routes->get('/forgot-password', 'ForgotPasswordController::index');
$routes->post('/forgot-password/sendResetLink', 'ForgotPasswordController::sendResetLink');
$routes->get('/reset-password/(:any)', 'ResetPasswordController::index/$1');
$routes->post('/reset-password/updatePassword', 'ResetPasswordController::updatePassword');

// Routes protégées (requièrent une session active)
$routes->group('', ['filter' => 'auth'], function ($routes) {
    // Page d'accueil et ses fonctionnalités
    $routes->get('/ControllerOFC', 'ControllerOFC::index');
    $routes->get('/InfoProduitController', 'InfoProduitController::index');
    $routes->get('/navbar/entreprise', 'EntrepriseController::index');

    // Les tâches

    // Les Commentaires

    // Se déconnecter

    // Profil utilisateur

    // Changer le mot de passe

    // FAQ
    $routes->get('/faq', 'FaqController::index');

    // Contact
    $routes->post('/contact/send', 'ContactController::sendMail');
    $routes->post('/faq/send', 'FaqController::sendMail');

    // Admin - Produits

    // Admin - FAQ
    $routes->get('/faq/admin', 'FaqController::admin');
    $routes->get('/faq/create', 'FaqController::viewCreate');
    $routes->post('/faq/create', 'FaqController::create');
    $routes->get('/faq/edit/(:num)', 'FaqController::edit/$1');
    $routes->post('/faq/update/(:num)', 'FaqController::update/$1');
    $routes->get('/faq/delete/(:num)', 'FaqController::delete/$1');
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

$routes->post('/admin/gammes/ajouter-produit/(:num)', "GammeController::ajouter_produit/$1");
$routes->post('/admin/gammes/enlever-produit/(:num)', "GammeController::enlever_produit/$1");

$routes->post('/admin/bundles/ajouter-produit/(:num)', "BundleController::ajouter_produit/$1");
$routes->post('/admin/bundles/enlever-produit/(:num)', "BundleController::enlever_produit/$1");
