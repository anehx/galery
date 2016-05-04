<?php

session_start();

require_once __DIR__ . '/utils/Router.class.php';

require_once __DIR__ . '/controllers/LoginController.class.php';
require_once __DIR__ . '/controllers/NotFoundController.class.php';

$router = new Router();

$router->route('\/login', 'LoginController::handle');
$router->route('\/*',     'NotFoundController::handle');

if (isset($_SERVER['REDIRECT_URL'])) {
    $router->execute(explode('?', $_SERVER['REDIRECT_URL'])[0]);
}
