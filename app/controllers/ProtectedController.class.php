<?php

require_once __DIR__ . '/Controller.class.php';
require_once __DIR__ . '/../utils/Request.class.php';
require_once __DIR__ . '/../utils/Template.class.php';

class ProtectedController extends Controller {
    protected static function render($tpl) {
        echo Template::render($tpl, true);
    }

    public static function handle($params = array(), $request = null) {
        $request = new Request();

        if (!$_SESSION['isAuthenticated']) {
            static::redirect('/login');
        }

        parent::handle($params, $request);
    }
}
