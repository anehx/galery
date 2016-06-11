<?php

require_once __DIR__ . '/ProtectedController.class.php';
require_once __DIR__ . '/../models/User.class.php';

/**
 * Controller to edit the current user
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2016, Jonas Metzener
 */
class SettingsController extends ProtectedController {
    /**
     * Display an user edit form
     *
     * @param Request $request
     * @param array $params
     * @return void
     */
    protected function get($request, $params) {
        $this->user = User::findRecord($request->user->id);

        $this->render('settings');
    }

    /**
     * Edit the current user
     *
     * @param Request $request
     * @param array $params
     * @return void
     */
    protected function post($request, $params) {
        try {
            $user = User::findRecord($request->user->id);

            if (password_verify($request->get('passwordold'), $user->password)) {
                if ($request->get('password') !== $request->get('password2')) {
                    throw new Exception('Passwörter stimmen nicht überein');
                }

                $user->email    = $request->get('email');
                $user->password = password_hash($request->get('password'), PASSWORD_BCRYPT);

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
