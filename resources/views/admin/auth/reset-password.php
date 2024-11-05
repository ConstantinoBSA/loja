<?php
ob_start(); // Inicia o buffer de saída
?>

<form action="/reset-password" method="POST">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="password" name="new_password" placeholder="Digite sua nova senha" required>
    <button type="submit">Redefinir Senha</button>

    <div class="text-start">
        <a href="/login">Voltar ao Login</a>
    </div>
</form>

<?php
$content = ob_get_clean(); // Obtém o conteúdo do buffer e limpa o buffer
$title = 'Resetar Senha';
require __DIR__ . '/../layouts/auth.php'; // Inclui o layout mestre
