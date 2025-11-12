<?php
/**
 * Model Produto
 * Operacoes relacionadas a produtos do mercado
 */

class Produto {
    private $pdo;

    public function __construct() {
        $this->pdo = conectarBanco();
    }

    public function buscarTodos() {
        $stmt = $this->pdo->query("SELECT * FROM produtos ORDER BY nome");
        return $stmt->fetchAll();
    }

    public function buscar($termo = "") {
        if (!empty($termo)) {
            $t = "%" . $termo . "%";
            $stmt = $this->pdo->prepare(
                "SELECT * FROM produtos WHERE nome LIKE ? OR codigo_barras LIKE ? OR categoria LIKE ? OR marca LIKE ? ORDER BY nome"
            );
            $stmt->execute([$t, $t, $t, $t]);
        } else {
            $stmt = $this->pdo->query("SELECT * FROM produtos ORDER BY nome");
        }
        return $stmt->fetchAll();
    }

    public function buscarPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM produtos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function cadastrar($dados) {
        $erros = $this->validar($dados);
        if (!empty($erros)) {
            return ['sucesso' => false, 'mensagem' => implode('<br>', $erros)];
        }

        try {
            $sql = "INSERT INTO produtos (nome, codigo_barras, categoria, marca, preco_custo, preco_venda, estoque_atual, estoque_minimo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                sanitizar($dados['nome']),
                sanitizar($dados['codigo_barras'] ?? null),
                sanitizar($dados['categoria'] ?? null),
                sanitizar($dados['marca'] ?? null),
                $dados['preco_custo'] !== '' ? $dados['preco_custo'] : null,
                $dados['preco_venda'] !== '' ? $dados['preco_venda'] : null,
                $dados['estoque_atual'] ?? 0,
                $dados['estoque_minimo'] ?? 10
            ]);

            return ['sucesso' => true, 'mensagem' => 'Produto cadastrado com sucesso!'];
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return ['sucesso' => false, 'mensagem' => 'Codigo de barras ja cadastrado no sistema.'];
            }
            return ['sucesso' => false, 'mensagem' => 'Erro ao cadastrar produto: ' . $e->getMessage()];
        }
    }

    public function atualizar($id, $dados) {
        $erros = $this->validar($dados);
        if (!empty($erros)) {
            return ['sucesso' => false, 'mensagem' => implode('<br>', $erros)];
        }

        try {
            $sql = "UPDATE produtos SET nome = ?, codigo_barras = ?, categoria = ?, marca = ?, preco_custo = ?, preco_venda = ?, estoque_atual = ?, estoque_minimo = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                sanitizar($dados['nome']),
                sanitizar($dados['codigo_barras'] ?? null),
                sanitizar($dados['categoria'] ?? null),
                sanitizar($dados['marca'] ?? null),
                $dados['preco_custo'] !== '' ? $dados['preco_custo'] : null,
                $dados['preco_venda'] !== '' ? $dados['preco_venda'] : null,
                $dados['estoque_atual'] ?? 0,
                $dados['estoque_minimo'] ?? 10,
                $id
            ]);

            return ['sucesso' => true, 'mensagem' => 'Produto atualizado com sucesso!'];
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return ['sucesso' => false, 'mensagem' => 'Codigo de barras ja cadastrado no sistema.'];
            }
            return ['sucesso' => false, 'mensagem' => 'Erro ao atualizar produto: ' . $e->getMessage()];
        }
    }

    public function excluir($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM produtos WHERE id = ?");
            $stmt->execute([$id]);
            return ['sucesso' => true, 'mensagem' => 'Produto excluido com sucesso!'];
        } catch (PDOException $e) {
            return ['sucesso' => false, 'mensagem' => 'Erro ao excluir produto: ' . $e->getMessage()];
        }
    }

    public function ordenarPorNome(array $produtos) {
        $n = count($produtos);
        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = 0; $j < $n - $i - 1; $j++) {
                if (strcasecmp($produtos[$j]['nome'], $produtos[$j + 1]['nome']) > 0) {
                    $temp = $produtos[$j];
                    $produtos[$j] = $produtos[$j + 1];
                    $produtos[$j + 1] = $temp;
                }
            }
        }
        return $produtos;
    }

    public function atualizarEstoque($id, $novoEstoque) {
        $stmt = $this->pdo->prepare("UPDATE produtos SET estoque_atual = ? WHERE id = ?");
        return $stmt->execute([$novoEstoque, $id]);
    }

    private function validar(array $dados) {
        $erros = [];
        if (empty($dados['nome'])) {
            $erros[] = 'Nome e obrigatorio';
        }
        if (isset($dados['estoque_atual']) && (!is_numeric($dados['estoque_atual']) || $dados['estoque_atual'] < 0)) {
            $erros[] = 'Estoque atual deve ser um numero valido';
        }
        if (isset($dados['estoque_minimo']) && (!is_numeric($dados['estoque_minimo']) || $dados['estoque_minimo'] < 0)) {
            $erros[] = 'Estoque minimo deve ser um numero valido';
        }
        if (isset($dados['preco_custo']) && $dados['preco_custo'] !== '' && !is_numeric($dados['preco_custo'])) {
            $erros[] = 'Preco custo invalido';
        }
        if (isset($dados['preco_venda']) && $dados['preco_venda'] !== '' && !is_numeric($dados['preco_venda'])) {
            $erros[] = 'Preco venda invalido';
        }
        return $erros;
    }
}
