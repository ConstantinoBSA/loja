<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>asd</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body>
    <header class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/"><img src="./assets/images/logo.jpg" alt="LOGO" width="30" class="me-2">asd</a>
            <form class="d-flex ms-auto">
                <div class="input-group me-2">
                    <input type="search" class="form-control" placeholder="Procurar" style="width: 450px;">
                    <button class="btn btn-outline-secondary" type="button" id="button-addon2"><i class="fa fa-search"></i></button>
                </div>
            </form>
            <div class="dropdown ms-3">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php echo auth()->user()->name ?? 'Usuário'; ?> (<?php echo auth()->user()->email ?? ''; ?>)
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="/admin/perfil-usuario">Perfil</a></li>
                    <li><a class="dropdown-item" href="/admin/configuracoes">Configurações</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="admin/logout">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <div class="main-content">
        <nav class="sidebar">
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link active ps-0" href="/admin"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
                <li class="nav-header"><i class="fa fa-plus fa-fw"></i> Cadastros</li>
                <li class="nav-item"><a class="nav-link" href="/admin/categorias/index">- Categorias</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/formas_pagamento/index">- Formas de Pagamento</a></li>
                <li class="nav-header"><i class="fa fa-plus fa-fw"></i> Gerenciamento</li>
                <li class="nav-item"><a class="nav-link" href="/admin/clientes/index">- Clientes</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/produtos/index">- Produtos</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/kits/index">- Kits</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/vendas/index">- Vendas</a></li>
                <li class="nav-header"><i class="fa fa-plus fa-fw"></i> Site</li>
                <li class="nav-item"><a class="nav-link" href="/admin/contatos/index">- Contatos</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/inscricoes/index">- Inscrições</a></li>
                <li class="nav-header"><i class="fa fa-plus fa-fw"></i> Sistema</li>
                <li class="nav-item"><a class="nav-link" href="/admin/usuarios/index">- Usuários</a></li>
                <li class="nav-item mt-4"><a class="nav-link ps-0" href="/admin/consultas/vendas"><i class="fa fa-search fa-fw"></i> Consultas</a></li>
                <li class="nav-item mt-1"><a class="nav-link ps-0" href="/admin/relatorios/vendas"><i class="fa fa-print fa-fw"></i> Relatórios</a></li>
            </ul>
        </nav>

        <main class="content">
            <?= yieldSection('content') ?>
        </main>
    </div>

    <footer class="bg-light text-center py-3">
        <p class="mb-0">&copy; <?= date('Y') ?> Meu Aplicativo</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <!-- Toastr JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="/js/admin.js"></script>
    <script>
        $(document).ready(function() {
            <?php if (isset($_SESSION['message']) && isset($_SESSION['message_type'])): ?>
                toastr.<?php echo $_SESSION['message_type']; ?>("<?php echo $_SESSION['message']; ?>");
                <?php
                    unset($_SESSION['message']);
                unset($_SESSION['message_type']);
                ?>
            <?php endif; ?>
        });
    </script>
</body>
</html>
