<?php

require_once __DIR__ . '/Model.class.php';

class Image extends Model {

    protected static $fields = array(
        'galery_id' => array(
            'type'         => 'int',
            'required'     => true,
            'related'      => 'Galery',
            'related_name' => 'galery'
        ),

        'name' => array(
            'type'     => 'string',
            'required' => true
        )
    );
}
