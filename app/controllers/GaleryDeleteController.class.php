<?php

require_once __DIR__ . '/ProtectedController.class.php';
require_once __DIR__ . '/../models/Galery.class.php';

class GaleryDeleteController extends ProtectedController {
    protected function get($request, $params) {
        $this->galery = Galery::find(array('id' => $params[0]));

        $this->render('galery-delete');
    }

    protected function post($request, $params) {
        Galery::find(array('id' => $params[0]))->delete();

        $this->redirect('/');
    }
}
