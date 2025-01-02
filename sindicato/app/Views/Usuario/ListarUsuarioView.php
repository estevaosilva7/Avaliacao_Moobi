<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Listar Usuários</title>
</head>
<body>
<header>
    <h1>Usuários Cadastrados</h1>
</header>
<?php if (!empty($aUsuarios)): ?>
    <table border="1" cellpadding="10">
        <thead>
        <tr>
            <th>Nome</th>
            <th>Tipo</th>
            <?php if ($isAdmin): ?>
                <th>Ações</th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($aUsuarios as $aUsuario): ?>
            <tr>
                <td><?php echo htmlspecialchars($aUsuario['usu_Nome']); ?></td>
                <td><?php echo htmlspecialchars($aUsuario['usu_Tipo']); ?></td>

                <?php if ($isAdmin):
                    ?>
                    <td>
                        <a href="<?php echo Config::pegarUrl() . 'usuario/editar?id=' . $aUsuario['usu_Id']; ?>">Editar</a> |
                        <a href="<?php echo Config::pegarUrl() . 'usuario/deletar?id=' . $aUsuario['usu_Id']; ?>"
                           onclick="return confirm('Tem certeza que deseja deletar este usuário?');">Excluir</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Nenhum usuário encontrado.</p>
<?php endif; ?>
<a href="<?php echo Config::pegarUrl() . 'usuario/dashboard'; ?>">
    <button type="button">Voltar</button>
</a>
</body>
</html>