<?php

require_once __DIR__ . '/Model.class.php';

class Tag extends Model {

    protected static $fields = array(
        'user_id' => array(
            'type'          => 'int',
            'required'      => true,
            'related'       => 'User',
            'related_name'  => 'user'
        ),

        'name' => array(
            'type'     => 'string',
            'required' => true
        )
    );
}
