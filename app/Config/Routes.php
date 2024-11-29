<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//Administrateur
$routes->get('/admin/produits', 'ProduitController::index');
$routes->post('/admin/produits/creer', 'ProduitController::creer');
$routes->post('/admin/produits/modifier/', 'ProduitController::modifier');
$routes->get('/admin/produits/supprimer/(:num)', 'ProduitController::supprimer/$1');
