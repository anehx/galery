<?php

class Template {
    public static function render($tpl, $includeNav = false) {
        $base = file_get_contents(__DIR__ . '/../views/base.html');
        $nav  = '';
        $body = '';

        ob_start();

        require_once __DIR__ . '/../views/' . $tpl . '.tpl';
        $body = ob_get_contents();

        ob_end_clean();

        if ($includeNav) {
            ob_start();

            require_once __DIR__ . '/../views/nav.tpl';
            $nav = ob_get_contents();

            ob_end_clean();
        }

        $base = str_replace('{{NAV}}', $nav, $base);

        return str_replace('{{BODY}}', $body, $base);
    }

}
