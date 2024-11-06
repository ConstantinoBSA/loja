<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    private $categoriaModel;

    public function __construct()
    {
        parent::__construct();
        $this->categoriaModel = new Categoria();

        if (!$this->hasPermission('categorias')) {
            abort('403', 'Você não tem acesso a está área do sistema');
        }
    }

    public function index()
    {
        try {
            $search = $this->request->all()['search'] ?? '';
            $page = $this->request->all()['page'] ?? 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;

            $categorias = $this->categoriaModel->getAll($search, $limit, $offset);
            $totalCategorias = $this->categoriaModel->countCategorias($search);
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
        } catch (\Exception $e) {
            $this->handleException($e, 'Ocorreu um erro ao obter categorias.');
        }
    }

    public function create()
    {
        $errors = $_SESSION['errors'] ?? [];
        $oldData = $_SESSION['old_data'] ?? [];

        unset($_SESSION['errors'], $_SESSION['old_data']);

        $this->view('admin/categorias/create', [
            'errors' => $errors,
            'data' => $oldData
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dadosRequisicao = $this->request->all();

            if (!$this->validateCsrfToken($dadosRequisicao['csrf_token'])) {
                $this->redirectToWithMessage('/admin/categorias/adicionar', 'Token CSRF inválido.', 'error');
            }

            $sanitizedData = $this->sanitizeData($dadosRequisicao);

            $rules = [
                'nome' => 'required|unique:categorias',
                'status' => 'required'
            ];

            $errors = $this->validator->validate($sanitizedData, $rules);

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old_data'] = $dadosRequisicao;
                $this->redirect('/admin/categorias/adicionar');
            } else {
                try {
                    $categoria = $this->categoriaModel->create($sanitizedData['nome'], $sanitizedData['status']);
                    if ($categoria) {
                        $this->redirectToWithMessage('/admin/categorias/index', 'Registro adicionado com sucesso!', 'success');
                    } else {
                        $this->redirectToWithMessage('/admin/categorias/adicionar', 'Erro ao adicionar categoria. Por favor, tente novamente!', 'error');
                    }
                } catch (\Exception $e) {
                    $this->handleException($e, 'Erro ao adicionar a categoria.');
                }
            }
        }
    }

    public function edit($id)
    {
        try {
            $categoria = $this->categoriaModel->getById($id);
            if (!$categoria) {
                throw new \Exception('Categoria não encontrada.', 404);
            }

            $errors = $_SESSION['errors'] ?? [];
            $oldData = $_SESSION['old_data'] ?? [];

            unset($_SESSION['errors'], $_SESSION['old_data']);

            foreach ($oldData as $key => $value) {
                if (property_exists($categoria, $key)) {
                    $categoria->$key = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                }
            }

            $this->view('admin/categorias/edit', [
                'categoria' => $categoria,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            $this->renderErrorPage('Erro 404', $e->getMessage());
        }
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dadosRequisicao = $this->request->all();

            if (!$this->validateCsrfToken($dadosRequisicao['csrf_token'])) {
                $this->redirectToWithMessage('/admin/categorias/editar/' . $id, 'Token CSRF inválido.', 'error');
            }

            $sanitizedData = $this->sanitizeData($dadosRequisicao);

            $rules = [
                'nome' => "required|unique:categorias,nome,{$id}",
                'status' => 'required'
            ];

            $errors = $this->validator->validate($sanitizedData, $rules);

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old_data'] = $dadosRequisicao;
                $this->redirect('/admin/categorias/editar/' . $id);
            } else {
                try {
                    $categoria = $this->categoriaModel->update($id, $sanitizedData['nome'], $sanitizedData['status']);
                    if ($categoria) {
                        $this->redirectToWithMessage('/admin/categorias/index', 'Registro editado com sucesso!', 'success');
                    } else {
                        $this->redirectToWithMessage('/admin/categorias/editar/' . $id, 'Erro ao editar categoria. Por favor, tente novamente!', 'error');
                    }
                } catch (\Exception $e) {
                    $this->handleException($e, 'Erro ao editar a categoria.');
                }
            }
        }
    }

    public function show($id)
    {
        try {
            $categoria = $this->categoriaModel->getById($id);
            if ($categoria) {
                $this->view('admin/categorias/show', ['categoria' => $categoria]);
            } else {
                $this->renderErrorPage('Erro 404', 'Categoria não encontrada.');
            }
        } catch (\Exception $e) {
            $this->renderErrorPage('Erro 404', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $categoria = $this->categoriaModel->delete($id);
            if ($categoria) {
                $this->redirectToWithMessage('/admin/categorias/index', 'Registro deletado com sucesso!', 'success');
            } else {
                $this->redirectToWithMessage('/admin/categorias/index', 'Erro ao deletar categoria. Por favor, tente novamente!', 'error');
            }
        } catch (\Exception $e) {
            $this->handleException($e, 'Erro ao deletar a categoria.');
        }
    }

    public function status($id)
    {
        try {
            $categoria = $this->categoriaModel->alterarStatus($id);
            if ($categoria) {
                $this->redirectToWithMessage('/admin/categorias/index', 'Status alterado com sucesso!', 'success');
            } else {
                $this->redirectToWithMessage('/admin/categorias/index', 'Erro ao alterar status. Por favor, tente novamente!', 'error');
            }
        } catch (\Exception $e) {
            $this->handleException($e, 'Erro ao alterar status.');
        }
    }

    private function renderErrorPage($title, $message)
    {
        $errorTitle = $title;
        $errorMessage = $message;
        include __DIR__ . '/../../../resources/views/errors/default.php';
        exit();
    }

    private function sanitizeData($data)
    {
        return [
            'nome' => $this->sanitizer->sanitizeString($data['nome']),
            'status' => $this->sanitizer->sanitizeString($data['status']),
        ];
    }
}
