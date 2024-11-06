<?php

namespace App\Models;

use App\Core\Model;
use PDO;
use PDOException;

class Categoria extends Model
{
    private $table_name = "categorias";

    public $id;
    public $nome;
    public $slug;
    public $status;

    // Retorna todas as categorias, com suporte a busca, limite e offset
    public function getAll($search = '', $limit = 10, $offset = 0)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name;
            $params = [];

            if ($search) {
                $sql .= " WHERE nome LIKE :nome OR slug LIKE :slug";
                $params[':nome'] = '%' . $search . '%';
                $params[':slug'] = '%' . $search . '%';
            }

            $sql .= " ORDER BY nome ASC";
            $sql .= " LIMIT " . (int) $limit . " OFFSET " . (int) $offset;

            $stmt = $this->pdo->prepare($sql);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, \PDO::PARAM_STR);
            }

            $stmt->execute();
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Transformar cada linha em um objeto Categoria
            return array_map([$this, 'mapRowToModel'], $rows);
        } catch (PDOException $e) {
            // Log the error message
            error_log($e->getMessage());
            return [];
        }
    }

    // Mapeia a linha do banco de dados para o objeto Categoria
    protected function mapRowToModel(array $row)
    {
        $model = new self();
        $model->id = htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8');
        $model->nome = htmlspecialchars($row['nome'], ENT_QUOTES, 'UTF-8');
        $model->slug = htmlspecialchars($row['slug'], ENT_QUOTES, 'UTF-8');
        $model->status = htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8');
        return $model;
    }

    // Retorna categorias ativas
    public function getAllActive()
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE status = 'active' ORDER BY nome ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map([$this, 'mapRowToModel'], $rows);
        } catch (PDOException $e) {
            // Log the error message
            error_log($e->getMessage());
            return [];
        }
    }

    // Retorna a contagem de categorias
    public function countCategorias($search = '')
    {
        try {
            $sql = "SELECT COUNT(*) FROM " . $this->table_name;
            $params = [];

            if ($search) {
                $sql .= " WHERE nome LIKE :nome OR slug LIKE :slug";
                $params[':nome'] = '%' . $search . '%';
                $params[':slug'] = '%' . $search . '%';
            }

            $stmt = $this->pdo->prepare($sql);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }

            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            // Log the error message
            error_log($e->getMessage());
            return 0;
        }
    }

    // Busca uma categoria por ID
    public function getById($id)
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = ?');
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row ? $this->mapRowToModel($row) : null;
        } catch (PDOException $e) {
            // Log the error message
            error_log($e->getMessage());
            return null;
        }
    }

    // Cria uma nova categoria
    public function create($nome, $slug, $status)
    {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO ' . $this->table_name . ' (nome, slug, status) VALUES (?, ?, ?)');
            return $stmt->execute([$nome, $slug, $status]);
        } catch (PDOException $e) {
            // Log the error message
            error_log($e->getMessage());
            return false;
        }
    }

    // Atualiza uma categoria existente
    public function update($id, $nome, $slug, $status)
    {
        try {
            $stmt = $this->pdo->prepare('UPDATE ' . $this->table_name . ' SET nome = ?, slug = ?, status = ? WHERE id = ?');
            return $stmt->execute([$nome, $slug, $status, $id]);
        } catch (PDOException $e) {
            // Log the error message
            error_log($e->getMessage());
            return false;
        }
    }

    // Deleta uma categoria
    public function delete($id)
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM ' . $this->table_name . ' WHERE id = ?');
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            // Log the error message
            error_log($e->getMessage());
            return false;
        }
    }
}
