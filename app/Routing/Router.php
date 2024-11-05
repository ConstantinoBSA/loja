<?php

namespace App\Routing;

use App\Middleware\AuthMiddleware;

class Router
{
    protected $routes = [];
    protected $protectedRoutes = [];

    public function add($method, $uri, $action, $protected = false)
    {
        $method = strtoupper($method);
        $this->routes[$method][$uri] = $action;
        if ($protected) {
            $this->protectedRoutes[] = $uri;
        }
    }

    public function match($method, $uri)
    {
        if (isset($this->routes[$method])) {
            if (isset($this->routes[$method][$uri])) {
                return true;
            }
        }
    
        return false;
    }

    public function dispatch($uri, $method)
    {
        global $request;

        if ($this->match($method, $uri)) {
            $this->executeAction($uri, $method);
            if (in_array($uri, $this->protectedRoutes)) {
                $middleware = new AuthMiddleware();
                $middleware->handle($request, function() use ($uri, $method) {
                    $this->executeAction($uri, $method);
                });
            } else {
                $this->executeAction($uri, $method);
            }
        } else {
            $this->renderErrorPage('Erro 404', 'Página não encontrada.');
        }
    }
    
    protected function executeAction($uri, $method)
    {
        list($controllerClass, $method) = $this->routes[$method][$uri];

        if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
            $controller = new $controllerClass();
            call_user_func([$controller, $method]);
        } else {
            $this->renderErrorPage('Erro 404', 'Controlador ou método não encontrado.');
        }
    }

    protected function renderErrorPage($title, $message)
    {
        $errorTitle = $title;
        $errorMessage = $message;
        include __DIR__ . '/../../resources/views/admin/error.php';
        exit();
    }
}
