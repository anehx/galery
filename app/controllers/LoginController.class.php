<?php

require_once __DIR__ . '/Controller.class.php';

class LoginController extends Controller {

    protected static function get($request, $params) {
        echo static::render('login');
    }
}
