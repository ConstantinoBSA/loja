DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS password_resets;
DROP TABLE IF EXISTS email_verifications;
DROP TABLE IF EXISTS audit_logs;
DROP TABLE IF EXISTS access_logs;
DROP TABLE IF EXISTS kits_produtos;
DROP TABLE IF EXISTS kits;
DROP TABLE IF EXISTS produtos;
DROP TABLE IF EXISTS categorias;
DROP TABLE IF EXISTS contatos;
DROP TABLE IF EXISTS subscribers;
DROP TABLE IF EXISTS itens_venda;
DROP TABLE IF EXISTS vendas;
DROP TABLE IF EXISTS clientes;
DROP TABLE IF EXISTS formas_pagamento;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    status BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (email)
);

CREATE TABLE email_verifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (email)
);

CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(255) NOT NULL,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE access_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    login_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    logout_time DATETIME NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT NOT NULL,
    session_id VARCHAR(255) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabela de Categorias
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE,
    status BOOLEAN DEFAULT TRUE
);

-- Tabela de Formas de Pagamento
CREATE TABLE formas_pagamento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    status BOOLEAN DEFAULT TRUE
);

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(15),
    email VARCHAR(100) UNIQUE NOT NULL,
    data_nascimento DATE,
    endereco VARCHAR(255),
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de Produtos
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    codigo VARCHAR(255) UNIQUE,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    preco_promocional DECIMAL(10, 2) NOT NULL,
    codigo_barras VARCHAR(255),
    estoque INT NOT NULL,
    slug VARCHAR(255) UNIQUE,
    imagem VARCHAR(255),
    categoria_id INT,
    informacoes_relevantes TEXT,
    data_lancamento DATE,
    pontos INT DEFAULT 0,
    promocao BOOLEAN DEFAULT FALSE,
    destaque BOOLEAN DEFAULT FALSE,
    status BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- Tabela de Kits
CREATE TABLE kits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    codigo VARCHAR(255) UNIQUE,
    slug VARCHAR(255) UNIQUE,
    codigo_barras VARCHAR(255),
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    estoque INT NOT NULL,
    informacoes_relevantes TEXT,
    data_lancamento DATE,
    destaque BOOLEAN DEFAULT FALSE,
    imagem VARCHAR(255),
    status BOOLEAN DEFAULT TRUE
);

CREATE TABLE kits_produtos (
    kit_id INT,
    produto_id INT,
    PRIMARY KEY (kit_id, produto_id),
    FOREIGN KEY (kit_id) REFERENCES kits(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
);

CREATE TABLE vendas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    forma_pagamento_id INT,
    numero VARCHAR(255) UNIQUE,
    data_venda DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (forma_pagamento_id) REFERENCES formas_pagamento(id) ON DELETE CASCADE,
    status BOOLEAN DEFAULT TRUE
);

CREATE TABLE itens_venda (
    id INT AUTO_INCREMENT PRIMARY KEY,
    venda_id INT,
    produto_id INT,
    quantidade INT,
    preco DECIMAL(10, 2),
    FOREIGN KEY (venda_id) REFERENCES vendas(id)
);

CREATE TABLE contatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    mensagem TEXT NOT NULL,
    data_contato DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO usuarios (name, email, email_verified_at, password, status) VALUES ('João da Silva', 'joao.silva@example.com', '2024-11-03 16:00:00', '$2y$10$PgJZ8QykkZmU7IMiOu4/Q.dKo6JPbyLl1mxJiRuHr7xNVEFYWdXZe', TRUE);

INSERT INTO categorias (nome, slug) VALUES
('Cosméticos', 'cosmeticos'),
('Perfumaria', 'perfumaria');

INSERT INTO formas_pagamento (nome) VALUES 
('Dinheiro'),
('Pix'),
('Cartão de Débito'),
('Cartão de Crédito');

INSERT INTO clientes (nome, telefone, endereco, data_nascimento, email, status) 
VALUES ('Cliente Exemplo', '123456789', 'Rua Exemplo, 123', '1990-01-01', 'cliente@exemplo.com', 'ativo');

