<?php startSection('title'); ?>
Teste
<?php endSection(); ?>

<?php startSection('content'); ?>
<div class="p-4 p-md-5 mb-4 text-white bg-dark">
    <div class="row">
        <div class="col-md-12 px-0 text-center">
            <h1 class="display-4 fst-italic">Contato</h1>
            <p class="lead my-3">Explore nossos produtos de cosméticos e perfumaria com promoções exclusivas.</p>
        </div>
    </div>    
</div>

<div class="container my-5" id="form-contato">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2 class="text-center display-6 fst-italic mb-1">Contato Conosco</h2>
            <span class="d-block text-center mb-5 text-muted">Preencha os campos do formulário abaixo. Os campos com (*) são obrigatórios!</span>

            <?php
            // Defina as variáveis de erro e valores do formulário
            $nome = $_SESSION['form_data']['nome'] ?? '';
$email = $_SESSION['form_data']['email'] ?? '';
$mensagem = $_SESSION['form_data']['mensagem'] ?? '';

// Limpe os dados do formulário da sessão após uso
unset($_SESSION['form_data']);

// Exibe a mensagem, se existir
if (!empty($_SESSION['message_contato'])):
    ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($_SESSION['message_contato']); ?>
                    </div>
                <?php
        // Limpa a mensagem para que não seja exibida novamente
        unset($_SESSION['message_contato']);
endif;
?>
            <form action="/enviar_contato" method="POST">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome: <span class="requerido"></span></label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($nome); ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email: <span class="requerido"></span></label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email); ?>">
                </div>
                <div class="mb-3">
                    <label for="mensagem" class="form-label">Mensagem: <span class="requerido"></span></label>
                    <textarea class="form-control" id="mensagem" name="mensagem" rows="5"><?= htmlspecialchars($mensagem); ?></textarea>
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-warning"><i class="fa fa-paper-plane fa-fw"></i> Enviar Contato</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endSection(); ?>

<?php extend('layouts/site'); ?>
