<?php

namespace App\Models;

use App\Core\Model;

class Categoria extends Model
{
    public function getAll($search = '', $limit = 10, $offset = 0)
    {
        $sql = "SELECT * FROM categorias";
        $params = [];
    
        if ($search) {
            $sql .= " WHERE nome LIKE :search OR slug LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }

        $sql .= " ORDER BY nome ASC";
    
        $sql .= " LIMIT :limit OFFSET :offset";
        $params[':limit'] = (int) $limit;
        $params[':offset'] = (int) $offset;
    
        $stmt = $this->pdo->prepare($sql);
    
        // Vincula os parâmetros
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
        }
    
        $stmt->execute();
        $categorias = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Sanitiza os dados
        return array_map(function($categoria) {
            return [
                'id' => htmlspecialchars($categoria['id'], ENT_QUOTES, 'UTF-8'),
                'nome' => htmlspecialchars($categoria['nome'], ENT_QUOTES, 'UTF-8'),
                'slug' => htmlspecialchars($categoria['slug'], ENT_QUOTES, 'UTF-8'),
                'status' => htmlspecialchars($categoria['status'], ENT_QUOTES, 'UTF-8'),
            ];
        }, $categorias);
    }

    public function getAllActive()
    {
        $sql = "SELECT * FROM categorias";
        $sql .= " ORDER BY nome ASC";
   
        $stmt = $this->pdo->prepare($sql);
    
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countCategorias($search = '')
    {
        $sql = "SELECT COUNT(*) FROM categorias";
        $params = [];

        if ($search) {
            $sql .= " WHERE nome LIKE :search OR slug LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }

        $stmt = $this->pdo->prepare($sql);

        if (!empty($params)) {
            $stmt->bindValue(':search', $params[':search'], \PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM categorias WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($nome, $slug, $status)
    {
        $stmt = $this->pdo->prepare('INSERT INTO categorias (nome, slug, status) VALUES (?, ?, ?)');
        return $stmt->execute([$nome, $slug, $status]);
    }

    public function update($id, $nome, $slug, $status)
    {
        $stmt = $this->pdo->prepare('UPDATE categorias SET nome = ?, slug = ?, status = ? WHERE id = ?');
        return $stmt->execute([$nome, $slug, $status, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM categorias WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
