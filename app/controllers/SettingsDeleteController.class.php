<?php

require_once __DIR__ . '/ProtectedController.class.php';
require_once __DIR__ . '/../models/User.class.php';

/**
 * Controller to delete the current user
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2016, Jonas Metzener
 */
class SettingsDeleteController extends ProtectedController {
    /**
     * Display a confirmation page
     *
     * @param Request $request
     * @param array $params
     * @return void
     */
    protected function get($request, $params) {
        $this->render('settings-delete');
    }

    /**
     * Delete the current user
     *
     * @param Request $request
     * @param array $params
     * @return void
     */
    protected function post($request, $params) {
        User::findRecord($request->user->id)->delete();

        $this->redirect('/logout');
    }
}
