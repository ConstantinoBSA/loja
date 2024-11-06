<?php

use App\Core\Database;

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

