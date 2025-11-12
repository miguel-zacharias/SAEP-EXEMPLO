-- script_banco.sql
-- Cria o banco de dados e as tabelas solicitadas e insere dados de teste

DROP DATABASE IF EXISTS mercado_db;
CREATE DATABASE mercado_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE mercado_db;

-- Tabela usuarios
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL,
  usuario VARCHAR(100) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabela produtos
CREATE TABLE produtos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL,
  codigo_barras VARCHAR(100) UNIQUE,
  categoria VARCHAR(100),
  marca VARCHAR(100),
  preco_custo DECIMAL(10,2),
  preco_venda DECIMAL(10,2),
  estoque_atual INT NOT NULL DEFAULT 0,
  estoque_minimo INT NOT NULL DEFAULT 10,
  data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabela movimentacoes
CREATE TABLE movimentacoes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  produto_id INT NOT NULL,
  usuario_id INT NOT NULL,
  tipo ENUM('entrada','saida') NOT NULL,
  quantidade INT NOT NULL,
  data_movimentacao DATE NOT NULL,
  data_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
  observacao TEXT,
  FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Inserir usuarios de teste (3)
-- Senha: 123456 (hash fornecido no enunciado)
INSERT INTO usuarios (nome, usuario, senha) VALUES
('Administrador', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Maria Silva', 'maria', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Jose Pereira', 'jose', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Inserir 15 produtos variados
INSERT INTO produtos (nome, codigo_barras, categoria, marca, preco_custo, preco_venda, estoque_atual, estoque_minimo) VALUES
('Arroz Tipo 1', '7890000000010', 'Alimentos', 'ArrozBom', 20.00, 28.00, 50, 10),
('Feijao Preto', '7890000000027', 'Alimentos', 'FeijaoTop', 8.50, 12.00, 5, 10),
('Refrigerante Cola 2L', '7890000000034', 'Bebidas', 'Fresca', 3.50, 6.50, 20, 10),
('Suco de Laranja 1L', '7890000000041', 'Bebidas', 'Suca', 4.00, 7.00, 8, 10),
('Sabonete 90g', '7890000000058', 'Higiene', 'Limpeza+','0.80', 1.50, 30, 10),
('Shampoo 400ml', '7890000000065', 'Higiene', 'Capilar', 5.00, 9.00, 12, 10),
('Detergente Liquido', '7890000000072', 'Limpeza', 'Brilha', 2.00, 4.50, 3, 10),
('Agua Sanitaria 1L', '7890000000089', 'Limpeza', 'Sanit', 1.20, 2.50, 0, 10),
('Banana Nanica', '7890000000096', 'Hortifruti', 'Fazenda', 1.00, 1.80, 25, 10),
('Tomate', '7890000000102', 'Hortifruti', 'Horti', 2.00, 3.50, 4, 10),
('Pao Frances', '7890000000119', 'Padaria', 'Padoca', 0.10, 0.25, 40, 10),
('Bolo de Chocolate', '7890000000126', 'Padaria', 'Doces', 8.00, 15.00, 2, 10),
('Carne Bovina', '7890000000133', 'Acougue', 'CasaCarnes', 20.00, 35.00, 6, 10),
('Frango Inteiro', '7890000000140', 'Acougue', 'Frangueira', 10.00, 18.00, 15, 10),
('Pilhas AA', '7890000000157', 'Outros', 'PilhaBoa', 1.50, 3.50, 18, 10);

-- Inserir 20 movimentacoes (histórico) para justificar os estoques atuais
-- Observações: os IDs de produtos/usuarios assumem ordem de insercao acima

-- Arroz (produto_id = 1): entrada 80, saida 30 => estoque 50
INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade, data_movimentacao, observacao) VALUES
(1, 1, 'entrada', 80, '2025-10-01', 'Entrada inicial de estoque'),
(1, 2, 'saida', 30, '2025-10-10', 'Venda/saida');

-- Feijao (2): entrada 20, saida 15 => estoque 5
INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade, data_movimentacao, observacao) VALUES
(2, 2, 'entrada', 20, '2025-09-15', 'Compra fornecedor'),
(2, 3, 'saida', 15, '2025-09-20', 'Saida para venda');

