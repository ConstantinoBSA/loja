<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Categoria;
use App\Models\Configuracao;
use App\Models\Dashboard;
use App\Models\Kit;
use App\Models\Produto;
use App\Models\Venda;

class HomeController extends Controller
{
    protected $configModel;

    public function __construct()
    {
        $this->configModel = new Configuracao();
    }

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
        $configuracoes = $this->configModel->getAllConfiguracoes();

        $this->view('admin/configuracoes', [
            'configuracoes' => $configuracoes
        ]);
    }

    public function salvarConfiguracoes()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['config'])) {
            $configs = $_POST['config'];
            foreach ($configs as $chave => $valor) {
                $this->configModel->updateConfiguracao($chave, $valor);
            }            
        }

        // Redirecionar ou mostrar mensagem de sucesso
        header('Location: /admin/configuracoes');
        exit();
    }
}
