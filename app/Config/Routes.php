<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


 // RUTAS POR DEFECTO

 $routes->setDefaultController('Usuario');
 $routes->setDefaultMethod('index');
// $routes->get('/', 'Home::index');


// RUTA DE INICIO DE SESION

 $routes->get('/', 'Usuario::login');

 $routes->post('usuario/valida', 'Usuario::valida');

 $routes->get('contenido', 'Home::index');


 // RUTAS DEL MENÚ PARTE SUPERIOR DERECHA

 $routes->get('usuario/logout', 'Usuario::logout');
 $routes->get('usuario/cambia_password', 'Usuario::cambia_password');
 $routes->post('usuario/actualizar_password', 'Usuario::actualizar_password');


// RUTAS PARA EL PRIMER SUB MENÚ

//  rutas para entrada
$routes->get('entrada', 'Entrada::index');
$routes->get('entrada/nuevo', 'Entrada::nuevo');
$routes->post('entrada/insertar', 'Entrada::insertar');
$routes->get('entrada/editar/(:alphanum)', 'Entrada::editar/$1');
$routes->post('entrada/actualizar', 'Entrada::actualizar');
$routes->get('entrada/eliminar/(:alphanum)', 'Entrada::eliminar/$1');
$routes->get('entrada/eliminados', 'Entrada::eliminados');
$routes->get('entrada/reingresar/(:alphanum)', 'Entrada::reingresar/$1');

$routes->post('entrada/guardar', 'Entrada::guardar');

//--------------------------------------------------------------
//  rutas para entrada_salida x item

$routes->get('entrada_salida', 'EntradaSalida::index');
$routes->get('entrada_salida/buscaEntradasSalidasItem/(:alphanum)', 'EntradaSalida::buscaEntradasSalidasItem/$1');
$routes->get('entrada_salida/editar/(:alphanum)','EntradaSalida::editar/$1');
$routes->post('entrada_salida/actualizar_entrada', 'EntradaSalida::actualizar_entrada');
$routes->post('entrada_salida/actualizar_salida', 'EntradaSalida::actualizar_salida');
$routes->get('entrada_salida/eliminar/(:alphanum)', 'EntradaSalida::eliminar/$1');


//--------------------------------------------------------------


//  rutas para salida
$routes->get('salida', 'Salida::index');
$routes->get('salida/nuevo', 'Salida::nuevo');
$routes->post('salida/insertar', 'Salida::insertar');
$routes->get('salida/editar/(:alphanum)', 'Salida::editar/$1');
$routes->post('salida/actualizar', 'Salida::actualizar');
$routes->get('salida/eliminar/(:alphanum)', 'Salida::eliminar/$1');
$routes->get('salida/eliminados', 'Salida::eliminados');
$routes->get('salida/reingresar/(:alphanum)', 'Salida::reingresar/$1');

//  rutas para tipo entrada
$routes->get('tentrada', 'Tentrada::index');
// $routes->get('tentrada/nuevo', 'Tentrada::nuevo');
$routes->post('tentrada/insertar', 'Tentrada::insertar');
$routes->get('tentrada/editar/(:alphanum)', 'Tentrada::editar/$1');
$routes->post('tentrada/actualizar', 'Tentrada::actualizar');
$routes->get('tentrada/eliminar/(:alphanum)', 'Tentrada::eliminar/$1');
$routes->get('tentrada/eliminados', 'Tentrada::eliminados');
$routes->get('tentrada/reingresar/(:alphanum)', 'Tentrada::reingresar/$1');

//  rutas para tipo salida
$routes->get('tsalida', 'Tsalida::index');
// $routes->get('tsalida/nuevo', 'Tsalida::nuevo');
$routes->post('tsalida/insertar', 'Tsalida::insertar');
$routes->get('tsalida/editar/(:alphanum)', 'Tsalida::editar/$1');
$routes->post('tsalida/actualizar', 'Tsalida::actualizar');
$routes->get('tsalida/eliminar/(:alphanum)', 'Tsalida::eliminar/$1');
$routes->get('tsalida/eliminados', 'Tsalida::eliminados');
$routes->get('tsalida/reingresar/(:alphanum)', 'Tsalida::reingresar/$1');


// RUTAS PARA EL SEGUNDO SUB MENÚ

