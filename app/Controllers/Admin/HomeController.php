<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Categoria;
use App\Models\Dashboard;
use App\Models\Kit;
use App\Models\Produto;
use App\Models\Venda;

class HomeController extends Controller
{
    public function index()
    {
        $venda = new Venda();
        $total_vendas = $venda->countVendas();

        $kit = new Kit();
        $total_kits = $kit->countKits();

        $produto = new Produto();
        $total_produtos = $produto->countProdutos();

        $categoria = new Categoria();
        $total_categorias = $categoria->countCategorias();

        // Obter dados para o ano atual
        $anoAtual = date('Y');
        $dashboardModel = new Dashboard();
        $vendasPorMes = $dashboardModel->getVendasPorMes($anoAtual);

        $meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
        $dadosVendas = array_fill(0, 12, 0);

        foreach ($vendasPorMes as $mes => $total) {
            $dadosVendas[$mes - 1] = (float) $total; // Subtrai 1 de $mes porque $meses é indexado de 0 a 11
        }

        $this->view('admin/home', [
            'total_vendas' => $total_vendas,
            'total_kits' => $total_kits,
            'total_produtos' => $total_produtos,
            'total_categorias' => $total_categorias,
            'dadosVendas' => $dadosVendas,
            'anoAtual' => $anoAtual,
            'meses' => $meses,
        ]);
    }

    public function perfil()
    {
        $this->view('admin/perfil');
    }

    public function configuracoes()
    {
        $this->view('admin/configuracoes');
    }
}
