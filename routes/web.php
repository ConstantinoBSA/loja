<?php

use App\Routing\Router;

use App\Controllers\Site\IndexController;
use App\Controllers\Site\CosmeticoController;
use App\Controllers\Site\PerfumariaController;
use App\Controllers\Site\KitController;
use App\Controllers\Site\PromocaoController;
use App\Controllers\Site\DestaqueController;

use App\Controllers\Admin\HomeController;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\CategoriaController;
use App\Controllers\Admin\FormaPagamentoController;
use App\Controllers\Admin\ClienteController;
use App\Controllers\Admin\ProdutoController;
use App\Controllers\Admin\KitController as KitAdminController;
use App\Controllers\Admin\VendaController;
use App\Controllers\Admin\UsuarioController;
use App\Controllers\Admin\ConsultaController;
use App\Controllers\Admin\RelatorioController;

use App\Controllers\Admin\PdvController;

$router = new Router();

// Site
$router->add('GET', '', [IndexController::class, 'index']);
$router->add('GET', 'cosmeticos', [CosmeticoController::class, 'index']);
$router->add('GET', 'cosmeticos/{slug}', [CosmeticoController::class, 'detalhes']);
$router->add('GET', 'perfumaria', [PerfumariaController::class, 'index']);
$router->add('GET', 'perfumaria/{slug}', [PerfumariaController::class, 'detalhes']);
$router->add('GET', 'kits', [KitController::class, 'index']);
$router->add('GET', 'kits/{slug}', [KitController::class, 'detalhes']);
$router->add('GET', 'promocoes', [PromocaoController::class, 'index']);
$router->add('GET', 'promocoes/{slug}', [PromocaoController::class, 'detalhes']);
$router->add('GET', 'destaques', [DestaqueController::class, 'index']);
$router->add('GET', 'destaques/{slug}', [DestaqueController::class, 'detalhes']);
$router->add('GET', 'contato', [IndexController::class, 'contato']);
$router->add('GET', 'enviar_contato', [IndexController::class, 'enviar_contato']);
$router->add('GET', 'processar_pedido', [IndexController::class, 'processar_pedido']);
$router->add('GET', 'subscribers', [IndexController::class, 'subscribers']);

// Admin
// Auth
$router->add('GET', 'admin/forgot-password', [AuthController::class, 'forgotPassword']);
$router->add('POST', 'admin/reset-password', [AuthController::class, 'resetPassword']);
$router->add('GET', 'admin/verify-required', [AuthController::class, 'verifyRequired']);
$router->add('POST', 'admin/verify-email', [AuthController::class, 'verifyEmail']);
$router->add('GET', 'admin/login', [AuthController::class, 'showLoginForm']);
$router->add('POST', 'admin/login', [AuthController::class, 'login']);
$router->add('GET', 'admin/logout', [AuthController::class, 'logout'], true);

$router->add('GET', 'admin', [HomeController::class, 'index'], true);
$router->add('GET', 'admin/perfil-usuario', [HomeController::class, 'perfil'], true);
$router->add('GET', 'admin/configuracoes', [HomeController::class, 'configuracoes'], true);

// Categorias
$router->add('GET', 'admin/categorias/index', [CategoriaController::class, 'index'], true);
$router->add('GET', 'admin/categorias/create', [CategoriaController::class, 'create'], true);
$router->add('GET', 'admin/categorias/edit/{id}', [CategoriaController::class, 'edit'], true);
$router->add('GET', 'admin/categorias/show/{id}', [CategoriaController::class, 'show'], true);
$router->add('GET', 'admin/categorias/delete/{id}', [CategoriaController::class, 'delete'], true);

// Formas de Pagamento
$router->add('GET', 'admin/formas_pagamento/index', [FormaPagamentoController::class, 'index'], true);
$router->add('GET', 'admin/formas_pagamento/create', [FormaPagamentoController::class, 'create'], true);
$router->add('GET', 'admin/formas_pagamento/edit/{id}', [FormaPagamentoController::class, 'edit'], true);
$router->add('GET', 'admin/formas_pagamento/show/{id}', [FormaPagamentoController::class, 'show'], true);
$router->add('GET', 'admin/formas_pagamento/delete/{id}', [FormaPagamentoController::class, 'delete'], true);

