<?php

require_once __DIR__ . '/Controller.class.php';
require_once __DIR__ . '/../models/User.class.php';

class LoginController extends Controller {

    protected function get($request, $params) {
        $this->render('login');
    }

    protected function post($request, $params) {
        try {
            $user = User::queryRecord(array('email' => $request->get('email')));

            if (password_verify($request->get('password'), $user->password)) {
                $_SESSION['isAuthenticated'] = true;
                $_SESSION['user'] = serialize($user);

                $this->redirect('/');
            }
            else {
                $this->addError('Falsches Passwort');
            }
        }
        catch (Exception $e) {
            $this->addError($e->getMessage());
        }

        $this->render('login');
    }
}
