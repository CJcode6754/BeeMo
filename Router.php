<?php
class Router {
    private $routes = [];

    public function get($path, $callback) {
        $this->routes['GET'][$this->normalizePath($path)] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$this->normalizePath($path)] = $callback;
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route => $callback) {
                if ($route === $this->normalizePath($path)) {
                    call_user_func($callback);
                    return;
                }
            }
        }

        http_response_code(404);
        echo '404 Not Found';
    }

    private function normalizePath($path) {
        return rtrim($path, '/');
    }
}
?>
