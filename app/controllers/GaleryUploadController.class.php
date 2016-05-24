<?php

require_once __DIR__ . '/ProtectedController.class.php';

class GaleryUploadController extends ProtectedController {
    protected function get($request, $params) {
        $this->render('galery-upload');
    }
}
