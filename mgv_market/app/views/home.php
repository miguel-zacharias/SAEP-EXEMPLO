<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home - Sistema de Estoque</title>
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
        // fallback: use a sane default so the link tag exists (will 404 if wrong)
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
        .home-card {
            background: #232c43;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(16,24,42,0.18);
            padding: 36px 32px 28px 32px;
            max-width: 400px;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 18px;
            align-items: center;
        }
        h1 { color: #2196f3; text-align: center; margin: 0 0 12px 0; }
        ul { list-style: none; padding: 0; margin: 0; width: 100%; }
        ul li { margin: 12px 0; }
        ul li a {
            display: block;
            background: #2196f3;
            color: #fff;
            text-decoration: none;
            padding: 12px 0;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            transition: background 0.2s;
        }
        ul li a:hover { background: #2979ff; }
        .usuario { color: #b0b8c9; font-size: 1rem; margin-bottom: 8px; }
        </style>
</head>
<body>
        <div class="home-card">
            <h1>Menu Principal</h1>
            <div class="usuario">Usuário: <?php echo htmlspecialchars($usuario_nome); ?></div>
            <ul>
                <li><a href="index.php?action=produtos">Cadastro de Produtos</a></li>
                <li><a href="index.php?action=estoque">Gestão de Estoque</a></li>
                <li><a href="index.php?action=logout">Logout</a></li>
            </ul>
        </div>
</body>
</html>
