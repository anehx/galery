<?php

require_once __DIR__ . '/ProtectedController.class.php';
require_once __DIR__ . '/../models/Galery.class.php';

class IndexController extends ProtectedController {
    protected function get($request, $params) {
        $this->galeries = Galery::query(array('user_id' => $request->user->id));

        $this->render('index');
    }
}