INSERT INTO produtos (nome, codigo, descricao, preco, preco_promocional, codigo_barras, estoque, slug, imagem, categoria_id, informacoes_relevantes, data_lancamento, promocao, destaque) VALUES
('Creme Hidratante', 'CH001', 'Creme hidratante para todos os tipos de pele', 29.90, 24.90, '123456789012', 50, 'creme-hidratante', 'creme.jpg', 1, 'Ideal para uso diário.', '2024-01-15', FALSE, TRUE),
('Perfume Floral', 'PF001', 'Perfume com notas florais e cítricas', 120.00, 110.00, '987654321098', 30, 'perfume-floral', 'perfume-floral.jpg', 2, 'Fragrância leve e duradoura.', '2024-02-01', TRUE, TRUE),
('Shampoo Revitalizante', 'SR001', 'Shampoo para cabelos danificados', 15.00, 12.00, '321654987123', 100, 'shampoo-revitalizante', 'shampoo.jpg', 1, 'Fortalece e revitaliza o cabelo.', '2024-03-05', FALSE, FALSE),
('Condicionador Suave', 'CS001', 'Condicionador suave com extrato de camomila', 18.50, 15.50, '654987321456', 80, 'condicionador-suave', 'condicionador.jpg', 1, 'Deixa o cabelo macio e brilhoso.', '2024-03-10', FALSE, FALSE),
('Batom Vermelho', 'BV001', 'Batom vermelho mate de longa duração', 25.00, 21.00, '789123456789', 60, 'batom-vermelho', 'batom.jpg', 2, 'Cores vibrantes e cobertura perfeita.', '2024-04-02', TRUE, FALSE),
('Base Líquida', 'BL001', 'Base líquida com acabamento natural', 55.00, 50.00, '456789123012', 40, 'base-liquida', 'base.jpg', 2, 'Oferece cobertura uniforme.', '2024-04-20', FALSE, TRUE),
('Perfume Amadeirado', 'PA001', 'Perfume masculino com notas amadeiradas', 150.00, 130.00, '210987654321', 25, 'perfume-amadeirado', 'perfume-amadeirado.jpg', 2, 'Elegante e marcante.', '2024-05-18', TRUE, TRUE),
('Loção Corporal', 'LC001', 'Loção corporal com essência de lavanda', 19.90, 12.90, '567890123456', 70, 'locao-corporal', 'locao.jpg', 1, 'Hidratação profunda com aroma relaxante.', '2024-05-25', FALSE, FALSE),
('Esmalte Rosa', 'ER001', 'Esmalte rosa com brilho intenso', 8.50, 7.50, '654321098765', 90, 'esmalte-rosa', 'esmalte.jpg', 1, 'Cores vivas e secagem rápida.', '2024-06-12', FALSE, TRUE),
('Creme Anti-Idade', 'CA001', 'Creme anti-idade com colágeno', 75.00, 72.00, '321098765432', 35, 'creme-anti-idade', 'creme-anti-idade.jpg', 1, 'Reduz sinais de envelhecimento.', '2024-07-03', TRUE, FALSE),
('Gel de Limpeza Facial', 'GL001', 'Gel de limpeza facial para peles sensíveis', 32.00, 28.00, '112233445566', 80, 'gel-limpeza-facial', 'gel-limpeza.jpg', 2, 'Limpeza suave sem ressecar.', '2024-07-15', FALSE, FALSE),
('Máscara Facial Revitalizante', 'MF001', 'Máscara facial com vitaminas e antioxidantes', 40.00, 35.00, '223344556677', 50, 'mascara-facial', 'mascara-facial.jpg', 1, 'Restaura a vitalidade da pele.', '2024-07-20', TRUE, TRUE),
('Creme para Mãos', 'CM001', 'Creme hidratante para as mãos', 12.00, 10.00, '334455667788', 120, 'creme-maos', 'creme-maos.jpg', 1, 'Protege e hidrata a pele.', '2024-08-01', FALSE, FALSE),
('Sabonete Líquido', 'SL001', 'Sabonete líquido com fragrância de baunilha', 14.00, 12.00, '445566778899', 90, 'sabonete-liquido', 'sabonete-liquido.jpg', 2, 'Limpeza e perfume suave.', '2024-08-10', FALSE, TRUE),
('Óleo de Massagem', 'OM001', 'Óleo de massagem com essências relaxantes', 45.00, 38.00, '556677889900', 60, 'oleo-massagem', 'oleo-massagem.jpg', 1, 'Aroma calmante e hidratação intensa.', '2024-08-15', TRUE, FALSE),
('Desodorante Natural', 'DN001', 'Desodorante sem alumínio com aroma cítrico', 18.00, 15.00, '667788990011', 110, 'desodorante-natural', 'desodorante-natural.jpg', 2, 'Proteção natural e eficaz.', '2024-08-20', FALSE, TRUE),
('Sérum Facial Antioxidante', 'SF001', 'Sérum facial com vitamina C', 100.00, 90.00, '778899001122', 40, 'serum-facial', 'serum-facial.jpg', 1, 'Reduz rugas e sinais de fadiga.', '2024-08-25', TRUE, TRUE),
('Condicionador de Volume', 'CV001', 'Condicionador que aumenta o volume dos cabelos', 22.00, 19.00, '889900112233', 70, 'condicionador-volume', 'condicionador-volume.jpg', 1, 'Volume extra sem pesar.', '2024-09-01', FALSE, FALSE),
('Protetor Térmico Capilar', 'PT001', 'Protetor térmico para cabelos', 30.00, 25.00, '990011223344', 55, 'protetor-termico', 'protetor-termico.jpg', 1, 'Protege do calor e dá brilho.', '2024-09-05', FALSE, TRUE),
('Tônico Capilar Revitalizante', 'TC001', 'Tônico capilar para cabelos sem brilho', 35.00, 30.00, '001122334455', 65, 'tonico-capilar', 'tonico-capilar.jpg', 2, 'Revitaliza e dá vida aos fios.', '2024-09-10', TRUE, FALSE),
('Máscara de Hidratação', 'MH001', 'Máscara capilar para hidratação profunda', 50.00, 45.00, '1122334455667', 50, 'mascara-hidratacao', 'mascara-hidratacao.jpg', 1, 'Hidrata profundamente e recupera o brilho.', '2024-09-15', TRUE, TRUE);

