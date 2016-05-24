<?php

require_once __DIR__ . '/ProtectedController.class.php';

class GaleryController extends ProtectedController {
    protected function get($request, $params) {
        $this->galery = Galery::find(array('id' => $params[0]));

        $this->render('galery');
    }
}
