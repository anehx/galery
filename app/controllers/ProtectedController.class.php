<?php

require_once __DIR__ . '/Controller.class.php';
require_once __DIR__ . '/../utils/Request.class.php';

/**
 * Controller which needs authentication
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2016, Jonas Metzener
 */
class ProtectedController extends Controller {
    /**
     * Check if the current user has permission
     * to handle a certain object
     *
     * @param mixed $obj
     * @param Request $request
     * @return void
     */
    protected function checkPermission($obj, $request) {
        if ($obj->user->id !== $request->user->id) {
            $this->render('no-permission');
        }
    }

    /**
     * Render the specified template (with navigation)
     *
     * @param string $tpl
     * @return void
     */
    protected function render($tpl) {
        $base = file_get_contents(__DIR__ . '/../views/base.html');
        $nav  = '';
        $body = '';

        ob_start();

        require_once __DIR__ . '/../views/' . $tpl . '.phtml';
        $body = ob_get_contents();

        ob_end_clean();

        ob_start();

        require_once __DIR__ . '/../views/nav.phtml';
        $nav = ob_get_contents();

        ob_end_clean();

        $base = str_replace('{{NAV}}', $nav, $base);

        echo str_replace('{{BODY}}', $body, $base);
        exit;
    }

    /**
     * Check if the client is authenticated
     *
     * @param Request $request
     * @return void
     */
    protected function checkAuth($request) {
        if (!$_SESSION['isAuthenticated']) {
            $this->redirect('/login');
        }
        elseif ($_SESSION['expire'] < time()) {
            session_unset();
            session_destroy();

            $this->redirect('/login');
        }
    }

    /**
     * Main handle function
     *
     * @param string $params
     * @param Request $request
     * @return void
     */
    public function handle($params = array(), $request = null) {
        session_start();

        $request = new Request();

        $this->checkAuth($request);

        parent::handle($params, $request);
    }
}
