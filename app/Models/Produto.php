<?php

namespace App\Models;

use App\Core\Model;

class Produto extends Model
{
    public function getAll($search = '', $limit = 10, $offset = 0)
    {
        $sql = "SELECT * FROM produtos";
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
        $produtos = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Sanitiza os dados
        return array_map(function($produto) {
            return [
                'id' => htmlspecialchars($produto['id'], ENT_QUOTES, 'UTF-8'),
                'nome' => htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8'),
                'slug' => htmlspecialchars($produto['slug'], ENT_QUOTES, 'UTF-8'),
                'status' => htmlspecialchars($produto['status'], ENT_QUOTES, 'UTF-8'),
            ];
        }, $produtos);
    }

    public function getProdutosVenda($search = '', $limit = 10, $offset = 0)
    {
        $sql = "SELECT * FROM produtos";
        $sql .= " ORDER BY nome ASC";
    
        $stmt = $this->pdo->prepare($sql);
    
        $stmt->execute();
        $produtos = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Sanitiza os dados
        return array_map(function($produto) {
            return [
                'id' => htmlspecialchars($produto['id'], ENT_QUOTES, 'UTF-8'),
                'nome' => htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8'),
                'slug' => htmlspecialchars($produto['slug'], ENT_QUOTES, 'UTF-8'),
                'preco' => htmlspecialchars($produto['preco'], ENT_QUOTES, 'UTF-8'),
                'status' => htmlspecialchars($produto['status'], ENT_QUOTES, 'UTF-8'),
            ];
        }, $produtos);
    }

    public function countProdutos($search = '')
    {
        $sql = "SELECT COUNT(*) FROM produtos";
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
        $stmt = $this->pdo->prepare('SELECT * FROM produtos WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create(
        $nome,
        $codigo,
        $descricao,
        $preco,
        $preco_promocional,
        $codigo_barras,
        $estoque,
        $slug,
        $imagem,
        $categoria_id,
        $informacoes_relevantes,
        $data_lancamento,
        $pontos,
        $promocao,
        $destaque,
        $status
    )
    {
        $stmt = $this->pdo->prepare('INSERT INTO produtos (nome, codigo, descricao, preco, preco_promocional, codigo_barras, estoque, slug, imagem, categoria_id, informacoes_relevantes, data_lancamento, pontos, promocao, destaque, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        return $stmt->execute([
            $nome,
            $codigo,
            $descricao,
            $preco,
            $preco_promocional,
            $codigo_barras,
            $estoque,
            $slug,
            $imagem,
            $categoria_id,
            $informacoes_relevantes,
            $data_lancamento,
            $pontos,
            $promocao,
            $destaque,
            $status
        ]);
    }

    public function update(
        $id,
        $nome,
        $codigo,
        $descricao,
        $preco,
        $preco_promocional,
        $codigo_barras,
        $estoque,
        $slug,
        $imagem,
        $categoria_id,
        $informacoes_relevantes,
        $data_lancamento,
        $pontos,
        $promocao,
        $destaque,
        $status
    )
    {
        $stmt = $this->pdo->prepare('UPDATE produtos SET nome = ?, codigo = ?, descricao = ?, preco = ?, preco_promocional = ?, codigo_barras = ?, estoque = ?, slug = ?, imagem = ?, categoria_id = ?, informacoes_relevantes = ?, data_lancamento = ?, pontos = ?, promocao = ?, destaque = ?, status = ? WHERE id = ?');
        return $stmt->execute([
            $nome,
            $codigo,
            $descricao,
            $preco,
            $preco_promocional,
            $codigo_barras,
            $estoque,
            $slug,
            $imagem,
            $categoria_id,
            $informacoes_relevantes,
            $data_lancamento,
            $pontos,
            $promocao,
            $destaque,
            $status,
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM produtos WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
