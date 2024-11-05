<?php

namespace App\Controllers\Site;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Site\Kit;
use App\Models\Site\Produto;

class IndexController extends Controller
{
    public function index()
    {
        $kitModel = new Kit();        
        $kitsPromocionais = $kitModel->kitsPromocionais();

        $produtoModel = new Produto();
        $produtosPromocionais = $produtoModel->produtosPromocionais();
        $produtosDestaques = $produtoModel->produtosDestaques();
        $produtosUltimosLancados = $produtoModel->produtosUltimosLancados();

        $this->view('site/index', [
            'kitsPromocionais' => $kitsPromocionais,
            'produtosPromocionais' => $produtosPromocionais,
            'produtosDestaques' => $produtosDestaques,
            'produtosUltimosLancados' => $produtosUltimosLancados,
        ]);
    }

    public function contato()
    {
        $this->view('site/contato');
    }

    public function enviar_contato()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtenha os valores do formulário
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $mensagem = $_POST['mensagem'] ?? '';

            // Armazenar dados do formulário na sessão para repopulação
            $_SESSION['form_data'] = [
                'nome' => $nome,
                'email' => $email,
                'mensagem' => $mensagem
            ];
        
            // Validação básica dos campos obrigatórios
            if (empty($nome) || empty($email) || empty($mensagem)) {
                $_SESSION['message_contato'] = 'Todos os campos são obrigatórios.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['message_contato'] = 'Por favor, insira um e-mail válido.';
            } else {
                try {
                    $pdo = Database::getInstance()->getConnection();
                    $stmt = $pdo->prepare("INSERT INTO contatos (nome, email, mensagem) VALUES (:nome, :email, :mensagem)");
                    $stmt->bindValue(':nome', $nome, \PDO::PARAM_STR);
                    $stmt->bindValue(':email', $email, \PDO::PARAM_STR);
                    $stmt->bindValue(':mensagem', $mensagem, \PDO::PARAM_STR);
                    $stmt->execute();
        
                    $_SESSION['message'] = 'Contato enviado com sucesso!';
                    $_SESSION['message_type'] = 'success';
                    unset($_SESSION['form_data']);
                    unset($_SESSION['message_contato']);
                } catch (PDOException $e) {
                    $_SESSION['message'] = 'Erro ao enviar contato. Tente novamente mais tarde.';
                    $_SESSION['message_type'] = 'error';
                }                
            }

            header('Location: /contato#form-contato');
        }  
    }

    public function subscribers()
    {
        session_start(); 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Assumindo que a conexão do PDO já foi estabelecida e esteja armazenada na variável $pdo
            $url = $_POST['url'] ?? '';
            $email = $_POST['email'] ?? '';
        
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                try {
                    $pdo = Database::getInstance()->getConnection();
                    $stmt = $pdo->prepare("INSERT INTO subscribers (email) VALUES (:email)");
                    $stmt->bindValue(':email', $email, \PDO::PARAM_STR);
                    $stmt->execute();

                    $_SESSION['message'] = "Inscrição realizada com sucesso!";
                    $_SESSION['message_type'] = "success";
                } catch (\PDOException $e) {
                    if ($e->getCode() == 23000) { // Código de erro para chave duplicada
                        $_SESSION['message_subscribers'] = "Este e-mail já está registrado!";
                    } else {
                        $_SESSION['message'] = 'Erro ao inscrever. Tente novamente mais tarde.';
                        $_SESSION['message_type'] = "error";
                    }
                }
            } else {
                $_SESSION['message_subscribers'] = "Por favor, insira um e-mail válido.";
            }

            header('Location: /'.$url.'#subscribers');
        }
    }

    public function processar_pedido()
    {
        if (isset($_POST['produto_id'])) {
            $produto_id = $_POST['produto_id'];
            
            // Simula a obtenção dos detalhes do produto de um banco de dados
            $detalhes_produto = $this->obterDetalhesProduto($produto_id); // Função fictícia
            
            // Formata a mensagem para WhatsApp
            $mensagem = $this->formatarMensagemWhatsApp($detalhes_produto);
            
            // Envia a mensagem usando a API do WhatsApp
            $this->enviarMensagemWhatsApp($mensagem);
        } else {
            echo "Erro: Produto não especificado.";
        }
    }

    public function obterDetalhesProduto($produto_id)
    {
        $produtoModel = new Produto();
        return $produtoModel->detalhesProduto($produto_id);
    }

    public function formatarMensagemWhatsApp($detalhes)
    {
        $meses = [
            'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
            'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
        ];
    
        $dataAtual = new \DateTime();
        $dia = $dataAtual->format('d');
        $mes = $meses[$dataAtual->format('n') - 1];
        $ano = $dataAtual->format('Y');
        $dataEnvio = "{$dia} de {$mes} de {$ano}";

        // Formata a mensagem com negrito
        return "*NOVO PEDIDO:*\n" .
            "*Produto:* {$detalhes['nome']}\n" .
            "*Código do Produto:* {$detalhes['codigo']}\n" .
            "*Preço:* R$ {$detalhes['preco']}\n" .
            "*Descrição:* {$detalhes['descricao']}\n" .
            "*Data do Envio:* $dataEnvio";
    }
    
    public function enviarMensagemWhatsApp($mensagem)
    {
        // Número de telefone formato internacional, sem símbolos ou espaços
        $numeroDestino = '5582991521762'; // Substitua pelo número de destino (código do país + número)

        // Codifica a mensagem para ser usada em uma URL
        $mensagemEncoded = urlencode($mensagem);

        // Cria a URL do WhatsApp com a mensagem pré-preenchida
        $urlWhatsApp = "https://wa.me/$numeroDestino?text=$mensagemEncoded";

        // Redireciona o usuário para o WhatsApp Web
        header("Location: $urlWhatsApp");
        exit;
    }
}
