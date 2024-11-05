<?php
ob_start(); // Inicia o buffer de saída
?>

<div class="p-4 p-md-5 mb-4 text-white bg-dark">
    <div class="row">
        <div class="col-md-12 px-0 text-center">
            <h1 class="display-4 fst-italic">Produtos Destaques</h1>
            <p class="lead my-3">Explore nossos produtos de cosméticos e perfumaria com promoções exclusivas.</p>
        </div>
    </div>    
</div>

<div class="container my-5">
    <div class="row">
        <?php
            include __DIR__.'../../includes/detalhe_produto.php';
        ?>
    </div>
</div>

<?php
$content = ob_get_clean(); // Obtém o conteúdo do buffer e limpa o buffer
$title = 'Página Inicial';
require __DIR__ . '/../../layouts/site.php'; // Inclui o layout mestre
