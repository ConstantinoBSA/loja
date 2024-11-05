<?php

namespace App\Core;

class Controller
{
    public function view($view, $data = [])
    {
        extract($data);
        $viewPath = __DIR__ . '/../../resources/views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            echo "View not found: $viewPath"; // Debugging
        }
    }
}
