<?php
class Router {
    private $routes = [];

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'] ?: '/';

        foreach ($this->routes[$method] as $route => $callback) {
            if ($route === $path) {
                call_user_func($callback);
                return;
            }
        }

        http_response_code(404);
        echo '404 Not Found';
    }
}
?>
