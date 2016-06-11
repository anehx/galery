<?php

require_once __DIR__ . '/Model.class.php';

/**
 * The user model
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2016, Jonas Metzener
 */
class User extends Model {
    /**
     * The fields of the tag
     *
     * @var array $fields
     */
    protected static $fields = array(
        'email'        => array(
            'type'     => 'string',
            'required' => true
        ),

        'password'     => array(
            'type'     => 'string',
            'required' => true
        )
    );
}
