<?php

namespace App\Controllers\Site;

use App\Core\Controller;
use App\Models\Site\Produto;

class PromocaoController extends Controller
{
    public function index()
    {
        $search = $_GET['search'] ?? '';
        $page = $_GET['page'] ?? 1;
        $order = $_GET['ordenar'] ?? 'mais-relevantes';
        $limit = 6;
        $offset = ($page - 1) * $limit;
        
        $produtoModel = new Produto();
        $totalProdutos = $produtoModel->countPromocoes($search);
        $totalPages = ceil($totalProdutos / $limit);
        
        $produtos = $produtoModel->promocoesAll($search, $order, $limit, $offset);

        $start = $offset + 1;
        $end = min($offset + $limit, $totalProdutos);

        $this->view('site/promocoes/index', [
            'produtos' => $produtos,
            'start' => $start,
            'end' => $end,
            'currentPage' => $page,
            'totalProdutos' => $totalProdutos,
            'totalPages' => $totalPages,
            'search' => $search,
            'order' => $order
        ]);
    }

    public function detalhes($slug)
    {
        $produtoModel = new Produto();
        $produto = $produtoModel->produtoSingle($slug);

        $this->view('site/promocoes/detalhes', [
            'produto' => $produto
        ]);
    }
}
