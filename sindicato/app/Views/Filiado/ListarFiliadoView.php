<?php
$mPaginaAtual = $mPaginaAtual ?? 1;
$mTotalPaginas = $mTotalPaginas ?? 1;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Filiados</title>
</head>
<body>
<header>
    <h1>Lista de Filiados</h1>
</header>
<form method="GET" action="">
    <fieldset>
        <legend>Filtrar Filiados</legend>
        <label for="nome">Nome do Filiado:</label>
        <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($_GET['nome'] ?? ''); ?>">
        <label for="mes_nascimento">Mês de Nascimento:</label>
        <select name="mes_nascimento" id="mes_nascimento">
            <option value="">-- Todos --</option>
            <?php
            for ($i = 1; $i <= 12; $i++):
                $mes = str_pad($i, 2, '0', STR_PAD_LEFT);
                ?>
                <option value="<?php echo $mes; ?>" <?php echo ($aDados['mes_nascimento'] ?? '') == $mes ? 'selected' : ''; ?>>
                    <?php echo strftime('%B', mktime(0, 0, 0, $i, 1)); ?>
                </option>
            <?php endfor; ?>
        </select>

        <button type="submit">Filtrar</button>
    </fieldset>
</form>
<?php if (!empty($filiados)): ?>
    <h2>Filiados Cadastrados</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
        <tr>
            <th>Nome</th>
            <th>CPF</th>
            <th>RG</th>
            <th>Data de Nascimento</th>
            <th>Idade</th>
            <th>Empresa</th>
            <th>Cargo</th>
            <th>Situação</th>
            <th>Telefone Residencial</th>
            <th>Celular</th>
            <th>Última Atualização</th>
            <?php if ($isAdmin): ?>
                <th>Ações</th>
            <?php endif; ?>
            <th>Dependentes</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($filiados as $filiado): ?>
            <tr>
                <td><?php echo htmlspecialchars($filiado['flo_Nome']); ?></td>
                <td><?php echo htmlspecialchars($filiado['flo_CPF']); ?></td>
                <td><?php echo htmlspecialchars($filiado['flo_RG']); ?></td>
                <td><?php echo htmlspecialchars($filiado['flo_Data_De_Nascimento']); ?></td>
                <td><?php echo htmlspecialchars($filiado['flo_Idade']); ?></td>
                <td><?php echo htmlspecialchars($filiado['flo_Empresa']); ?></td>
                <td><?php echo htmlspecialchars($filiado['flo_Cargo']); ?></td>
                <td><?php echo htmlspecialchars($filiado['flo_Situacao']); ?></td>
                <td><?php echo htmlspecialchars($filiado['flo_Telefone_Residencial']); ?></td>
                <td><?php echo htmlspecialchars($filiado['flo_Celular']); ?></td>
                <td><?php echo htmlspecialchars($filiado['flo_Data_Ultima_Atualizacao']); ?></td>
                <?php if ($isAdmin): ?>
                    <td>
                        <a href="<?php echo Config::pegarUrl() . 'filiado/editar?id=' . $filiado['flo_Id']; ?>">Editar</a> |
                        <a href="<?php echo Config::pegarUrl() . 'filiado/deletar?id=' . $filiado['flo_Id']; ?>"
                           onclick="return confirm('Tem certeza que deseja deletar este filiado?');">Excluir</a>
                    </td>
                <?php endif; ?>
                <td>
                    <a href="<?php echo Config::pegarUrl() . 'dependente/listar?id=' . $filiado['flo_Id']; ?>"
                    <button type="button">Acessar</button>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <nav>
        <ul style="list-style: none; display: flex; gap: 10px;">
            <?php if ($mPaginaAtual > 1): ?>
                <li><a href="?pagina=<?php echo $mPaginaAtual - 1; ?>&nome=<?php echo urlencode($mNome ?? '');
                ?>&mes_nascimento=<?php echo urlencode($mesNascimento ?? ''); ?>">Anterior</a></li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $mTotalPaginas; $i++): ?>
                <li>
                    <a href="?pagina=<?php echo $i; ?>&nome=<?php echo urlencode($mNome ?? '');
                    ?>&mes_nascimento=<?php echo urlencode($mMesNascimento ?? ''); ?>"
                       style="<?php echo $i == $mPaginaAtual ? 'font-weight: bold;' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
            <?php if ($mPaginaAtual < $mTotalPaginas): ?>
                <li><a href="?pagina=<?php echo $mPaginaAtual + 1; ?>&nome=<?php echo urlencode($mNome ?? '');
                ?>&mes_nascimento=<?php echo urlencode($mMesNascimento ?? ''); ?>">Próxima</a></li>
            <?php endif; ?>
        </ul>
    </nav>
<?php else: ?>
    <p>Nenhum filiado encontrado.</p>
<?php endif; ?>
<a href="<?php echo Config::pegarUrl() . 'usuario/dashboard'; ?>">
    <button type="button">Voltar</button>
</a>
</body>
</html>