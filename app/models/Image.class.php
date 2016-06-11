<?php

require_once __DIR__ . '/Model.class.php';

/**
 * The image model
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2016, Jonas Metzener
 */
class Image extends Model {
    /**
     * The fields of the image
     *
     * @var array $fields
     */
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

    /**
     * The temporary name of the image (while uploading)
     *
     * @var string $tmpName
     */
    private $tmpName = null;

    /**
     * The thumbnail size
     *
     * @var string $thumbnailSize
     */
    private static $thumbnailSize = 200;

    /**
     * The thumbnail prefix (in the name)
     *
     * @var string $thumbnailPrefix
     */
    private static $thumbnailPrefix = 'thumb';

    /**
     * The base path to the image
     *
     * @var string $basePath
     */
    private static $basePath = 'media';

    /**
     * Link a bunch of tags to the image
     *
     * @param array $tags
     * @return void
     */
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

    /**
     * Get the full filename
     *
     * @return string
     */
    public function getFilename() {
        return sprintf('%s.%s', $this->name, $this->ext);
    }

    /**
     * Get the url to the image
     *
     * @return string
     */
    public function getUrl() {
        return sprintf('/%s/%s',
            static::$basePath,
            $this->getFilename()
        );
    }

    /**
     * Get the url to the thumbnail image
     *
     * @return string
     */
    public function getThumbnailUrl() {
        return sprintf('/%s/%s_%s',
            static::$basePath,
            static::$thumbnailPrefix,
            $this->getFilename()
        );
    }

    /**
     * Generate a thumbnail for the image
     *
     * @return void
     */
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

    /**
     * Save the image to the filesystem
     *
     * @return bool
     */
    private function saveToFilesystem() {
        $this->generateThumbnail();

        $target = sprintf('%s/%s/%s', dirname(__DIR__), static::$basePath, $this->getFilename());

        return move_uploaded_file($this->tmpName, $target);
    }

    /**
     * Get the referenced tags of the image
     *
     * @return array
     */
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

    /**
     * Save the image to the filesystem and
     * to the database
     *
     * @return void
     */
    public function save() {
        $this->saveToFilesystem();
        parent::save();
    }

    /**
     * Create the image object
     *
     * @param array $data
     * @return static
     */
    public function __construct($data) {
        parent::__construct($data);

        $this->tags = $this->getTags();
    }

    /**
     * Get a random name
     *
     * @return string
     */
    protected static function getRandomName() {
        return uniqid();
    }

    /**
     * Create image models from upload data
     *
     * @param array $raw
     * @param array $data
     * @return array
     */
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
