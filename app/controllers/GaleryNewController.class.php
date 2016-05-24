<?php

require_once __DIR__ . '/ProtectedController.class.php';

class GaleryNewController extends ProtectedController {
    protected function get($request, $params) {
        $this->render('galery-new');
    }

    protected function post($request, $params) {
        try {
            $galery = new Galery(array(
                'user_id' => $request->user->id,
                'name'    => $request->get('name'),
                'public'  => (bool)$request->get('public')
            ));
            $galery->save();

            $this->redirect('/');
        }
        catch (Exception $e) {
            $this->addError($e->getMessage());
        }

        $this->render('galery-new');
    }
}
