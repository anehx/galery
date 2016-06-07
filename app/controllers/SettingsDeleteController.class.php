<?php

require_once __DIR__ . '/ProtectedController.class.php';
require_once __DIR__ . '/../models/User.class.php';

class SettingsDeleteController extends ProtectedController {
    protected function get($request, $params) {
        $this->render('settings-delete');
    }

    protected function post($request, $params) {
        User::findRecord($request->user->id)->delete();

        $this->redirect('/logout');
    }
}
