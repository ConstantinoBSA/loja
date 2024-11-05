<?php

namespace App\Models\System;

use App\Core\Model;

class Tenant extends Model
{
    private $table_name = "tenants";

    public $id;
    public $name;
    public $database_name;
    public $database_user;
    public $database_password;

    public function getTenantByName($name) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE name = ? LIMIT 0,1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(1, $name);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            $this->name = $row['name'];
            $this->database_name = $row['database_name'];
            $this->database_user = $row['database_user'];
            $this->database_password = $row['database_password'];
            return true;
        }

        return false;
    }
}
