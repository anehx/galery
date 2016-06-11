<?php

require_once __DIR__ . '/ProtectedController.class.php';
require_once __DIR__ . '/../models/User.class.php';

class SettingsController extends ProtectedController {
    protected function get($request, $params) {
        $this->user = User::findRecord($request->user->id);

        $this->render('settings');
    }

    protected function post($request, $params) {
        try {
            $user = $request->user;

            if (password_verify($request->get('passwordold'), $user->password)) {
                if ($request->get('password') !== $request->get('password2')) {
                    throw new Exception('Passwörter stimmen nicht überein');
                }

                $user->email    = $request->get('email');
                $user->password = $request->get('password');

                $user->save();

                $this->redirect('/');
            }
            else {
                throw new Exception('Altes Passwort ist falsch');
            }
        }
        catch (Exception $e) {
            $this->addError($e->getMessage());
        }

        $this->render('settings');
    }
}
