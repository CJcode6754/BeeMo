<?php

// return [
//     '/' => 'controllers/dashboard.php',
//     '/chooseHive' => 'controllers/chooseHive.php',
//     '/parameterMonitoring' => 'controllers/parameterMonitoring.php',
//     '/reports' => 'controllers/reports.php',
//     '/harvestCycle' => 'controllers/harvestCycle.php',
//     '/beeguide' => 'controllers/beeguide.php',
//     '/workers' => 'controllers/worker/index.php',
//     '/worker' => 'controllers/worker/show.php',
//     '/worker/create' => 'controllers/worker/create.php',
//     '/about' => 'controllers/about.php',
// ];

//GET METHOD
$router->get('/', 'controllers/dashboard.php');
$router->get('/chooseHive','controllers/chooseHive.php');
$router->get('/parameterMonitoring','controllers/parameterMonitoring.php');
$router->get('/reports','controllers/reports.php');
$router->get('/harvestCycle', 'controllers/harvestCycle.php');
$router->get('/beeguide','controllers/beeguide.php');
$router->get('/about','controllers/about.php');
$router->get('/workers', 'controllers/worker/index.php');
$router->get('/worker', 'controllers/worker/show.php');
$router->get('/worker/create', 'controllers/worker/create.php');

//POST METHOD
$router->post('/worker/create', 'controllers/worker/create.php');

//DELET METHOD
$router->delete('/worker/delete', 'controllers/worker/destroy.php');