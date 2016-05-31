<?php

require_once __DIR__ . '/Controller.class.php';

class LogoutController extends Controller {
    protected function get($request, $params) {
        session_destroy();

        $this->redirect('/login');
    }
}
