<?php
if (!isset($_SESSION['user_authenticated'])) {
    header('Location: /admin/login');
    exit();
}

ob_start(); // Inicia o buffer de saída
?>

<div class="row justify-content-center text-center">
    <img src="../images/logo.jpg" alt="" style="width: 20%;">

    <a class="mt-5 btn btn-dark btn-lg" href="/admin/pdv/tela">Nova Venda</a>
</div>

<?php
$content = ob_get_clean(); // Obtém o conteúdo do buffer e limpa o buffer
$title = 'Lista de Tarefas';
require __DIR__ . '/../../layouts/pdv.php'; // Inclui o layout mestre
