<?php

namespace App\Models;

use App\Core\Model;
use PDO;
use PDOException;

class Permissao extends Model
{
    private $table_name = "permissoes";

    public $id;
    public $nome;
    public $descricao;

    // Retorna todas as permissoes, com suporte a busca, limite e offset
    public function getAll($search = '', $limit = 10, $offset = 0)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name;
            $params = [];

            if ($search) {
                $sql .= " WHERE nome LIKE :searchName OR slug LIKE :searchDescricao";
                $params[':searchName'] = '%' . $search . '%';
                $params[':searchDescricao'] = '%' . $search . '%';
            }

            $sql .= " ORDER BY nome ASC";
            $sql .= " LIMIT " . (int) $limit . " OFFSET " . (int) $offset;

            $stmt = $this->pdo->prepare($sql);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, \PDO::PARAM_STR);
            }

            $stmt->execute();
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Transformar cada linha em um objeto Permissao
            return array_map([$this, 'mapRowToModel'], $rows);
        } catch (PDOException $e) {
            // Log the error message
            error_log($e->getMessage());
            return [];
        }
    }

    // Mapeia a linha do banco de dados para o objeto Permissao
    protected function mapRowToModel(array $row)
    {
        $model = new self();
        $model->id = htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8');
        $model->nome = htmlspecialchars($row['nome'], ENT_QUOTES, 'UTF-8');
        $model->descricao = htmlspecialchars($row['descricao'], ENT_QUOTES, 'UTF-8');
        return $model;
    }

    // Retorna a contagem de permissoes
    public function countPermissoes($search = '')
    {
        try {
            $sql = "SELECT COUNT(*) FROM " . $this->table_name;
            $params = [];

            if ($search) {
                $sql .= " WHERE nome LIKE :search OR descricao LIKE :search";
                $params[':search'] = '%' . $search . '%';
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

    // Busca uma permissao por ID
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

    // Cria uma nova permissao
    public function create($nome, $descricao)
    {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO ' . $this->table_name . ' (nome, descricao) VALUES (?, ?)');
            return $stmt->execute([$nome, $descricao]);
        } catch (PDOException $e) {
            // Log the error message
            error_log($e->getMessage());
            return false;
        }
    }

    // Atualiza uma permissao existente
    public function update($id, $nome, $descricao)
    {
        try {
            $stmt = $this->pdo->prepare('UPDATE ' . $this->table_name . ' SET nome = ?, descricao = ? WHERE id = ?');
            return $stmt->execute([$nome, $descricao, $id]);
        } catch (PDOException $e) {
            // Log the error message
            error_log($e->getMessage());
            return false;
        }
    }

    // Deleta uma permissao
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
