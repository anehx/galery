<?php

require_once __DIR__ . '/ProtectedController.class.php';

class SettingsController extends ProtectedController {
    protected static function get($request, $params) {
        static::render('settings');
    }
}
