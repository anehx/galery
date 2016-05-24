<?php

require_once __DIR__ . '/Controller.class.php';
require_once __DIR__ . '/../models/User.class.php';

class LoginController extends Controller {

    protected static function get($request, $params) {
        echo static::render('login');
    }

    protected static function post($request, $params) {
        $errors = array();

        try {
            $user = User::find(array('email' => $request->get('email')));

            if (password_verify($request->get('password'), $user->get('password'))) {
                $_SESSION['isAuthenticated'] = true;
                $_SESSION['user'] = $user;

                static::redirect('/');
            }
            else {
                $errors[] = array('type' => 'danger', 'message' => 'Wrong password');
            }
        }
        catch (Exception $e) {
            var_dump($e);
            exit;
            $errors[] = array('type' => 'danger', 'message' => $e->getMessage());
        }

        echo static::render('login');
    }
}
