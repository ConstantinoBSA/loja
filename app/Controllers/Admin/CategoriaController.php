<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Validator;
use App\Core\Sanitizer;
use App\Core\AuditLogger;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    protected $sanitizer;
    protected $validator;

    public function __construct()
    {
        $this->sanitizer = new Sanitizer();
        $this->validator = new Validator();
        $this->auditLogger = new AuditLogger();
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';
        $page = $_GET['page'] ?? 1;
        $limit = 10; // Número de categorias por página
        $offset = ($page - 1) * $limit;
        
        $categoriaModel = new Categoria();
        $categorias = $categoriaModel->getAll($search, $limit, $offset);
        $totalCategorias = $categoriaModel->countCategorias($search);
        $totalPages = ceil($totalCategorias / $limit);

        $start = $offset + 1;
        $end = min($offset + $limit, $totalCategorias);

        $this->view('admin/categorias/index', [
            'categorias' => $categorias,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'totalCategorias' => $totalCategorias,
            'start' => $start,
            'end' => $end,
            'search' => $search
        ]);
    }

    public function create()
    {
        $this->view('admin/categorias/create', ['error' => [], 'data' => []]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die('Token CSRF inválido.');
            }

            $sanitizedData = [
                'nome' => $this->sanitizer->sanitizeString($_POST['nome']),
                'slug' => $this->sanitizer->sanitizeString($_POST['slug']),
                'status' => $this->sanitizer->sanitizeString($_POST['status']),
            ];

            $rules = [
                'nome' => 'required|unique:categorias',
                'slug' => 'required',
                'status' => 'required'
            ];

            $errors = $this->validator->validate($sanitizedData, $rules);

            if (!empty($errors)) {
                $this->view('admin/categorias/create', ['error' => $errors, 'data' => $sanitizedData]);
            } else {
                $categoriaModel = new Categoria();
                $categoria = $categoriaModel->create($sanitizedData['nome'], $sanitizedData['slug'], $sanitizedData['status']);
                if ($categoria) {
                    $_SESSION['message'] = "Registro adicionaado com sucesso!";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['message'] = 'Erro ao adicionar categoria. Por favor, tente novamente!';
                    $_SESSION['message_type'] = "success";
                }

                // Token válido, remova-o da sessão
                unset($_SESSION['csrf_token']);

                header('Location: /admin/categorias/index');
            }
        }
    }

    public function edit($id)
    {
        $categoriaModel = new Categoria();
        $categoria = $categoriaModel->getById($id);
        if ($categoria) {
            $this->view('admin/categorias/edit', ['error' => [], 'data' => $categoria]);
        } else {
            $this->renderErrorPage('Erro 404', 'Categoria não encontrada.');
        }
    }

    public function update($id)
    {
        $categoriaModel = new Categoria();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die('Token CSRF inválido.');
            }

            $rules = [
                'nome' => 'required|unique:categorias,nome,'.$id,
                'slug' => 'required',
                'status' => 'required'
            ];

            $errors = $this->validator->validate($_POST, $rules);

            if (!empty($errors)) {
                $data = array_merge($_POST, ['id' => $id]);
                $this->view('admin/categorias/edit', ['error' => $errors, 'data' => $data]);
            } else {
                $categoria = $categoriaModel->update($id, $_POST['nome'], $_POST['slug'], $_POST['status']);
                if ($categoria) {
                    $_SESSION['message'] = "Registro editado com sucesso!";
                    $_SESSION['message_type'] = "success";

                    $this->auditLogger->log(auth()->user()->id, 'Descrição da ação', 'Detalhes adicionais sobre a ação');
                } else {
                    $_SESSION['message'] = 'Erro ao editar categoria. Por favor, tente novamente!';
                    $_SESSION['message_type'] = "success";
                }

                // Token válido, remova-o da sessão
                unset($_SESSION['csrf_token']);

                header('Location: /admin/categorias/index');
            }
        }
    }

    public function show($id)
    {
        $categoriaModel = new Categoria();
        $categoria = $categoriaModel->getById($id);
        if ($categoria) {
            $this->view('admin/categorias/show', ['categoria' => $categoria]);
        } else {
            $this->renderErrorPage('Erro 404', 'Categoria não encontrada.');
        }
    }

    public function delete($id)
    {
        $categoriaModel = new Categoria();
        $categoria = $categoriaModel->delete($id);
        if ($categoria) {
            $_SESSION['message'] = "Registro deletado com sucesso!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = 'Erro ao editar deletar. Por favor, tente novamente!';
            $_SESSION['message_type'] = "success";
        }
        header('Location: /admin/categorias/index');
    }

    // Função para renderizar a view de erro
    private function renderErrorPage($title, $message)
    {
        $errorTitle = $title;
        $errorMessage = $message;
        include '../resources/views/error.php';
        exit();
    }
}
