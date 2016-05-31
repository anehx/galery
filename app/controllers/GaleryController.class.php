<?php

require_once __DIR__ . '/ProtectedController.class.php';

require_once __DIR__ . '/../models/Galery.class.php';
require_once __DIR__ . '/../models/Tag.class.php';

class GaleryController extends ProtectedController {
    protected function get($request, $params) {
        $this->galery = Galery::findRecord($params[0]);
        $this->tags   = Tag::query(array('user_id' => $request->user->id));

        $this->render('galery');
    }
}
