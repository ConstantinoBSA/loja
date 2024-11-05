<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Validator;
use App\Core\Sanitizer;
use App\Core\AuditLogger;
use App\Models\Categoria;
use App\Models\Produto;

class ProdutoController extends Controller
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
        $limit = 10; // Número de produtos por página
        $offset = ($page - 1) * $limit;
        
        $produtoModel = new Produto();
        $produtos = $produtoModel->getAll($search, $limit, $offset);
        $totalProdutos = $produtoModel->countProdutos($search);
        $totalPages = ceil($totalProdutos / $limit);

        $start = $offset + 1;
        $end = min($offset + $limit, $totalProdutos);

        $this->view('admin/produtos/index', [
            'produtos' => $produtos,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'totalProdutos' => $totalProdutos,
            'start' => $start,
            'end' => $end,
            'search' => $search
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die('Token CSRF inválido.');
            }

            $nome = $this->sanitizer->sanitizeString($_POST['nome']);
            $slug = generateSlug($nome); // Gerar slug automaticamente
            $codigo = generateProductNumber(); // Gerar slug automaticamente
            $preco = (float) str_replace(['.', ','], ['', '.'], $_POST['preco']);
            $preco_promocional = (float) str_replace(['.', ','], ['', '.'], $_POST['preco_promocional']);

            $sanitizedData = [
                'nome' => $this->sanitizer->sanitizeString($_POST['nome']),
                'codigo' => $this->sanitizer->sanitizeString($codigo),
                'descricao' => $this->sanitizer->sanitizeString($_POST['descricao']),
                'preco' => $this->sanitizer->sanitizeString($preco),
                'preco_promocional' => $this->sanitizer->sanitizeString($preco_promocional),
                'codigo_barras' => $this->sanitizer->sanitizeString($_POST['codigo_barras']),
                'estoque' => $this->sanitizer->sanitizeString($_POST['estoque']),
                'slug' => $this->sanitizer->sanitizeString($slug ),
                'imagem' => null,
                'categoria_id' => $this->sanitizer->sanitizeString($_POST['categoria_id']),
                'informacoes_relevantes' => $this->sanitizer->sanitizeString($_POST['informacoes_relevantes']),
                'data_lancamento' => $this->sanitizer->sanitizeString($_POST['data_lancamento']),
                'pontos' => $this->sanitizer->sanitizeString($_POST['pontos']),
                'promocao' => $this->sanitizer->sanitizeString($_POST['promocao']),
                'destaque' => $this->sanitizer->sanitizeString($_POST['destaque']),
                'status' => $this->sanitizer->sanitizeString($_POST['status']),
            ];

            $rules = [
                'nome' => 'required|unique:produtos',
                'descricao' => 'required',
                'preco' => 'required',
                'preco_promocional' => 'required',
                'estoque' => 'required',
                'categoria_id' => 'required',
                'informacoes_relevantes' => 'required',
                'data_lancamento' => 'required',
                'pontos' => 'required',
                'status' => 'required'
            ];

            $errors = $this->validator->validate($sanitizedData, $rules);

            if (!empty($errors)) {
                $categoriaModel = new Categoria();
                $categorias = $categoriaModel->getAllActive();

                $this->view('admin/produtos/create', ['error' => $errors, 'data' => $sanitizedData, 'categorias' => $categorias]);
            } else {
                $produtoModel = new Produto();
                $produto = $produtoModel->create(
                    $sanitizedData['nome'], 
                    $sanitizedData['codigo'],
                    $sanitizedData['descricao'],
                    $sanitizedData['preco'],
                    $sanitizedData['preco_promocional'],
                    $sanitizedData['codigo_barras'],
                    $sanitizedData['estoque'],
                    $sanitizedData['slug'],
                    $sanitizedData['imagem'],
                    $sanitizedData['categoria_id'],
                    $sanitizedData['informacoes_relevantes'],
                    $sanitizedData['data_lancamento'],
                    $sanitizedData['pontos'],
                    $sanitizedData['promocao'],
                    $sanitizedData['destaque'],
                    $sanitizedData['status']
                );
                if ($produto) {
                    $_SESSION['message'] = "Registro adicionaado com sucesso!";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['message'] = 'Erro ao adicionar produto. Por favor, tente novamente!';
                    $_SESSION['message_type'] = "success";
                }

                // Token válido, remova-o da sessão
                unset($_SESSION['csrf_token']);

                header('Location: /admin/produtos/index');
            }
        } else {
            $categoriaModel = new Categoria();
            $categorias = $categoriaModel->getAllActive();

            $this->view('admin/produtos/create', ['error' => [], 'data' => [], 'categorias' => $categorias]);
        }
    }

    public function edit($id)
    {
        $produtoModel = new Produto();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die('Token CSRF inválido.');
            }

            $nome = $this->sanitizer->sanitizeString($_POST['nome']);
            $slug = generateSlug($nome); // Gerar slug automaticamente
            $codigo = generateProductNumber(); // Gerar slug automaticamente
            $preco = (float) str_replace(['.', ','], ['', '.'], $_POST['preco']);
            $preco_promocional = (float) str_replace(['.', ','], ['', '.'], $_POST['preco_promocional']);

            $sanitizedData = [
                'nome' => $this->sanitizer->sanitizeString($_POST['nome']),
                'codigo' => $this->sanitizer->sanitizeString($codigo),
                'descricao' => $this->sanitizer->sanitizeString($_POST['descricao']),
                'preco' => $this->sanitizer->sanitizeString($preco),
                'preco_promocional' => $this->sanitizer->sanitizeString($preco_promocional),
                'codigo_barras' => $this->sanitizer->sanitizeString($_POST['codigo_barras']),
                'estoque' => $this->sanitizer->sanitizeString($_POST['estoque']),
                'slug' => $this->sanitizer->sanitizeString($slug ),
                'imagem' => null,
                'categoria_id' => $this->sanitizer->sanitizeString($_POST['categoria_id']),
                'informacoes_relevantes' => $this->sanitizer->sanitizeString($_POST['informacoes_relevantes']),
                'data_lancamento' => $this->sanitizer->sanitizeString($_POST['data_lancamento']),
                'pontos' => $this->sanitizer->sanitizeString($_POST['pontos']),
                'promocao' => $this->sanitizer->sanitizeString($_POST['promocao']),
                'destaque' => $this->sanitizer->sanitizeString($_POST['destaque']),
                'status' => $this->sanitizer->sanitizeString($_POST['status']),
            ];

            $rules = [
                'nome' => 'required|unique:produtos,nome,'.$id,
                'descricao' => 'required',
                'preco' => 'required',
                'preco_promocional' => 'required',
                'estoque' => 'required',
                'categoria_id' => 'required',
                'informacoes_relevantes' => 'required',
                'data_lancamento' => 'required',
                'pontos' => 'required',
                'status' => 'required'
            ];

            $errors = $this->validator->validate($sanitizedData, $rules);

            if (!empty($errors)) {
                $categoriaModel = new Categoria();
                $categorias = $categoriaModel->getAllActive();

                $data = array_merge($_POST, ['id' => $id]);
                $this->view('admin/produtos/edit', ['error' => $errors, 'data' => $data, 'categorias' => $categorias]);
            } else {
                $produto = $produtoModel->update(
                    $id,
                    $sanitizedData['nome'], 
                    $sanitizedData['codigo'],
                    $sanitizedData['descricao'],
                    $sanitizedData['preco'],
                    $sanitizedData['preco_promocional'],
                    $sanitizedData['codigo_barras'],
                    $sanitizedData['estoque'],
                    $sanitizedData['slug'],
                    $sanitizedData['imagem'],
                    $sanitizedData['categoria_id'],
                    $sanitizedData['informacoes_relevantes'],
                    $sanitizedData['data_lancamento'],
                    $sanitizedData['pontos'],
                    $sanitizedData['promocao'],
                    $sanitizedData['destaque'],
                    $sanitizedData['status']
                );
                if ($produto) {
                    $_SESSION['message'] = "Registro editado com sucesso!";
                    $_SESSION['message_type'] = "success";

                    $this->auditLogger->log(auth()->user()->id, 'Descrição da ação', 'Detalhes adicionais sobre a ação');
                } else {
                    $_SESSION['message'] = 'Erro ao editar produto. Por favor, tente novamente!';
                    $_SESSION['message_type'] = "success";
                }

                // Token válido, remova-o da sessão
                unset($_SESSION['csrf_token']);

                header('Location: /admin/produtos/index');
            }
        } else {
            $categoriaModel = new Categoria();
            $categorias = $categoriaModel->getAllActive();

            $produto = $produtoModel->getById($id);
            if ($produto) {
                $produto['preco'] = number_format($produto['preco'], 2, ',', '.');
                $produto['preco_promocional'] = number_format($produto['preco_promocional'], 2, ',', '.');
                
                $this->view('admin/produtos/edit', ['error' => [], 'data' => $produto, 'categorias' => $categorias]);
            } else {
                $this->renderErrorPage('Erro 404', 'Produto não encontrada.');
            }
        }
    }

    public function show($id)
    {
        $produtoModel = new Produto();
        $produto = $produtoModel->getById($id);
        if ($produto) {
            $this->view('admin/produtos/show', ['produto' => $produto]);
        } else {
            $this->renderErrorPage('Erro 404', 'Produto não encontrada.');
        }
    }

    public function delete($id)
    {
        $produtoModel = new Produto();
        $produto = $produtoModel->delete($id);
        if ($produto) {
            $_SESSION['message'] = "Registro deletado com sucesso!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = 'Erro ao editar deletar. Por favor, tente novamente!';
            $_SESSION['message_type'] = "success";
        }
        header('Location: /admin/produtos/index');
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
