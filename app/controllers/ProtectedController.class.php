<?php

require_once __DIR__ . '/Controller.class.php';
require_once __DIR__ . '/../utils/Request.class.php';

class ProtectedController extends Controller {
    protected function checkPermission($obj, $request) {
        if ($obj->user->id !== $request->user->id) {
            $this->render('no-permission');
        }
    }

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

    public function handle($params = array(), $request = null) {
        session_start();

        $request = new Request();

        $this->checkAuth($request);

        try {
            parent::handle($params, $request);
        }
        catch (Exception $e) {
            $this->addError($e->getMessage());

            $this->render('error');
        }
    }
}
