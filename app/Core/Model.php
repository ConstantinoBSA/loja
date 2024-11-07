<?php

namespace App\Core;

use InvalidArgumentException;
use PDO;

class Model
{
    protected $pdo;
    protected $tableName;
    protected $query;
    protected $bindings = [];
    protected $selectColumns = '*';
    protected $fillable = [];

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
        $this->resetQuery();
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function fill(array $attributes)
    {
        $filteredAttributes = array_intersect_key($attributes, array_flip($this->fillable));

        foreach ($filteredAttributes as $key => $value) {
            $this->$key = $value;
        }
    }

    public function select(...$columns)
    {
        $this->selectColumns = implode(', ', $columns);
        $this->query = "SELECT {$this->selectColumns} FROM {$this->tableName}";
        return $this;
    }

    public function distinct()
    {
        $this->query = str_replace('SELECT', 'SELECT DISTINCT', $this->query);
        return $this;
    }

    public function groupBy(...$columns)
    {
        $this->query .= ' GROUP BY ' . implode(', ', $columns);
        return $this;
    }

    public function where($column, $operator = '=', $value = null, $boolean = 'AND')
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $this->validateOperator($operator);

        if ($operator === 'LIKE') {
            $value = '%' . $value . '%';
        }

        $this->bindings[] = $value;
        $clause = count($this->bindings) === 1 ? ' WHERE ' : " $boolean ";
        $this->query .= "$clause $column $operator ?";
        return $this;
    }

    public function orWhere($column, $operator = '=', $value = null)
    {
        return $this->where($column, $operator, $value, 'OR');
    }

    public function first()
    {
        $this->query .= ' LIMIT 1';
        $stmt = $this->executeQuery();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $object = new static();
            $object->fillFromDatabase($result);
            return $object;
        }

        return null;
    }

    public function innerJoin($table, $first, $operator, $second)
    {
        $this->query .= " INNER JOIN $table ON $first $operator $second";
        return $this;
    }

    public function leftJoin($table, $first, $operator, $second)
    {
        $this->query .= " LEFT JOIN $table ON $first $operator $second";
        return $this;
    }

    public function rightJoin($table, $first, $operator, $second)
    {
        $this->query .= " RIGHT JOIN $table ON $first $operator $second";
        return $this;
    }

    public function fullJoin($table, $first, $operator, $second)
    {
        $this->query .= " FULL JOIN $table ON $first $operator $second";
        return $this;
    }

    public function get()
    {
        $stmt = $this->executeQuery();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $objects = [];
        foreach ($results as $result) {
            $object = new static();
            $object->fillFromDatabase($result); // Preenche usando o resultado do banco de dados
            $objects[] = $object;
        }

        return $objects;
    }

    public function paginate($perPage = 10, $currentPage = 1)
    {
        // Calcular o deslocamento para a paginação
        $offset = ($currentPage - 1) * $perPage;

        // Modifique a consulta para incluir LIMIT e OFFSET
        $paginatedQuery = $this->query . " LIMIT :limit OFFSET :offset";

        // Prepare a consulta
        $stmt = $this->pdo->prepare($paginatedQuery);

        // Vincule valores nomeados para LIMIT e OFFSET
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        // Execute a consulta. Se bindings estiver vazio, não há problema.
        $stmt->execute(); // Remova o $this->bindings se não houver parâmetros a serem vinculados

        // Obtenha os resultados
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Query para contar o número total de registros (sem LIMIT e OFFSET)
        $totalQuery = str_replace("SELECT {$this->selectColumns}", 'SELECT COUNT(*) as total', $this->query);
        $totalStmt = $this->pdo->prepare($totalQuery);
        // Execute a consulta para contar o total de registros
        $totalStmt->execute($this->bindings); // Pode continuar usando $this->bindings aqui se for relevante

        $totalRecords = (int)$totalStmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Calcular o número total de páginas
        $totalPages = (int)ceil($totalRecords / $perPage);

        // Criar objetos do modelo a partir dos resultados
        $objects = [];
        foreach ($results as $result) {
            $object = new static();
            $object->fillFromDatabase($result);
            $objects[] = $object;
        }

        // Retornar dados e informações de paginação
        return [
            'data' => $objects,
            'pagination' => [
                'current_page' => $currentPage,
                'per_page' => $perPage,
                'total_records' => $totalRecords,
                'total_pages' => $totalPages,
            ],
        ];
    }

    public function count()
    {
        $this->query = str_replace('SELECT *', 'SELECT COUNT(*) as count', $this->query);
        return $this->executeQuery()->fetchColumn();
    }

    public function sum($column)
    {
        return $this->aggregate('SUM', $column);
    }

    public function avg($column)
    {
        return $this->aggregate('AVG', $column);
    }

    public function min($column)
    {
        return $this->aggregate('MIN', $column);
    }

    public function max($column)
    {
        return $this->aggregate('MAX', $column);
    }

    private function aggregate($function, $column)
    {
        $query = str_replace('SELECT *', "SELECT {$function}({$column}) as aggregate", $this->query);
        return $this->executeQuery($query)->fetchColumn();
    }

    private function executeQuery($query = null)
    {
        if ($query === null) {
            $query = $this->query;
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($this->bindings);
        return $stmt;
    }

    public function hasOne($relatedClass, $foreignKey, $localKey)
    {
        $instance = new $relatedClass($this->pdo);
        $query = "SELECT * FROM {$instance->getTableName()} WHERE {$foreignKey} = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$this->$localKey]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $object = new $relatedClass();
            $object->fillFromDatabase($result);
            return $object;
        }

        return null;
    }

    public function hasMany($relatedClass, $foreignKey, $localKey)
    {
        $instance = new $relatedClass($this->pdo);
        $query = "SELECT * FROM {$instance->getTableName()} WHERE {$foreignKey} = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$this->$localKey]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $objects = [];
        foreach ($results as $result) {
            $object = new $relatedClass();
            $object->fillFromDatabase($result);
            $objects[] = $object;
        }

        return $objects;
    }

    public function belongsTo($relatedClass, $foreignKey, $ownerKey)
    {
        $instance = new $relatedClass($this->pdo);
        $query = "SELECT * FROM {$instance->getTableName()} WHERE {$ownerKey} = ? LIMIT 1";
        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$this->$foreignKey]); // Usa o valor da chave estrangeira para executar a consulta

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $object = new $relatedClass();
            $object->fillFromDatabase($result); // Preenche o objeto relacionado após obter os dados
            return $object;
        }

        return null;
    }

    public function belongsToMany($relatedClass, $pivotTable, $foreignKey, $relatedKey, $localKey, $relatedLocalKey)
    {
        $instance = new $relatedClass($this->pdo);
        $query = "SELECT {$instance->getTableName()}.* FROM {$instance->getTableName()}
                  JOIN {$pivotTable} ON {$instance->getTableName()}.{$relatedLocalKey} = {$pivotTable}.{$relatedKey}
                  WHERE {$pivotTable}.{$foreignKey} = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$this->$localKey]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $objects = [];
        foreach ($results as $result) {
            $object = new $relatedClass();
            $object->fillFromDatabase($result);
            $objects[] = $object;
        }

        return $objects;
    }

    public function with($relations)
    {
        if (is_string($relations)) {
            $relations = [$relations];
        }

        foreach ($relations as $relation) {
            if (method_exists($this, $relation)) {
                $relatedData = $this->$relation();
                $property = strtolower($relation);  // Nome da relação como propriedade
                $this->$property = $relatedData;
            }
        }
        return $this;
    }

    /**
     * Retorna todos os registros da tabela.
     */
    public function all()
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->tableName}");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $objects = [];
        foreach ($results as $result) {
            $object = new static();
            $object->fillFromDatabase($result);
            $objects[] = $object;
        }

        return $objects;
    }

    /**
     * Encontra um registro pelo seu ID.
     */
    public function find($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->tableName} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $object = new static();
            $object->fillFromDatabase($result);
            return $object;
        }

        return null;
    }

    /**
     * Cria um novo registro no banco de dados.
     */
    public function create(array $data)
    {
        $filteredData = array_intersect_key($data, array_flip($this->fillable));

        $columns = implode(', ', array_keys($filteredData));
        $placeholders = ':' . implode(', :', array_keys($filteredData));

        $sql = "INSERT INTO {$this->tableName} ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($filteredData);
    }

    /**
     * Atualiza um registro existente.
     */
    public function update($id, array $data)
    {
        $filteredData = array_intersect_key($data, array_flip($this->fillable));

        $fields = '';
        foreach ($filteredData as $column => $value) {
            $fields .= "{$column} = :{$column}, ";
        }
        $fields = rtrim($fields, ', ');

        $sql = "UPDATE {$this->tableName} SET {$fields} WHERE id = :id";
        $filteredData['id'] = $id;  // Adiciona o id à lista de parâmetros
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($filteredData);
    }

    /**
     * Deleta um registro do banco de dados.
     */
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->tableName} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    private function validateOperator($operator)
    {
        $validOperators = ['=', '>=', '>', '<', '<=', 'LIKE'];
        if (!in_array($operator, $validOperators, true)) {
            throw new InvalidArgumentException("Operador inválido: $operator");
        }
    }

    private function fillFromDatabase($dataArray)
    {       
        foreach ($dataArray as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->$key = $value;
            }
        }
    }

    public function resetQuery()
    {
        $this->selectColumns = '*';
        $this->query = "SELECT {$this->selectColumns} FROM {$this->tableName}";
        $this->bindings = [];
    }
}
