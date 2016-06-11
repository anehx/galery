<?php

require_once __DIR__ . '/ProtectedController.class.php';
require_once __DIR__ . '/../models/Galery.class.php';

/**
 * Controller to delete a galery
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2016, Jonas Metzener
 */
class GaleryDeleteController extends ProtectedController {
    /**
     * Display a confirmation page
     *
     * @param Request $request
     * @param array $params
     * @return void
     */
    protected function get($request, $params) {
        $this->galery = Galery::findRecord($params[0]);

        $this->checkPermission($this->galery, $request);

        $this->render('galery-delete');
    }

    /**
     * Delete a galery
     *
     * @param Request $request
     * @param array $params
     * @return void
     */
    protected function post($request, $params) {
        $galery = Galery::findRecord($params[0]);

        $this->checkPermission($galery, $request);

        $galery->delete();

        $this->redirect('/');
    }
}
