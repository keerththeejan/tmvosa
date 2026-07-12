<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private array $middleware = [];

    public function get(string $path, array $handler, array $middleware = []): void
    {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    public function post(string $path, array $handler, array $middleware = []): void
    {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    public function put(string $path, array $handler, array $middleware = []): void
    {
        $this->addRoute('PUT', $path, $handler, $middleware);
    }

    public function delete(string $path, array $handler, array $middleware = []): void
    {
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }

    private function addRoute(string $method, string $path, array $handler, array $middleware): void
    {
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '([^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';
        $this->routes[] = compact('method', 'path', 'pattern', 'handler', 'middleware');
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        $uri = $this->resolveRequestUri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches);
                $this->runMiddleware($route['middleware']);
                $this->callHandler($route['handler'], $matches);
                return;
            }
        }

        http_response_code(404);
        View::render('errors/404');
    }

    private function resolveRequestUri(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));

        if ($scriptDir !== '/' && $scriptDir !== '.' && str_starts_with($uri, $scriptDir)) {
            $uri = substr($uri, strlen($scriptDir)) ?: '/';
        }

        $appBase = dirname($scriptDir);
        if ($appBase !== '/' && $appBase !== '.' && str_starts_with($uri, $appBase)) {
            $uri = substr($uri, strlen($appBase)) ?: '/';
        }

        $uri = '/' . ltrim($uri, '/');
        if ($uri === '/index.php') {
            $uri = '/';
        }

        return rtrim($uri, '/') ?: '/';
    }

    private function runMiddleware(array $middleware): void
    {
        foreach ($middleware as $mw) {
            $roles = [];
            $name = $mw;
            if (str_contains($mw, ':')) {
                [$name, $roleList] = explode(':', $mw, 2);
                $roles = array_values(array_filter(array_map('trim', explode(',', $roleList))));
            }

            $class = "App\\Middleware\\{$name}";
            if (class_exists($class)) {
                $instance = $roles ? new $class($roles) : new $class();
                $instance->handle();
            }
        }
    }

    private function callHandler(array $handler, array $params): void
    {
        [$controller, $method] = $handler;
        $class = "App\\Controllers\\{$controller}";
        $instance = new $class();

        if (!method_exists($instance, $method)) {
            http_response_code(500);
            echo json_encode(['error' => 'Method not found']);
            return;
        }

        call_user_func_array([$instance, $method], $params);
    }
}
