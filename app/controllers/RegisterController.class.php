<?php

require_once __DIR__ . '/Controller.class.php';
require_once __DIR__ . '/../models/User.class.php';

/**
 * Controller to register a new user
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2016, Jonas Metzener
 */
class RegisterController extends Controller {
    /**
     * Display a registration form
     *
     * @param Request $request
     * @param array $params
     * @return void
     */
    protected function get($request, $params) {
        $this->render('register');
    }

    /**
     * Create a new user
     *
     * @param Request $request
     * @param array $params
     * @return void
     */
    protected function post($request, $params) {
        try {
            try {
                User::queryRecord(array('email' => $request->get('email')));

                $this->addError('Es existiert bereits ein Benutzer mit dieser Email');
            }
            catch (Exception $e) {
                if ($request->get('password') !== $request->get('password2')) {
                    throw new Exception('Passwörter stimmen nicht überein');
                }

                $user = new User(array(
                    'email'    => $request->get('email'),
                    'password' => password_hash($request->get('password'), PASSWORD_BCRYPT),
                ));
                $user->save();

                $this->redirect('/login');
            }
        }
        catch (Exception $e) {
            $this->addError($e->getMessage());
        }

        $this->render('register');
    }
}
