<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtém a URL atual, excluindo a parte do protocolo e domínio
$currentPath = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Cria uma função para verificar se o link está ativo
function isActive($linkPattern, $currentPath)
{
    // Verifica se o início do caminho atual corresponde ao padrão fornecido
    return strpos($currentPath, $linkPattern) === 0 ? 'active' : '';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $config['app']['app_name'] ?? '[NOME_PROJETO]'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/site.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body>
    <!-- Cabeçalho com Logo e Menu -->
    <header class="d-flex flex-wrap align-items-center justify-content-between p-3 border-bottom" style="background-color: #F5E111;margin-bottom: -1px">
        <a href="#" class="d-flex align-items-center mb-lg-0 text-dark text-decoration-none position-relative">
            <img class="logo-principal" src="<?php echo BASE_URL; ?>/images/logo-principal.png" alt="Logo">
            <img class="logo-textos" src="<?php echo BASE_URL; ?>/images/logo-textos.png" alt="Logo">
        </a>
        <ul class="nav col-12 col-md-auto justify-content-center mb-md-0">
            <li class="<?php echo isActive('', $currentPath); ?>"><a href="/" class="nav-link px-2 link-dark"><i class="fa fa-home fa-fw"></i> Início</a></li>
            <li class="<?php echo isActive('cosmeticos', $currentPath); ?>"><a href="/cosmeticos" class="nav-link px-2 link-dark">Cosméticos</a></li>
            <li class="<?php echo isActive('perfumaria', $currentPath); ?>"><a href="/perfumaria" class="nav-link px-2 link-dark">Perfumaria</a></li>
            <li class="<?php echo isActive('kits', $currentPath); ?>"><a href="/kits" class="nav-link px-2 link-dark">Kits</a></li>
            <li class="<?php echo isActive('promocoes', $currentPath); ?>"><a href="/promocoes" class="nav-link px-2 link-dark">Promoções</a></li>
            <li class="<?php echo isActive('destaques', $currentPath); ?>"><a href="/destaques" class="nav-link px-2 link-dark">Destaques</a></li>
            <li class="<?php echo isActive('contato', $currentPath); ?>"><a href="/contato" class="nav-link px-2 link-dark">Contato</a></li>
            <li><a href="/admin" class="nav-link px-2 link-dark">Admin</a></li>
        </ul>
    </header>

    <!-- Página Inicial -->
    <main>        
        <?php echo $content; ?>
    </main>

    <!-- Rodapé -->
    <footer class="footer-custom">
        <div class="container py-5">
            <div class="row my-4 align-items-start">
                <div class="col-md-4">
                    <h4 class="text-start mb-4 display-6 fst-italic" style="font-size: 22px;">Nosso Endereço</h4>
                    <img src="<?php echo BASE_URL; ?>/images/logo-h-w.png" alt="Logo" width="80%">
                    <ul class="mt-3 list-unstyled">
                        <li><span>Avenida Benedito Casado, 58, Centro,</span></li>
                        <li><span>57925-000 Barra de Santo Antônio/AL</span></li>
                        <li><span><a href="mailto:contato@neidjaneperminio.com.br"><i class="fa fa-envelope fa-fw"></i> contato@neidjaneperminio.com.br</a></span></li>
                        <li><span><a href="tel:82991521762"><i class="fa fa-phone fa-fw"></i> (82) 99152-1762</a></span></li>
                    </ul>
                    <p class="text-center mt-4 w-75">
                        <a href="https://www.instagram.com/np_cosmeticosofc" target="_blank" style="color: #ffc107!important"><i class="fab fa-instagram fa-fw"></i> Instagram</a> | 
                        <a href="https://wa.me/5582991521762?text=Olá%2C%20gostaria%20de%20saber%20mais%20sobre%20os%20seus%20produtos." target="_blank" style="color: #ffc107!important"><i class="fab fa-whatsapp fa-fw"></i> WhatsApp</a>
                    </p>
                </div>
                <div class="col-md-4">
                    <h4 class="text-left mb-4 display-6 fst-italic" style="font-size: 22px;">Menu Principal</h4>
                    <ul class="menu-footer">
                        <li><a href="/" class="nav-link px-2 link-dark">- Início</a></li>
                        <li><a href="/cosmeticos" class="nav-link px-2 link-dark">- Cosméticos</a></li>
                        <li><a href="/perfumaria" class="nav-link px-2 link-dark">- Perfumaria</a></li>
                        <li><a href="/kits" class="nav-link px-2 link-dark">- Kits</a></li>
                        <li><a href="/promocoes" class="nav-link px-2 link-dark">- Promoções</a></li>
                        <li><a href="/destaques" class="nav-link px-2 link-dark">- Destaques</a></li>
                        <li><a href="/contato" class="nav-link px-2 link-dark">- Contato</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h4 class="text-left mb-4 display-6 fst-italic" style="font-size: 22px;">Se inscreva para receber novidades!</h4>

                    <?php
                    // Exibe a mensagem, se existir
                    if (!empty($_SESSION['message_subscribers'])):
                    ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($_SESSION['message_subscribers']); ?>
                        </div>
                    <?php
                        // Limpa a mensagem para que não seja exibida novamente
                        unset($_SESSION['message_subscribers']);
                    endif;
                    ?>

                    <form method="POST" action="/subscribers" id="subscribers">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Deixe seu e-mail</label>
                            <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                            <div id="emailHelp" class="form-text">Escreva seu e-mail para receber promoções e novidades.</div>
                        </div>
                        <input type="hidden" name="url" value="<?php echo $currentPath ?>">
                        <button type="submit" class="btn btn-warning"><i class="fa fa-paper-plane fa-fw"></i> Inscrever Agora</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="text-center py-4" style="background-color: #111111;">
            <p class="mb-0">&copy; 2024 Neidjane Perminio - Cosméticos e Perfumaria. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <!-- Toastr JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/js/script.js"></script>
    <script>
        $(document).ready(function() {
            <?php if (isset($_SESSION['message']) && isset($_SESSION['message_type'])): ?>
                toastr.<?php echo $_SESSION['message_type']; ?>("<?php echo $_SESSION['message']; ?>");
                <?php
                    unset($_SESSION['message']);
                unset($_SESSION['message_type']);
                ?>
            <?php endif; ?>
        });
    </script>
</body>
</html>
