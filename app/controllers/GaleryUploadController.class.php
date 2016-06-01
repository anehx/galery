<?php

require_once __DIR__ . '/ProtectedController.class.php';

require_once __DIR__ . '/../models/Galery.class.php';
require_once __DIR__ . '/../models/Image.class.php';

class GaleryUploadController extends ProtectedController {
    protected function get($request, $params) {
        $this->render('galery-upload');
    }

    protected function post($request, $params) {
        $galery = Galery::findRecord($params[0]);

        $images = Image::createFromFiles($request->files['images'], array('galery_id' => $galery->id));

        foreach ($images as $image) {
            # TODO: tags
            $image->save();
        }

        $this->redirect('/galery/' . $galery->id);
    }
}
