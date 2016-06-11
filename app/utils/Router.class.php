<?php

/**
 * The URL router
 *
 * @author Jonas Metzener
 * @license MIT
 * @copyright Copyright (c) 2016, Jonas Metzener
 */
class Router {
    /**
     * The routes of this router
     *
     * @var array $routes
     */
    private $routes = array();

    /**
     * The constructor of the router
     *
     * @param string $base
     * @return void
     */
    public function __construct($base = '') {
        $this->base = $base;
    }

    /**
     * Add a new route
     *
     * @param string $pattern
     * @param string $controller
     * @return void
     */
    public function route($pattern, $controller) {
        $p = $this->base . $pattern;

        $this->routes['/' . $p . '\/?$/'] = $controller;
    }

    /**
     * Check the current URL for matches in routed
     *
     * @param string $uri
     * @return mixed
     */
    public function execute($uri) {
        foreach ($this->routes as $pattern => $controller) {
            if (preg_match($pattern, $uri, $params) === 1) {
                array_shift($params);
                $ctrl = new $controller();
                return $ctrl->handle(array_values($params));
            }
        }
    }

}
