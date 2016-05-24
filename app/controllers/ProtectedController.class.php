<?php

require_once __DIR__ . '/Controller.class.php';
require_once __DIR__ . '/../utils/Request.class.php';

class ProtectedController extends Controller {
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

    public function handle($params = array(), $request = null) {
        $request = new Request();

        if (!$_SESSION['isAuthenticated']) {
            $this->redirect('/login');
        }

        parent::handle($params, $request);
    }
}
