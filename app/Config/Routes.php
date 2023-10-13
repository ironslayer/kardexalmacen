<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */



// RUTAS PARA EL PRIMER SUB MENÚ




// RUTAS PARA EL SEGUNDO SUB MENÚ

//  rutas para unidad
$routes->get('/', 'Home::index');
$routes->get('unidades', 'Unidades::index');
$routes->get('unidades/nuevo', 'Unidades::nuevo');
$routes->post('unidades/insertar', 'Unidades::insertar');
$routes->get('unidades/editar/(:alphanum)', 'Unidades::editar/$1');
$routes->post('unidades/actualizar', 'Unidades::actualizar');
$routes->get('unidades/eliminar/(:alphanum)', 'Unidades::eliminar/$1');
$routes->get('unidades/eliminados', 'Unidades::eliminados');
$routes->get('unidades/reingresar/(:alphanum)', 'Unidades::reingresar/$1');

//  rutas para producto
$routes->get('/', 'Home::index');
$routes->get('producto', 'Producto::index');
$routes->get('producto/nuevo', 'Producto::nuevo');
$routes->post('producto/insertar', 'Producto::insertar');
$routes->get('producto/editar/(:alphanum)', 'Producto::editar/$1');
$routes->post('producto/actualizar', 'Producto::actualizar');
$routes->get('producto/eliminar/(:alphanum)', 'Producto::eliminar/$1');
$routes->get('producto/eliminados', 'Producto::eliminados');
$routes->get('producto/reingresar/(:alphanum)', 'Producto::reingresar/$1');

//  rutas para item
$routes->get('/', 'Home::index');
$routes->get('item', 'Item::index');
$routes->get('item/nuevo', 'Item::nuevo');
$routes->post('item/insertar', 'Item::insertar');
$routes->get('item/editar/(:alphanum)', 'Item::editar/$1');
$routes->post('item/actualizar', 'Item::actualizar');
$routes->get('item/eliminar/(:alphanum)', 'Item::eliminar/$1');
$routes->get('item/eliminados', 'Item::eliminados');
$routes->get('item/reingresar/(:alphanum)', 'Item::reingresar/$1');

// RUTAS PARA EL TERCER SUB MENÚ

//  rutas para proveedor
$routes->get('/', 'Home::index');
$routes->get('proveedor', 'Proveedor::index');
$routes->get('proveedor/nuevo', 'Proveedor::nuevo');
$routes->post('proveedor/insertar', 'Proveedor::insertar');
$routes->get('proveedor/editar/(:alphanum)', 'Proveedor::editar/$1');
$routes->post('proveedor/actualizar', 'Proveedor::actualizar');
$routes->get('proveedor/eliminar/(:alphanum)', 'Proveedor::eliminar/$1');
$routes->get('proveedor/eliminados', 'Proveedor::eliminados');
$routes->get('proveedor/reingresar/(:alphanum)', 'Proveedor::reingresar/$1');

// RUTAS PARA EL CUARTO SUB MENÚ



// RUTAS PARA EL QUINTO SUB MENÚ






