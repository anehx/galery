<?php

// include_once __DIR__ . '/../models/User.class.php';
include_once __DIR__ . '/../utils/Request.class.php';

/**
 * The main controller class
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2015, Jonas Metzener
 */
class Controller {

    protected static function render($tpl) {
        $base = file_get_contents(__DIR__ . '/../views/base.html');

        ob_start();
        require_once __DIR__ . '/../views/' . $tpl . '.tpl';
        $body = ob_get_contents();
        ob_end_clean();

        echo str_replace('{{BODY}}', $body, $base);
        exit;
    }

    /**
     * Authorizes a request
     *
     * @param Request $request
     * @return Request
     */
    protected static function authorize($request) {
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
    }

    /**
     * Main handle function
     *
     * @param string $params
     * @return void
     */
    public static function handle($params = array()) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, User-Agent');

        $request = new Request();

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
        static::response(array(), 404, 'No GET handler defined for this route.');
    }

    /**
     * The post handler of this controller
     *
     * @param Request $request
     * @param string $params
     * @return void
     */
    protected static function post($request, $params) {
        static::response(array(), 404, 'No POST handler defined for this route.');
    }

}
