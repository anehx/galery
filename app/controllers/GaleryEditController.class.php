<?php

require_once __DIR__ . '/ProtectedController.class.php';
require_once __DIR__ . '/../models/Galery.class.php';

/**
 * Controller to edit a galery
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2016, Jonas Metzener
 */
class GaleryEditController extends ProtectedController {
    /**
     * Display an edit form
     *
     * @param Request $request
     * @param array $params
     * @return void
     */
    protected function get($request, $params) {
        $this->galery = Galery::findRecord($params[0]);

        $this->checkPermission($this->galery, $request);

        $this->render('galery-edit');
    }

    /**
     * Edit a galery
     *
     * @param Request $request
     * @param array $params
     * @return void
     */
    protected function post($request, $params) {
        $galery = Galery::findRecord($params[0]);

        $this->checkPermission($galery, $request);

        try {
            $galery->name   = $request->get('name');
            $galery->public = (bool)$request->get('public');

            $galery->save();

            $this->redirect('/galery/' . $params[0]);
        }
        catch (Exception $e) {
            $this->addError($e->getMessage());
        }

        $this->render('galery-edit');
    }
}
