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
});

//Administrateur

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
        $routes->get('', "$controller::index"); // Liste
        $routes->get('creation', "$controller::creation"); // Page de création
        $routes->post('creer', "$controller::creer"); // Action de création
        $routes->get('modification/(:num)', "$controller::modification/$1"); // Page de modification
        $routes->post('modifier/(:num)', "$controller::modifier/$1"); // Action de modification
        $routes->get('supprimer/(:num)', "$controller::supprimer/$1"); // Suppression
        $routes->get('desactiver/(:num)', "$controller::desactiver/$1"); // Désactivation
    });
}

