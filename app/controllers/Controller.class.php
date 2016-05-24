<?php

require_once __DIR__ . '/../utils/Request.class.php';
require_once __DIR__ . '/../models/User.class.php';

/**
 * The main controller class
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2015, Jonas Metzener
 */
class Controller {

    protected function addError($msg) {
        $this->errors[] = $msg;
    }

    protected function outputErrors() {
        if (!empty($this->errors)) {
            echo '<div class="alert alert-danger"><ul>';

            foreach ($this->errors as $e) {
                echo "<li>$e</li>";
            }

            echo '</ul></div>';
        }
    }

    protected $errors = array();

    protected function render($tpl) {
        $base = file_get_contents(__DIR__ . '/../views/base.html');

        ob_start();

        require_once __DIR__ . '/../views/' . $tpl . '.phtml';
        $body = ob_get_contents();

        ob_end_clean();

        $base = str_replace('{{NAV}}', '', $base);
        echo str_replace('{{BODY}}', $body, $base);
        exit;
    }

    protected function redirect($url) {
        header("Location: $url");
        exit;
    }

    /**
     * Main handle function
     *
     * @param string $params
     * @return void
     */
    public function handle($params = array(), $request = null) {
        if (is_null($request)) {
            $request = new Request();
        }

        switch ($request->method) {
            case 'GET':
                $this->get($request, $params);
                break;
            case 'POST':
                $this->post($request, $params);
                break;
            default:
                $this->get($request, $params);
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
    protected function get($request, $params) {}

    /**
     * The post handler of this controller
     *
     * @param Request $request
     * @param string $params
     * @return void
     */
    protected function post($request, $params) {}

}
