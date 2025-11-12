<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Sistema de Estoque</title>
    <?php
        $docRoot = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\');
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
        if ($base === '.' || $base === '\\' || $base === '/') $base = '';
        $candidates = [
            $base . '/css/style.css',
            $base . '/public/css/style.css',
            '/public/css/style.css',
            '/css/style.css',
            '/mgv_market/public/css/style.css',
            '/mgv_market/css/style.css'
        ];
        $cssHref = null;
        foreach ($candidates as $c) {
            if (file_exists($docRoot . $c)) { $cssHref = $c; break; }
        }
        if (!$cssHref) $cssHref = '/mgv_market/public/css/style.css';
    ?>
        <style>
        body {
            background: linear-gradient(180deg, #10182a 0%, #18213a 100%);
            color: #f4f7fa;
            font-family: 'Inter', 'Poppins', 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #232c43;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(16,24,42,0.18);
            padding: 36px 32px 28px 32px;
            max-width: 350px;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        h1 { color: #2196f3; text-align: center; margin: 0 0 12px 0; }
        .form { display: flex; flex-direction: column; gap: 12px; }
        label { color: #b0b8c9; font-size: 14px; margin-bottom: 2px; }
        input[type="text"], input[type="password"] {
            background: #18213a;
            border: 1px solid #2979ff;
            color: #f4f7fa;
            padding: 10px 12px;
            border-radius: 8px;
            outline: none;
            font-size: 1rem;
            transition: box-shadow 0.2s, border-color 0.2s;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #2196f3;
            box-shadow: 0 0 0 2px #2196f355;
        }
        button[type="submit"] {
            border: none;
            border-radius: 8px;
            padding: 12px 0;
            font-size: 1rem;
            font-weight: 600;
            background: linear-gradient(90deg,#2196f3,#2979ff);
            color: #fff;
            box-shadow: 0 2px 12px #2196f344;
            cursor: pointer;
            margin-top: 8px;
            transition: background 0.2s;
        }
        button[type="submit"]:hover { background: #2979ff; }
        .msg { color: #ff5252; text-align: center; font-size: 14px; margin-bottom: 8px; }
        </style>
</head>
<body>
        <div class="login-card">
            <h1>Login</h1>
            <?php if (!empty($_SESSION['flash']['mensagem'])): ?>
                <div class="msg"><?php echo htmlspecialchars($_SESSION['flash']['mensagem']); unset($_SESSION['flash']['mensagem']); ?></div>
            <?php endif; ?>
            <form method="post" action="index.php?action=autenticar" class="form">
                <label for="usuario">Usuário</label>
                <input type="text" id="usuario" name="usuario" required>
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
                <button type="submit">Entrar</button>
            </form>
        </div>
</body>
</html>
