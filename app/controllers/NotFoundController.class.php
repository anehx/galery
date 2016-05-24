<?php

require_once __DIR__ . '/ProtectedController.class.php';

/**
 * The NotFoundController
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2015, Jonas Metzener
 */
class NotFoundController extends ProtectedController {

    /**
     * Displays a 404 error
     *
     * @param Request $request
     * @param string $params
     * @return void
     */
    protected function get($request, $params) {
        $this->render('notfound');
    }
}
