<?php

require_once __DIR__ . '/ProtectedController.class.php';
require_once __DIR__ . '/../models/Image.class.php';

class ImageDeleteController extends ProtectedController {
    protected function get($request, $params) {
        $this->image = Image::findRecord($params[0]);

        $this->checkPermission($this->image, $request);

        $this->render('image-delete');
    }

    protected function post($request, $params) {
        $image = Image::findRecord($params[0]);

        $this->checkPermission($image, $request);

        $image->delete();

        $this->redirect('/galery/' . $image->galery->id);
    }
}
