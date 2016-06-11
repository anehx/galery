<?php

require_once __DIR__ . '/ProtectedController.class.php';

require_once __DIR__ . '/../models/Galery.class.php';
require_once __DIR__ . '/../models/Tag.class.php';
require_once __DIR__ . '/../models/Image.class.php';

class GaleryController extends ProtectedController {
    protected function get($request, $params) {
        $this->galery = Galery::findRecord($params[0]);

        if (!$this->galery->public) {
            $this->checkPermission($this->galery, $request);
        }

        $this->tags   = Tag::query(array('user_id' => $request->user->id));
        $this->images = Image::query(array('galery_id' => $this->galery->id));
        $this->tag    = (int)$request->get('tag');

        if ($this->tag) {
            $this->images = array_filter($this->images, function($img) {
                $ids = array_map(function($tag) { return $tag->id; }, array_values($img->tags));

                return array_search($this->tag, $ids) !== false;
            });
        }

        $this->render('galery');
    }
}
