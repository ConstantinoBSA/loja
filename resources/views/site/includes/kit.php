<div class="card mb-5 shadow-sm">
    <img src="<?php __DIR__ ?>/assets/images/imagem-600x400.jpg" srcset="images/imagem-600x400.jpg 2x, images/imagem-900x600.jpg 3x" class="bd-placeholder-img card-img-top" width="100%" alt="Kit em Promoção">
    <div class="card-body pb-4">              
        <h5 class="card-title"><?= htmlspecialchars($kit['nome']); ?></h5>
        <p class="card-text descricao mt-2"><?= htmlspecialchars($kit['descricao']); ?></p>
        <div class="text-end">
            <h5 class="text-muted">R$ <?= number_format($kit['preco'], 2, ',', '.'); ?></h5>
        </div>
        <div class="d-flex justify-content-center align-items-center mt-4">
            <div>
                <a href="#" class="btn btn-warning me-1"><i class="fa fa-shopping-cart fa-fw"></i> Comprar Agora</a>
                <a href="/kits/<?= $kit['slug'] ?>" class="btn btn-outline-secondary"><i class="fa fa-plus fa-fw"></i> Ver Detalhes</a>
            </div>
        </div>
    </div>
</div>
