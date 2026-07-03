-- ============================================================
--  ConstrutorMax — Banco de Dados
--  Execute este arquivo no MySQL/MariaDB antes de rodar o site
--  Comando: mysql -u root -p < construtormax.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS construtormax
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE construtormax;

-- ------------------------------------------------------------
-- Tabela: produtos
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS produtos (
    id        INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    nome      VARCHAR(150)    NOT NULL,
    categoria VARCHAR(80)     NOT NULL,
    preco     DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    estoque   INT UNSIGNED    NOT NULL DEFAULT 0,
    imagem    VARCHAR(500)    NOT NULL DEFAULT '',
    descricao TEXT            NOT NULL,
    criado_em TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabela: produto_especificacoes
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS produto_especificacoes (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    produto_id  INT UNSIGNED NOT NULL,
    chave       VARCHAR(100) NOT NULL,
    valor       VARCHAR(255) NOT NULL,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabela: usuarios
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
    id          INT UNSIGNED   AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(150)   NOT NULL,
    email       VARCHAR(200)   NOT NULL UNIQUE,
    senha_hash  VARCHAR(255)   NOT NULL,
    telefone    VARCHAR(30)    NOT NULL DEFAULT '',
    tipo        ENUM('cliente','admin') NOT NULL DEFAULT 'cliente',
    criado_em   TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabela: pedidos
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS pedidos (
    id             INT UNSIGNED   AUTO_INCREMENT PRIMARY KEY,
    usuario_id     INT UNSIGNED   NOT NULL,
    produto_id     INT UNSIGNED   NOT NULL,
    quantidade     INT UNSIGNED   NOT NULL DEFAULT 1,
    valor_unitario DECIMAL(10,2)  NOT NULL,
    data_pedido    DATE           NOT NULL,
    status         ENUM('processando','em_transito','entregue','cancelado') NOT NULL DEFAULT 'processando',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabela: clientes
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS clientes (
    id             INT UNSIGNED   AUTO_INCREMENT PRIMARY KEY,
    nome           VARCHAR(150)   NOT NULL,
    email          VARCHAR(200)   NOT NULL,
    telefone       VARCHAR(30)    NOT NULL DEFAULT '',
    cpf            VARCHAR(20)    NOT NULL DEFAULT '',
    endereco       VARCHAR(300)   NOT NULL DEFAULT '',
    data_cadastro  DATE           NOT NULL,
    total_compras  INT UNSIGNED   NOT NULL DEFAULT 0,
    valor_total    DECIMAL(12,2)  NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabela: locais
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS locais (
    id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome      VARCHAR(150) NOT NULL,
    endereco  VARCHAR(200) NOT NULL,
    bairro    VARCHAR(100) NOT NULL DEFAULT '',
    cidade    VARCHAR(100) NOT NULL DEFAULT '',
    estado    CHAR(2)      NOT NULL DEFAULT '',
    cep       VARCHAR(10)  NOT NULL DEFAULT '',
    telefone  VARCHAR(30)  NOT NULL DEFAULT '',
    horario   VARCHAR(200) NOT NULL DEFAULT '',
    mapa      VARCHAR(500) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  DADOS INICIAIS
-- ============================================================

-- ------------------------------------------------------------
-- Produtos
-- ------------------------------------------------------------
INSERT INTO produtos (nome, categoria, preco, estoque, imagem, descricao) VALUES
('Cimento CP-II 50kg',        'Cimentos',             39.90,  150,  'https://placehold.co/400x300/e8e8e8/555?text=Cimento+CP-II',    'Cimento Portland Composto de alta qualidade, ideal para construções em geral, argamassas e concreto.'),
('Tijolo Cerâmico 8 furos',   'Tijolos',               1.20, 5000,  'https://placehold.co/400x300/c8a06e/fff?text=Tijolo+8+Furos',   'Tijolo cerâmico de 8 furos para alvenaria, resistente e com excelente acabamento.'),
('Areia Média Lavada',         'Agregados',           180.00,   80,  'https://placehold.co/400x300/d4b483/333?text=Areia+Media',      'Areia média lavada de alta qualidade, ideal para argamassas, concreto e assentamento de piso.'),
('Brita 0 Granito',            'Agregados',           210.00,   60,  'https://placehold.co/400x300/888/fff?text=Brita+0',             'Brita granítica número 0, utilizada em concretos estruturais e argamassas de alta resistência.'),
('Tinta Acrílica Branca 18L',  'Tintas',              189.90,   40,  'https://placehold.co/400x300/f0f0f0/333?text=Tinta+Acrilica',   'Tinta acrílica premium para ambientes internos e externos, alta cobertura e durabilidade.'),
('Tubo PVC Esgoto 100mm',      'Hidráulica',           42.50,  200,  'https://placehold.co/400x300/b0d0e8/333?text=Tubo+PVC',         'Tubo de PVC para sistema de esgoto sanitário, resistente e de fácil instalação.'),
('Vergalhão CA-50 10mm',       'Ferro e Aço',          28.00,  300,  'https://placehold.co/400x300/777/fff?text=Vergalhao+CA-50',     'Vergalhão de aço CA-50 para uso em estruturas de concreto armado.'),
('Telha Cerâmica Portuguesa',  'Coberturas',            2.80, 2000,  'https://placehold.co/400x300/c0604a/fff?text=Telha+Ceramica',   'Telha cerâmica no estilo português, resistente às intempéries e de alto isolamento térmico.'),
('Piso Cerâmico 60x60cm',      'Pisos e Revestimentos',55.00,  500,  'https://placehold.co/400x300/e0e0e0/555?text=Piso+Ceramico',   'Piso cerâmico retificado 60x60 cm, ideal para ambientes internos e externos.');

-- ------------------------------------------------------------
-- Especificações dos produtos (produto_id segue a ordem INSERT)
-- ------------------------------------------------------------

-- Produto 1 — Cimento CP-II
INSERT INTO produto_especificacoes (produto_id, chave, valor) VALUES
(1, 'Peso',        '50 kg'),
(1, 'Tipo',        'CP-II-E-32'),
(1, 'Norma',       'ABNT NBR 11578'),
(1, 'Rendimento',  'Aprox. 2 m² por saco (reboco)'),
(1, 'Validade',    '3 meses');

-- Produto 2 — Tijolo
INSERT INTO produto_especificacoes (produto_id, chave, valor) VALUES
(2, 'Dimensões',   '9x14x19 cm'),
(2, 'Material',    'Cerâmica'),
(2, 'Furos',       '8'),
(2, 'Resistência', '3 MPa'),
(2, 'Unidade',     'Por peça');

-- Produto 3 — Areia
INSERT INTO produto_especificacoes (produto_id, chave, valor) VALUES
(3, 'Granulometria', 'Média'),
(3, 'Volume',        '1 m³'),
(3, 'Origem',        'Rio'),
(3, 'Lavada',        'Sim'),
(3, 'Entrega',       'Em caminhão basculante');

-- Produto 4 — Brita
INSERT INTO produto_especificacoes (produto_id, chave, valor) VALUES
(4, 'Tipo',      'Brita 0'),
(4, 'Material',  'Granito'),
(4, 'Volume',    '1 m³'),
(4, 'Diâmetro',  '4,8 a 9,5 mm'),
(4, 'Norma',     'ABNT NBR 7211');

-- Produto 5 — Tinta
INSERT INTO produto_especificacoes (produto_id, chave, valor) VALUES
(5, 'Volume',      '18 litros'),
(5, 'Tipo',        'Acrílica'),
(5, 'Rendimento',  'Até 180 m² (2 demãos)'),
(5, 'Acabamento',  'Fosco'),
(5, 'Secagem',     '2 horas ao toque');

-- Produto 6 — Tubo PVC
INSERT INTO produto_especificacoes (produto_id, chave, valor) VALUES
(6, 'Diâmetro',    '100 mm'),
(6, 'Comprimento', '6 metros'),
(6, 'Material',    'PVC Rígido'),
(6, 'Série',       'Normal (SN)'),
(6, 'Norma',       'ABNT NBR 5688');

-- Produto 7 — Vergalhão
INSERT INTO produto_especificacoes (produto_id, chave, valor) VALUES
(7, 'Bitola',      '10 mm'),
(7, 'Comprimento', '12 metros'),
(7, 'Tipo',        'CA-50'),
(7, 'Norma',       'ABNT NBR 7480'),
(7, 'Peso',        'Aprox. 7,4 kg');

-- Produto 8 — Telha
INSERT INTO produto_especificacoes (produto_id, chave, valor) VALUES
(8, 'Dimensões',   '40x16 cm'),
(8, 'Material',    'Cerâmica'),
(8, 'Rendimento',  '16 telhas/m²'),
(8, 'Cor',         'Barro natural'),
(8, 'Unidade',     'Por peça');

-- Produto 9 — Piso
INSERT INTO produto_especificacoes (produto_id, chave, valor) VALUES
(9, 'Dimensões',   '60x60 cm'),
(9, 'Espessura',   '8 mm'),
(9, 'Caixa',       '2,16 m² (6 peças)'),
(9, 'Acabamento',  'Brilhante'),
(9, 'PEI',         '4');

-- ------------------------------------------------------------
-- Clientes
-- ------------------------------------------------------------
INSERT INTO clientes (nome, email, telefone, cpf, endereco, data_cadastro, total_compras, valor_total) VALUES
('João Silva',     'joao.silva@email.com',      '(11) 98765-4321', '123.456.789-00', 'Rua das Flores, 123 - São Paulo/SP',       '2024-01-15', 3,  1250.00),
('Maria Oliveira', 'maria.oliveira@email.com',  '(11) 97654-3210', '987.654.321-00', 'Av. Paulista, 456 - São Paulo/SP',         '2024-02-20', 7,  4380.50),
('Carlos Pereira', 'carlos.p@email.com',        '(11) 96543-2109', '456.789.123-00', 'Rua do Comércio, 789 - Guarulhos/SP',      '2024-03-10', 2,   780.00),
('Ana Costa',      'ana.costa@email.com',       '(11) 95432-1098', '321.654.987-00', 'Rua Nova, 321 - Osasco/SP',                '2024-04-05', 5,  2100.00),
('Roberto Lima',   'roberto.lima@email.com',    '(11) 94321-0987', '654.321.098-00', 'Av. Brasil, 654 - Santo André/SP',         '2024-04-22', 12, 8960.00),
('Fernanda Souza', 'fernanda.s@email.com',      '(11) 93210-9876', '789.012.345-00', 'Rua das Palmeiras, 987 - Mauá/SP',         '2024-05-18', 1,   420.00);

-- ------------------------------------------------------------
-- Usuários (senhas: admin123 e cliente123)
-- ------------------------------------------------------------
-- admin123 / cliente123
INSERT INTO usuarios (nome, email, senha_hash, telefone, tipo) VALUES
('Administrador',  'admin@construtormax.com',   '$2y$12$vSWj2IWU/fdk9o6Mq/0gEeoWZAL2vmOxNGGwj8.Wlhk.Xrgpz9vdy', '(11) 3000-0000', 'admin'),
('João Silva',     'joao.silva@email.com',       '$2y$12$uRhwUU.JvCWDEF/u2M.rf.lb1Xz91nSG0rUMhlsvNWoUYqi7BhoDC', '(11) 98765-4321', 'cliente'),
('Maria Oliveira', 'maria.oliveira@email.com',   '$2y$12$uRhwUU.JvCWDEF/u2M.rf.lb1Xz91nSG0rUMhlsvNWoUYqi7BhoDC', '(11) 97654-3210', 'cliente');

-- ------------------------------------------------------------
-- Pedidos de demonstração
-- ------------------------------------------------------------
INSERT INTO pedidos (usuario_id, produto_id, quantidade, valor_unitario, data_pedido, status) VALUES
(2, 1, 10, 39.90, '2024-02-10', 'entregue'),
(2, 3,  2, 180.00, '2024-03-15', 'entregue'),
(2, 7,  5, 28.00, '2024-04-01', 'em_transito'),
(3, 5,  1, 189.90, '2024-03-20', 'entregue'),
(3, 8, 50,  2.80, '2024-04-10', 'entregue'),
(3, 9,  3, 55.00, '2024-04-22', 'processando'),
(3, 6,  4, 42.50, '2024-05-05', 'em_transito');

-- ------------------------------------------------------------
-- Locais de retirada
-- ------------------------------------------------------------
INSERT INTO locais (nome, endereco, bairro, cidade, estado, cep, telefone, horario, mapa) VALUES
('Matriz - Centro', 'Rua da Construção, 100', 'Centro',    'São Paulo', 'SP', '01000-000', '(11) 3000-1000', 'Seg-Sex: 07h às 18h | Sáb: 07h às 13h', 'https://www.google.com/maps'),
('Filial Norte',    'Av. das Indústrias, 500', 'Zona Norte','São Paulo', 'SP', '02000-000', '(11) 3000-2000', 'Seg-Sex: 07h às 17h | Sáb: 08h às 12h', 'https://www.google.com/maps'),
('Filial Sul',      'Rua do Material, 250',    'Zona Sul',  'São Paulo', 'SP', '04000-000', '(11) 3000-3000', 'Seg-Sex: 07h às 18h | Sáb: 07h às 13h', 'https://www.google.com/maps');
