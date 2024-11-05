<?php

namespace App\Models\System;

use App\Core\Model;

class Usuario extends Model
{
    private $table_name = "usuarios";

    public $id;
    public $nome;
    public $email;
    public $senha;

    // Método para listar todos os usuários
    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Método para criar um usuário
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET nome=:nome, email=:email, senha=:senha";
        $stmt = $this->pdo->prepare($query);

        // Limpeza de dados
        $this->nome=htmlspecialchars(strip_tags($this->nome));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->senha=htmlspecialchars(strip_tags($this->senha));

        // Bind de parâmetros
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":senha", password_hash($this->senha, PASSWORD_BCRYPT));

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para atualizar um usuário
    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET nome = :nome, email = :email, senha = :senha WHERE id = :id";
        $stmt = $this->pdo->prepare($query);

        // Limpeza de dados
        $this->nome=htmlspecialchars(strip_tags($this->nome));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->senha=htmlspecialchars(strip_tags($this->senha));

        // Bind de parâmetros
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":senha", password_hash($this->senha, PASSWORD_BCRYPT));
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para deletar um usuário
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->pdo->prepare($query);

        // Limpeza e bind de dado
        $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
