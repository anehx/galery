<?php

require_once __DIR__ . '/ProtectedController.class.php';
require_once __DIR__ . '/../models/User.class.php';

class SettingsController extends ProtectedController {
    protected function get($request, $params) {
        $this->user = User::find(array('id' => $request->user->id));

        $this->render('settings');
    }
}
