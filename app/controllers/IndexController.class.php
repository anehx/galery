<?php

require_once __DIR__ . '/ProtectedController.class.php';
require_once __DIR__ . '/../models/Galery.class.php';

/**
 * Controller to display all galeries of an user
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2016, Jonas Metzener
 */
class IndexController extends ProtectedController {
    /**
     * Display all galeries
     *
     * @param Request $request
     * @param array $params
     * @return void
     */
    protected function get($request, $params) {
        $this->galeries = Galery::query(array('user_id' => $request->user->id));

        $this->render('index');
    }
}
