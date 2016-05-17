<?php

session_start();

require_once __DIR__ . '/utils/Router.class.php';

require_once __DIR__ . '/controllers/LoginController.class.php';

require_once __DIR__ . '/controllers/IndexController.class.php';

require_once __DIR__ . '/controllers/GaleryNewController.class.php';
require_once __DIR__ . '/controllers/GaleryController.class.php';
require_once __DIR__ . '/controllers/GaleryEditController.class.php';
require_once __DIR__ . '/controllers/GaleryUploadController.class.php';

require_once __DIR__ . '/controllers/SettingsController.class.php';

require_once __DIR__ . '/controllers/NotFoundController.class.php';

$router = new Router();

$router->route('\/login',    'LoginController::handle');
// $router->route('\/logout',   'LogoutController::handle');
// $router->route('\/register', 'RegisterController::handle');

$router->route('\/', 'IndexController::handle');

$router->route('\/galery\/new',           'GaleryNewController::handle');
$router->route('\/galery\/(\d+)',         'GaleryController::handle');
$router->route('\/galery\/(\d+)\/edit',   'GaleryEditController::handle');
$router->route('\/galery\/(\d+)\/upload', 'GaleryUploadController::handle');

$router->route('\/settings', 'SettingsController::handle');

$router->route('\/*', 'NotFoundController::handle');

if (isset($_SERVER['REDIRECT_URL'])) {
    $router->execute(explode('?', $_SERVER['REDIRECT_URL'])[0]);
}
