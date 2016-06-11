<?php

/**
 * A HTTP request
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2016, Jonas Metzener
 */
class Request {
    /**
     * The HTTP method
     *
     * @var string $method
     */
    public $method;

    /**
     * The request body
     *
     * @var string $request
     */
    private $body;

    /**
     * The request headers
     *
     * @var array $headers
     */
    private $headers;

    /**
     * The requests user object
     *
     * @var User $user
     */
    public $user;

    /**
     * The requests include params
     *
     * @var string $include
     */
    public $include;

    /**
     * The request constructor which builds the request object
     *
     * @return void
     */
    public function __construct() {
        $this->method  = $_SERVER['REQUEST_METHOD'];
        $this->headers = getallheaders();
        $this->user    = unserialize($_SESSION['user']);
        $this->files   = $_FILES;

        switch ($this->method) {
            case 'POST':
            case 'PUT':
                if (!empty($_POST)) {
                    $this->body = array_merge($_POST, $_GET);
                }
                else {
                    parse_str(file_get_contents('php://input'), $post);
                    $this->body = array_merge($post, $_GET);
                }
                break;
            case 'GET':
                $this->body = $_GET;
                break;
            default:
                $this->body = array();
        }

        try {
            $this->include = $this->get('include');
        }
        catch (OutOfBoundsException $e) {
            $this->include = null;
        }
    }

    /**
     * Returns the value of a key in the request body
     *
     * @param string $key
     * @return mixed
     */
    public function get($key) {
        if (!isset($this->body[$key])) {
            return null;
        }

        return $this->body[$key];
    }

    /**
     * Returns the value of a key in the request headers
     *
     * @param string $key
     * @throws OutOfBoundsException
     * @return mixed
     */
    public function getHeader($key) {
        if (!isset($this->headers[$key])) {
            throw new OutOfBoundsException(sprintf('Header %s is not given', $key));
        }

        return $this->headers[$key];
    }

}
