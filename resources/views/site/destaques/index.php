<?php startSection('title'); ?>
Teste
<?php endSection(); ?>

<?php startSection('content'); ?>
<div class="p-4 p-md-5 mb-4 text-white bg-dark">
    <div class="row">
        <div class="col-md-12 px-0 text-center">
            <h1 class="display-4 fst-italic">Produtos Destaques</h1>
            <p class="lead my-3">Explore nossos produtos de cosméticos e perfumaria com promoções exclusivas.</p>
        </div>
    </div>    
</div>

<!-- Kits em Promoções -->
<div class="container py-5">
    <section class="">
        <form method="GET" action="/destques">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2">
                        <label for="ordenar" class="form-label mb-0">Ordenar por:</label>
                        <select name="ordenar" id="ordenar" class="form-select w-auto" onchange="this.form.submit()">
                            <option value="mais-relevantes" <?php echo $order === 'mais-relevantes' ? 'selected' : ''; ?>>Mais Relevantes</option>
                            <option value="menor-preco" <?php echo $order === 'menor-preco' ? 'selected' : ''; ?>>Menor Preço</option>
                            <option value="maior-preco" <?php echo $order === 'maior-preco' ? 'selected' : ''; ?>>Maior Preço</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Procurar por..." value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES); ?>">
                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2"><i class="fa fa-search fa-fw"></i> Procurar</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="row justify-content-center mt-4">
            <!-- Exemplo de Kit -->
            <?php foreach ($produtos as $produto): ?>
                <div class="col-md-4">  
                    <?php
                        include __DIR__.'../../includes/produto.php';
                ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="row">
            <div class="col-md-12 text-center">
                <div class="mb-3">
                    Mostrando de <?php echo $start; ?> até <?php echo $end; ?> de <?php echo $totalProdutos; ?> registros
                </div>
                <?php if ($totalPages > 1): ?>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
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
    </section>
</div>
<?php endSection(); ?>

<?php extend('layouts/site'); ?>
