<?php

require_once __DIR__ . '/ProtectedController.class.php';
require_once __DIR__ . '/../models/Galery.class.php';

class GaleryEditController extends ProtectedController {
    protected function get($request, $params) {
        $this->galery = Galery::findRecord($params[0]);

        $this->checkPermission($this->galery, $request);

        $this->render('galery-edit');
    }

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
