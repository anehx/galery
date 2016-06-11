<?php

require_once __DIR__ . '/ProtectedController.class.php';
require_once __DIR__ . '/../models/Image.class.php';

/**
 * Controller to delete an image from a galery
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2016, Jonas Metzener
 */
class ImageDeleteController extends ProtectedController {
    /**
     * Display a confirmation page
     *
     * @param Request $request
     * @param array $params
     * @return void
     */
    protected function get($request, $params) {
        $this->image = Image::findRecord($params[0]);

        $this->checkPermission($this->image->galery, $request);

        $this->render('image-delete');
    }

    /**
     * Delete an image
     *
     * @param Request $request
     * @param array $params
     * @return void
     */
    protected function post($request, $params) {
        $image = Image::findRecord($params[0]);

        $this->checkPermission($image->galery, $request);

        $image->delete();

        $this->redirect('/galery/' . $image->galery->id);
    }
}
