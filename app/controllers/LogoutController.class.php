<?php

require_once __DIR__ . '/ProtectedController.class.php';

/**
 * Controller to logout user
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2016, Jonas Metzener
 */
class LogoutController extends ProtectedController {
    /**
     * Log an user out
     *
     * @param Request $request
     * @param array $params
     * @return void
     */
    protected function get($request, $params) {
        session_unset();
        session_destroy();

        $this->redirect('/login');
    }
}
