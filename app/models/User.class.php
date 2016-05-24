<?php

require_once __DIR__ . '/Model.class.php';

class User extends Model {

    protected static $fields = array(
        'email'        => array(
            'type'     => 'string',
            'required' => true,
            'related'  => null
        ),
        'password'     => array(
            'type'     => 'string',
            'required' => true,
            'related'  => null
        )
    );

    protected $password;
}