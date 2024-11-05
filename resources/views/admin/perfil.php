<?php startSection('title'); ?>
Teste
<?php endSection(); ?>

<?php startSection('content'); ?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo BASE_URL ?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Perfil do Usuário</li>
  </ol>
</nav>

<div class="row mt-5">
    <h4>Alterar Senha</h4>
    <form method="POST" action="/update-password">
        <label for="current_password">Senha Atual:</label><br>
        <input type="password" id="current_password" name="current_password" required><br><br>

        <label for="new_password">Nova Senha:</label><br>
        <input type="password" id="new_password" name="new_password" required><br><br>

        <label for="confirm_password">Confirme a Nova Senha:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>

        <input type="submit" value="Alterar Senha">
    </form>
</div>
<?php endSection(); ?>

<?php extend('layouts/admin'); ?>
