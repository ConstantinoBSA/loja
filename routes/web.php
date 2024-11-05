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
$router->addRoute('GET', '', [IndexController::class, 'index']);
$router->addRoute('GET', 'cosmeticos', [CosmeticoController::class, 'index']);
$router->addRoute('GET', 'cosmeticos/{slug}', [CosmeticoController::class, 'detalhes']);
$router->addRoute('GET', 'perfumaria', [PerfumariaController::class, 'index']);
$router->addRoute('GET', 'perfumaria/{slug}', [PerfumariaController::class, 'detalhes']);
$router->addRoute('GET', 'kits', [KitController::class, 'index']);
$router->addRoute('GET', 'kits/{slug}', [KitController::class, 'detalhes']);
$router->addRoute('GET', 'promocoes', [PromocaoController::class, 'index']);
$router->addRoute('GET', 'promocoes/{slug}', [PromocaoController::class, 'detalhes']);
$router->addRoute('GET', 'destaques', [DestaqueController::class, 'index']);
$router->addRoute('GET', 'destaques/{slug}', [DestaqueController::class, 'detalhes']);
$router->addRoute('GET', 'contato', [IndexController::class, 'contato']);
$router->addRoute('GET', 'enviar_contato', [IndexController::class, 'enviar_contato']);
$router->addRoute('GET', 'processar_pedido', [IndexController::class, 'processar_pedido']);
$router->addRoute('GET', 'subscribers', [IndexController::class, 'subscribers']);

// Admin
// Auth
$router->addRoute('GET', 'admin/forgot-password', [AuthController::class, 'forgotPassword']);
$router->addRoute('POST', 'admin/reset-password', [AuthController::class, 'resetPassword']);
$router->addRoute('GET', 'admin/verify-required', [AuthController::class, 'verifyRequired']);
$router->addRoute('POST', 'admin/verify-email', [AuthController::class, 'verifyEmail']);
$router->addRoute('GET', 'admin/login', [AuthController::class, 'showLoginForm']);
$router->addRoute('POST', 'admin/login', [AuthController::class, 'login']);
$router->addRoute('GET', 'admin/logout', [AuthController::class, 'logout'], true);

$router->addRoute('GET', 'admin', [HomeController::class, 'index'], true);
$router->addRoute('GET', 'admin/perfil-usuario', [HomeController::class, 'perfil'], true);
$router->addRoute('GET', 'admin/configuracoes', [HomeController::class, 'configuracoes'], true);

// Categorias
$router->addRoute('GET', 'admin/categorias/index', [CategoriaController::class, 'index'], true);
$router->addRoute('GET', 'admin/categorias/adicionar', [CategoriaController::class, 'create'], true);
$router->addRoute('POST', 'admin/categorias/store', [CategoriaController::class, 'store'], true);
$router->addRoute('GET', 'admin/categorias/editar/{id}', [CategoriaController::class, 'edit'], true);
$router->addRoute('POST', 'admin/categorias/update/{id}', [CategoriaController::class, 'update'], true);
$router->addRoute('GET', 'admin/categorias/exibir/{id}', [CategoriaController::class, 'show'], true);
$router->addRoute('GET', 'admin/categorias/delete/{id}', [CategoriaController::class, 'delete'], true);

// Formas de Pagamento
$router->addRoute('GET', 'admin/formas_pagamento/index', [FormaPagamentoController::class, 'index'], true);
$router->addRoute('GET', 'admin/formas_pagamento/adicionar', [FormaPagamentoController::class, 'create'], true);
$router->addRoute('POST', 'admin/formas_pagamento/store', [FormaPagamentoController::class, 'store'], true);
$router->addRoute('GET', 'admin/formas_pagamento/editar/{id}', [FormaPagamentoController::class, 'edit'], true);
$router->addRoute('POST', 'admin/formas_pagamento/update/{id}', [FormaPagamentoController::class, 'update'], true);
$router->addRoute('GET', 'admin/formas_pagamento/exibir/{id}', [FormaPagamentoController::class, 'show'], true);
$router->addRoute('GET', 'admin/formas_pagamento/delete/{id}', [FormaPagamentoController::class, 'delete'], true);

// Clientes
$router->addRoute('GET', 'admin/clientes/index', [ClienteController::class, 'index'], true);
$router->addRoute('GET', 'admin/clientes/adicionar', [ClienteController::class, 'create'], true);
$router->addRoute('POST', 'admin/clientes/store', [ClienteController::class, 'store'], true);
$router->addRoute('GET', 'admin/clientes/editar/{id}', [ClienteController::class, 'edit'], true);
$router->addRoute('POST', 'admin/clientes/update/{id}', [ClienteController::class, 'update'], true);
$router->addRoute('GET', 'admin/clientes/exibir/{id}', [ClienteController::class, 'show'], true);
$router->addRoute('GET', 'admin/clientes/delete/{id}', [ClienteController::class, 'delete'], true);

