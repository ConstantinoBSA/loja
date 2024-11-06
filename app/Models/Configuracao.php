<?php

namespace App\Models;

use App\Core\Model;

class Configuracao extends Model
{
    public function getConfiguracao($chave)
    {
        $stmt = $this->pdo->prepare("SELECT valor FROM configuracoes WHERE chave = ?");
        $stmt->execute([$chave]);
        return $stmt->fetchColumn();
    }

    public function getAllConfiguracoes()
    {
        $stmt = $this->pdo->query("SELECT chave, valor FROM configuracoes");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function updateConfiguracao($chave, $valor)
    {
        $stmt = $this->pdo->prepare("REPLACE INTO configuracoes (chave, valor) VALUES (?, ?)");
        return $stmt->execute([$chave, $valor]);
    }
}
