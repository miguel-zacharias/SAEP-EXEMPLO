<?php
class LoginController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function mostrarLogin() {
        // Exibe a view de login
        require_once '../views/login.php';
    }

    public function autenticar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=login");
            exit;
        }

        $usuario = $_POST['usuario'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $resultado = $this->usuarioModel->autenticar($usuario, $senha);

        if ($resultado['sucesso']) {
            $_SESSION['usuario_id'] = $resultado['usuario']['id'];
            $_SESSION['usuario_nome'] = $resultado['usuario']['nome'];
            header("Location: index.php?action=home");
            exit;
        } else {
            $_SESSION['flash']['mensagem'] = $resultado['mensagem'];
            $_SESSION['flash']['tipo_mensagem'] = 'error';
            header("Location: index.php?action=login");
            exit;
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }
}