// Produtos
$router->addRoute('GET', 'admin/produtos/index', [ProdutoController::class, 'index'], true);
$router->addRoute('GET', 'admin/produtos/adicionar', [ProdutoController::class, 'create'], true);
$router->addRoute('POST', 'admin/produtos/store', [ProdutoController::class, 'store'], true);
$router->addRoute('GET', 'admin/produtos/editar/{id}', [ProdutoController::class, 'edit'], true);
$router->addRoute('POST', 'admin/produtos/update/{id}', [ProdutoController::class, 'update'], true);
$router->addRoute('GET', 'admin/produtos/exibir/{id}', [ProdutoController::class, 'show'], true);
$router->addRoute('GET', 'admin/produtos/delete/{id}', [ProdutoController::class, 'delete'], true);

// Kits
$router->addRoute('GET', 'admin/kits/index', [KitAdminController::class, 'index'], true);
$router->addRoute('GET', 'admin/kits/adicionar', [KitAdminController::class, 'create'], true);
$router->addRoute('POST', 'admin/kits/store', [KitAdminController::class, 'store'], true);
$router->addRoute('GET', 'admin/kits/editar/{id}', [KitAdminController::class, 'edit'], true);
$router->addRoute('POST', 'admin/kits/update/{id}', [KitAdminController::class, 'update'], true);
$router->addRoute('GET', 'admin/kits/exibir/{id}', [KitAdminController::class, 'show'], true);
$router->addRoute('GET', 'admin/kits/delete/{id}', [KitAdminController::class, 'delete'], true);

// Vendas
$router->addRoute('GET', 'admin/vendas/index', [VendaController::class, 'index'], true);
$router->addRoute('GET', 'admin/vendas/adicionar', [VendaController::class, 'create'], true);
$router->addRoute('POST', 'admin/vendas/store', [VendaController::class, 'store'], true);
$router->addRoute('GET', 'admin/vendas/editar/{id}', [VendaController::class, 'edit'], true);
$router->addRoute('POST', 'admin/vendas/update/{id}', [VendaController::class, 'update'], true);
$router->addRoute('GET', 'admin/vendas/exibir/{id}', [VendaController::class, 'show'], true);
$router->addRoute('GET', 'admin/vendas/delete/{id}', [VendaController::class, 'delete'], true);

// Usuários
$router->addRoute('GET', 'admin/usuarios/index', [UsuarioController::class, 'index'], true);
$router->addRoute('GET', 'admin/usuarios/adicionar', [UsuarioController::class, 'create'], true);
$router->addRoute('POST', 'admin/usuarios/store', [UsuarioController::class, 'store'], true);
$router->addRoute('GET', 'admin/usuarios/editar/{id}', [UsuarioController::class, 'edit'], true);
$router->addRoute('POST', 'admin/usuarios/update/{id}', [UsuarioController::class, 'update'], true);
$router->addRoute('GET', 'admin/usuarios/exibir/{id}', [UsuarioController::class, 'show'], true);
$router->addRoute('GET', 'admin/usuarios/delete/{id}', [UsuarioController::class, 'delete'], true);

// Consultas
$router->addRoute('GET', 'admin/consultas/vendas', [ConsultaController::class, 'vendas'], true);
$router->addRoute('GET', 'admin/consultas/impressao', [ConsultaController::class, 'impressao'], true);

// Relatórios
$router->addRoute('GET', 'admin/relatorios/vendas', [RelatorioController::class, 'mensal'], true);
$router->addRoute('GET', 'admin/relatorios/impressao', [RelatorioController::class, 'impressao'], true);

// PDV
$router->addRoute('GET', 'admin/pdv', [PdvController::class, 'index'], true);
$router->addRoute('GET', 'admin/pdv/tela', [PdvController::class, 'tela'], true);
$router->addRoute('GET', 'admin/pdv/search_products', [PdvController::class, 'search_products'], true);
$router->addRoute('GET', 'admin/pdv/addRoute-product', [PdvController::class, 'addRouteProduct'], true);
$router->addRoute('GET', 'admin/pdv/update-quantity', [PdvController::class, 'updateQuantity'], true);
$router->addRoute('GET', 'admin/pdv/remove-product', [PdvController::class, 'removeProduct'], true);
$router->addRoute('GET', 'admin/pdv/clear-cart', [PdvController::class, 'clearCart'], true);
$router->addRoute('GET', 'admin/pdv/finalize-purchase', [PdvController::class, 'finalizePurchase'], true);
$router->addRoute('GET', 'admin/pdv/get-cart', [PdvController::class, 'getCart'], true);
