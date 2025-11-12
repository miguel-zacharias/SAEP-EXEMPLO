<?php
class HomeController {
    public function index() {
        verificarLogin();
        $usuario_nome = $_SESSION['usuario_nome'];
        require_once '../views/home.php';
    }
}
