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
    $routes->get('/ControllerSGT', 'ControllerSGT::index');
    $routes->post('/ControllerSGT/filterTasks', 'ControllerSGT::filterTasks');
    $routes->get('/ControllerSGT/modification', 'ControllerSGT::modification');
    $routes->get('/ControllerSGT/creation', 'ControllerSGT::creation');
    $routes->get('ControllerSGT/getComments/(:num)', 'ControllerSGT::getComments/$1');
    $routes->get('/ControllerSGT/resetFilters', 'ControllerSGT::resetFilters');

    // Les tâches
    $routes->get('/tache/nouveau', 'TacheController::nouveau');
	$routes->post('/TacheController/ajouter', 'TacheController::ajouter');
    $routes->get('/TacheController/modification/(:any)', 'TacheController::modification/$1');
    $routes->post('/TacheController/modifier/(:any)', 'TacheController::modifier/$1');
    $routes->get('/TacheController/supprimer/(:any)', 'TacheController::supprimer/$1');
    $routes->get('/TacheController/terminer/(:any)', 'TacheController::terminer/$1');
    $routes->post('/TacheController/modifierStatus/(:any)', 'TacheController::modifierStatus/$1');

    // Les Commentaires
    $routes->post('/CommentaireController/ajouter', 'CommentaireController::ajouter');

    // Se déconnecter
    $routes->get('/logout', 'SigninController::logout');

    // Profil utilisateur
    $routes->get('/user/profile', 'UserController::profile');

    // Changer le mot de passe
    $routes->get('/user/change_password', 'UserController::change_password');
    $routes->post('/user/update_password', 'UserController::update_password');
});
