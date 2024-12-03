<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Routes publiques (sans authentification)
// Accueil et pages statiques
$routes->get('/', 'ControllerOFC::index');
$routes->get('/ControllerOFC', 'ControllerOFC::index');
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
$routes->get('/InfoProduitController', 'InfoProduitController::index');


// Routes protégées (requièrent une session active)
$routes->group('', ['filter' => 'auth'], function ($routes) {
    // Page d'accueil et ses fonctionnalités

    // Les tâches

    // Les Commentaires

    // Se déconnecter

    // Profil utilisateur

    // Changer le mot de passe

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

