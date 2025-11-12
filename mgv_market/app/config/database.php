<?php
/**
 * Arquivo de configuracao do banco de dados e funcoes auxiliares
 * Conexao PDO para o banco `mercado_db`
 */

function conectarBanco() {
    try {
        $pdo = new PDO(
            "mysql:host=localhost;dbname=mercado_db;charset=utf8mb4",
            "root",
            ""
        );

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        return $pdo;
    } catch (PDOException $e) {
        die("Erro ao conectar ao banco de dados: " . $e->getMessage());
    }
}

function sanitizar($dados) {
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados);
    return $dados;
}

function verificarLogin() {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: index.php?action=login");
        exit;
    }
}
