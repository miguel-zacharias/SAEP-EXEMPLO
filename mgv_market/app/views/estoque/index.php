<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestao de Estoque</title>
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
        }
        .container {
            max-width: 1100px;
            margin: 32px auto;
            background: #232c43;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(16,24,42,0.18);
            padding: 32px 24px;
        }
        h1 { color: #2196f3; text-align: center; margin-top: 0; }
        h2 { color: #1565c0; margin-top: 32px; }
        .usuario { color: #b0b8c9; font-size: 1rem; margin-bottom: 18px; text-align: center; }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #232c43;
            color: #f4f7fa;
            margin-bottom: 24px;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 14px;
            border-bottom: 1px solid #18213a;
            text-align: left;
        }
        th { background: #18213a; color: #b0b8c9; font-size: 14px; }
        tr:nth-child(odd) { background: #232c43; }
        tr:nth-child(even) { background: #1a2233; }
        tr:hover { background: #2196f322; }
        form { display: inline; }
        input, select, button {
            background: #18213a;
            border: 1px solid #2979ff;
            color: #f4f7fa;
            padding: 8px 10px;
            border-radius: 8px;
            outline: none;
            font-size: 1rem;
            margin-right: 4px;
            margin-bottom: 4px;
            transition: box-shadow 0.2s, border-color 0.2s;
        }
        input:focus, select:focus {
            border-color: #2196f3;
            box-shadow: 0 0 0 2px #2196f355;
        }
        button {
            background: linear-gradient(90deg,#2196f3,#2979ff);
            color: #fff;
            border: none;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 12px #2196f344;
            margin-bottom: 0;
        }
        button:hover { background: #2979ff; }
        .msg { color: #ff5252; text-align: center; font-size: 14px; margin-bottom: 8px; }
        a { color: #2196f3; }
        </style>
</head>
<body>
        <div class="container">
            <h1>Gestão de Estoque</h1>
            <div class="usuario">Usuário: <?php echo htmlspecialchars($usuario_nome); ?></div>
            <?php if ($mensagem): ?>
                <div class="msg"><?php echo htmlspecialchars($mensagem); ?></div>
            <?php endif; ?>
            <?php if ($alertaEstoque): ?>
                <div class="msg" style="color:#ff5252;"><?php echo htmlspecialchars($alertaEstoque); ?></div>
            <?php endif; ?>
            <h2>Produtos (ordenados alfabeticamente)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Estoque</th>
                        <th>Mínimo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $p): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($p['nome']); ?></td>
                            <td><?php echo htmlspecialchars($p['estoque_atual']); ?></td>
                            <td><?php echo htmlspecialchars($p['estoque_minimo']); ?></td>
                            <td>
                                <form method="post" action="index.php?action=estoque-registrar">
                                    <input type="hidden" name="produto_id" value="<?php echo htmlspecialchars($p['id']); ?>">
                                    <select name="tipo"><option value="entrada">Entrada</option><option value="saida">Saída</option></select>
                                    <input type="number" name="quantidade" min="1" required>
                                    <input type="date" name="data_movimentacao" required value="<?php echo date('Y-m-d'); ?>">
                                    <input type="text" name="observacao" placeholder="Observação (opcional)">
                                    <button type="submit">Registrar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h2>Histórico (últimas 20)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Data registro</th>
                        <th>Produto</th>
                        <th>Tipo</th>
                        <th>Quantidade</th>
                        <th>Responsável</th>
                        <th>Observação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historico as $h): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($h['data_registro']); ?></td>
                            <td><?php echo htmlspecialchars($h['nome_produto']); ?></td>
                            <td><?php echo htmlspecialchars($h['tipo']); ?></td>
                            <td><?php echo htmlspecialchars($h['quantidade']); ?></td>
                            <td><?php echo htmlspecialchars($h['responsavel']); ?></td>
                            <td><?php echo htmlspecialchars($h['observacao']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p style="text-align:center;margin-top:18px;"><a href="index.php?action=home">Voltar</a></p>
        </div>
</body>
</html>