INSERT INTO kits (nome, codigo, slug, codigo_barras, descricao, preco, estoque, informacoes_relevantes, data_lancamento, destaque, imagem) VALUES
('Kit Cuidados Diários', 'KIT001', 'kit-cuidados-diarios', '1234567890123', 'Kit completo para cuidados diários com a pele e cabelo.', 99.90, 50, 'Inclui produtos hipoalergênicos', '2024-01-15', FALSE, 'kit-cuidados-diarios.jpg'),
('Kit Perfume Delicado', 'KIT002', 'kit-perfume-delicado', '1234567890124', 'Kit com três fragrâncias femininas delicadas.', 250.00, 30, 'Fragrâncias de longa duração', '2024-02-10', TRUE, 'kit-perfume-delicado.jpg'),
('Kit Maquiagem Completa', 'KIT003', 'kit-maquiagem-completa', '1234567890125', 'Kit de maquiagem com batom, sombra e delineador.', 120.00, 20, 'Ideal para todos os tipos de pele', '2024-03-05', FALSE, 'kit-maquiagem-completa.jpg'),
('Kit Luxo Spa', 'KIT004', 'kit-luxo-spa', '1234567890126', 'Experiência de spa em casa com óleos essenciais e velas aromáticas.', 300.00, 10, 'Inclui óleos essenciais premium', '2024-04-20', TRUE, 'kit-luxo-spa.jpg'),
('Kit Atleta', 'KIT005', 'kit-atleta', '1234567890127', 'Produtos essenciais para recuperação pós-treino.', 180.00, 25, 'Contém suplementos e acessórios', '2024-05-15', FALSE, 'kit-atleta.jpg'),
('Kit Infantil Divertido', 'KIT006', 'kit-infantil-divertido', '1234567890128', 'Conjunto de brinquedos educativos e seguros para crianças.', 75.50, 40, 'Brinquedos educativos e seguros', '2024-06-01', FALSE, 'kit-infantil-divertido.jpg'),
('Kit Gourmet Cozinha', 'KIT007', 'kit-gourmet-cozinha', '1234567890129', 'Utensílios e ingredientes para chefs caseiros.', 145.00, 15, 'Inclui ingredientes gourmet', '2024-07-10', TRUE, 'kit-gourmet-cozinha.jpg'),
('Kit Relaxamento Total', 'KIT008', 'kit-relaxamento-total', '1234567890130', 'Conjunto para relaxamento com sais de banho e loções.', 120.00, 30, 'Produtos relaxantes para banho', '2024-08-01', FALSE, 'kit-relaxamento-total.jpg'),
('Kit Verão Saudável', 'KIT009', 'kit-verao-saudavel', '1234567890131', 'Acessórios e produtos para aproveitar o verão com saúde.', 99.00, 50, 'Acessórios para atividades ao ar livre', '2024-09-05', FALSE, 'kit-verao-saudavel.jpg'),
('Kit Aventura ao Ar Livre', 'KIT010', 'kit-aventura-ao-ar-livre', '1234567890132', 'Equipamentos e acessórios para explorar a natureza.', 200.00, 20, 'Inclui mochilas e ferramentas', '2024-10-20', TRUE, 'kit-aventura-ao-ar-livre.jpg');

INSERT INTO kits_produtos (kit_id, produto_id) VALUES
(1, 1),  -- Kit Cuidados Diários inclui Creme Hidratante
(1, 2),  -- Kit Cuidados Diários inclui Perfume Floral
(2, 7),  -- Kit Perfume Delicado inclui Perfume Amadeirado
(3, 5),  -- Kit Maquiagem Completa inclui Batom Vermelho
(3, 6),  -- Kit Maquiagem Completa inclui Base Líquida
(4, 8),  -- Kit Luxo Spa inclui Óleo Essencial
(4, 9),  -- Kit Luxo Spa inclui Vela Aromática
(5, 10), -- Kit Atleta inclui Suplemento Pós-Treino
(5, 11), -- Kit Atleta inclui Toalha Fitness
(6, 12), -- Kit Infantil Divertido inclui Brinquedo Educativo
(6, 13), -- Kit Infantil Divertido inclui Livro de Histórias
(7, 14), -- Kit Gourmet Cozinha inclui Conjunto de Facas
(7, 15), -- Kit Gourmet Cozinha inclui Ingredientes Especiais
(8, 16), -- Kit Relaxamento Total inclui Sais de Banho
(8, 17), -- Kit Relaxamento Total inclui Loção Corporal
(9, 18), -- Kit Verão Saudável inclui Protetor Solar
(9, 19), -- Kit Verão Saudável inclui Chapéu de Praia
(10, 20), -- Kit Aventura ao Ar Livre inclui Mochila de Trilhas
(10, 21); -- Kit Aventura ao Ar Livre inclui Lanterna LED

