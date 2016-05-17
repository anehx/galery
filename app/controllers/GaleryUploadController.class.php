<?php

require_once __DIR__ . '/ProtectedController.class.php';

class GaleryUploadController extends ProtectedController {
    protected static function get($request, $params) {
        static::render('galery-upload');
    }
}
