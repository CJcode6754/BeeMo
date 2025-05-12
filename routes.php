<?php
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
$router->get('/worker/edit', 'controllers/worker/edit.php');

//POST METHOD
$router->post('/worker/store', 'controllers/worker/store.php');

//DELETE METHOD
$router->delete('/worker/delete', 'controllers/worker/destroy.php');

$router->patch('/worker/patch', 'controllers/worker/update.php');

$router->get('/login', 'controllers/auth/create.php')->only('guest');
$router->post('/sessions', 'controllers/auth/store.php')->only('guest');
$router->delete('/session', 'controllers/auth/destroy.php')->only('auth');