<?php

require_once __DIR__ . '/../utils/DbManager.class.php';

/**
 * The basic model class
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2015, Jonas Metzener
 */
class Model {
    /**
     * Fields of the model (needs to be defined in child class)
     *
     * @var array $fields
     */
    protected static $fields = array();

    /**
     * Table name of the model
     *
     * @var string $tableName
     */
    protected static $tableName = null;

    /**
     * Default ordering key
     *
     * @var string $ordering
     */
    protected static $ordering = 'id';

    /**
     * Returns the table name of the model
     *
     * @return string
     */
    public static function getTableName() {
        return static::$tableName ? static::$tableName : strtolower(get_called_class());
    }

    /**
     * Casts a variable to a given type
     *
     * @param  string $type
     * @param  string $variable
     * @return mixed
     */
    private static function __cast($type, $variable) {
        switch ($type) {
            case 'int':
                $variable = (int)$variable;
                break;
            case 'string':
                $variable = (string)$variable;
                break;
            case 'bool':
                $variable = (bool)$variable;
                break;
            default:
                break;
        }
        return $variable;
    }

    /**
     * Checks if a field is a property of the model
     *
     * @param  string $field
     * @return void
     */
    protected static function __checkField($field) {
        if (!isset(static::$fields[$field]) && $field !== 'id') {
            throw new Exception(sprintf('Field %s does not exist.', $field));
        }
    }

    /**
     * Validates a field of a model
     *
     * @param  string $field
     * @param  mixed  $value
     * @return void
     */
    protected static function __validate($field, $value) {
        static::__checkField($field);

        $attr = static::$fields[$field];

        if (is_null($value) && $attr['required']) {
            throw new Exception(sprintf('Field %s can not be null.', $field));
        }

        if ($attr['related']) {
            if (!call_user_func_array(array($attr['related'], 'findRecord'), array($value))) {
                throw new Exception(sprintf('Relation %s with id %d does not exist.', $field, $value));
            }
        }
    }

    /**
     * Constructor of the model
     *
     * @param  array  $data
     * @return static
     */
    public function __construct($data) {
        $this->id = isset($data['id']) ? (int)$data['id'] : null;

        foreach (static::$fields as $field => $attr) {
            $value = isset($data[$field]) ? static::__cast($attr['type'], $data[$field]) : null;

            static::__validate($field, $value);

            $this->$field = $value;
        }

        foreach (static::$fields as $k => $v) {
            if ($v['related']) {
                $this->$v['related_name'] = call_user_func_array(
                    array($v['related'], 'findRecord'),
                    array($this->$k)
                );
            }
        }
    }

    /**
     * Creates or updates a model
     *
     * @return static
     */
    public function save() {
        if (is_null($this->id)) {
            return $this->create();
        }
        else {
            return $this->update();
        }
    }

    /**
     * Updates a model in the db
     *
     * @return static
     */
    public function update() {
        $fieldNames = array_keys(static::$fields);

        $query = sprintf(
            'UPDATE %s SET %s WHERE id = :id',
            static::getTableName(),
            join(',', array_map(function($k) { return sprintf('%s = :%s', $k, $k); }, $fieldNames))
        );

        $stmt = DbManager::prepare($query);

        $parsed = array_filter((array)$this, function($field) {
            return !is_object($field);
        });

        $stmt->execute($parsed);

        return $this;
    }

    /**
     * Deletes a model in the db
     *
     * @return bool
     */
    public function delete() {
        $query = sprintf(
            'DELETE FROM %s WHERE id = :id',
            static::getTableName()
        );

        $stmt= DbManager::prepare($query);
        $stmt->execute(array('id' => $this->id));

        return $stmt;
    }

    /**
     * Creates a model in the db
     *
     * @return static
     */
    public function create() {
        $fieldNames = array_keys(static::$fields);

        $query = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            static::getTableName(),
            join(',', $fieldNames),
            join(',', array_map(function($k) { return ':'.$k; }, $fieldNames))
        );

        $stmt   = DbManager::prepare($query);
        $values = array();

        foreach (static::$fields as $k => $v) {
            $values[$k] = $this->$k;
        }

        $stmt->execute($values);
        $this->id = DbManager::lastInsertId();

        return $this;
    }


    private static function getRelatedFields() {
        return array_map(
            function ($field) {
                return $field;
            },
            array_filter(
                static::$fields,
                function($field) {
                    return isset($field['related']);
                }
            )
        );
    }

    private static function getBaseQuery() {
        return 'SELECT * FROM ' . static::getTableName();
    }

    private static function buildWhere($criteria) {
        if (empty($criteria)) {
            return '';
        }

        return 'WHERE ' . join(' AND ', array_map(
            function($key) {
                return sprintf('%s = :%s', $key, $key);
            },
            array_keys($criteria)
        ));
    }

    private static function buildOrdering($ordering) {
        $val   = $ordering ? $ordering : static::$ordering;
        $order = strpos($val, '.') ? $val: static::getTableName() . '.' . $val;

        return 'ORDER BY ' . $order;
    }

    /**
     * Queries db for models by given criteria
     *
     * @param  array  $criteria
     * @param  int    $limit
     * @return array
     */
    public static function query($criteria = array(), $limit = null, $ordering = null) {
        $query = implode(' ', array(
            static::getBaseQuery(),
            static::buildWhere($criteria),
            static::buildOrdering($ordering),
            $limit ? 'LIMIT ' . $limit : ''
        ));

        $stmt = DbManager::prepare($query);

        $stmt->execute($criteria);

        return array_map(
            function($row) { return new static($row); },
            $stmt->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    /**
     * Finds a single model in the db by given criteria
     *
     * @param  array $criteria
     * @return static
     */
    public static function queryRecord($criteria) {
        $query = static::query($criteria, 1);

        if (empty($query)) {
            throw new Exception(sprintf('No %s with this criteria found.', strtolower(get_called_class())));
        }

        return $query[0];
    }

    /**
     * Finds a single model in the db by given id
     *
     * @param  int $id
     * @return static
     */
    public static function findRecord($id) {
        $query = static::query(array('id' => $id), 1);

        if (empty($query)) {
            throw new Exception(sprintf('No %s with this criteria found.', strtolower(get_called_class())));
        }

        return $query[0];
    }

    /**
     * Finds all models in the db
     * Wrapper for query
     *
     * @return array
     */
    public static function findAll() {
        return static::query();
    }
}
