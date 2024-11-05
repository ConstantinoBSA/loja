<?php

$sections = [];
$currentSection = null;

function startSection($name)
{
    global $sections, $currentSection;

    $currentSection = $name;
    ob_start(); // Inicia o buffer de saída para a seção atual
}

function endSection()
{
    global $sections, $currentSection;

    // Captura o conteúdo do buffer e o associa à seção atual
    if ($currentSection !== null) {
        $sections[$currentSection] = ob_get_clean();
        $currentSection = null;
    } else {
        throw new Exception("Section end without a start.");
    }
}

function yieldSection($name)
{
    global $sections;

    return $sections[$name] ?? ''; // Retorna o conteúdo da seção ou uma string vazia
}

function view($view, $data = [])
{
    extract($data); // Extrai variáveis do array para uso na view
    ob_start();
    require __DIR__ . "/../resources/views/{$view}.php"; // Inclui a view
    return ob_get_clean(); // Retorna o conteúdo bufferizado
}

function extend($layout)
{
    global $sections;

    include __DIR__ . "/../../resources/views/{$layout}.php"; // Inclui o layout principal
}
