<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//  rutas para unidad
$routes->get('/', 'Home::index');
$routes->get('unidades', 'Unidades::index');
$routes->get('unidades/nuevo', 'Unidades::nuevo');
$routes->post('unidades/insertar', 'Unidades::insertar');
$routes->get('unidades/editar/(:num)', 'Unidades::editar/$1');
$routes->post('unidades/actualizar', 'Unidades::actualizar');
$routes->get('unidades/eliminar/(:num)', 'Unidades::eliminar/$1');
$routes->get('unidades/eliminados', 'Unidades::eliminados');
$routes->get('unidades/reingresar/(:num)', 'Unidades::reingresar/$1');

//  rutas para producto
$routes->get('/', 'Home::index');
$routes->get('producto', 'Producto::index');
$routes->get('producto/nuevo', 'Producto::nuevo');
$routes->post('producto/insertar', 'Producto::insertar');
$routes->get('producto/editar/(:num)', 'Producto::editar/$1');
$routes->post('producto/actualizar', 'Producto::actualizar');
$routes->get('producto/eliminar/(:num)', 'Producto::eliminar/$1');
$routes->get('producto/eliminados', 'Producto::eliminados');
$routes->get('producto/reingresar/(:num)', 'Producto::reingresar/$1');










