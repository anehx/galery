<?php

require_once __DIR__ . '/../utils/Request.class.php';
require_once __DIR__ . '/../models/User.class.php';

/**
 * The main controller class
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2016, Jonas Metzener
 */
class Controller {
    /**
     * The errors of this controller
     *
     * @var array $errors
     */
    protected $errors = array();

    /**
     * Add an error message to this controller instance
     *
     * @param string $msg
     * @return void
     */
    protected function addError($msg) {
        $this->errors[] = $msg;
    }

    /**
     * Show all errors in an error box
     *
     * @return void
     */
    protected function outputErrors() {
        if (!empty($this->errors)) {
            echo '<div class="alert alert-danger"><ul>';

            foreach ($this->errors as $e) {
                echo "<li>$e</li>";
            }

            echo '</ul></div>';
        }
    }

    /**
     * Render the specified template
     *
     * @param string $tpl
     * @return void
     */
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

    /**
     * Redirect to an URL
     *
     * @param string $url
     * @return void
     */
    protected function redirect($url) {
        header("Location: $url");
        exit;
    }

    /**
     * Main handle function
     *
     * @param string $params
     * @param Request $request
     * @return void
     */
    public function handle($params = array(), $request = null) {
        try {
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
        catch (Exception $e) {
            $this->addError($e->getMessage());

            $this->render('error');
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
