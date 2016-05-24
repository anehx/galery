<?php

require_once __DIR__ . '/ProtectedController.class.php';
require_once __DIR__ . '/../models/Galery.class.php';

class GaleryEditController extends ProtectedController {
    protected function get($request, $params) {
        $this->galery = Galery::find(array('id' => $params[0]));

        $this->render('galery-edit');
    }

    protected function post($request, $params) {
        $galery = Galery::find(array('id' => $params[0]));

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
