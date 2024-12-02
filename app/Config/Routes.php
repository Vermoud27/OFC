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
    $routes->get('/navbar/entreprise', 'EntrepriseController::index');

    // Les tâches

    // Les Commentaires

    // Se déconnecter

    // Profil utilisateur

    // Changer le mot de passe
});

//Administrateur
$routes->get('/admin/produits', 'ProduitController::index');
$routes->post('/admin/produits/creer', 'ProduitController::creer');
$routes->post('/admin/produits/modifier/', 'ProduitController::modifier');
$routes->get('/admin/produits/supprimer/(:num)', 'ProduitController::supprimer/$1');
