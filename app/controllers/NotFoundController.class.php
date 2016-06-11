<?php

require_once __DIR__ . '/ProtectedController.class.php';

/**
 * Controller to display a 404 page
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2016, Jonas Metzener
 */
class NotFoundController extends ProtectedController {
    /**
     * Display a 404 error
     *
     * @param Request $request
     * @param string $params
     * @return void
     */
    protected function get($request, $params) {
        $this->render('notfound');
    }
}
