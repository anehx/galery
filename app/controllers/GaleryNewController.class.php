<?php

require_once __DIR__ . '/ProtectedController.class.php';

/**
 * Controller to create a galery
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2016, Jonas Metzener
 */
class GaleryNewController extends ProtectedController {
    /**
     * Display an creation form
     *
     * @param Request $request
     * @param array $params
     * @return void
     */
    protected function get($request, $params) {
        $this->render('galery-new');
    }

    /**
     * Create a galery
     *
     * @param Request $request
     * @param array $params
     * @return void
     */
    protected function post($request, $params) {
        try {
            $galery = new Galery(array(
                'user_id' => $request->user->id,
                'name'    => $request->get('name'),
                'public'  => (bool)$request->get('public')
            ));
            $galery->save();

            $this->redirect('/galery/' . $galery->id);
        }
        catch (Exception $e) {
            $this->addError($e->getMessage());
        }

        $this->render('galery-new');
    }
}
