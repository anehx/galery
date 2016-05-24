<?php

require_once __DIR__ . '/Model.class.php';

class Galery extends Model {

    protected static $fields = array(
        'user_id'      => array(
            'type'     => 'int',
            'required' => true,
            'related'  => 'User'
        ),
        'name'         => array(
            'type'     => 'string',
            'required' => true,
            'related'  => null
        ),
        'public'       => array(
            'type'     => 'bool',
            'required' => true,
            'related'  => null
        )
    );
}
