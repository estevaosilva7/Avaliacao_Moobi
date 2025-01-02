<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dependentes</title>
</head>
<body>
<h2>Lista de Dependentes</h2>

<?php if (!empty($aDependentes)): ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
        <tr>
            <th>Nome</th>
            <th>Data de Nascimento</th>
            <th>Grau de Parentesco</th>
            <?php if ($isAdmin): ?>
                <th>Ações</th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($aDependentes as  $aDependente): ?>
            <tr>
                <td><?php echo htmlspecialchars($aDependente['dpe_Nome']); ?></td>
                <td><?php echo htmlspecialchars($aDependente['dpe_Data_De_Nascimento']); ?></td>
                <td><?php echo htmlspecialchars($aDependente['dpe_Grau_De_Parentesco']); ?></td>
                <?php if ($isAdmin): ?>
                    <td>
                        <a href="<?php echo Config::pegarUrl() . 'dependente/editar?id=' . $aDependente['dpe_Id'] . '&filiadoId=' . $mFiliadoId; ?>">Editar</a> |
                        <a href="<?php echo Config::pegarUrl() . 'dependente/deletar?id=' . $aDependente['dpe_Id'] . '&filiadoId=' . $mFiliadoId; ?>"
                           onclick="return confirm('Tem certeza que deseja deletar este dependente?');">Excluir</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Nenhum dependente cadastrado.</p>
<?php endif; ?>

<?php if ($isAdmin): ?>
    <a href="<?php echo Config::pegarUrl() . "dependente/cadastrar&filiadoId=$mFiliadoId" ?>">
        <button type="button">Adicionar Dependente</button>
    </a>
<?php endif; ?>

<br>
<a href="<?php echo Config::pegarUrl() . 'filiado/listar'; ?>">
    <button type="button">Voltar</button>
</a>
</body>
</html>
