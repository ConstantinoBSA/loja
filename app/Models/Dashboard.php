<?php

namespace App\Models;

use App\Core\Model;

class Dashboard extends Model
{
    function getVendasPorMes($ano) {
        $stmt = $this->pdo->prepare("
            SELECT 
                MONTH(data_venda) AS mes, 
                SUM(total) AS total
            FROM vendas
            WHERE YEAR(data_venda) = :ano
            GROUP BY MONTH(data_venda)
        ");
        $stmt->execute(['ano' => $ano]);
        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }
}
