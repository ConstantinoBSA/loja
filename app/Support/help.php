<?php

use App\Core\Auth;
use App\Core\Database;

function dd(...$terms)
{
    echo '<pre>';
    foreach ($terms as $term) {
        var_dump($term);
    }
    echo '</pre>';
    die;
}

function generateSixDigitPassword() {
    // Gera um número aleatório entre 100000 e 999999
    return random_int(100000, 999999);
}

if (!function_exists('auth')) {
    function auth() {
        $auth = new Auth();
        return $auth;
    }
}

function generateSaleNumber()
{
    // Lógica para gerar um número de venda único
    // Exemplo: concatenar ano atual com um ID autoincremental ou outro identificador único
    $pdo = Database::getInstance()->getConnection();
    $stmt = $pdo->query("SELECT MAX(id) AS max_id FROM vendas");
    $row = $stmt->fetch(\PDO::FETCH_ASSOC);
    $nextId = $row ? $row['max_id'] + 1 : 1;
    $currentYear = date('Y');
    return "{$currentYear}-{$nextId}";
}

function generateProductNumber()
{
    // Lógica para gerar um número de venda único
    // Exemplo: concatenar ano atual com um ID autoincremental ou outro identificador único
    $pdo = Database::getInstance()->getConnection();
    $stmt = $pdo->query("SELECT MAX(id) AS max_id FROM produtos");
    $row = $stmt->fetch(\PDO::FETCH_ASSOC);
    $nextId = $row ? $row['max_id'] + 1 : 1;
    $currentYear = date('Y');
    return "{$currentYear}-{$nextId}";
}

function generateSlug($string) {
    // Converte para minúsculas
    $string = strtolower($string);
    // Remove caracteres especiais
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    // Substitui espaços e múltiplos hifens por um único hífen
    $string = preg_replace('/[\s-]+/', '-', $string);
    // Remove hifens no início e no final
    $string = trim($string, '-');
    return $string;
}
