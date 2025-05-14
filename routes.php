<?php
//GET METHOD
$router->get('/', 'dashboard.php');
$router->get('/chooseHive','chooseHive.php');
$router->get('/parameterMonitoring','parameterMonitoring.php');
$router->get('/reports','reports.php');
$router->get('/beeguide','beeguide.php');
$router->get('/about','about.php');
$router->get('/workers', 'worker/index.php');
$router->get('/worker', 'worker/show.php');
$router->get('/worker/create', 'worker/create.php');
$router->get('/worker/edit', 'worker/edit.php');

//POST METHOD
$router->post('/worker/store', 'worker/store.php');

//DELETE METHOD
$router->delete('/worker/delete', 'worker/destroy.php');

$router->patch('/worker/patch', 'worker/update.php');

$router->get('/login', 'auth/create.php')->only('guest');
$router->post('/sessions', 'auth/store.php')->only('guest');
$router->delete('/session', 'auth/destroy.php')->only('auth');

$router->get('/harvestCycle', 'cycle/index.php');
$router->post('/harvestCycle/create', 'cycle/create.php');
$router->get('/harvestCycle/edit', 'cycle/edit.php');
$router->patch('/harvestCycle/patch', 'cycle/update.php');
$router->delete('/harvestCycle/delete', 'cycle/destroy.php');