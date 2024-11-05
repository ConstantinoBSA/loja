<?php

namespace App\Controllers\Site;

use App\Core\Controller;
use App\Models\Site\Produto;

class PerfumariaController extends Controller
{
    public function index()
    {
        $search = $_GET['search'] ?? '';
        $page = $_GET['page'] ?? 1;
        $order = $_GET['ordenar'] ?? 'mais-relevantes';
        $limit = 6;
        $offset = ($page - 1) * $limit;
        
        $kitModel = new Produto();
        $totalProdutos = $kitModel->countPerfumarias($search);
        $totalPages = ceil($totalProdutos / $limit);
        
        $produtos = $kitModel->perfumariasAll($search, $order, $limit, $offset);

        $start = $offset + 1;
        $end = min($offset + $limit, $totalProdutos);

        $this->view('site/perfumarias/index', [
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

        $this->view('site/perfumarias/detalhes', [
            'produto' => $produto
        ]);
    }
}
