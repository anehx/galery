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

    /**
     * Authorizes a request
     *
     * @param Request $request
     * @return Request
     */
    protected static function authorize($request) {
        return false;
        /*
        try {
            $token = str_replace('Basic ', '', $request->getHeader('Authorization'));

            $decrypted = base64_decode($token);

            list($username, $password) = explode(':', $decrypted);

            $user = User::find(array('username' => $username));

            if (!$user) {
                throw new Exception();
            }
            elseif (password_verify($password, $user->get('password'))) {
                $request->user = $user;
            }
            else {
                throw new Exception();
            }
        }
        catch (Exception $e) {
            static::response(array(), 401, 'Not Authorized');
        }

        return $request;
        */
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
