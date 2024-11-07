<?php

namespace App\Models;

use App\Core\Model;

class Categoria extends Model
{
    protected $tableName = 'categorias';
    protected $fillable = ['id', 'nome', 'slug', 'created_at', 'updated_at'];

    public $id;
    public $nome;
    public $slug;
    public $status;
}
