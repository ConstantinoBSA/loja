<?php startSection('title'); ?>
Teste
<?php endSection(); ?>

<?php startSection('content'); ?>
<div class="p-4 p-md-5 mb-4 text-white bg-dark">
    <div class="row">
        <div class="col-md-12 px-0 text-center">
            <h1 class="display-4 fst-italic">Bem-vindo à Nossa Loja!</h1>
            <p class="lead my-3">Explore nossos produtos de cosméticos e perfumaria com promoções exclusivas.</p>
        </div>
    </div>    
</div>

<!-- Kits em Promoções -->
<div class="container">
    <section class="mb-5">
        <h2 class="text-center my-5 display-6 fst-italic">Kits em Promoção</h2>
        <div class="row justify-content-center">
            <!-- Exemplo de Kit -->
            <?php foreach ($kitsPromocionais as $kit): ?>
                <div class="col-md-4">  
                    <?php
                        include __DIR__.'/includes/kit.php';
                ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center">
            <hr>
            <a class="btn btn-outline-dark btn-lg mt-3" href="/kits"><i class="fa fa-plus fa-fw"></i> Ver todos os Kits...</a>
        </div>
    </section>
 </div>

<!-- Faixa de Contato WhatsApp -->
<div class="highlight-banner">
    <p class="display-6 fst-italic" style="font-size: 28px;">Entre em contato conosco agora mesmo!</p>
    <a href="https://api.whatsapp.com/send?phone=SEUNUMERO" target="_blank" class="btn btn-dark btn-lg"><i class="fab fa-whatsapp fa-fw"></i> Fale via WhatsApp</a>
</div>

<!-- Produtos em Promoções -->
<div class="container">
    <section class="mb-5">
        <h2 class="text-center my-5 display-6 fst-italic">Produtos em Promoção</h2>
        <div class="row justify-content-center">
            <!-- Exemplo de Produto -->
            <?php foreach ($produtosPromocionais as $produto): ?>
                <div class="col-md-4">  
                    <?php
                    include __DIR__.'/includes/produto.php';
                ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center">
            <hr>
            <a class="btn btn-outline-dark btn-lg mt-3" href="/promocoes"><i class="fa fa-plus fa-fw"></i> Ver todos os produtos em promoção...</a>
        </div>
    </section>
</div>

<!-- Produtos em Destaque -->
<div class="container">
    <section class="mb-5">
        <h2 class="text-center my-5 display-6 fst-italic">Produtos em Destaque</h2>
        <div class="row justify-content-center">
            <!-- Exemplo de Produto Destaque -->
            <?php foreach ($produtosDestaques as $produto): ?>
                <div class="col-md-4">  
                    <?php
                    include __DIR__.'/includes/produto.php';
                ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center">
            <hr>
            <a class="btn btn-outline-dark btn-lg mt-3" href="/destaques"><i class="fa fa-plus fa-fw"></i> Ver todos os produtos em destaque...</a>
        </div>
    </section>
</div>

<!-- Faixa para Seguir no Instagram -->
<div class="highlight-banner">
    <p class="display-6 fst-italic" style="font-size: 28px;">Siga-nos no Instagram para mais novidades!</p>
    <a href="https://www.instagram.com/seuusuario" target="_blank" class="btn btn-dark btn-lg"><i class="fab fa-instagram fa-fw"></i> Instagram</a>
</div>

<!-- Últimos Produtos Lançados -->
<div class="container">
    <section class="mb-5">
        <h2 class="text-center my-5 display-6 fst-italic">Últimos Produtos Lançados</h2>
        <div class="row justify-content-center">
            <!-- Exemplo de Produto Lançado -->
            <?php foreach ($produtosUltimosLancados as $produto): ?>
                <div class="col-md-4">  
                    <?php
                    include __DIR__.'/includes/produto.php';
                ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>
<?php endSection(); ?>

<?php extend('layouts/site'); ?>
