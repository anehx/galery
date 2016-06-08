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