//  rutas para unidad
$routes->get('unidades', 'Unidades::index');
// $routes->get('unidades/nuevo', 'Unidades::nuevo');
$routes->post('unidades/insertar', 'Unidades::insertar');
$routes->get('unidades/editar/(:alphanum)', 'Unidades::editar/$1');
$routes->post('unidades/actualizar', 'Unidades::actualizar');
$routes->get('unidades/eliminar/(:alphanum)', 'Unidades::eliminar/$1');
$routes->get('unidades/eliminados', 'Unidades::eliminados');
$routes->get('unidades/reingresar/(:alphanum)', 'Unidades::reingresar/$1');







//  rutas para producto
$routes->get('producto', 'Producto::index');
// $routes->get('producto/nuevo', 'Producto::nuevo');
$routes->post('producto/insertar', 'Producto::insertar');
$routes->get('producto/editar/(:alphanum)', 'Producto::editar/$1');
$routes->post('producto/actualizar', 'Producto::actualizar');
$routes->get('producto/eliminar/(:alphanum)', 'Producto::eliminar/$1');
$routes->get('producto/eliminados', 'Producto::eliminados');
$routes->get('producto/reingresar/(:alphanum)', 'Producto::reingresar/$1');

//  rutas para item
$routes->get('item', 'Item::index');
// $routes->get('item/nuevo', 'Item::nuevo');
$routes->post('item/insertar', 'Item::insertar');
$routes->get('item/editar/(:alphanum)', 'Item::editar/$1');
$routes->post('item/actualizar', 'Item::actualizar');
$routes->get('item/eliminar/(:alphanum)', 'Item::eliminar/$1');
$routes->get('item/eliminados', 'Item::eliminados');
$routes->get('item/reingresar/(:alphanum)', 'Item::reingresar/$1');

$routes->get('item/buscaCantidadItem/(:alphanum)', 'Item::buscaCantidadItem/$1');




// RUTAS PARA EL TERCER SUB MENÚ

//  rutas para usuario
$routes->get('usuario', 'Usuario::index');
$routes->get('usuario/nuevo', 'Usuario::nuevo');
$routes->post('usuario/insertar', 'Usuario::insertar');
$routes->get('usuario/editar/(:alphanum)', 'Usuario::editar/$1');
$routes->post('usuario/actualizar', 'Usuario::actualizar');
$routes->get('usuario/eliminar/(:alphanum)', 'Usuario::eliminar/$1');
$routes->get('usuario/eliminados', 'Usuario::eliminados');
$routes->get('usuario/reingresar/(:alphanum)', 'Usuario::reingresar/$1');

//  rutas para proveedor
$routes->get('proveedor', 'Proveedor::index');
// $routes->get('proveedor/nuevo', 'Proveedor::nuevo');
$routes->post('proveedor/insertar', 'Proveedor::insertar');
$routes->get('proveedor/editar/(:alphanum)', 'Proveedor::editar/$1');
$routes->post('proveedor/actualizar', 'Proveedor::actualizar');
$routes->get('proveedor/eliminar/(:alphanum)', 'Proveedor::eliminar/$1');
$routes->get('proveedor/eliminados', 'Proveedor::eliminados');
$routes->get('proveedor/reingresar/(:alphanum)', 'Proveedor::reingresar/$1');

// RUTAS PARA EL CUARTO SUB MENÚ

//rutas para reportes
$routes->get('reportes/vistaKardexFisicoValorado', 'Reportes::vistaKardexFisicoValorado');
$routes->get('reportes/generaPdfKardexFisicoValorado/(:any)/(:any)/(:any)', 'Reportes::generaPdfKardexFisicoValorado/$1/$2/$3');

$routes->get('reportes/vistaResumenKardex', 'Reportes::vistaResumenKardex');
$routes->get('reportes/generaPdfResumenKardex/(:any)/(:any)', 'Reportes::generaPdfResumenKardex/$1/$2');


$routes->get('reportes/vistaReporteGeneral', 'Reportes::vistaReporteGeneral');
$routes->get('reportes/generaPdfGeneral/(:any)/(:any)/(:any)', 'Reportes::generaPdfGeneral/$1/$2/$3');


$routes->get('reportes/vistaReporteEntradaSalida', 'Reportes::vistaReporteEntradaSalida');
$routes->get('reportes/generaPdfEntradasSalidas/(:any)/(:any)/(:any)', 'Reportes::generaPdfEntradasSalidas/$1/$2/$3');



$routes->get('reportes/vistaReporteVarios', 'Reportes::vistaReporteVarios');
$routes->get('reportes/generaPdfReporteVarios/(:any)/(:any)/(:any)', 'Reportes::generaPdfReporteVarios/$1/$2/$3');




// RUTAS PARA EL QUINTO SUB MENÚ






