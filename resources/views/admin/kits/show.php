<?php startSection('title'); ?>
Teste
<?php endSection(); ?>

<?php startSection('content'); ?>
<div class="row mb-2">
    <div class="col-md-6">
        <h4 class="titulo-pagina">
            <span><i class="fa fa-eye fa-fw"></i> Kits</span>
            <small>Exibindo kit</small>
        </h4>
    </div>
    <div class="col-md-6">
        <nav aria-label="breadcrumb" class="d-flex justify-content-end">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin/dashboard"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
                <li class="breadcrumb-item"><a href="/kits/index">Kits</a></li>
                <li class="breadcrumb-item active" aria-current="page">Exibir</li>
            </ol>
        </nav>
    </div>
</div>

<ul class="lista-item mt-5">
    <li><span>#ID:</span> <b><?php echo $kit['id'] ?></b></li>
    <li><span>Nome:</span> <b><?php echo $kit['nome'] ?></b></li>
    <li><span>Slug:</span> <b><?php echo $kit['slug'] ?></b></li>
    <li class="mt-3"><span>Status:</span> <b><?php echo $kit['status'] ?></b></li>
    <li class="mt-3">
        <span>Ações:</span>
        <a class="btn btn-warning" href="/admin/kits/editar/<?php echo $kit['id']; ?>"><i class="fa fa-pencil fa-fw"></i> Editar Kit</a>
        <button type="button" class="btn btn-danger ms-1" data-bs-toggle="modal" data-bs-target="#modalDelete<?php echo $kit['id']; ?>"><i class="fa fa-trash fa-fw"></i> Deletar Kit</button>
        <a class="btn btn-secondary ms-1" href="/admin/kits/index"><i class="fa fa-arrow-left fa-fw"></i> Voltar a Listagem</a>
    </li>
</ul>

<!-- Modal -->
<div class="modal fade" id="modalDelete<?php echo $kit['id']; ?>" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDeleteLabel">Confirmação de exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Você deseja deletar este registro?</h6>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                <a href="/admin/kits/delete/<?php echo $kit['id']; ?>" class="btn btn-primary">Sim</a>
            </div>
        </div>
    </div>
</div>
<?php endSection(); ?>

<?php extend('layouts/admin'); ?>
