<?php

require_once __DIR__ . '/Model.class.php';

class Image extends Model {

    private $tmpName = null;

    private static $thumbnailSize = 200;
    private static $thumbnailPrefix = 'thumb';
    private static $basePath = 'media';

    public function linkTags($tags) {
        DbManager::beginTransaction();

        $deleteQuery = 'DELETE FROM image_tag WHERE image_id = :image_id';
        $deleteStmt  = DbManager::prepare($deleteQuery);

        $deleteStmt->execute(array('image_id' => $this->id));

        $insertQuery = 'INSERT INTO image_tag (image_id, tag_id) VALUES (:image_id, :tag_id)';
        $insertStmt  = DbManager::prepare($insertQuery);

        foreach ($tags as $tag) {
            $insertStmt->execute(array('image_id' => $this->id, 'tag_id' => $tag->id));
        }

        DbManager::commit();
    }

    public function getFilename() {
        return sprintf('%s.%s', $this->name, $this->ext);
    }

    public function getUrl() {
        return sprintf('/%s/%s',
            static::$basePath,
            $this->getFilename()
        );
    }

    public function getThumbnailUrl() {
        return sprintf('/%s/%s_%s',
            static::$basePath,
            static::$thumbnailPrefix,
            $this->getFilename()
        );
    }

    private function generateThumbnail() {
        $target = sprintf('%s/%s/%s_%s',
            dirname(__DIR__),
            static::$basePath,
            static::$thumbnailPrefix,
            $this->getFilename()
        );

        $imagick = new \Imagick($this->tmpName);

        list($ow, $oh)= getimagesize($this->tmpName);
        $ratio = $ow / $oh;

        if ($ratio > 1) {
            $w = static::$thumbnailSize;
            $h = static::$thumbnailSize / $ratio;
        }
        else {
            $w = static::$thumbnailSize * $ratio;
            $h = static::$thumbnailSize;
        }

        $imagick->thumbnailImage($w, $h);
        $imagick->writeImage($target);
        $imagick->destroy();
    }

    private function saveToFilesystem() {
        $this->generateThumbnail();

        $target = sprintf('%s/%s/%s', dirname(__DIR__), static::$basePath, $this->getFilename());

        return move_uploaded_file($this->tmpName, $target);
    }

    private function getTags() {
        $query = 'SELECT tag_id FROM image_tag WHERE image_id = :image_id';
        $stmt  = DbManager::prepare($query);

        $stmt->execute(array('image_id' => $this->id));

        $ids = array_map(
            function($obj) {
                return $obj['tag_id'];
            },
            $stmt->fetchAll(PDO::FETCH_ASSOC)
        );

        return array_map(
            function($id) {
                return Tag::findRecord($id);
            },
            $ids
        );
    }

    public function save() {
        $this->saveToFilesystem();
        parent::save();
    }

    protected static function getRandomName() {
        return uniqid();
    }

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
        ),

        'ext' => array(
            'type'     => 'string',
            'required' => true
        )
    );

    public function __construct($data) {
        parent::__construct($data);

        $this->tags = $this->getTags();
    }

    public static function createFromFiles($raw, $data) {
        $files  = array();
        $models = array();

        foreach ($raw as $key => $values) {
            foreach ($values as $i => $value) {
                if (!isset($files[$i])) {
                    $files[$i] = array();
                }

                $files[$i][$key] = $value;
            }
        }

        foreach ($files as $file) {
            if (!getimagesize($file['tmp_name'])) {
                throw new Exception('Es dürfen nur Bilder hochgeladen werden');
            }

            $data['name'] = static::getRandomName();
            $data['ext']  = pathinfo($file['name'], PATHINFO_EXTENSION);

            $model          = new static($data);
            $model->tmpName = $file['tmp_name'];
            $models[]       = $model;
        }

        return $models;
    }
}
