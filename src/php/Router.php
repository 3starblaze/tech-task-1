<?php

namespace TechTask\Router;

use TechTask\Util\Util;

/**
 * Handle the connection between URL and the content by binding route to a
 * callback.
 */
class Router
{
    private $routeHandlers = array();

    private $defaultHandler = null;

    /**
     * Bind a callable to a route that is executed when user visits $route.
     *
     * @param $route Route which does not include the domain and which starts
     * with a slash.
     * @param $handler The callable that is going to be triggered on route
     * visit.
     */
    public function registerRoute(string $route, callable $handler)
    {
        $this->routeHandlers[$route] = $handler;
    }

    /**
     * Bind callable that is called when the requested route does not have a
     * handler.
     */
    public function registerDefaultHandler(callable $handler)
    {
        $this->defaultHandler = $handler;
    }

    /**
     * Check the current route and trigger corresponding handler callable
     * (if it exists).
     */
    public function handleRequest()
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (array_key_exists($uri, $this->routeHandlers)) {
            $this->routeHandlers[$uri]();
        } elseif ($this->defaultHandler) {
            ($this->defaultHandler)();
        } else {
            Util::throwError("Route does not exist!");
        }
    }
}
