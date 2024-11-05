<!-- src/views/error.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro</title>
    <link rel="stylesheet" href="/path/to/your/styles.css">
</head>
<body>
    <header>
        <h1>Erro</h1>
    </header>
    <main>
        <h2><?php echo htmlspecialchars($errorTitle); ?></h2>
        <p><?php echo htmlspecialchars($errorMessage); ?></p>
        <a href="/admin">Voltar para a página inicial</a>
    </main>
    <footer>
        <p>&copy; 2024 Meu Aplicativo</p>
    </footer>
</body>
</html>
