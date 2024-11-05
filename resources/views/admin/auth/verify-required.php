<?php
ob_start(); // Inicia o buffer de saída

if (isset($_GET['email'])) {
    $email = $_GET['email'];
} else {
    header("Location: /login");
    exit();
}
?>

<?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green;"><?php echo $_SESSION['success_message'];
    unset($_SESSION['success_message']); ?></p>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <p style="color: red;"><?php echo $_SESSION['error_message'];
    unset($_SESSION['error_message']); ?></p>
<?php endif; ?>

<form action="/verify-required" method="POST">
    <input type="text" value="<?php echo htmlspecialchars($email) ?>">
    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email) ?>">
    <button type="submit">Reenviar E-mail de Verificação</button>

    <div class="text-start">
        <a href="/login">Voltar ao Login</a>
    </div>
</form>

<?php
$content = ob_get_clean(); // Obtém o conteúdo do buffer e limpa o buffer
$title = 'Recuperar Senha';
require __DIR__ . '/../layouts/auth.php'; // Inclui o layout mestre
