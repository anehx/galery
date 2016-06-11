<?php

require_once __DIR__ . '/ProtectedController.class.php';
require_once __DIR__ . '/../models/Galery.class.php';

/**
 * Controller to display all public galeries
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2016, Jonas Metzener
 */
class PublicController extends ProtectedController {
    /**
     * Display all public galeries
     *
     * @param Request $request
     * @param array $params
     * @return void
     */
    protected function get($request, $params) {
        $this->galeries = Galery::query(array('public' => true));

        $this->render('public');
    }
}
