<div class="col-md-7">
    <img src="../images/imagem-900x600.jpg" class="img-fluid" alt="images/imagem-900x600.jpg" width="100%">
</div>
<div class="col-md-5 ps-5">
    <h2 class="mb-0"><?= htmlspecialchars($kit['nome']); ?></h2>
    <p>Código do Kit: <?= htmlspecialchars($kit['codigo']); ?></p>

    <p class="mb-0"><strong>Preço: R$</strong></p>
    <h5 class="text-muted">R$ <?= number_format($kit['preco'], 2, ',', '.'); ?></h5> 

    <p class="mb-0 mt-3"><strong>Descrição:</strong></p>
    <p><?= htmlspecialchars($kit['descricao']); ?></p>

    <hr>

    <p class="mb-0">Estoque: <b><?= htmlspecialchars($kit['estoque']); ?> unidades disponíveis</b></p>
    <p class="mb-0">Data de Lançamento: <b><?= date('d/m/Y', strtotime($kit['data_lancamento'])); ?></b></p>

    <div class="d-flex justify-content-start my-4">
        <form action="/processar_pedido" method="POST" target="_blank">
            <input type="hidden" name="kit_id" value="<?= $kit['id'] ?>">
            <button type="submit" class="btn btn-warning btn-lg">
                <i class="fa fa-shopping-cart fa-fw"></i> Comprar Agora
            </button>
        </form>
    </div>

    <?php if ($kit['destaque']): ?>
        <span class="badge bg-secondary">Destaque</span>
    <?php endif; ?>
</div>

<div class="mt-3">    
    <hr>
    <p class="mb-0"><strong>Produtos vínculados ao Kit:</strong></p>
    <ul class="list-unstyled mt-2">
        <?php foreach ($produtos as $produto): ?>
            <li class="mb-4">
                <div class="d-flex align-items-start">
                    <!-- Imagem do produto -->
                    <img src="../images/imagem-600x400.jpg" alt="<?php echo htmlspecialchars($produto['nome'], ENT_QUOTES); ?>" class="me-3" style="width: 120px; height: auto;">
                    
                    <!-- Informações do produto -->
                    <div>
                        <h4 class="mb-1"><?php echo htmlspecialchars($produto['nome'], ENT_QUOTES); ?></h4>
                        <p class="mb-0"><small>Descrição:</small> <?php echo htmlspecialchars($produto['descricao'], ENT_QUOTES); ?></p>
                        <p class="mb-0"><small>Categoria:</small> <?php echo htmlspecialchars($produto['categoria_nome'], ENT_QUOTES); ?> | <small>Cód. Produto:</small> <?php echo htmlspecialchars($produto['codigo'], ENT_QUOTES); ?></p>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="mt-3">    
    <hr>
    <p class="mb-0"><strong>Informações Relevantes:</strong></p>
    <p><?= htmlspecialchars($kit['informacoes_relevantes']); ?></p>
</div>
