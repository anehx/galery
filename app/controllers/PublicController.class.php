<?php

require_once __DIR__ . '/ProtectedController.class.php';
require_once __DIR__ . '/../models/Galery.class.php';

class PublicController extends ProtectedController {
    protected function get($request, $params) {
        $this->galeries = Galery::query(array('public' => true));

        $this->render('public');
    }
}
