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

function hasProfile($profileName) {
    $pdo = Database::getInstance()->getConnection();
    $query = "SELECT COUNT(*) FROM perfis 
        JOIN perfil_usuario ON perfis.id = perfil_usuario.perfil_id
        WHERE perfil_usuario.usuario_id = ? AND perfis.nome = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([auth()->user()->id, $profileName]);
    $result = $stmt->fetchColumn();

    return $result > 0;
}

function hasPermission($permissionName) {
    $pdo = Database::getInstance()->getConnection();
    $query = "SELECT COUNT(*) FROM permissoes 
        JOIN permissao_perfil ON permissoes.id = permissao_perfil.permissao_id
        JOIN perfil_usuario ON permissao_perfil.perfil_id = perfil_usuario.perfil_id
        WHERE perfil_usuario.usuario_id = ? AND permissoes.nome = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([auth()->user()->id, $permissionName]);
    $result = $stmt->fetchColumn();
    
    if ($result > 0) {
        return true;
    }

    // Verifique permissões diretas atribuídas ao usuário
    $query = "SELECT COUNT(*) FROM permissoes 
        JOIN permissao_usuario ON permissoes.id = permissao_usuario.permissao_id
        WHERE permissao_usuario.usuario_id = ? AND permissoes.nome = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([auth()->user()->id, $permissionName]);
    $result = $stmt->fetchColumn();

    return $result > 0;
}

function abort($code, $mensagem)
{
    $errorTitle = $code;
    $errorMessage = $mensagem;
    include __DIR__ . '/../../resources/views/errors/'.$code.'.php';
    exit();
}
