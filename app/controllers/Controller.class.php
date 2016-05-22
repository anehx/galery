<?php

// include_once __DIR__ . '/../models/User.class.php';
include_once __DIR__ . '/../utils/Request.class.php';
include_once __DIR__ . '/../utils/Template.class.php';

/**
 * The main controller class
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2015, Jonas Metzener
 */
class Controller {

    protected static function render($tpl) {
        echo Template::render($tpl);
    }

    protected static function redirect($url) {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Main handle function
     *
     * @param string $params
     * @return void
     */
    public static function handle($params = array(), $request = null) {
        if (is_null($request)) {
            $request = new Request();
        }

        switch ($request->method) {
            case 'GET':
                static::get($request, $params);
                break;
            case 'POST':
                static::post($request, $params);
                break;
            default:
                static::get($request, $params);
                break;
        }
    }

    /**
     * The get handler of this controller
     *
     * @param Request $request
     * @param string $params
     * @return void
     */
    protected static function get($request, $params) {
        return null;
    }

    /**
     * The post handler of this controller
     *
     * @param Request $request
     * @param string $params
     * @return void
     */
    protected static function post($request, $params) {
        return null;
    }

}
