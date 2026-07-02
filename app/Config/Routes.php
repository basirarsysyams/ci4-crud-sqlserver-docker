<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'ProductController::index');
// Routes Product
$routes->get('products', 'ProductController::index');

// PERBAIKAN: Gunakan POST untuk ajax-list
$routes->post('products/ajax-list', 'ProductController::ajaxList');

$routes->get('products/ajax-get/(:num)', 'ProductController::ajaxGet/$1');
$routes->post('products/ajax-store', 'ProductController::ajaxStore');
$routes->post('products/ajax-update/(:num)', 'ProductController::ajaxUpdate/$1');
$routes->post('products/ajax-delete/(:num)', 'ProductController::ajaxDelete/$1');
