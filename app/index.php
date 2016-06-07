<?php

require_once __DIR__ . '/utils/Router.class.php';

require_once __DIR__ . '/controllers/LoginController.class.php';
require_once __DIR__ . '/controllers/LogoutController.class.php';
require_once __DIR__ . '/controllers/RegisterController.class.php';

require_once __DIR__ . '/controllers/IndexController.class.php';
require_once __DIR__ . '/controllers/PublicController.class.php';

require_once __DIR__ . '/controllers/GaleryNewController.class.php';
require_once __DIR__ . '/controllers/GaleryController.class.php';
require_once __DIR__ . '/controllers/GaleryEditController.class.php';
require_once __DIR__ . '/controllers/GaleryUploadController.class.php';
require_once __DIR__ . '/controllers/GaleryDeleteController.class.php';

require_once __DIR__ . '/controllers/SettingsController.class.php';
require_once __DIR__ . '/controllers/SettingsDeleteController.class.php';

require_once __DIR__ . '/controllers/NotFoundController.class.php';

$router = new Router();

$router->route('\/login',    'LoginController');
$router->route('\/logout',   'LogoutController');
$router->route('\/register', 'RegisterController');

$router->route('\/',       'IndexController');
$router->route('\/public', 'PublicController');

$router->route('\/galery\/new',           'GaleryNewController');
$router->route('\/galery\/(\d+)',         'GaleryController');
$router->route('\/galery\/(\d+)\/edit',   'GaleryEditController');
$router->route('\/galery\/(\d+)\/upload', 'GaleryUploadController');
$router->route('\/galery\/(\d+)\/delete', 'GaleryDeleteController');

$router->route('\/settings',         'SettingsController');
$router->route('\/settings\/delete', 'SettingsDeleteController');

$router->route('\/*', 'NotFoundController');

if (isset($_SERVER['REDIRECT_URL'])) {
    $router->execute(explode('?', $_SERVER['REDIRECT_URL'])[0]);
}
