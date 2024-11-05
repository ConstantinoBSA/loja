<?php startSection('title'); ?>
Teste
<?php endSection(); ?>

<?php startSection('content'); ?>
<h2>Lista de Usuários</h2>

<div class="row">
    <div class="col-md-6">
        <a class="btn btn-success" href="/usuarios/adicionar"><i class="fa fa-plus fa-fw"></i> Criar Novo Usuário</a>
    </div>
    <div class="col-md-6">
        <form method="GET" action="/usuarios/index">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Pesquisar usuários por título ou descrição..." value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES); ?>">
                <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Pesquisar</button>
            </div>
            <div class="text-end">
                <a href="/usuarios/index"><small>Limpar Pesquisa</small></a>
            </div>
        </form>
    </div>
</div>

<table class="table">
    <thead>
        <th width="70" class="text-center">ID</th>
        <th>Nome</th>
        <th>E-mail</th>
        <th>Status</th>
        <th width="130" class="text-center">Ações</th>
    </thead>
    <tbody>
    <?php if (empty($data['usuarios'])): ?>
    <tr>
        <td colspan="5" class="text-center">Nenhum registro encontrado.</td>
    </tr>
        <?php else: ?>
            <?php foreach ($data['usuarios'] as $usuario): ?>
                <tr>
                    <td class="text-center"><?php echo $usuario['id']; ?></td>
                    <td><?php echo $usuario['name']; ?></td>
                    <td><?php echo $usuario['email']; ?></td>
                    <td>
                        <?php if ($usuario['status'] == 'pendente'): ?>
                            <span class="badge bg-danger">Pendente</span>
                        <?php else: ?>
                            <span class="badge bg-success">Concluído</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <a class="btn btn-warning btn-sm" href="/usuarios/editar/<?php echo $usuario['id']; ?>"><i class="fa fa-pencil"></i></a>
                        <a class="btn btn-danger btn-sm" href="/usuarios/delete/<?php echo $usuario['id']; ?>"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<div class="row">
    <div class="col-md-4">
        Mostrando de <?php echo $start; ?> até <?php echo $end; ?> de <?php echo $totalUsuarios; ?> registros
    </div>
    <div class="col-md-8">
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-end">
                    <li class="page-item <?php echo $currentPage == 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>&search=<?php echo htmlspecialchars($search, ENT_QUOTES); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo $i == $currentPage ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search, ENT_QUOTES); ?>">
                        <?php echo $i; ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo $currentPage == $totalPages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>&search=<?php echo htmlspecialchars($search, ENT_QUOTES); ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>
<?php endSection(); ?>

<?php extend('layouts/admin'); ?>