-- Refrigerante (3): entrada 20 => estoque 20
INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade, data_movimentacao, observacao) VALUES
(3, 1, 'entrada', 20, '2025-10-05', 'Reposicao bebida');

-- Suco (4): entrada 10, saida 2 => estoque 8
INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade, data_movimentacao, observacao) VALUES
(4, 2, 'entrada', 10, '2025-10-07', 'Entrada lote'),
(4, 3, 'saida', 2, '2025-10-12', 'Venda/saida');

-- Sabonete (5): entrada 30 => estoque 30
INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade, data_movimentacao, observacao) VALUES
(5, 1, 'entrada', 30, '2025-10-02', 'Entrada caixa');

-- Shampoo (6): entrada 15, saida 3 => estoque 12
INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade, data_movimentacao, observacao) VALUES
(6, 2, 'entrada', 15, '2025-09-25', 'Compra fornecedor'),
(6, 3, 'saida', 3, '2025-10-03', 'Venda');

-- Detergente (7): entrada 3 => estoque 3
INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade, data_movimentacao, observacao) VALUES
(7, 1, 'entrada', 3, '2025-10-08', 'Reposicao pequena');

-- Agua Sanitaria (8): entrada 10, saida 10 => estoque 0 (esgotado)
INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade, data_movimentacao, observacao) VALUES
(8, 1, 'entrada', 10, '2025-09-20', 'Compra'),
(8, 2, 'saida', 10, '2025-10-01', 'Vendas');

-- Banana (9): entrada 25 => estoque 25
INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade, data_movimentacao, observacao) VALUES
(9, 3, 'entrada', 25, '2025-11-01', 'Fornecimento hortifruti');

-- Tomate (10): entrada 10, saida 6 => estoque 4
INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade, data_movimentacao, observacao) VALUES
(10, 2, 'entrada', 10, '2025-10-20', 'Entrada diaria'),
(10, 3, 'saida', 6, '2025-10-22', 'Vendas');

-- Pao Frances (11): entrada 40 => estoque 40
INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade, data_movimentacao, observacao) VALUES
(11, 1, 'entrada', 40, '2025-11-05', 'Fornecimento padaria');

-- Bolo de Chocolate (12): entrada 5, saida 3 => estoque 2
INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade, data_movimentacao, observacao) VALUES
(12, 3, 'entrada', 5, '2025-11-04', 'Entrega confeitaria'),
(12, 1, 'saida', 3, '2025-11-04', 'Vendas');

-- Carne Bovina (13): entrada 10, saida 4 => estoque 6
INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade, data_movimentacao, observacao) VALUES
(13, 2, 'entrada', 10, '2025-10-15', 'Compra frigorifico'),
(13, 3, 'saida', 4, '2025-10-18', 'Venda');

-- Frango Inteiro (14): entrada 15 => estoque 15
INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade, data_movimentacao, observacao) VALUES
(14, 1, 'entrada', 15, '2025-10-30', 'Compra fornecedor');

-- Pilhas AA (15): entrada 30, saida 12 => estoque 18
INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade, data_movimentacao, observacao) VALUES
(15, 2, 'entrada', 30, '2025-09-10', 'Compra'),
(15, 3, 'saida', 12, '2025-10-10', 'Vendas');

-- SELECTs de validacao
SELECT 'USUARIOS' as tabela, COUNT(*) as total FROM usuarios;
SELECT * FROM usuarios;
SELECT 'PRODUTOS' as tabela, COUNT(*) as total FROM produtos;
SELECT id, nome, estoque_atual, estoque_minimo FROM produtos ORDER BY nome;
SELECT 'MOVIMENTACOES' as tabela, COUNT(*) as total FROM movimentacoes;
SELECT m.id, m.produto_id, p.nome as produto, m.usuario_id, u.nome as usuario, m.tipo, m.quantidade, m.data_movimentacao, m.data_registro, m.observacao
FROM movimentacoes m
LEFT JOIN produtos p ON m.produto_id = p.id
LEFT JOIN usuarios u ON m.usuario_id = u.id
ORDER BY m.data_registro DESC
LIMIT 20;
