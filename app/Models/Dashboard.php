<?php

namespace App\Models;

use App\Core\Model;

class Dashboard extends Model
{
    function getVendasPorMes($ano) {
        $stmt = $this->pdo->prepare("
            SELECT 
                MONTH(data) AS mes, 
                SUM(total) AS total
            FROM vendas
            WHERE YEAR(data) = :ano
            GROUP BY MONTH(data)
        ");
        $stmt->execute(['ano' => $ano]);
        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }
}
