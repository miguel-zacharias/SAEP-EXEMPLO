<?php
/**
 * Model Movimentacao
 * Registra entradas e saidas de produtos no estoque
 */

class Movimentacao {
    private $pdo;

    public function __construct() {
        $this->pdo = conectarBanco();
    }

    public function registrar($dados, $usuarioId) {
        $erros = $this->validar($dados);
        if (!empty($erros)) {
            return ['sucesso' => false, 'mensagem' => implode('<br>', $erros)];
        }

        try {
            $this->pdo->beginTransaction();

            $produtoModel = new Produto();
            $produto = $produtoModel->buscarPorId($dados['produto_id']);
            if (!$produto) {
                throw new Exception('Produto nao encontrado');
            }

            $novoEstoque = $produto['estoque_atual'];
            if ($dados['tipo'] === 'entrada') {
                $novoEstoque += $dados['quantidade'];
            } else {
                if ($dados['quantidade'] > $produto['estoque_atual']) {
                    throw new Exception('Quantidade de saida maior que estoque disponivel');
                }
                $novoEstoque -= $dados['quantidade'];
            }

            $produtoModel->atualizarEstoque($dados['produto_id'], $novoEstoque);

            $sql = "INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade, data_movimentacao, observacao) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $dados['produto_id'],
                $usuarioId,
                $dados['tipo'],
                $dados['quantidade'],
                $dados['data_movimentacao'],
                sanitizar($dados['observacao'] ?? '')
            ]);

            $this->pdo->commit();

            $alerta = '';
            if ($dados['tipo'] === 'saida' && $novoEstoque < $produto['estoque_minimo']) {
                $deficit = $produto['estoque_minimo'] - $novoEstoque;
                $alerta = "ALERTA: O produto '{$produto['nome']}' esta com estoque abaixo do minimo! Estoque atual: {$novoEstoque} | Estoque minimo: {$produto['estoque_minimo']} | Deficit: {$deficit} unidades";
            }

            return ['sucesso' => true, 'mensagem' => 'Movimentacao registrada com sucesso!', 'alerta' => $alerta];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return ['sucesso' => false, 'mensagem' => 'Erro ao registrar movimentacao: ' . $e->getMessage()];
        }
    }

    public function historico($limite = 20) {
        $stmt = $this->pdo->query(
            "SELECT m.*, p.nome AS nome_produto, p.codigo_barras, u.nome as responsavel
            FROM movimentacoes m
            INNER JOIN produtos p ON m.produto_id = p.id
            INNER JOIN usuarios u ON m.usuario_id = u.id
            ORDER BY m.data_registro DESC
            LIMIT {$limite}"
        );
        return $stmt->fetchAll();
    }

    private function validar(array $dados) {
        $erros = [];
        if (empty($dados['produto_id'])) {
            $erros[] = 'Selecione um produto';
        }
        if (empty($dados['tipo']) || !in_array($dados['tipo'], ['entrada', 'saida'])) {
            $erros[] = 'Tipo de movimentacao invalido';
        }
        if (!is_numeric($dados['quantidade']) || $dados['quantidade'] <= 0) {
            $erros[] = 'Quantidade deve ser maior que zero';
        }
        if (empty($dados['data_movimentacao'])) {
            $erros[] = 'Data da movimentacao e obrigatoria';
        }
        return $erros;
    }
}
