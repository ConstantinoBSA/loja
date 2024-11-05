<?php startSection('title'); ?>
Teste
<?php endSection(); ?>

<?php startSection('content'); ?>
<h1>Criar Nova Tarefa</h1>
<a href="/usuarios/index">Voltar</a>

<form method="post" action="/usuarios/store">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
    
    <div class="mb-3">
        <label for="name" class="form-label">Nome</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($data['name'] ?? ''); ?>">
        <?php if (!empty($error['name'])): ?>
            <p class="error"><?php echo htmlspecialchars($error['name']); ?></p>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="text" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>">
        <?php if (!empty($error['email'])): ?>
            <p class="error"><?php echo htmlspecialchars($error['email']); ?></p>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" class="form-select" id="status">
            <option value="1" <?php if (($data['status'] ?? '') === '1') {
                echo 'selected';
            } ?>>Ativo</option>
            <option value="0" <?php if (($data['status'] ?? '') === '0') {
                echo 'selected';
            } ?>>Inativo</option>
        </select>
        <?php if (!empty($error['status'])): ?>
            <p class="error"><?php echo htmlspecialchars($error['status']); ?></p>
        <?php endif; ?>
    </div>

    <button class="btn btn-primary" type="submit">Criar Tarefa</button>
</form>
<?php endSection(); ?>

<?php extend('layouts/admin'); ?>
