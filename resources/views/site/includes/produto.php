<div class="card mb-5 shadow-sm">
    <span class="badge bg-secondary position-absolute" style="left: 8px; top: 8px"><?= htmlspecialchars($produto['categoria_nome']); ?></span>
    <img src="<?php __DIR__ ?>/assets/images/imagem-600x400.jpg" class="bd-placeholder-img card-img-top" width="100%" alt="Kit em Promoção">
    <div class="card-body pb-4">
        <h5 class="card-title"><?= htmlspecialchars($produto['produto_nome']); ?></h5>
        <div class="row">
            <div class="col-md-6">
                <small class="text-muted">CÓD: <?= htmlspecialchars($produto['codigo_barras']); ?></small>
            </div>
            <div class="col-md-6 text-end">
                <small class="text-muted">Estoque: <?= htmlspecialchars($produto['estoque']); ?> unidades</small>
            </div>
        </div>                     
        <p class="card-text descricao mt-2"><?= htmlspecialchars($produto['descricao']); ?></p>
        <div class="text-end">
            <?php if ($produto['promocao'] == 1 && $produto['preco_promocional'] < $produto['preco']): ?>
                <div class="d-flex justify-content-end gap-2">
                    <h5 class="text-muted" style="text-decoration: line-through;">R$ <?= number_format($produto['preco'], 2, ',', '.'); ?></h5>
                    <h5 class="text-danger" style="font-weight: bold;">R$ <?= number_format($produto['preco_promocional'], 2, ',', '.'); ?></h5>
                </div>
            <?php else: ?>
                <div class="d-flex justify-content-end gap-2">
                    <h5 class="text-muted">R$ <?= number_format($produto['preco'], 2, ',', '.'); ?></h5>
                </div>
            <?php endif; ?>
        </div>     
    </div>
    <div class="card-footer py-3">
            <div class="d-flex justify-content-center align-items-center">
                <div class="d-flex gap-1">
                    <form class="me-1" action="/processar_pedido" method="POST" target="_blank">
                        <input type="hidden" name="produto_id" value="<?= $produto['produto_id'] ?>">
                        <button type="submit" class="btn btn-warning">
                            <i class="fa fa-shopping-cart fa-fw"></i> Comprar Agora
                        </button>
                    </form>
                    <a href="/<?= $produto['categoria_nome'] == 'Perfumaria' ? 'perfumaria' : 'cosmeticos' ?>/<?= $produto['slug'] ?>" class="btn btn-outline-secondary"><i class="fa fa-plus fa-fw"></i> Ver Detalhes</a>
                </div>
            </div>
        </div>
</div>
