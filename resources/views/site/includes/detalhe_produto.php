<div class="col-md-7">
    <img src="../images/imagem-900x600.jpg" class="img-fluid" alt="images/imagem-900x600.jpg" width="100%">
</div>
<div class="col-md-5 ps-5">
    <h2 class="mb-0"><?= htmlspecialchars($produto['nome']); ?></h2>
    <h6 class="text-muted"><?= htmlspecialchars($produto['categoria_nome']); ?></h6>
    <p>Código do Produto: <?= htmlspecialchars($produto['codigo']); ?></p>

    <p class="mb-0"><strong>Preço: R$</strong></p>
    <?php if ($produto['promocao'] == 1 && $produto['preco_promocional'] < $produto['preco']): ?>
        <div class="d-flex justify-content-start gap-2">
            <h5 class="text-muted" style="text-decoration: line-through;">R$ <?= number_format($produto['preco'], 2, ',', '.'); ?></h5>
            <h5 class="text-danger" style="font-weight: bold;">R$ <?= number_format($produto['preco_promocional'], 2, ',', '.'); ?></h5>
        </div>
    <?php else: ?>
        <h5 class="text-muted">R$ <?= number_format($produto['preco'], 2, ',', '.'); ?></h5>
    <?php endif; ?>    

    <p class="mb-0 mt-3"><strong>Descrição:</strong></p>
    <p><?= htmlspecialchars($produto['descricao']); ?></p>

    <hr>

    <p class="mb-0">Estoque: <b><?= htmlspecialchars($produto['estoque']); ?> unidades disponíveis</b></p>
    <p class="mb-0">Data de Lançamento: <b><?= date('d/m/Y', strtotime($produto['data_lancamento'])); ?></b></p>

    <div class="d-flex justify-content-start my-4">
        <form action="/processar_pedido" method="POST" target="_blank">
            <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">
            <button type="submit" class="btn btn-warning btn-lg">
                <i class="fa fa-shopping-cart fa-fw"></i> Comprar Agora
            </button>
        </form>
    </div>

    <?php if ($produto['promocao']): ?>
        <span class="badge bg-secondary">Promoção</span>
    <?php endif; ?>
    <?php if ($produto['destaque']): ?>
        <span class="badge bg-secondary">Destaque</span>
    <?php endif; ?>
</div>

<div class="mt-3">    
    <hr>
    <p class="mb-0"><strong>Informações Relevantes:</strong></p>
    <p><?= htmlspecialchars($produto['informacoes_relevantes']); ?></p>
</div>
