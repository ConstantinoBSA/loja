<?php

namespace App\Models;

use App\Core\Model;
use PDO;
use PDOException;

class Perfil extends Model
{
    private $table_name = "perfis";

    public $id;
    public $nome;
    public $label;
    public $descricao;
    public $permissoes;

    // Retorna todas as perfis, com suporte a busca, limite e offset
    public function getAll($search = '', $limit = 10, $offset = 0)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name;
            $params = [];

            if ($search) {
                $sql .= " WHERE nome LIKE :nome OR label LIKE :label";
                $params[':nome'] = '%' . $search . '%';
                $params[':label'] = '%' . $search . '%';
            }

            $sql .= " ORDER BY nome ASC";
            $sql .= " LIMIT " . (int) $limit . " OFFSET " . (int) $offset;

            $stmt = $this->pdo->prepare($sql);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, \PDO::PARAM_STR);
            }

            $stmt->execute();
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($rows as $key => &$row) {
                $rows[$key]['permissoes'] = $this->getPermissionsByProfile($row['id']);
            }

            // Transformar cada linha em um objeto Perfil
            return array_map([$this, 'mapRowToModel'], $rows);
        } catch (PDOException $e) {
            // Log the error message
            error_log($e->getMessage());
            return [];
        }
    }

    public function getPermissionsByProfile($perfilId) {
        $stmt = $this->pdo->prepare("SELECT nome FROM permissoes 
            JOIN permissao_perfil ON permissoes.id = permissao_perfil.permissao_id
            WHERE permissao_perfil.perfil_id = ?");
        $stmt->execute([$perfilId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Mapeia a linha do banco de dados para o objeto Perfil
    protected function mapRowToModel(array $row)
    {
        $model = new self();
        $model->id = htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8');
        $model->nome = htmlspecialchars($row['nome'], ENT_QUOTES, 'UTF-8');
        $model->label = htmlspecialchars($row['label'], ENT_QUOTES, 'UTF-8');
        $model->descricao = htmlspecialchars($row['descricao'], ENT_QUOTES, 'UTF-8');
        $model->permissoes = $row['permissoes'] ?? [];
        return $model;
    }

    // Retorna a contagem de perfis
    public function countPerfis($search = '')
    {
        try {
            $sql = "SELECT COUNT(*) FROM " . $this->table_name;
            $params = [];

            if ($search) {
                $sql .= " WHERE nome LIKE :nome OR label LIKE :label";
                $params[':nome'] = '%' . $search . '%';
                $params[':label'] = '%' . $search . '%';
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

    public function getPermissoes()
    {
        try {
            $sql = "SELECT id, nome, label, agrupamento FROM permissoes";
            $stmt = $this->pdo->prepare($sql);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log the error message
            error_log($e->getMessage());
            return 0;
        }
    }

    // Busca uma perfil por ID
    public function getById($id)
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = ?');
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $row['permissoes'] = $this->getPermissionsByProfile($row['id']);

            return $row ? $this->mapRowToModel($row) : null;
        } catch (PDOException $e) {
            // Log the error message
            error_log($e->getMessage());
            return null;
        }
    }

    // Cria uma nova perfil
    public function create($nome, $label, $descricao)
    {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO ' . $this->table_name . ' (nome, label, descricao) VALUES (?, ?, ?)');
            return $stmt->execute([$nome, $label, $descricao]);
        } catch (PDOException $e) {
            // Log the error message
            error_log($e->getMessage());
            return false;
        }
    }

    // Atualiza uma perfil existente
    public function update($id, $nome, $label, $descricao)
    {
        try {
            $stmt = $this->pdo->prepare('UPDATE ' . $this->table_name . ' SET nome = ?, label = ?, descricao = ? WHERE id = ?');
            return $stmt->execute([$nome, $label, $descricao, $id]);
        } catch (PDOException $e) {
            // Log the error message
            error_log($e->getMessage());
            return false;
        }
    }

    // Deleta uma perfil
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
