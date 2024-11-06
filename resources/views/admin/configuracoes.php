<?php startSection('title'); ?>
Teste
<?php endSection(); ?>

<?php startSection('content'); ?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin/dashboard"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Configurações</li>
  </ol>
</nav>

<div class="row mt-5">
    <h4>Configurações do Sistema</h4>

    <form method="post" action="/config/salvar">
      <?php foreach ($configuracoes as $config): ?>
          <div class="mb-3">
              <label for="config_<?php echo htmlspecialchars($config['chave'], ENT_QUOTES, 'UTF-8'); ?>" class="form-label">
                  <?php echo htmlspecialchars(ucfirst($config['chave']), ENT_QUOTES, 'UTF-8'); ?>
              </label>
              <input type="text" class="form-control" id="config_<?php echo htmlspecialchars($config['chave'], ENT_QUOTES, 'UTF-8'); ?>"
                    name="config[<?php echo htmlspecialchars($config['chave'], ENT_QUOTES, 'UTF-8'); ?>]"
                    value="<?php echo htmlspecialchars($config['valor'], ENT_QUOTES, 'UTF-8'); ?>">
          </div>
      <?php endforeach; ?>
      <button type="submit" class="btn btn-primary">Salvar Configurações</button>
  </form>
</div>
<?php endSection(); ?>

<?php extend('layouts/admin'); ?>
