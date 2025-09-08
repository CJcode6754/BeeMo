<?php
//GET METHOD
$router->get('/', 'dashboard.php')->only('auth');
$router->get('/chooseHive','hive/index.php');
$router->post('/set-hive','hive/set-hive.php');
$router->post('/chooseHive/store','hive/store.php');
$router->delete('/chooseHive/destroy','hive/destroy.php');
$router->get('/parameterMonitoring','parameterMonitoring.php')->only('hiveID');
$router->get('/reports','reports.php')->only('hiveID')->only('hiveID');
$router->get('/beeguide','beeguide.php');
$router->get('/about','about.php');
$router->get('/workers', 'worker/index.php');
$router->get('/worker', 'worker/show.php');
$router->get('/worker/create', 'worker/create.php');
$router->get('/verify-worker', 'worker/verify-worker.php');
$router->post('/verify', 'worker/verify-worker.php');
$router->get('/worker/edit', 'worker/edit.php');

//POST METHOD
$router->post('/worker/store', 'worker/store.php');

//DELETE METHOD
$router->delete('/worker/delete', 'worker/destroy.php');

$router->patch('/worker/patch', 'worker/update.php');

$router->get('/login', 'auth/create.php')->only('guest');
$router->post('/sessions', 'auth/store.php')->only('guest');
$router->delete('/session', 'auth/destroy.php')->only('auth');

$router->get('/harvestCycle', 'cycle/index.php')->only('hiveID');
$router->post('/harvestCycle/create', 'cycle/create.php');
$router->get('/harvestCycle/edit', 'cycle/edit.php');
$router->patch('/harvestCycle/patch', 'cycle/update.php');
$router->delete('/harvestCycle/delete', 'cycle/destroy.php');
$router->get('/cycle/getLatest', '/cycle/getLatest.php');
$router->get('/cycle/end', '/cycle/endCycle.php');