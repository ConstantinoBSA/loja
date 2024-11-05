<?php

namespace App\Controllers\Site;

use App\Core\Controller;
use App\Models\Site\Produto;

class DestaqueController extends Controller
{
    public function index()
    {
        $search = $_GET['search'] ?? '';
        $page = $_GET['page'] ?? 1;
        $order = $_GET['ordenar'] ?? 'mais-relevantes';
        $limit = 6;
        $offset = ($page - 1) * $limit;
        
        $produtoModel = new Produto();
        $totalProdutos = $produtoModel->countDestaques($search);
        $totalPages = ceil($totalProdutos / $limit);
        
        $produtos = $produtoModel->destaquesAll($search, $order, $limit, $offset);

        $start = $offset + 1;
        $end = min($offset + $limit, $totalProdutos);

        $this->view('site/destaques/index', [
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

        $this->view('site/destaques/detalhes', [
            'produto' => $produto
        ]);
    }
}
