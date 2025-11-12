<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Produtos - Lista</title>
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
        }
        .app {
            display: grid;
            grid-template-columns: 240px 1fr;
            grid-template-rows: auto 1fr;
            min-height: 100vh;
            align-items: start;
        }
        .sidebar {
            background: #18213a;
            padding: 12px 10px;
            border-right: 1px solid #232c43;
            min-height: calc(100vh - 56px);
            grid-row: 2;
        }
        .user { display: flex; gap: 12px; align-items: center; margin-bottom: 24px; }
        .avatar { width: 44px; height: 44px; border-radius: 10px; background: #2196f3; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.3rem; box-shadow: 0 0 12px #2196f355; }
        .menu { display: flex; flex-direction: column; gap: 8px; }
        .menu a { color: #b0b8c9; text-decoration: none; padding: 10px 12px; border-radius: 8px; transition: background 0.2s, color 0.2s; display: flex; align-items: center; gap: 10px; }
        .menu a.active, .menu a:hover { background: #2196f3; color: #fff; }
    .navbar { background: #232c43; color: #fff; padding: 0 16px; height: 56px; display: flex; align-items: center; border-bottom: 1px solid #232c43; grid-column: 1 / -1; }
        .brand { display: flex; align-items: center; gap: 12px; font-weight: 600; }
        .logo { width: 38px; height: 38px; border-radius: 8px; background: #2979ff; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 1.1rem; }
        .topbar-actions { margin-left: auto; display: flex; gap: 12px; align-items: center; }
        .nav-item { color: #b0b8c9; }
    .content { padding: 8px 10px; grid-row: 2; }
        .container { max-width: 1100px; margin: 0 auto; }
    .card { background: #232c43; border-radius: 10px; padding: 12px; box-shadow: 0 6px 18px rgba(16,24,42,0.14); margin-bottom: 12px; }
    h1, h2 { margin-top: 0; margin-bottom:6px; }
    h1 { color: #2196f3; font-size: 1.6rem; text-align: center; margin-top:6px; }
    h2 { color: #1565c0; font-size: 1.05rem; }
    .form { display: flex; flex-direction: column; gap: 8px; }
        .label { font-size: 14px; color: #b0b8c9; margin-bottom: 2px; }
    .input { background: #18213a; border: 1px solid #2979ff; color: #f4f7fa; padding: 8px 10px; border-radius: 6px; outline: none; transition: box-shadow 0.12s, border-color 0.12s; font-size: 0.98rem; }
        .input:focus { border-color: #2196f3; box-shadow: 0 0 0 2px #2196f355; }
        .btn { border: none; border-radius: 8px; padding: 10px 18px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s, color 0.2s, box-shadow 0.2s; }
        .btn-primary { background: linear-gradient(90deg,#2196f3,#2979ff); color: #fff; box-shadow: 0 2px 12px #2196f344; }
        .btn-primary:hover { background: #2979ff; }
        .btn-ghost { background: transparent; color: #2196f3; border: 1px solid #2196f3; }
        .btn-ghost:hover { background: #2196f3; color: #fff; }
        .center { display: flex; align-items: center; justify-content: center; }
        .small { font-size: 13px; color: #b0b8c9; }
    .table-wrap { overflow-x: auto; border-radius: 6px; }
        .table { width: 100%; border-collapse: collapse; background: #232c43; color: #f4f7fa; }
        .table thead th { text-align: left; font-weight: 600; padding: 12px 14px; color: #b0b8c9; font-size: 14px; background: #18213a; }
        .table tbody td { padding: 12px 14px; border-bottom: 1px solid #18213a; }
        .table tbody tr:nth-child(odd) { background: #232c43; }
        .table tbody tr:nth-child(even) { background: #1a2233; }
        .table tbody tr:hover { background: #2196f322; }
        @media (max-width: 900px) {
            .app { grid-template-columns: 70px 1fr; }
            .sidebar { padding: 12px 4px; }
            .menu a .label { display: none; }
        }
        @media (max-width: 600px) {
            .app { grid-template-columns: 1fr; }
            .sidebar { display: none; }
            .content { padding: 12px 2px; }
        }
        </style>
</head>
<body>
    <div class="app">
        <aside class="sidebar">
            <div class="user">
                <div class="avatar"><?= strtoupper(substr(trim($usuario_nome ?? 'U'),0,1)) ?></div>
                <div>
                    <div style="font-weight:600;color:var(--text)">Olá,</div>
                    <div class="small"><?= htmlspecialchars($usuario_nome ?? 'Usuário') ?></div>
                </div>
            </div>

            <nav class="menu">
                <a href="index.php?action=home"><span class="icon">🏠</span><span class="label">Home</span></a>
                <a href="index.php?action=produtos" class="active"><span class="icon">📦</span><span class="label">Produtos</span></a>
                <a href="index.php?action=estoque"><span class="icon">🧾</span><span class="label">Estoque</span></a>
                <a href="index.php?action=logout"><span class="icon">🚪</span><span class="label">Sair</span></a>
            </nav>
        </aside>

        <header class="navbar">
            <div class="brand"><div class="logo">MG</div><div>mgv_market</div></div>
            <div class="topbar-actions">
                <div class="nav-item">Usuário: <strong><?= htmlspecialchars($usuario_nome ?? '') ?></strong></div>
            </div>
        </header>

    <main class="content container" style="margin-top:0 !important; padding-top:0 !important;">
            <?php if ($mensagem): ?>
                <div class="card" style="margin-bottom:12px;"><div class="small"><?= htmlspecialchars($mensagem) ?></div></div>
            <?php endif; ?>




            <!-- Formulário de cadastro -->
            <section class="card" style="max-width:760px;margin:0 auto 12px auto;">
                <h2 style="color:#1565c0;">Cadastrar Produto</h2>
                <form method="post" action="index.php?action=produtos-cadastrar" class="form">
                    <label class="label">Nome</label>
                    <input class="input" name="nome" required>
                    <label class="label">Código de Barras</label>
                    <input class="input" name="codigo_barras">
                    <label class="label">Categoria</label>
                    <input class="input" name="categoria">
                    <label class="label">Marca</label>
                    <input class="input" name="marca">
                    <label class="label">Preço Custo</label>
                    <input class="input" name="preco_custo" type="number" step="0.01">
                    <label class="label">Preço Venda</label>
                    <input class="input" name="preco_venda" type="number" step="0.01">
                    <label class="label">Estoque Atual</label>
                    <input class="input" name="estoque_atual" type="number" value="0" required>
                    <label class="label">Estoque Mínimo</label>
                    <input class="input" name="estoque_minimo" type="number" value="10" required>
                    <div class="center" style="margin-top:12px;"><button class="btn btn-primary" type="submit">Salvar</button></div>
                </form>
            </section>

            <!-- Formulário de busca -->
            <section class="card" style="max-width:760px;margin:0 auto 12px auto;">
                <h1 style="text-align:center;color:#2196f3;margin-top:0;">Produtos</h1>
                <form method="get" action="index.php" class="form">
                    <input type="hidden" name="action" value="produtos">
                    <input class="input" type="text" name="busca" placeholder="Buscar..." value="<?php echo isset($_GET['busca']) ? htmlspecialchars($_GET['busca']) : '' ?>">
                    <div class="center" style="margin-top:8px;"><button class="btn btn-primary" type="submit">Buscar</button></div>
                </form>
            </section>

            <!-- Tabela de produtos -->
            <section class="card" style="padding:12px;margin-top:0;">
                <h2 style="color:#2196f3;">Lista de Produtos</h2>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Código</th>
                                <th>Categoria</th>
                                <th>Marca</th>
                                <th>Preço Venda</th>
                                <th>Estoque</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($produtos)): ?>
                                <tr>
                                    <td colspan="8" style="text-align:center;color:#b0b8c9;font-size:1.1rem;">Nenhum produto cadastrado.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($produtos as $p): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($p['id']); ?></td>
                                        <td><?php echo htmlspecialchars($p['nome']); ?></td>
                                        <td><?php echo htmlspecialchars($p['codigo_barras']); ?></td>
                                        <td><?php echo htmlspecialchars($p['categoria']); ?></td>
                                        <td><?php echo htmlspecialchars($p['marca']); ?></td>
                                        <td><?php echo isset($p['preco_venda']) ? number_format($p['preco_venda'],2,',','.') : ''; ?></td>
                                        <td>
                                            <?php
                                                $status = 'OK';
                                                if ((int)$p['estoque_atual'] <= 0) $status = 'Esgotado';
                                                elseif ((int)$p['estoque_atual'] < (int)$p['estoque_minimo']) $status = 'Baixo';
                                            ?>
                                            <?php echo htmlspecialchars($p['estoque_atual']) . ' (' . $status . ')'; ?>
                                        </td>
                                        <td>
                                            <form method="post" action="index.php?action=produtos-editar" style="display:inline">
                                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($p['id']); ?>">
                                                <input type="hidden" name="nome" value="<?php echo htmlspecialchars($p['nome']); ?>">
                                                <button class="btn btn-ghost" type="submit">Editar</button>
                                            </form>
                                            <form method="post" action="index.php?action=produtos-excluir" style="display:inline" onsubmit="return confirm('Confirma exclusao?')">
                                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($p['id']); ?>">
                                                <button class="btn btn-ghost" type="submit">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <p class="small center" style="margin-top:18px;"><a href="index.php?action=home">Voltar</a></p>
        </main>
    </div>
</body>
</html>
