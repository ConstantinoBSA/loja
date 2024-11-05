<?php

namespace App\Controllers\Site;

use App\Core\Controller;
use App\Models\Site\Kit;
use App\Models\Site\Produto;

class KitController extends Controller
{
    public function index()
    {
        $search = $_GET['search'] ?? '';
        $page = $_GET['page'] ?? 1;
        $order = $_GET['ordenar'] ?? 'mais-relevantes';
        $limit = 6;
        $offset = ($page - 1) * $limit;
        
        $kitModel = new Kit();
        $totalKits = $kitModel->countKits($search);
        $totalPages = ceil($totalKits / $limit);
        
        $kits = $kitModel->kitsAll($search, $order, $limit, $offset);

        $start = $offset + 1;
        $end = min($offset + $limit, $totalKits);

        $this->view('site/kits/index', [
            'kits' => $kits,
            'start' => $start,
            'end' => $end,
            'currentPage' => $page,
            'totalKits' => $totalKits,
            'totalPages' => $totalPages,
            'search' => $search,
            'order' => $order
        ]);
    }

    public function detalhes($slug)
    {
        $kitModel = new Kit();
        $kit = $kitModel->kitDetalhes($slug);
        $produtos = $kitModel->produtosKit($kit['id']);

        $this->view('site/kits/detalhes', [
            'kit' => $kit,
            'produtos' => $produtos,
        ]);
    }
}
