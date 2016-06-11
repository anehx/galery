<?php

require_once __DIR__ . '/Model.class.php';

/**
 * The tag model
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2016, Jonas Metzener
 */
class Tag extends Model {
    /**
     * The fields of the tag
     *
     * @var array $fields
     */
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

    /**
     * Create tag objects from a string by
     * an input field (foo, bar, baz)
     *
     * @param string $string
     * @param int $userId
     * @return array
     */
    public static function createOrGetFromString($string, $userId) {
        $names = array_map(trim, explode(',', $string));
        $tags  = array();

        foreach ($names as $name) {
            try {
                $tag = static::queryRecord(array('name' => $name, 'user_id' => $userId));
            }
            catch (Exception $e) {
                $tag = new static(array('name' => $name, 'user_id' => $userId));

                $tag->save();
            }
            $tags[] = $tag;
        }

        return $tags;
    }
}