// Clientes
$router->add('GET', 'admin/clientes/index', [ClienteController::class, 'index'], true);
$router->add('GET', 'admin/clientes/create', [ClienteController::class, 'create'], true);
$router->add('GET', 'admin/clientes/edit/{id}', [ClienteController::class, 'edit'], true);
$router->add('GET', 'admin/clientes/show/{id}', [ClienteController::class, 'show'], true);
$router->add('GET', 'admin/clientes/delete/{id}', [ClienteController::class, 'delete'], true);

// Produtos
$router->add('GET', 'admin/produtos/index', [ProdutoController::class, 'index'], true);
$router->add('GET', 'admin/produtos/create', [ProdutoController::class, 'create'], true);
$router->add('GET', 'admin/produtos/edit/{id}', [ProdutoController::class, 'edit'], true);
$router->add('GET', 'admin/produtos/show/{id}', [ProdutoController::class, 'show'], true);
$router->add('GET', 'admin/produtos/delete/{id}', [ProdutoController::class, 'delete'], true);

// Kits
$router->add('GET', 'admin/kits/index', [KitAdminController::class, 'index'], true);
$router->add('GET', 'admin/kits/create', [KitAdminController::class, 'create'], true);
$router->add('GET', 'admin/kits/edit/{id}', [KitAdminController::class, 'edit'], true);
$router->add('GET', 'admin/kits/show/{id}', [KitAdminController::class, 'show'], true);
$router->add('GET', 'admin/kits/delete/{id}', [KitAdminController::class, 'delete'], true);

// Vendas
$router->add('GET', 'admin/vendas/index', [VendaController::class, 'index'], true);
$router->add('GET', 'admin/vendas/create', [VendaController::class, 'create'], true);
$router->add('GET', 'admin/vendas/edit/{id}', [VendaController::class, 'edit'], true);
$router->add('GET', 'admin/vendas/show/{id}', [VendaController::class, 'show'], true);
$router->add('GET', 'admin/vendas/delete/{id}', [VendaController::class, 'delete'], true);

// Usuários
$router->add('GET', 'admin/usuarios/index', [UsuarioController::class, 'index'], true);
$router->add('GET', 'admin/usuarios/create', [UsuarioController::class, 'create'], true);
$router->add('GET', 'admin/usuarios/edit/{id}', [UsuarioController::class, 'edit'], true);
$router->add('GET', 'admin/usuarios/show/{id}', [UsuarioController::class, 'show'], true);
$router->add('GET', 'admin/usuarios/delete/{id}', [UsuarioController::class, 'delete'], true);

// Consultas
$router->add('GET', 'admin/consultas/vendas', [ConsultaController::class, 'vendas'], true);
$router->add('GET', 'admin/consultas/impressao', [ConsultaController::class, 'impressao'], true);

// Relatórios
$router->add('GET', 'admin/relatorios/vendas', [RelatorioController::class, 'mensal'], true);
$router->add('GET', 'admin/relatorios/impressao', [RelatorioController::class, 'impressao'], true);

// PDV
$router->add('GET', 'admin/pdv', [PdvController::class, 'index'], true);
$router->add('GET', 'admin/pdv/tela', [PdvController::class, 'tela'], true);
$router->add('GET', 'admin/pdv/search_products', [PdvController::class, 'search_products'], true);
$router->add('GET', 'admin/pdv/add-product', [PdvController::class, 'addProduct'], true);
$router->add('GET', 'admin/pdv/update-quantity', [PdvController::class, 'updateQuantity'], true);
$router->add('GET', 'admin/pdv/remove-product', [PdvController::class, 'removeProduct'], true);
$router->add('GET', 'admin/pdv/clear-cart', [PdvController::class, 'clearCart'], true);
$router->add('GET', 'admin/pdv/finalize-purchase', [PdvController::class, 'finalizePurchase'], true);
$router->add('GET', 'admin/pdv/get-cart', [PdvController::class, 'getCart'], true);

