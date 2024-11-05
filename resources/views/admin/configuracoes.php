<?php startSection('title'); ?>
Teste
<?php endSection(); ?>

<?php startSection('content'); ?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Configurações</li>
  </ol>
</nav>

<div class="row mt-5">
    <h4>Configurações do Sistema</h4>
</div>
<?php endSection(); ?>

<?php extend('layouts/admin'); ?>
