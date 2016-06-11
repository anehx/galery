<?php

require_once __DIR__ . '/Model.class.php';

/**
 * The galery model
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2016, Jonas Metzener
 */
class Galery extends Model {
    /**
     * The fields of the galery
     *
     * @var array $fields
     */
    protected static $fields = array(
        'user_id' => array(
            'type'         => 'int',
            'required'     => true,
            'related'      => 'User',
            'related_name' => 'user'
        ),

        'name' => array(
            'type'     => 'string',
            'required' => true
        ),

        'public' => array(
            'type'     => 'bool',
            'required' => true
        )
    );
}
