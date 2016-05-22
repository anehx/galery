<?php

require_once __DIR__ . '/Controller.class.php';

class LogoutController extends Controller {
    protected static function get($request, $params) {
        unset($_SESSION['isAuthenticated']);
        unset($_SESSION['user']);

        static::redirect('/login');
    }
}
