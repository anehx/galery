<?php

/**
 * Wrapper for a PDO instance
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2016, Jonas Metzener
 */
class DbManager {
    /**
     * PDO instance with given configuration
     *
     * @property PDO $_db
     */
    private static $_db;

    /**
     * Returns a PDO instance
     *
     * @return PDO
     */
    public static function getInstance() {
        if (!isset(self::$_db)) {
            self::$_db = new PDO('mysql:host=db;dbname=galery;charset=utf8', 'galery', 'galery');
            self::$_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // self::$_db->exec('PRAGMA foreign_keys = ON;');
        }

        return self::$_db;
    }

    /**
     * Redirect all static calls to PDO instance
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments) {
        $inst = self::getInstance();

        return call_user_func_array(
            array(
                $inst,
                $name
            ),
            $arguments
        );
    }
}
