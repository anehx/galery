<?php

require_once __DIR__ . '/ProtectedController.class.php';

class LogoutController extends ProtectedController {
    protected function get($request, $params) {
        session_unset();
        session_destroy();

        $this->redirect('/login');
    }
}
