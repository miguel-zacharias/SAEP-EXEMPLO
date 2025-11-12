<?php
/**
 * Model Usuario
 * Responsavel por todas as operacoes relacionadas a usuarios
 */

class Usuario {
    private $pdo;

    public function __construct() {
        $this->pdo = conectarBanco();
    }

    public function autenticar($usuario, $senha) {
        $usuario = sanitizar($usuario);

        if (empty($usuario) || empty($senha)) {
            return ['sucesso' => false, 'mensagem' => 'Por favor, preencha todos os campos.'];
        }

        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);
        $usuarioDB = $stmt->fetch();

        if (!$usuarioDB) {
            return ['sucesso' => false, 'mensagem' => 'Usuario nao encontrado.'];
        }
        // Primeiro tenta verificar utilizando password_verify (hash bcrypt/argon2 etc.)
        if (password_verify($senha, $usuarioDB['senha'])) {
            return ['sucesso' => true, 'usuario' => $usuarioDB];
        }

        // Verifica se o campo armazenado parece ser um hash SHA-256 (hex de 64 caracteres).
        // Muitos sistemas antigos usavam SHA2(...,256) e armazenavam o valor hexadecimal.
        $stored = $usuarioDB['senha'];
        if (preg_match('/^[0-9a-f]{64}$/i', $stored)) {
            // Compara de forma segura com o SHA-256 da senha informada
            if (hash_equals($stored, hash('sha256', $senha))) {
                // Ao autenticar com sucesso, re-hash para um algoritmo moderno (bcrypt/argon2)
                $novoHash = password_hash($senha, PASSWORD_DEFAULT);
                try {
                    $upd = $this->pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
                    $upd->execute([$novoHash, $usuarioDB['id']]);
                    // Atualiza o valor retornado para refletir o novo hash (opcional)
                    $usuarioDB['senha'] = $novoHash;
                } catch (Exception $e) {
                    // Se a persistência falhar, não impede o login; poderia ser registrado em log.
                }

                return ['sucesso' => true, 'usuario' => $usuarioDB];
            }
        }

        // Caso a senha armazenada esteja em texto puro (migração antiga),
        // compara diretamente. Se bater, re-hash e atualiza o banco automaticamente.
        // Detecta senhas em texto simples por heuristica: se a string armazenada
        // não parece um hash bcrypt/argon2 (não começa com $2y$/$2a$/$argon2 etc.)
        $looks_hashed = (strpos($stored, '$2y$') === 0) || (strpos($stored, '$2a$') === 0) || (strpos($stored, '$argon2') === 0);

        if (!$looks_hashed && hash_equals((string)$stored, (string)$senha)) {
            // Re-hash a senha informada e atualiza o registro do usuario
            $novoHash = password_hash($senha, PASSWORD_DEFAULT);
            try {
                $upd = $this->pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
                $upd->execute([$novoHash, $usuarioDB['id']]);
                // Atualiza o valor retornado para refletir o hash novo (opcional)
                $usuarioDB['senha'] = $novoHash;
            } catch (Exception $e) {
                // Se falhar a persistencia, não impede o login (mas poderia ser logado)
            }

            return ['sucesso' => true, 'usuario' => $usuarioDB];
        }

        // Senha incorreta
        return ['sucesso' => false, 'mensagem' => 'Senha incorreta.'];
    }
}
