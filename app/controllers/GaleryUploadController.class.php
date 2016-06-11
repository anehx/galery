<?php

require_once __DIR__ . '/ProtectedController.class.php';

require_once __DIR__ . '/../models/Galery.class.php';
require_once __DIR__ . '/../models/Image.class.php';
require_once __DIR__ . '/../models/Tag.class.php';

/**
 * Controller to upload an image into a galery
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2016, Jonas Metzener
 */
class GaleryUploadController extends ProtectedController {
    /**
     * Display an upload form
     *
     * @param Request $request
     * @param array $params
     * @return void
     */
    protected function get($request, $params) {
        $this->galery = Galery::findRecord($params[0]);

        $this->checkPermission($this->galery, $request);

        $this->render('galery-upload');
    }

    /**
     * Upload one or multiple images
     *
     * @param Request $request
     * @param array $params
     * @return void
     */
    protected function post($request, $params) {
        $galery = Galery::findRecord($params[0]);

        $this->checkPermission($galery, $request);

        $images = Image::createFromFiles($request->files['images'], array('galery_id' => $galery->id));
        $tags   = Tag::createOrGetFromString($request->get('tags'), $request->user->id);

        foreach ($images as $image) {
            $image->save();
            $image->linkTags($tags);
        }

        $this->redirect('/galery/' . $galery->id);
    }
}
